<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Coach;
use App\Models\ClassPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GymClass>
 */
class GymClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'class_type' => $this->faker->randomElement(['yoga', 'pilates', 'zumba', 'kickboxing', 'cardio']),
            'class_image' => "0f4ef1ea2b094082918e071b3d28e4d1.png",
            'classroom' => $this->faker->randomElement(['A101', 'A102', 'A103', 'A104', 'A105']),
            'class_date' => $this->faker->dateTimeBetween('now', '+2 month')->format('Y-m-d H:i:s'),
            'start_time' => $startTime = $this->faker->time(),
            'end_time' => Carbon::parse($startTime)->addMinutes($this->faker->numberBetween(60, 120))->format('H:i:s'),
            'coach_id' => Coach::factory(),
            'package_id' => ClassPackage::factory()
        ];
    }
}
