<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pour CATEGORIES : On vérifie si la colonne 'created_at' manque avant d'ajouter
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'created_at')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // 2. Pour PLAT
        if (Schema::hasTable('plat') && !Schema::hasColumn('plat', 'created_at')) {
            Schema::table('plat', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // 3. Pour COMMANDE
        if (Schema::hasTable('commande') && !Schema::hasColumn('commande', 'created_at')) {
            Schema::table('commande', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // 4. Pour COMMANDE_PLAT
        if (Schema::hasTable('commande_plat') && !Schema::hasColumn('commande_plat', 'created_at')) {
            Schema::table('commande_plat', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {

    }
};



