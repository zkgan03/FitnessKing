<?php

namespace App\Http\Controllers\ClassPackageEnrollment;

use Illuminate\Http\Request;
use App\Services\Payment\Paypal;
use App\Http\Controllers\Controller;
use App\Services\Payment\CardPayment;
use App\Services\Payment\PaymentService;
use App\Services\Enrollment\EnrollmentService;
use App\Services\Payment\PaymentGatewayService;
use App\Services\ClassPackage\ClassPackageService;
use Illuminate\Support\Carbon;
use Validator;


/**
 * Author:  GAN ZHI KEN
 * 
 */
class PaymentController extends Controller
{
    public function __construct(
        private readonly ClassPackageService $classPackageService,
        private readonly PaymentService $paymentService,
        private readonly EnrollmentService $enrollmentService,
    ) {
    }

    public function index($id)
    {

        $package = $this->classPackageService->getPackageById($id);
        $userId = auth('customer')->id();

        // enrolled
        if ($this->enrollmentService->isEnrolled($userId, $id)) {
            return redirect()
                ->route('class_package.show', $id)
                ->with('error', 'You have already enrolled this package');
        }

        // is started
        $packageStartDate = Carbon::parse($package->start_date, "Asia/Kuala_Lumpur");
        if ($packageStartDate->isPast()) {
            return redirect()
                ->route('class_package.show', $id)
                ->with('error', 'Package has already started / ended');
        }

        // Enrollment::create([
        //     'customer_id' => 1,
        //     'package_id' => $id,
        //     'payment_id' => 1
        // ]);

        return view('payment.index', ['package' => $package]);
    }

    public function processPayment(Request $request)
    {
        $package = $this->classPackageService->getPackageById($request->package_id);
        if (!$package) {
            return redirect()
                ->back()
                ->with('error', 'Package not found'); // no package found
        }

        // set payment method for the service class
        $paymentGateway = null;
        if ($request->payment_method == "paypal") {
            $paymentGateway = new PaymentGatewayService(new Paypal());
        } else if ($request->payment_method == "card") {
            $paymentGateway = new PaymentGatewayService(new CardPayment());
        }

        // get redirect url to redirect to payment gateway
        $redirecteUrl = $paymentGateway->requestPayment(
            $package->package_id,
            $package->package_name,
            $package->price
        );

        return redirect()->away($redirecteUrl);
    }

    public function success(Request $request)
    {
        $paymentMethod = $request['method']; // get payment method in query string
        $paymentGateway = null;
        $transactionId = null;

        // check if payment is success
        if ($paymentMethod == "paypal") {
            $paymentGateway = new PaymentGatewayService(new Paypal());
            $transactionId = $request['token'];

        } else if ($paymentMethod == "card") {
            $paymentGateway = new PaymentGatewayService(new CardPayment());
            $transactionId = $request['id'];
        }

        // capture the payment 
        $paymentGateway->capturePayment($transactionId);

        // check if payment is completed after capture
        $isPaymentCompleted = $paymentGateway->isPaymentCompleted($transactionId);
        if ($isPaymentCompleted) {

            $paymentDetails = $paymentGateway->getPaymentDetails($transactionId);

            // create payment record
            $payment = $this->paymentService->createAndAddPayment(
                $paymentDetails["amount"],
                $paymentDetails['payment_method'],
                $paymentDetails["currency"],
            );

            $userId = auth('customer')->id(); 

            // enroll user to package
            $packageId = $request['package_id'];
            $this->enrollmentService->enroll($userId, $packageId, $payment->payment_id); // enroll user to package

            // redirect to schedule page
            return redirect()
                ->route('schedule.index')
                ->with('success', 'Payment successful');
        }

        // payment failed, redirect back to payment page
        return redirect()
            ->route('payment.index', $request->package_id)
            ->with('error', 'Payment failed');
    }

    public function cancel(Request $request)
    {
        return redirect()
            ->route('payment.index', $request->package_id)
            ->with('error', 'Payment was cancelled');
    }
}
