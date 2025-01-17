<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Models\Customer;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\GymClass;
use App\Models\ClassPackage;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        Customer::factory()->create();
        Payment::factory()->create();

        // Create 10 coaches and retrieve the collection of coaches
        $coaches = Coach::factory()->count(10)->create();

        // Create 10 class packages, each with 5 gym classes
        ClassPackage::factory()
            ->count(10)
            ->has(
                GymClass::factory()
                    ->count(5)
                    ->state(function (array $attributes) use ($coaches) {
                        return [
                            // Assign a random coach_id from the previously created coaches
                            'coach_id' => $coaches->random()->coach_id,
                        ];
                    }),
                'gymClasses'
            )
            ->create();

        //classSchedule
        // Create 10 gym classes with associated enrollments
        GymClass::factory(10)->create()->each(function ($gymClass) {
            // Create enrollments for customers who have paid for this class's package
            Enrollment::factory(count: 5)->create([
                'package_id' => $gymClass->package_id,
            ])->each(function ($enrollment) use ($gymClass) {
                // Create an attendance record for this customer in this class
                Attendance::factory()->create([
                    'class_id' => $gymClass->class_id,
                    'customer_id' => $enrollment->customer_id, // Assign the customer from the enrollment
                ]);
            });
        });

    }
}
