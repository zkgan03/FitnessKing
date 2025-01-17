<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassPackage>
 */
class ClassPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_name' => $this->faker->name(),
            'package_image' => "0f4ef1ea2b094082918e071b3d28e4d1.png",
            'description' => $this->faker->text(),
            'start_date' => $start_date = $this->faker->dateTimeBetween('-1 month', '+2 month')->format('Y-m-d H:i:s'),
            'end_date' => Carbon::parse($start_date)->addMonths(2)->format('Y-m-d H:i:s'),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'max_capacity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
