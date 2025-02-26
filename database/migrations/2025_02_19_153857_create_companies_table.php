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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('portfolio_id');
            $table->string('symbol')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('currency')->nullable();
            $table->string('exchange')->nullable();
            $table->string('country')->nullable();
            $table->string('sector')->nullable();
            $table->string('site')->nullable();
            $table->float('dividend_per_share')->nullable();
            $table->float('eps')->nullable();
            $table->float('analyst_target_price')->nullable();
            $table->integer('analyst_rating_strong_buy')->nullable();
            $table->integer('analyst_rating_buy')->nullable();
            $table->integer('analyst_rating_hold')->nullable();
            $table->integer('analyst_rating_sell')->nullable();
            $table->integer('analyst_rating_strong_sell')->nullable();
            $table->integer('52_week_high')->nullable();
            $table->integer('52_week_low')->nullable();
            $table->timestamps()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
