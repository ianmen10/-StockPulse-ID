<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Casts;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'stock_id',
    'price',
    'open',
    'high',
    'low',
    'previous_close',
    'change',
    'change_percent',
    'volume',
    'captured_at',
])]
#[Casts(['captured_at' => 'datetime'])]
class StockQuote extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<Stock, $this>
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
