<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_candles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->string('interval', 10);
            $table->decimal('open', 15, 4);
            $table->decimal('high', 15, 4);
            $table->decimal('low', 15, 4);
            $table->decimal('close', 15, 4);
            $table->unsignedBigInteger('volume')->default(0);
            $table->timestamp('timestamp');
            $table->timestamps();

            $table->unique(['stock_id', 'interval', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_candles');
    }
};
