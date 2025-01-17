<?php

namespace App\Services\Enrollment;

use App\Models\Customer;
use App\Models\Enrollment;
use App\Models\GymClass;

/**
 * Author:  GAN ZHI KEN
 * 
 */
class EnrollmentService
{
    /**
     * get all class packages that a user is enrolled in
     * 
     * @param string|int $userId
     * @return array
     */
    public function getAllEnrolledPackages(string|int $userId): array
    {
        $enrolledPackages = Customer::find($userId)
            ->enrollments()
            ->get();

        return $enrolledPackages->toArray();
    }

    /**
     * get all classes associated in the class package that a user is enrolled in
     * 
     * @param string|int $userId
     * @return array
     */
    public function getAllEnrolledClasses(string|int $userId): array
    {
        $enrolledClasses = GymClass::query()
            ->join('class_packages', 'gym_classes.package_id', '=', 'class_packages.package_id')
            ->join('enrollments', 'class_packages.package_id', '=', 'enrollments.package_id')
            ->where('enrollments.customer_id', "=", $userId)
            ->get();

        // dd($enrolledClasses->toArray());
        return $enrolledClasses->toArray();
    }

    /**
     * check if a user is enrolled in a class package
     * 
     * @param string|int $userId
     * @param string|int $packageId
     * @return bool
     */
    public function isEnrolled(string|int $userId, string|int $packageId): bool
    {
        $classPackage = Customer::find($userId)
            ->enrollments()
            ->where('package_id', $packageId)
            ->first();

        if (!empty($classPackage)) {
            return true;
        }

        return false;
    }

    /**
     *  enroll a user into a class package
     * 
     * @param $userId
     * @param $packageId
     * 
     * @return void
     */
    public function enroll($userId, $packageId, $paymentId): void
    {

        $enrollment = Enrollment::create([
            'customer_id' => $userId,
            'package_id' => $packageId,
            'payment_id' => $paymentId,
        ]);

    }

    /**
     *  unenroll a user from a class package
     * 
     * @param string|int $userId
     * @param string|int $packageId
     * @return void
     */
    public function unenroll(string|int $userId, string|int $packageId): void
    {
        $enrollment = Customer::find($userId)
            ->enrollments()
            ->where('package_id', "=", $packageId)
            ->first();

        if (empty($enrollment)) {
            throw new \Exception('User is not enrolled in this package', 400);
        }

        $enrollment->delete();

    }

}

