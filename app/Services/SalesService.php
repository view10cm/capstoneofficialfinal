<?php

namespace App\Services;

use App\Models\Sales;
use App\Models\StaffToKitchenTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesService
{
    /**
     * Get today's sales from staff_to_kitchen_transaction table
     */
    public function getTodaysSales()
    {
        $today = Carbon::today()->toDateString();
        
        // Check if we already have today's sales in sales table
        $salesRecord = Sales::where('date', $today)->first();
        
        if (!$salesRecord) {
            return $this->updateSalesForDate($today);
        }
        
        return $salesRecord->today_sales;
    }
    
    /**
     * Update sales data for a specific date
     */
    public function updateSalesForDate($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $dateString = $date->toDateString();
        
        // Calculate total sales for the day from staff_to_kitchen_transaction
        $totalSales = StaffToKitchenTransaction::whereDate('paymentProcessedAt', $dateString)
            ->whereNotNull('paymentProcessedAt')
            ->sum('totalPrice');
        
        // Update or create sales record
        $salesRecord = Sales::updateOrCreate(
            [
                'date' => $dateString,
            ],
            [
                'day' => $date->day,
                'month' => $date->month,
                'year' => $date->year,
                'today_sales' => $totalSales
            ]
        );
        
        return $salesRecord->today_sales;
    }
    
    /**
     * Update all sales data (for migration or daily job)
     */
    public function updateAllSales()
    {
        // Get all unique dates from staff_to_kitchen_transaction
        $transactions = StaffToKitchenTransaction::select(
            DB::raw('DATE(paymentProcessedAt) as date'),
            DB::raw('DAY(paymentProcessedAt) as day'),
            DB::raw('MONTH(paymentProcessedAt) as month'),
            DB::raw('YEAR(paymentProcessedAt) as year'),
            DB::raw('SUM(totalPrice) as total_sales')
        )
        ->whereNotNull('paymentProcessedAt')
        ->groupBy('date', 'day', 'month', 'year')
        ->get();
        
        foreach ($transactions as $transaction) {
            Sales::updateOrCreate(
                [
                    'date' => $transaction->date,
                ],
                [
                    'day' => $transaction->day,
                    'month' => $transaction->month,
                    'year' => $transaction->year,
                    'today_sales' => $transaction->total_sales
                ]
            );
        }
        
        return true;
    }
    
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $today = Carbon::today()->toDateString();
        
        // Get today's sales
        $todaysSales = $this->getTodaysSales();
        
        // Get meals served today (count of transactions)
        $mealsServed = StaffToKitchenTransaction::whereDate('paymentProcessedAt', $today)
            ->whereNotNull('paymentProcessedAt')
            ->count();
        
        // For active orders and low stock count, you might need to adjust based on your logic
        // These are placeholders - adjust based on your actual models
        $activeOrders = 0; // You'll need to implement this based on your order system
        $lowStockCount = 0; // You'll need to implement this based on your inventory
        
        return [
            'todaysSales' => $todaysSales,
            'mealsServed' => $mealsServed,
            'activeOrders' => $activeOrders,
            'lowStockCount' => $lowStockCount
        ];
    }
}