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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->index();
            $table->date('stock_date');
            $table->integer('portfolio_id');
            $table->integer('open')->nullable();
            $table->integer('low')->nullable();
            $table->integer('high')->nullable();
            $table->integer('close');
            $table->float('volume')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
