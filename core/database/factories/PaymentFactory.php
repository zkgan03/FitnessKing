<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_amount' => $this->faker->randomFloat(2, 0, 1000),
            'payment_currency' => $this->faker->randomElement(['USD']),
            'payment_method' => $this->faker->randomElement(['paypal', 'card']),
        ];
    }
}
