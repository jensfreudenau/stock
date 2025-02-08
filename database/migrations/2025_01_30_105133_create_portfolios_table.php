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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->index();
            $table->string('isin');
            $table->string('name')->nullable();
            $table->boolean('active')->nullable();
            $table->date('active_since');
            $table->string('share_type')->nullable();
            $table->text('description')->nullable();
            $table->string('country')->nullable();
            $table->string('sector')->nullable();
            $table->integer('analyst_rating_strong_buy')->nullable();
            $table->integer('analyst_rating_buy')->nullable();
            $table->integer('analyst_rating_hold')->nullable();
            $table->integer('analyst_rating_sell')->nullable();
            $table->integer('analyst_rating_strong_sell')->nullable();
            $table->timestamps();
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
