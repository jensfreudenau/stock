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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->integer('portfolio_id');
            $table->float('diff')->nullable();
            $table->float('buy_amount')->nullable();
            $table->float('sell_amount')->nullable();
            $table->integer('price')->nullable();
            $table->float('total_amount')->nullable();
            $table->integer('sales_proceeds')->nullable();
            $table->float('avg_price')->nullable();
            $table->float('balance')->nullable();
            $table->date('deal_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
