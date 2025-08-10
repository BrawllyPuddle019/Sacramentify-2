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
        Schema::create('municipios', function (Blueprint $table) {
            $table->integer('cve_municipio')->primary();
            $table->string('nombre_municipio', 45)->nullable();
            $table->integer('cve_estado')->nullable();
            $table->timestamps();
            
            $table->index('cve_estado');
            $table->foreign('cve_estado')->references('cve_estado')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
