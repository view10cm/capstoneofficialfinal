<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderToStaffTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_to_staff_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('orderID')->comment('Format: CAFFE00001, CAFFE00002, etc. - NOT unique');
            $table->integer('paymentNumber')->comment('Payment number from 1-20');
            $table->enum('orderType', ['dine-in', 'takeout']);
            $table->enum('orderPaymentMethod', ['cash', 'electronic']);
            $table->string('orderProductName');
            $table->integer('orderQuantity');
            $table->decimal('orderTotalProductPrice', 10, 2);
            $table->decimal('orderTotalProductTax', 10, 2);
            $table->enum('orderProductStatus', ['For Payment', 'In Progress', 'Completed', 'To Follow-up', 'Product Voided'])->default('For Payment');
            $table->text('orderNotes')->nullable();
            $table->timestamp('orderCreateDateAndTime')->useCurrent();
            
            // Indexes for better performance (orderID is NOT unique)
            $table->index('orderID');
            $table->index('paymentNumber');
            $table->index('orderCreateDateAndTime');
            $table->index('orderProductStatus');
        });

        // Add check constraint for paymentNumber (1-20)
        // Note: For MySQL 8.0.16+ and PostgreSQL only
        // Remove or comment this if using older MySQL versions
        // if (DB::getDriverName() === 'mysql' && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '8.0.16', '>=')) {
        //     DB::statement('ALTER TABLE order_to_staff_transaction ADD CONSTRAINT payment_number_range CHECK (paymentNumber >= 1 AND paymentNumber <= 20)');
        // } elseif (DB::getDriverName() === 'pgsql') {
        //     DB::statement('ALTER TABLE order_to_staff_transaction ADD CONSTRAINT payment_number_check CHECK (paymentNumber >= 1 AND paymentNumber <= 20)');
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_to_staff_transaction');
    }
}