<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffToKitchenTransaction extends Model
{
    use HasFactory;

    protected $table = 'staff_to_kitchen_transaction';
    
    public $timestamps = false;

    protected $fillable = [
        'orderID',
        'paymentNumber',
        'orderType',
        'paymentMethod',
        'productName',
        'quantity',
        'unitPrice',
        'totalPrice',
        'taxAmount',
        'productNotes',
        'paymentStatus',
        'amountPaid',
        'changeAmount',
        'referenceNumber',
        'staffName',
        'paymentProcessedAt',
        'cookingStatus' // Added new field
    ];

    protected $casts = [
        'paymentNumber' => 'integer',
        'quantity' => 'integer',
        'unitPrice' => 'decimal:2',
        'totalPrice' => 'decimal:2',
        'taxAmount' => 'decimal:2',
        'amountPaid' => 'decimal:2',
        'changeAmount' => 'decimal:2',
        'paymentProcessedAt' => 'datetime'
        // cookingStatus doesn't need casting as it's an enum
    ];
}