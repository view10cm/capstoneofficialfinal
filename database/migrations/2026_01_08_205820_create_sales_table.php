<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('today_sales', 10, 2)->default(0);
            $table->timestamps();
        });

        // Create an index for faster date-based queries
        Schema::table('sales', function (Blueprint $table) {
            $table->index(['date']);
            $table->index(['year', 'month', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};