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
        Schema::table('portfolios', function (Blueprint $table) {
            $table->integer('ing_id')->after('isin')->nullable();
            $table->string('wkn')->after('ing_id')->nullable();
            $table->string('internal_isin')->after('wkn')->nullable();
            $table->string('push_symbol')->after('internal_isin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            //
        });
    }
};
