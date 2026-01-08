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
        Schema::create('overall_meals_served', function (Blueprint $table) {
            $table->id('mealscountId');
            $table->integer('meals_overall')->default(0);
            $table->timestamps();
        });

        // Check if staff_to_kitchen_transaction table exists before inserting data
        if (Schema::hasTable('staff_to_kitchen_transaction')) {
            // Insert initial row with value calculated from staff_to_kitchen_transaction table
            $totalQuantity = DB::table('staff_to_kitchen_transaction')->sum('quantity');
            
            DB::table('overall_meals_served')->insert([
                'meals_overall' => $totalQuantity ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            // Insert default row if transaction table doesn't exist yet
            DB::table('overall_meals_served')->insert([
                'meals_overall' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overall_meals_served');
    }
};