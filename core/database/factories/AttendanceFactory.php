<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GymClass;
use App\Models\Customer;

class AttendanceFactory extends Factory
{
    public function definition()
    {
        return [
            'class_id' => GymClass::inRandomOrder()->first()->class_id,
            'customer_id' => Customer::inRandomOrder()->first()->customer_id, 
            'is_present' => $this->faker->boolean(80), // 80% chance of being present
            'created_at' => $this->faker->dateTimeBetween('-1 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
