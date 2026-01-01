<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffToKitchenTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_to_kitchen_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('orderID');
            $table->integer('paymentNumber');
            $table->enum('orderType', ['dine-in', 'takeout']);
            $table->enum('paymentMethod', ['cash', 'electronic']);
            $table->string('productName');
            $table->integer('quantity');
            $table->decimal('unitPrice', 10, 2);
            $table->decimal('totalPrice', 10, 2);
            $table->decimal('taxAmount', 10, 2);
            $table->text('productNotes')->nullable();
            $table->enum('paymentStatus', ['pending', 'completed', 'refunded'])->default('completed');
            $table->decimal('amountPaid', 10, 2);
            $table->decimal('changeAmount', 10, 2)->nullable();
            $table->string('referenceNumber')->nullable();
            $table->string('staffName');
            $table->timestamp('paymentProcessedAt')->useCurrent();
            
            // Indexes for better performance
            $table->index('orderID');
            $table->index('paymentNumber');
            $table->index('paymentProcessedAt');
            $table->index('paymentStatus');
            $table->index('referenceNumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_to_kitchen_transactions');
    }
}