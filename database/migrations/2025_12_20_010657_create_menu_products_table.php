<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_products', function (Blueprint $table) {
            // Primary Key
            $table->string('menuID', 50)->primary(); // Changed from $table->id('menuID')
            
            // Menu Information
            $table->string('menuName', 200);
            $table->string('menuCategory', 50);
            $table->string('menuSubcategory', 50);
            
            // Price
            $table->decimal('menuPrice', 10, 2);
            
            // Status with default value
            $table->enum('menuStatus', ['Available', 'Out of Stock', 'Discontinued'])->default('Available');
            
            // Image path (nullable as not all items might have images initially)
            $table->string('menuImage')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Add soft deletes
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('menuCategory');
            $table->index('menuSubcategory');
            $table->index('menuStatus');
            $table->index('deleted_at'); // Add index for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_products');
    }
}