<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voice_transcripts', function (Blueprint $table) {
            $table->string('matchedMenuItem')->nullable()->after('transcribedData');
        });
    }

    public function down(): void
    {
        Schema::table('voice_transcripts', function (Blueprint $table) {
            $table->dropColumn('matchedMenuItem');
        });
    }
};