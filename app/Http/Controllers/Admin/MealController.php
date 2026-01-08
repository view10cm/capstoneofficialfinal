<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OverallMealsServed;

class MealController extends Controller
{
    /**
     * Get overall meals served data
     */
    public function getMealsData()
    {
        try {
            // Get overall meals count
            $overallMeals = OverallMealsServed::getOverallMeals();
            
            // Get today's meals count
            $todayMeals = OverallMealsServed::getTodayMeals();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'overall_meals' => $overallMeals ? $overallMeals->meals_overall : 0,
                    'today_meals' => $todayMeals
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching meals data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch meals data',
                'data' => [
                    'overall_meals' => 0,
                    'today_meals' => 0
                ]
            ]);
        }
    }

    /**
     * Update meals count (can be triggered when new orders come in)
     */
    public function updateMealsCount()
    {
        try {
            $updatedMeals = OverallMealsServed::updateOverallMeals();
            
            return response()->json([
                'success' => true,
                'message' => 'Meals count updated successfully',
                'data' => [
                    'overall_meals' => $updatedMeals->meals_overall,
                    'today_meals' => OverallMealsServed::getTodayMeals()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error updating meals count: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update meals count'
            ], 500);
        }
    }
}