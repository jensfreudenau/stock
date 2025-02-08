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
        Schema::create('in_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->index();
            $table->integer('portfolio_id');
            $table->string('name')->nullable();
            $table->float('amount')->nullable();
            $table->float('price')->nullable();
            $table->date('buy_at')->nullable();
            $table->date('sell_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_stocks');
    }
};
