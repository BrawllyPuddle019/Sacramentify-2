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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['platica', 'bautizo', 'confirmacion', 'matrimonio', 'otro']);
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->boolean('todo_el_dia')->default(false);
            $table->enum('estado', ['pendiente', 'confirmado', 'cancelado', 'completado'])->default('pendiente');
            $table->string('color', 7)->default('#3498db');
            
            // Relaciones
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que creó el evento
            $table->unsignedBigInteger('sacerdote_id')->nullable();
            $table->unsignedBigInteger('ermita_id')->nullable();
            
            // Referencias a actas existentes (cuando el evento se convierte en acta real)
            $table->unsignedBigInteger('bautizo_id')->nullable();
            $table->unsignedBigInteger('confirmacion_id')->nullable();
            $table->unsignedBigInteger('matrimonio_id')->nullable();
            $table->unsignedBigInteger('platica_id')->nullable();
            
            // Información adicional
            $table->json('personas_involucradas')->nullable(); // Para almacenar IDs de personas relacionadas
            $table->text('notas')->nullable();
            $table->string('contacto_email')->nullable();
            $table->string('contacto_telefono')->nullable();
            
            $table->timestamps();
            
            // Índices para mejorar rendimiento
            $table->index('sacerdote_id');
            $table->index('ermita_id');
            $table->index('fecha_inicio');
            $table->index('tipo');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
