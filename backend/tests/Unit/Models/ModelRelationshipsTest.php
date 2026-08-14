<?php

namespace Tests\Unit\Models;

use App\Models\PriceAlert;
use App\Models\Stock;
use App\Models\StockCandle;
use App\Models\StockQuote;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    #[Test]
    public function stock_has_many_quotes()
    {
        $stock = new Stock;

        $this->assertInstanceOf(HasMany::class, $stock->quotes());
        $this->assertInstanceOf(StockQuote::class, $stock->quotes()->getRelated());
    }

    #[Test]
    public function stock_has_many_candles()
    {
        $stock = new Stock;

        $this->assertInstanceOf(HasMany::class, $stock->candles());
        $this->assertInstanceOf(StockCandle::class, $stock->candles()->getRelated());
    }

    #[Test]
    public function stock_has_many_watchlists()
    {
        $stock = new Stock;

        $this->assertInstanceOf(HasMany::class, $stock->watchlists());
        $this->assertInstanceOf(Watchlist::class, $stock->watchlists()->getRelated());
    }

    #[Test]
    public function stock_has_many_price_alerts()
    {
        $stock = new Stock;

        $this->assertInstanceOf(HasMany::class, $stock->priceAlerts());
        $this->assertInstanceOf(PriceAlert::class, $stock->priceAlerts()->getRelated());
    }

    #[Test]
    public function stock_quote_belongs_to_stock()
    {
        $quote = new StockQuote;

        $this->assertInstanceOf(BelongsTo::class, $quote->stock());
        $this->assertInstanceOf(Stock::class, $quote->stock()->getRelated());
    }

    #[Test]
    public function stock_candle_belongs_to_stock()
    {
        $candle = new StockCandle;

        $this->assertInstanceOf(BelongsTo::class, $candle->stock());
        $this->assertInstanceOf(Stock::class, $candle->stock()->getRelated());
    }

    #[Test]
    public function watchlist_belongs_to_user_and_stock()
    {
        $watchlist = new Watchlist;

        $this->assertInstanceOf(BelongsTo::class, $watchlist->user());
        $this->assertInstanceOf(User::class, $watchlist->user()->getRelated());
        $this->assertInstanceOf(Stock::class, $watchlist->stock()->getRelated());
    }

    #[Test]
    public function price_alert_belongs_to_user_and_stock()
    {
        $alert = new PriceAlert;

        $this->assertInstanceOf(BelongsTo::class, $alert->user());
        $this->assertInstanceOf(User::class, $alert->user()->getRelated());
        $this->assertInstanceOf(Stock::class, $alert->stock()->getRelated());
    }

    #[Test]
    public function user_has_many_watchlists_and_price_alerts()
    {
        $user = new User;

        $this->assertInstanceOf(HasMany::class, $user->watchlists());
        $this->assertInstanceOf(Watchlist::class, $user->watchlists()->getRelated());
        $this->assertInstanceOf(PriceAlert::class, $user->priceAlerts()->getRelated());
    }
}
