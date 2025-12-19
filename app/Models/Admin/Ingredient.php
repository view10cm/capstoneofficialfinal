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
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
     * @var array
     */
    protected $fillable = [
        'id',
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

    /**
     * Generate the next ingredient ID.
     *
     * @return string
     */
    public static function generateNextId()
    {
        // Get the last ingredient
        $lastIngredient = self::orderBy('created_at', 'desc')->first();
        
        if (!$lastIngredient) {
            // First ingredient
            return 'CA00001';
        }
        
        // Extract the numeric part from the last ID
        $lastId = $lastIngredient->id;
        $numericPart = (int) substr($lastId, 2); // Remove "CA" prefix
        
        // Increment and format with leading zeros
        $nextNumber = $numericPart + 1;
        return 'CA' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method to handle ID generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = self::generateNextId();
            }
        });
    }
}