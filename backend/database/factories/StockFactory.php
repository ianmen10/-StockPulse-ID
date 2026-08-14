<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'symbol' => fake()->unique()->regexify('[A-Z]{4}\.JK'),
            'name' => fake()->company().' Tbk',
            'sector' => fake()->randomElement([
                'Financials',
                'Energy',
                'Materials',
                'Telecommunications',
                'Consumer Staples',
            ]),
            'exchange' => 'IDX',
            'is_active' => true,
        ];
    }
}
