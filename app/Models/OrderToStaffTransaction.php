<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderToStaffTransaction extends Model
{
    use HasFactory;

    protected $table = 'order_to_staff_transaction';
    
    // Disable timestamps since we have orderCreateDateAndTime column
    public $timestamps = false;

    protected $fillable = [
        'orderID',
        'paymentNumber',
        'orderType',
        'orderPaymentMethod',
        'orderProductName',
        'orderQuantity',
        'orderTotalProductPrice',
        'orderTotalProductTax',
        'orderNotes',
        'orderCreateDateAndTime'
    ];

    protected $casts = [
        'paymentNumber' => 'integer',
        'orderQuantity' => 'integer',
        'orderTotalProductPrice' => 'decimal:2',
        'orderTotalProductTax' => 'decimal:2',
        'orderCreateDateAndTime' => 'datetime'
    ];

    /**
     * Generate a new order ID
     *
     * @return string
     */
    public static function generateOrderID()
    {
        // Get the last order ID (any record will do since orderID is not unique)
        $lastOrder = self::orderBy('id', 'desc')->first();
        
        if ($lastOrder) {
            // Extract the numeric part from the orderID
            // Handle both formats: CAFFE00001 and CAFFE00001-2
            $orderID = $lastOrder->orderID;
            
            // If it has a suffix (e.g., CAFFE00001-2), remove it
            if (strpos($orderID, '-') !== false) {
                $orderID = substr($orderID, 0, strpos($orderID, '-'));
            }
            
            // Extract the numeric part and increment
            $lastNumber = (int) substr($orderID, 5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        
        return 'CAFFE' . $newNumber;
    }
}