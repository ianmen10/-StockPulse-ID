<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockQuote>
 */
class StockQuoteFactory extends Factory
{
    public function definition(): array
    {
        $price = fake()->randomFloat(4, 100, 50000);

        return [
            'stock_id' => Stock::factory(),
            'price' => $price,
            'open' => fake()->randomFloat(4, 100, 50000),
            'high' => fake()->randomFloat(4, 100, 50000),
            'low' => fake()->randomFloat(4, 100, 50000),
            'previous_close' => fake()->randomFloat(4, 100, 50000),
            'change' => fake()->randomFloat(4, -1000, 1000),
            'change_percent' => fake()->randomFloat(4, -10, 10),
            'volume' => fake()->numberBetween(0, 100_000_000),
            'captured_at' => fake()->dateTimeThisYear(),
        ];
    }
}
