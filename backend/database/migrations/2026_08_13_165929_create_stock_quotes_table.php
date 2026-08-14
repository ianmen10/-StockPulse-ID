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
        Schema::create('stock_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 15, 4);
            $table->decimal('open', 15, 4)->nullable();
            $table->decimal('high', 15, 4)->nullable();
            $table->decimal('low', 15, 4)->nullable();
            $table->decimal('previous_close', 15, 4)->nullable();
            $table->decimal('change', 15, 4)->default(0);
            $table->decimal('change_percent', 10, 4)->default(0);
            $table->unsignedBigInteger('volume')->default(0);
            $table->timestamp('captured_at')->useCurrent();
            $table->timestamps();

            $table->index('stock_id');
            $table->index('captured_at');
            $table->index(['stock_id', 'captured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_quotes');
    }
};
