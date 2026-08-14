<?php

namespace Tests\Feature;

use App\Models\PriceAlert;
use App\Models\Stock;
use App\Models\StockCandle;
use App\Models\StockQuote;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function all_tables_exist()
    {
        foreach (['stocks', 'stock_quotes', 'stock_candles', 'watchlists', 'price_alerts'] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table {$table} does not exist");
        }
    }

    #[Test]
    public function stocks_table_has_expected_columns()
    {
        $columns = ['id', 'symbol', 'name', 'sector', 'exchange', 'is_active', 'created_at', 'updated_at'];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('stocks', $column), "Missing column {$column}");
        }
    }

    #[Test]
    public function stock_quotes_table_has_expected_columns()
    {
        $columns = [
            'id', 'stock_id', 'price', 'open', 'high', 'low', 'previous_close',
            'change', 'change_percent', 'volume', 'captured_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('stock_quotes', $column), "Missing column {$column}");
        }
    }

    #[Test]
    public function stock_candles_table_has_expected_columns()
    {
        $columns = ['id', 'stock_id', 'interval', 'open', 'high', 'low', 'close', 'volume', 'timestamp'];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('stock_candles', $column), "Missing column {$column}");
        }
    }

    #[Test]
    public function watchlists_table_has_expected_columns()
    {
        $columns = ['id', 'user_id', 'stock_id'];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('watchlists', $column), "Missing column {$column}");
        }
    }

    #[Test]
    public function price_alerts_table_has_expected_columns()
    {
        $columns = ['id', 'user_id', 'stock_id', 'condition', 'target_price', 'is_triggered', 'triggered_at'];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('price_alerts', $column), "Missing column {$column}");
        }
    }

    #[Test]
    public function stocks_symbol_is_unique()
    {
        $stock = Stock::factory()->create(['symbol' => 'BBCA.JK']);

        $this->expectException(UniqueConstraintViolationException::class);

        Stock::create(['symbol' => 'BBCA.JK', 'name' => 'Duplicate']);
    }

    #[Test]
    public function stock_candles_prevents_duplicate_candles()
    {
        $stock = Stock::factory()->create();

        StockCandle::create([
            'stock_id' => $stock->id,
            'interval' => '1d',
            'open' => 100, 'high' => 110, 'low' => 95, 'close' => 105,
            'volume' => 1000,
            'timestamp' => now()->startOfDay(),
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        StockCandle::create([
            'stock_id' => $stock->id,
            'interval' => '1d',
            'open' => 101, 'high' => 111, 'low' => 96, 'close' => 106,
            'volume' => 1000,
            'timestamp' => now()->startOfDay(),
        ]);
    }

    #[Test]
    public function watchlists_prevents_duplicate_entries()
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create();

        Watchlist::create(['user_id' => $user->id, 'stock_id' => $stock->id]);

        $this->expectException(UniqueConstraintViolationException::class);

        Watchlist::create(['user_id' => $user->id, 'stock_id' => $stock->id]);
    }

    #[Test]
    public function deleting_stock_cascades_to_quotes_candles_watchlists_and_alerts()
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create();

        StockQuote::factory()->create(['stock_id' => $stock->id]);
        StockCandle::factory()->create(['stock_id' => $stock->id]);
        Watchlist::factory()->create(['user_id' => $user->id, 'stock_id' => $stock->id]);
        PriceAlert::factory()->create(['user_id' => $user->id, 'stock_id' => $stock->id]);

        $stock->delete();

        $this->assertDatabaseCount('stock_quotes', 0);
        $this->assertDatabaseCount('stock_candles', 0);
        $this->assertDatabaseCount('watchlists', 0);
        $this->assertDatabaseCount('price_alerts', 0);
    }

    #[Test]
    public function stock_quotes_has_expected_indexes()
    {
        $indexes = Schema::getIndexes('stock_quotes');

        $flat = collect($indexes)->mapWithKeys(
            fn (array $index) => [$index['name'] => $index['columns']]
        )->all();

        $this->assertContains('stock_id', $flat['stock_quotes_stock_id_index']);
        $this->assertContains('captured_at', $flat['stock_quotes_captured_at_index']);
        $this->assertEquals(
            ['stock_id', 'captured_at'],
            $flat['stock_quotes_stock_id_captured_at_index']
        );
    }

    #[Test]
    public function stock_quotes_has_foreign_key_to_stocks()
    {
        $foreignKeys = Schema::getForeignKeys('stock_quotes');

        $fk = collect($foreignKeys)->first(
            fn (array $key) => $key['columns'] === ['stock_id']
        );

        $this->assertNotNull($fk, 'Foreign key stock_id not found');
        $this->assertEquals('stocks', $fk['foreign_table']);
        $this->assertEquals(['id'], $fk['foreign_columns']);
        $this->assertEquals('cascade', $fk['on_delete']);
    }

    #[Test]
    public function price_alerts_enforces_condition_enum()
    {
        $user = User::factory()->create();
        $stock = Stock::factory()->create();

        PriceAlert::create([
            'user_id' => $user->id,
            'stock_id' => $stock->id,
            'condition' => 'above',
            'target_price' => 5000,
        ]);

        $this->assertDatabaseHas('price_alerts', ['condition' => 'above']);
    }
}
