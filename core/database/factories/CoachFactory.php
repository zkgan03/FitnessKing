<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory
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
            'email' => $this->faker->email(),
            'gender' => $this->faker->randomElement(['m', 'f']),
            'phone_number' => $this->faker->phoneNumber(),
            'birth_date' => $this->faker->date(),
            'creation_date' => $this->faker->date(),
            'profile_pic' => $this->faker->imageUrl(),
            'description' => $this->faker->text(),
            'coach_status' => $this->faker->randomElement(['a', 'b']),
        ];
    }
}
