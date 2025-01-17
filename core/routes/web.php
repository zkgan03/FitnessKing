<?php

use App\Http\Middleware\CusAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ClassSchedule\TimetableController;
use App\Http\Controllers\ClassSchedule\AttendanceController;
use App\Http\Controllers\ClassPackageEnrollment\PaymentController;
use App\Http\Controllers\ClassPackageEnrollment\ScheduleController;
use App\Http\Controllers\ClassPackageEnrollment\ClassPackageController;


Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::view('/email', '/authorization/email');

// Reset Password Route
Route::get('reset-pass', function () {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return redirect()->route('account'); // Redirect if logged in
    }
    return view('authorization.reset-pass'); // Show reset pass page if not logged in
})->name('reset-pass');

// Register Route
Route::get('/register', function () {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return redirect()->route('account'); // Redirect if logged in
    }
    return app(AccountController::class)->showRegisterForm(); // Show register form if not logged in
})->name('register');

// Login Route
Route::get('/login', function () {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return redirect()->route('account'); // Redirect if logged in
    }
    return app(AccountController::class)->showLoginForm(); // Show login form if not logged in
})->name('login');

// POST Routes (No need to block access)
Route::post('/register', [AccountController::class, 'createCustomer']);
Route::post('/login', [AccountController::class, 'login']);
Route::post('/reset-pass', [AccountController::class, 'sendResetPasswordEmail']);

Route::get('/logout', function () {
    return view('authorization/logout');
})->name('logout.page');

Route::post('/logout', [AccountController::class, 'logout'])->name('logout');

// Protected routes for customers
Route::get('/account', function () {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return app(AccountController::class)->showProfile();
    }
    abort(403); // Unauthorized
})->name('account');

// Route to show the edit form
Route::get('/edit/{id}', function ($id) {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return app(AccountController::class)->showEditForm($id);
    }
    abort(403); // Unauthorized
})->name('edit');

// Route to handle the update
Route::post('/edit/{id}', function ($id, Request $request) {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return app(AccountController::class)->update($id, $request);
    }
    abort(403); // Unauthorized
})->name('update');

Route::get('/coach-exam', function () {
    if (Auth::guard('customer')->check() || Auth::guard('coach')->check()) {
        return app(AccountController::class)->showExamForm();
    }
    abort(403);
})->name('coach-exam');

Route::post('/coach-exam', [AccountController::class, 'CoachExamResultCheck'])->middleware('auth:customer');

// Class Package Routes
Route::get('/class-packages', [ClassPackageController::class, 'index'])
    ->name('class_package.index');

Route::get('/class-packages/{id}', [ClassPackageController::class, 'show'])
    ->name('class_package.show');

Route::delete('/class-packages/unenroll', [ClassPackageController::class, 'unenroll'])
    ->name('class_packages.unenroll');

//=========================================================================================
// Protected routes for coach
// Timetable Routes
Route::get('/timetable', function () {
    if (Auth::guard('coach')->check()) {
        return app(TimetableController::class)->index();
    }
    abort(403);
})->name('timetable.index');

Route::post('/timetable/assign', function (Request $request) {
    if (Auth::guard(name: 'coach')->check()) {
        return app(TimetableController::class)->assign($request);
    }
    abort(403);
})->name('timetable.assign');

Route::post('/timetable/remove', function (Request $request) {
    if (Auth::guard('coach')->check()) {
        return app(TimetableController::class)->remove($request);
    }
    abort(403);
})->name('timetable.remove');

// // // Attendance Routes
Route::get('/attendance', [AttendanceController::class, 'index'])
    ->middleware('auth:coach') // Apply the middleware directly
    ->name('attendance.index');

Route::post('/attendance/mark', function (Request $request) {
    if (Auth::guard('coach')->check()) {
        return app(AttendanceController::class)->markAttendance($request);
    }
    abort(403);
})->name('attendance.mark');

//without login for class schedule
// Route::get('/timetable', [TimetableController::class, 'index'])
//     ->name('timetable.index');

// Route::post('/timetable/assign', [TimetableController::class, 'assign'])
//     ->name('timetable.assign');

// Route::post('/timetable/remove', [TimetableController::class, 'remove'])
//     ->name('timetable.remove');

// Route::get('/attendance', [AttendanceController::class, 'index'])
//     ->name('attendance.index');

// Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance'])
//     ->name('attendance.mark');


//======================================================================================================================================
Route::middleware(CusAuth::class)->group(function () {
    // Payment Routes
    Route::get('/payment/{id}', [PaymentController::class, 'index'])
        ->name('payment.index');

    Route::post('/payment/process-payment', [PaymentController::class, 'processPayment'])
        ->name('payment.processPayment');

    Route::get('/payment/process-payment/success', [PaymentController::class, 'success'])
        ->name('payment.success');

    Route::get('/payment/process-payment/cancel', [PaymentController::class, 'cancel'])
        ->name('payment.cancel');

    // Class Package Routes
    Route::get('/class-packages', [ClassPackageController::class, 'index'])
        ->name('class_package.index')
        ->withoutMiddleware(CusAuth::class);

    Route::get('/class-packages/{id}', [ClassPackageController::class, 'show'])
        ->name('class_package.show')
        ->withoutMiddleware(CusAuth::class);

    Route::delete('/class-packages/unenroll', [ClassPackageController::class, 'unenroll'])
        ->name('class_packages.unenroll');

    // Schedule Routes
    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule.index');

    Route::get('/schedule/update-view', [ScheduleController::class, 'updateView'])
        ->name('schedule.updateView');
});
