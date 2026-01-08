<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallMenuProducts extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'overall_menu_products';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'overallMenuID';

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
    public $increments = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'overallMenuID',
        'overallMenu',
    ];

    /**
     * Get the overall menu products count
     */
    public static function getOverallMenuProducts()
    {
        return self::first();
    }

    /**
     * Get the active menu products count
     * This counts only "Available" menu items from the menu_products table
     */
    public static function getActiveMenuProductsCount()
    {
        return \App\Models\Admin\MenuProduct::where('menuStatus', 'Available')->count();
    }

    /**
     * Update the overall menu products count
     */
    public static function updateOverallMenuCount()
    {
        $overall = self::first();
        
        if (!$overall) {
            $overall = new self();
            $overall->overallMenuID = 'OVERALL001';
        }
        
        $overall->overallMenu = self::getActiveMenuProductsCount();
        $overall->save();
        
        return $overall;
    }
}