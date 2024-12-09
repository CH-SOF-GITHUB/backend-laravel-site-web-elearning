<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifiez si la colonne 'category_name' existe déjà avant de l'ajouter
        if (!Schema::hasColumn('categories', 'category_name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('category_name')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vérifiez si la colonne 'category_name' existe avant de la supprimer
        if (Schema::hasColumn('categories', 'category_name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('category_name');
            });
        }
    }
};
