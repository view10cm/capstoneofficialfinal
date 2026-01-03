<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtteranceGallery extends Model
{
    use HasFactory;

    protected $table = 'utterance_gallery';
    
    public $timestamps = false;

    protected $primaryKey = 'transcriptionID';
    
    public $incrementing = true;
    
    protected $keyType = 'int';

    protected $fillable = [
        'menuItem',
        'acceptedUtterance'
    ];
}