<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Casts;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['symbol', 'name', 'sector', 'exchange', 'is_active'])]
#[Casts(['is_active' => 'boolean'])]
class Stock extends Model
{
    use HasFactory;

    /**
     * @return HasMany<StockQuote, $this>
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(StockQuote::class);
    }

    /**
     * @return HasMany<StockCandle, $this>
     */
    public function candles(): HasMany
    {
        return $this->hasMany(StockCandle::class);
    }

    /**
     * @return HasMany<Watchlist, $this>
     */
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * @return HasMany<PriceAlert, $this>
     */
    public function priceAlerts(): HasMany
    {
        return $this->hasMany(PriceAlert::class);
    }
}
