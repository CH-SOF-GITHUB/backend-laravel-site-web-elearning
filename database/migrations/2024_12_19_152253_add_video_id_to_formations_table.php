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
        Schema::table('formations', function (Blueprint $table) {
            // Ajout du champ videoId
            $table->string('videoId')->nullable()->after('photo'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            // Supprime le champ videoId
            $table->dropColumn('videoId');

        });
    }
};