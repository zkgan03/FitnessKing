<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\ClassPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //for testing the attendance
            'customer_id' => Customer::factory(),
            'package_id' => ClassPackage::factory(),
            'payment_id' => Payment::factory(),
        ];
    }
}
