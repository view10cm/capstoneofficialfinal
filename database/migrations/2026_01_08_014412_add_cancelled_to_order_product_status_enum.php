<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCancelledToOrderProductStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For MySQL
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE order_to_staff_transaction MODIFY COLUMN orderProductStatus ENUM('For Payment', 'In Progress', 'Completed', 'To Follow-up', 'Product Voided', 'Cancelled') DEFAULT 'For Payment'");
        }
        
        // For PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE order_to_staff_transaction DROP CONSTRAINT order_to_staff_transaction_orderproductstatus_check");
            DB::statement("ALTER TABLE order_to_staff_transaction ADD CONSTRAINT order_to_staff_transaction_orderproductstatus_check CHECK (orderProductStatus::text = ANY (ARRAY['For Payment'::character varying, 'In Progress'::character varying, 'Completed'::character varying, 'To Follow-up'::character varying, 'Product Voided'::character varying, 'Cancelled'::character varying]::text[]))");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // For MySQL - revert to original enum
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE order_to_staff_transaction MODIFY COLUMN orderProductStatus ENUM('For Payment', 'In Progress', 'Completed', 'To Follow-up', 'Product Voided') DEFAULT 'For Payment'");
        }
        
        // For PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE order_to_staff_transaction DROP CONSTRAINT order_to_staff_transaction_orderproductstatus_check");
            DB::statement("ALTER TABLE order_to_staff_transaction ADD CONSTRAINT order_to_staff_transaction_orderproductstatus_check CHECK (orderProductStatus::text = ANY (ARRAY['For Payment'::character varying, 'In Progress'::character varying, 'Completed'::character varying, 'To Follow-up'::character varying, 'Product Voided'::character varying]::text[]))");
        }
    }
}