<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOverallSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the overall_sales table
        Schema::create('overall_sales', function (Blueprint $table) {
            $table->id('salesID'); // Primary key
            $table->decimal('overall_sales', 15, 2)->default(0.00); // Stores total sales sum
            
            // Timestamps for tracking
            $table->timestamps();
            
            // Index on salesID
            $table->index('salesID');
        });

        // Insert initial row with sum of totalPrice from staff_to_kitchen_transaction
        $totalSales = DB::table('staff_to_kitchen_transaction')->sum('totalPrice');
        
        DB::table('overall_sales')->insert([
            'overall_sales' => $totalSales,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create a trigger to automatically update overall_sales when new transactions are added
        if (DB::connection()->getDriverName() === 'mysql') {
            // MySQL trigger
            DB::unprepared('
                CREATE TRIGGER update_overall_sales_after_insert
                AFTER INSERT ON staff_to_kitchen_transaction
                FOR EACH ROW
                BEGIN
                    UPDATE overall_sales 
                    SET overall_sales = (
                        SELECT SUM(totalPrice) 
                        FROM staff_to_kitchen_transaction
                    ),
                    updated_at = NOW()
                    WHERE salesID = 1;
                END
            ');

            // Also update when transactions are modified or deleted
            DB::unprepared('
                CREATE TRIGGER update_overall_sales_after_update
                AFTER UPDATE ON staff_to_kitchen_transaction
                FOR EACH ROW
                BEGIN
                    UPDATE overall_sales 
                    SET overall_sales = (
                        SELECT SUM(totalPrice) 
                        FROM staff_to_kitchen_transaction
                    ),
                    updated_at = NOW()
                    WHERE salesID = 1;
                END
            ');

            DB::unprepared('
                CREATE TRIGGER update_overall_sales_after_delete
                AFTER DELETE ON staff_to_kitchen_transaction
                FOR EACH ROW
                BEGIN
                    UPDATE overall_sales 
                    SET overall_sales = (
                        SELECT SUM(totalPrice) 
                        FROM staff_to_kitchen_transaction
                    ),
                    updated_at = NOW()
                    WHERE salesID = 1;
                END
            ');
        } elseif (DB::connection()->getDriverName() === 'pgsql') {
            // PostgreSQL trigger
            DB::unprepared('
                CREATE OR REPLACE FUNCTION update_overall_sales()
                RETURNS TRIGGER AS $$
                BEGIN
                    UPDATE overall_sales 
                    SET overall_sales = (
                        SELECT COALESCE(SUM(totalPrice), 0)
                        FROM staff_to_kitchen_transaction
                    ),
                    updated_at = NOW()
                    WHERE salesID = 1;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER update_overall_sales_trigger
                AFTER INSERT OR UPDATE OR DELETE ON staff_to_kitchen_transaction
                FOR EACH ROW
                EXECUTE FUNCTION update_overall_sales();
            ');
        } elseif (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite doesn't support triggers in the same way, so we'll handle it in application logic
            // The triggers will be created but may not work in all SQLite configurations
            DB::unprepared('
                CREATE TRIGGER IF NOT EXISTS update_overall_sales_after_insert
                AFTER INSERT ON staff_to_kitchen_transaction
                BEGIN
                    UPDATE overall_sales 
                    SET overall_sales = (
                        SELECT COALESCE(SUM(totalPrice), 0)
                        FROM staff_to_kitchen_transaction
                    ),
                    updated_at = CURRENT_TIMESTAMP
                    WHERE salesID = 1;
                END;
            ');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop triggers first
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_overall_sales_after_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS update_overall_sales_after_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_overall_sales_after_delete');
        } elseif (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_overall_sales_trigger ON staff_to_kitchen_transaction');
            DB::unprepared('DROP FUNCTION IF EXISTS update_overall_sales');
        } elseif (DB::connection()->getDriverName() === 'sqlite') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_overall_sales_after_insert');
        }

        // Drop the table
        Schema::dropIfExists('overall_sales');
    }
}