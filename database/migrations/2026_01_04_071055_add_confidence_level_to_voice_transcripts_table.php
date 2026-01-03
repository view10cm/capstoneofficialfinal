<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('voice_transcripts', function (Blueprint $table) {
            $table->enum('confidence_level', ['Not Confident', 'Partially Confident', 'Confident'])
                  ->default('Not Confident')
                  ->after('matchedMenuItem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voice_transcripts', function (Blueprint $table) {
            $table->dropColumn('confidence_level');
        });
    }
};