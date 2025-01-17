<?php

namespace App\Http\Controllers\ClassPackageEnrollment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Enrollment\EnrollmentService;
use App\Services\ClassPackage\ClassPackageService;


/**
 * Author:  GAN ZHI KEN
 * 
 */
class ClassPackageController extends Controller
{

    public function __construct(
        private readonly ClassPackageService $classPackageService,
        private readonly EnrollmentService $enrollmentService,
    ) {
    }

    public function index(Request $request)
    {
        $request->validate(
            [
                'min_price' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'max_price' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'q' => [
                    'string',
                    'nullable',
                    'regex:/^[a-zA-Z0-9\s]+$/'
                ], // Alphanumeric and spaces only
            ]
        );

        //get query parameters, if it is set
        $page = $request['page'] ?? 1;
        $query = $request['q'] ?? "";
        $minPrice = $request['min_price'] ?? 0;
        $maxPrice = $request['max_price'] ?? 1000;
        $sort = $request['sort'] ?? "latest";

        $html = $this->classPackageService
            ->packagesToHtmlPaginatedWithFilter(
                $page ?? 1,
                6,
                $sort,
                [
                    'query' => $query,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice
                ]
            );

        // Return XML as response
        return view(
            'class_package.index',
            [
                'content' => $html,
                'query' => $query,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice
            ]
        );
    }


    public function show($packageId)
    {
        $classPackage = $this->classPackageService
            ->getPackageById($packageId);

        // dd($classPackage->package_id);
        $classes = $this->classPackageService
            ->getClassesById($packageId);
        $slotAvailable = $this->classPackageService
            ->getSlotAvailableById($packageId);

        $isEnrolled = false;
        if (auth('customer')->check()) {
            $userId = auth('customer')->id();
            // dd($userId);
            $isEnrolled = $this->enrollmentService
                ->isEnrolled($userId, $packageId);
            // dd($isEnrolled);
        }


        return view(
            'class_package.show',
            [
                'classPackage' => $classPackage,
                'classes' => $classes,
                'slotAvailable' => $slotAvailable,
                'isEnrolled' => $isEnrolled
            ]
        );
    }


    public function unenroll(Request $request)
    {
        // dd($request->all());
        $packageId = $request['package_id'];

        $userId = auth('customer')->id();
        $this->enrollmentService->unenroll($userId, $packageId);

        return redirect()
            ->back()
            ->with('success', 'Unenrolled successfully');
    }

}
