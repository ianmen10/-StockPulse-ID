<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockCandle>
 */
class StockCandleFactory extends Factory
{
    public function definition(): array
    {
        $open = fake()->randomFloat(4, 100, 50000);

        return [
            'stock_id' => Stock::factory(),
            'interval' => fake()->randomElement(['1d', '5d', '1mo', '3mo', '6mo', '1y']),
            'open' => $open,
            'high' => $open + fake()->randomFloat(4, 0, 1000),
            'low' => $open - fake()->randomFloat(4, 0, 1000),
            'close' => fake()->randomFloat(4, 100, 50000),
            'volume' => fake()->numberBetween(0, 100_000_000),
            'timestamp' => fake()->dateTimeThisYear(),
        ];
    }
}
