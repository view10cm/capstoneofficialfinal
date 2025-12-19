<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IngredientsCategory;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingredients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ingredientName',
        'ingredientQuantity',
        'ingredientCategory',
        'ingredientAvailability'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ingredientQuantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category associated with the ingredient.
     */
    public function category()
    {
        return $this->belongsTo(IngredientsCategory::class, 'ingredientCategory', 'id');
    }

    /**
     * Calculate availability based on quantity.
     *
     * @param int $quantity
     * @return string
     */
    public static function calculateAvailability($quantity)
    {
        if ($quantity == 0) {
            return 'Out of Stock';
        } elseif ($quantity <= 10) {
            return 'Low Stock';
        } else {
            return 'Available';
        }
    }
}