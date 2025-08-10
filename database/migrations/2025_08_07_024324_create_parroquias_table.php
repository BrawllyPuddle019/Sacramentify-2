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
        Schema::create('parroquias', function (Blueprint $table) {
            $table->integer('cve_parroquia')->primary();
            $table->string('nombre_parroquia', 100)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->integer('cve_municipio')->nullable();
            $table->integer('cve_diocesis')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            
            $table->index('cve_municipio');
            $table->index('cve_diocesis');
            $table->foreign('cve_municipio')->references('cve_municipio')->on('municipios');
            $table->foreign('cve_diocesis')->references('cve_diocesis')->on('diocesis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parroquias');
    }
};
