<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallLowStockItems extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'overall_low_stock_items';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'lowStockID';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lowStockID',
        'lowStockItemsCount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the current low stock count
     *
     * @return int
     */
    public static function getLowStockCount(): int
    {
        try {
            $lowStock = self::first();
            return $lowStock ? $lowStock->lowStockItemsCount : 0;
        } catch (\Exception $e) {
            \Log::error('Error getting low stock count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get the low stock data for dashboard
     *
     * @return array
     */
    public static function getLowStockData(): array
    {
        try {
            $lowStock = self::first();
            
            return [
                'low_stock_count' => $lowStock ? $lowStock->lowStockItemsCount : 0,
                'low_stock_id' => $lowStock ? $lowStock->lowStockID : 'LOW_STOCK_001'
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting low stock data: ' . $e->getMessage());
            
            return [
                'low_stock_count' => 0,
                'low_stock_id' => 'LOW_STOCK_001'
            ];
        }
    }
}