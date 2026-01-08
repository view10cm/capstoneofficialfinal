<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';
    
    protected $fillable = [
        'date',
        'day',
        'month',
        'year',
        'today_sales'
    ];

    protected $casts = [
        'date' => 'date',
        'today_sales' => 'decimal:2'
    ];
}