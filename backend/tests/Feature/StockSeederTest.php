<?php

namespace Tests\Feature;

use App\Models\Stock;
use Database\Seeders\StockSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StockSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function seeder_inserts_initial_idx_stocks()
    {
        $this->seed(StockSeeder::class);

        foreach (['BBCA.JK', 'BBRI.JK', 'BMRI.JK', 'TLKM.JK', 'ASII.JK', 'ANTM.JK', 'GOTO.JK'] as $symbol) {
            $this->assertDatabaseHas('stocks', ['symbol' => $symbol, 'exchange' => 'IDX', 'is_active' => true]);
        }
    }

    #[Test]
    public function seeder_is_idempotent()
    {
        $this->seed(StockSeeder::class);
        $this->seed(StockSeeder::class);

        $count = Stock::where('symbol', 'BBCA.JK')->count();
        $this->assertEquals(1, $count);
    }

    #[Test]
    public function seeded_stocks_have_sector_and_name()
    {
        $this->seed(StockSeeder::class);

        $bbca = Stock::where('symbol', 'BBCA.JK')->firstOrFail();

        $this->assertNotNull($bbca->name);
        $this->assertNotNull($bbca->sector);
        $this->assertTrue($bbca->is_active);
    }
}
