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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('ingredientName');
            $table->integer('ingredientQuantity')->default(0);
            $table->foreignId('ingredientCategory')->constrained('ingredients_categories')->onDelete('cascade');
            $table->enum('ingredientAvailability', ['Available', 'Low Stock', 'Out of Stock'])->default('Available');
            $table->timestamps();
            
            // Add index for better performance on category queries
            $table->index('ingredientCategory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};