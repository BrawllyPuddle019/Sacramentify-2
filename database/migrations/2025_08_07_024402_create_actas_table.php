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
        Schema::create('actas', function (Blueprint $table) {
            $table->integer('cve_actas')->primary();
            $table->integer('numero_consecutivo')->nullable();
            $table->integer('cve_persona')->nullable();
            $table->integer('cve_persona2')->nullable();
            $table->integer('Per_cve_padrino1')->nullable();
            $table->integer('Per_cve_madrina1')->nullable();
            $table->integer('cve_ermitas')->nullable();
            $table->integer('cve_sacerdotes_celebrante')->nullable();
            $table->integer('cve_sacerdotes_asistente')->nullable();
            $table->integer('cve_obispos_celebrante')->nullable();
            $table->integer('Per_cve_padrino')->nullable();
            $table->integer('Per_cve_madrina')->nullable();
            $table->integer('Per_cve_padre')->nullable();
            $table->integer('Per_cve_madre1')->nullable();
            $table->datetime('fecha')->nullable();
            $table->integer('Per_cve_madre')->nullable();
            $table->integer('Per_cve_padre1')->nullable();
            $table->string('Libro', 100)->nullable();
            $table->integer('Fojas')->nullable();
            $table->integer('Folio')->nullable();
            $table->integer('tipo_acta')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('cve_persona');
            $table->index('cve_ermitas');
            $table->index('cve_sacerdotes_celebrante');
            $table->index('cve_sacerdotes_asistente');
            $table->index('cve_obispos_celebrante');
            $table->index('Per_cve_padrino');
            $table->index('Per_cve_madrina');
            $table->index('Per_cve_padre');
            $table->index('Per_cve_madre1');
            $table->index('Per_cve_madre');
            $table->index('Per_cve_padre1');
            $table->index('tipo_acta');
            $table->index('cve_persona2');
            $table->index('Per_cve_padrino1');
            $table->index('Per_cve_madrina1');
            $table->index('deleted_at');
            $table->index('numero_consecutivo');
            
            // Foreign keys
            $table->foreign('cve_persona')->references('cve_persona')->on('personas');
            $table->foreign('cve_ermitas')->references('cve_ermitas')->on('ermitas');
            $table->foreign('cve_sacerdotes_celebrante')->references('cve_sacerdotes')->on('sacerdotes');
            $table->foreign('cve_sacerdotes_asistente')->references('cve_sacerdotes')->on('sacerdotes');
            $table->foreign('cve_obispos_celebrante')->references('cve_obispos')->on('obispos');
            $table->foreign('Per_cve_padrino')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_madrina')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_padre')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_madre1')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_madre')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_padre1')->references('cve_persona')->on('personas');
            $table->foreign('tipo_acta')->references('cve_sacramentos')->on('sacramentos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cve_persona2')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_madrina1')->references('cve_persona')->on('personas');
            $table->foreign('Per_cve_padrino1')->references('cve_persona')->on('personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actas');
    }
};
