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
        Schema::create('diocesis', function (Blueprint $table) {
            $table->integer('cve_diocesis')->primary();
            $table->string('nombre_diocesis', 100)->nullable();
            $table->string('direccion_diocesis', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diocesis');
    }
};
