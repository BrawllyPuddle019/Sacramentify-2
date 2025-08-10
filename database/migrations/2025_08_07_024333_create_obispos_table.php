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
        Schema::create('obispos', function (Blueprint $table) {
            $table->integer('cve_obispos')->primary();
            $table->string('nombre_obispo', 100)->nullable();
            $table->string('apellido_paterno', 45)->nullable();
            $table->string('apellido_materno', 45)->nullable();
            $table->integer('cve_diocesis')->nullable();
            $table->timestamps();
            
            $table->index('cve_diocesis');
            $table->foreign('cve_diocesis')->references('cve_diocesis')->on('diocesis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obispos');
    }
};
