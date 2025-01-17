<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'password' => $this->faker->password(),
            'email' => fake()->unique()->safeEmail(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'phone_number' => $this->faker->phoneNumber(),
            'birth_date' => $this->faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'profile_pic' => 0101,
        ];
    }
}
