<?php

namespace App\Http\Controllers\ClassPackageEnrollment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Enrollment\EnrollmentService;


/**
 * Author:  GAN ZHI KEN
 * 
 */
class ScheduleController extends Controller
{

    public function __construct(
        private EnrollmentService $enrollmentService
    ) {
    }

    public function index()
    {

        $currentDate = Carbon::now()->setTimezone('Asia/Kuala_Lumpur');
        $currentView = 'month';

        // get and transform the data of classes to the format that the calendar can understand
        $userId = auth('customer')->id();
        $enrolledClasses = $this->enrollmentService->getAllEnrolledClasses($userId);
        $classes = $this->transformClasses($enrolledClasses);

        return view(
            'schedule.index',
            ['currentDate' => $currentDate, 'currentView' => $currentView, 'classes' => $classes]
        );
    }

    /**
     * Update the calendar view based on the selected date and view type
     * Used for AJAX requests
     *
     **/
    public function updateView(Request $request)
    {
        $currentDate = Carbon::parse($request['date'] ?? now())
            ->setTimezone('Asia/Kuala_Lumpur');

        $currentView = $request['view'] ?? 'month';

        // get and transform the data of classes to the format that the calendar can understand
        $userId = auth('customer')->id();
        $enrolledClasses = $this->enrollmentService->getAllEnrolledClasses($userId);
        $classes = $this->transformClasses($enrolledClasses);

        // render the calendar view in server based on the selected date and view type
        if ($currentView === 'month') {
            return view('components.calendar.month-view', ['currentDate' => $currentDate, 'classes' => $classes])
                ->render();
        } else {
            return view('components.calendar.week-view', ['currentDate' => $currentDate, 'classes' => $classes])
                ->render();
        }
    }

    private function transformClasses($classes)
    {
        $colorMap = array();

        // Define an array of colors for all the events (can add more colors if needed)
        // need to add into safe list in tailwind config
        $availableColors = [
            'bg-sky-600',
            'bg-purple-600',
            'bg-indigo-600',
            'bg-pink-600',
            'bg-teal-600',
            'bg-cyan-600',
            'bg-primary-600',
            'bg-green-600',
            'bg-yellow-600',
            'bg-orange-600',
            'bg-gray-600',

        ];

        return collect($classes)->map(function ($class) use (&$colorMap, &$availableColors) {

            // generate a random color for the package's classes if it doesn't have one
            if (!isset($colorMap[$class['package_id']])) {
                // Assign a new color from the available colors to the package_id
                $colorMap[$class['package_id']] = array_shift($availableColors);
            }

            return [
                'class_id' => $class['class_id'],
                'package_id' => $class['package_id'],
                'class_name' => $class['class_name'],
                'description' => $class['description'],
                'classroom' => $class['classroom'],
                'start' => Carbon::parse($class['class_date'] . " " . $class['start_time'], 'Asia/Kuala_Lumpur'),
                'end' => Carbon::parse($class['class_date'] . " " . $class['end_time'], 'Asia/Kuala_Lumpur'),
                'color' => $colorMap[$class['package_id']],
            ];
        });
    }

}
