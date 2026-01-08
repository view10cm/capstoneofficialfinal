<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOverallMenuProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overall_menu_products', function (Blueprint $table) {
            // Primary Key
            $table->string('overallMenuID', 50)->primary();
            
            // Overall Menu Count
            $table->integer('overallMenu')->default(0);
            
            // Timestamps
            $table->timestamps();
        });

        // Insert the initial row with count from menu_products table
        DB::table('overall_menu_products')->insert([
            'overallMenuID' => 'OVERALL001',
            'overallMenu' => DB::table('menu_products')->count(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overall_menu_products');
    }
}