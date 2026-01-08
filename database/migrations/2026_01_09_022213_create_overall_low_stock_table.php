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
        Schema::create('overall_low_stock_items', function (Blueprint $table) {
            $table->string('lowStockID', 20)->primary();
            $table->integer('lowStockItemsCount')->default(0);
            $table->timestamps();
        });

        // Calculate the initial count of Low Stock items from ingredients table
        $lowStockCount = 0;
        
        // Check if ingredients table exists before querying
        if (Schema::hasTable('ingredients')) {
            try {
                $lowStockCount = DB::table('ingredients')
                    ->where('ingredientAvailability', 'Low Stock')
                    ->count();
            } catch (\Exception $e) {
                // If there's an error (e.g., column doesn't exist), default to 0
                $lowStockCount = 0;
            }
        }

        // Insert initial row with ID 'LOW_STOCK_001'
        DB::table('overall_low_stock_items')->insert([
            'lowStockID' => 'LOW_STOCK_001',
            'lowStockItemsCount' => $lowStockCount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create triggers to automatically update the low stock count
        $this->createTriggers();
    }

    /**
     * Create database triggers to update low stock count automatically
     */
    private function createTriggers(): void
    {
        $driverName = DB::connection()->getDriverName();

        if ($driverName === 'mysql') {
            // MySQL triggers
            DB::unprepared('
                CREATE TRIGGER update_low_stock_after_ingredient_insert
                AFTER INSERT ON ingredients
                FOR EACH ROW
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = "Low Stock"
                    ),
                    updated_at = NOW()
                    WHERE lowStockID = "LOW_STOCK_001";
                END
            ');

            DB::unprepared('
                CREATE TRIGGER update_low_stock_after_ingredient_update
                AFTER UPDATE ON ingredients
                FOR EACH ROW
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = "Low Stock"
                    ),
                    updated_at = NOW()
                    WHERE lowStockID = "LOW_STOCK_001";
                END
            ');

            DB::unprepared('
                CREATE TRIGGER update_low_stock_after_ingredient_delete
                AFTER DELETE ON ingredients
                FOR EACH ROW
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = "Low Stock"
                    ),
                    updated_at = NOW()
                    WHERE lowStockID = "LOW_STOCK_001";
                END
            ');
        } elseif ($driverName === 'pgsql') {
            // PostgreSQL triggers
            DB::unprepared('
                CREATE OR REPLACE FUNCTION update_low_stock_count()
                RETURNS TRIGGER AS $$
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = \'Low Stock\'
                    ),
                    updated_at = NOW()
                    WHERE lowStockID = \'LOW_STOCK_001\';
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER update_low_stock_trigger
                AFTER INSERT OR UPDATE OR DELETE ON ingredients
                FOR EACH ROW
                EXECUTE FUNCTION update_low_stock_count();
            ');
        } elseif ($driverName === 'sqlite') {
            // SQLite triggers
            DB::unprepared('
                CREATE TRIGGER IF NOT EXISTS update_low_stock_after_ingredient_insert
                AFTER INSERT ON ingredients
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = "Low Stock"
                    ),
                    updated_at = CURRENT_TIMESTAMP
                    WHERE lowStockID = "LOW_STOCK_001";
                END;
            ');

            DB::unprepared('
                CREATE TRIGGER IF NOT EXISTS update_low_stock_after_ingredient_update
                AFTER UPDATE ON ingredients
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = "Low Stock"
                    ),
                    updated_at = CURRENT_TIMESTAMP
                    WHERE lowStockID = "LOW_STOCK_001";
                END;
            ');

            DB::unprepared('
                CREATE TRIGGER IF NOT EXISTS update_low_stock_after_ingredient_delete
                AFTER DELETE ON ingredients
                BEGIN
                    UPDATE overall_low_stock_items 
                    SET lowStockItemsCount = (
                        SELECT COUNT(*) 
                        FROM ingredients 
                        WHERE ingredientAvailability = "Low Stock"
                    ),
                    updated_at = CURRENT_TIMESTAMP
                    WHERE lowStockID = "LOW_STOCK_001";
                END;
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers first
        $driverName = DB::connection()->getDriverName();
        
        if ($driverName === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_after_ingredient_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_after_ingredient_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_after_ingredient_delete');
        } elseif ($driverName === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_trigger ON ingredients');
            DB::unprepared('DROP FUNCTION IF EXISTS update_low_stock_count');
        } elseif ($driverName === 'sqlite') {
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_after_ingredient_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_after_ingredient_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_low_stock_after_ingredient_delete');
        }

        Schema::dropIfExists('overall_low_stock_items');
    }
};