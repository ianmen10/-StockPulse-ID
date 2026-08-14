<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceAlert>
 */
class PriceAlertFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'stock_id' => Stock::factory(),
            'condition' => fake()->randomElement(['above', 'below']),
            'target_price' => fake()->randomFloat(4, 100, 50000),
            'is_triggered' => false,
            'triggered_at' => null,
        ];
    }
}
