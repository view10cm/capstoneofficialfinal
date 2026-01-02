<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCookingStatusToStaffToKitchenTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_to_kitchen_transaction', function (Blueprint $table) {
            $table->enum('cookingStatus', ['In Progress', 'Cooking', 'Product Ready', 'Completed'])
                  ->default('In Progress')
                  ->after('paymentProcessedAt'); // You can place it wherever makes sense
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_to_kitchen_transaction', function (Blueprint $table) {
            $table->dropColumn('cookingStatus');
        });
    }
}