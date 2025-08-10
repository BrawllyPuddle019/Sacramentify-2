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
        Schema::table('actas', function (Blueprint $table) {
            // Primero eliminamos la primary key actual
            $table->dropPrimary(['cve_actas']);
        });
        
        Schema::table('actas', function (Blueprint $table) {
            // Luego agregamos auto-increment y recreamos la primary key
            $table->integer('cve_actas')->autoIncrement()->primary()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            $table->dropPrimary(['cve_actas']);
        });
        
        Schema::table('actas', function (Blueprint $table) {
            $table->integer('cve_actas')->primary()->change();
        });
    }
};
