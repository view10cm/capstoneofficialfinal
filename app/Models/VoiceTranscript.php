<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceTranscript extends Model
{
    use HasFactory;

    protected $table = 'voice_transcripts';
    
    // Disable timestamps since we don't have timestamp columns
    public $timestamps = false;

    protected $primaryKey = 'voiceID';
    
    public $incrementing = true;
    
    protected $keyType = 'int';

    protected $fillable = [
        'transcribedData',
        'matchedMenuItem',
        'confidence_level'
    ];

    // Cast confidence_level to string
    protected $casts = [
        'confidence_level' => 'string'
    ];
}