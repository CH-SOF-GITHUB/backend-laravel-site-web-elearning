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
        Schema::table('categories', function (Blueprint $table) {
            // Vérifiez si la colonne 'category_description' existe avant de l'ajouter
            if (!Schema::hasColumn('categories', 'category_description')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->text('category_description')->after('category_name');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Vérifiez si la colonne 'category_description' existe avant de la supprimer
            if (Schema::hasColumn('categories', 'category_description')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropColumn('category_description');
                });
            }
        });
    }
};
