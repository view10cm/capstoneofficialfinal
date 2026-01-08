<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallMealsServed extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'overall_meals_served';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'mealscountId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meals_overall'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meals_overall' => 'integer'
    ];

    /**
     * Get the first (and only) record
     */
    public static function getOverallMeals()
    {
        return self::first();
    }

    /**
     * Get today's meals served count
     */
    public static function getTodayMeals()
    {
        // Get today's date
        $today = now()->toDateString();
        
        // Calculate today's meals from staff_to_kitchen_transaction table
        $todayMeals = \DB::table('staff_to_kitchen_transaction')
            ->whereDate('paymentProcessedAt', $today)
            ->sum('quantity');
            
        return $todayMeals ?? 0;
    }

    /**
     * Update the overall meals count
     */
    public static function updateOverallMeals()
    {
        $overallMeals = self::first();
        
        if (!$overallMeals) {
            $overallMeals = self::create(['meals_overall' => 0]);
        }
        
        // Recalculate total meals from staff_to_kitchen_transaction
        $totalMeals = \DB::table('staff_to_kitchen_transaction')->sum('quantity');
        
        $overallMeals->meals_overall = $totalMeals;
        $overallMeals->save();
        
        return $overallMeals;
    }
}