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
        Schema::table('eventos', function (Blueprint $table) {
            // Campos para pláticas matrimoniales
            $table->unsignedBigInteger('padre_id')->nullable()->after('ermita_id');
            $table->unsignedBigInteger('madre_id')->nullable()->after('padre_id');
            
            // Campos para bautizos/confirmaciones
            $table->unsignedBigInteger('persona_principal_id')->nullable()->after('madre_id');
            $table->unsignedBigInteger('padrino_id')->nullable()->after('persona_principal_id');
            $table->unsignedBigInteger('madrina_id')->nullable()->after('padrino_id');
            
            // Índices
            $table->index('padre_id');
            $table->index('madre_id');
            $table->index('persona_principal_id');
            $table->index('padrino_id');
            $table->index('madrina_id');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropIndex(['padre_id']);
            $table->dropIndex(['madre_id']);
            $table->dropIndex(['persona_principal_id']);
            $table->dropIndex(['padrino_id']);
            $table->dropIndex(['madrina_id']);
            
            $table->dropColumn(['padre_id', 'madre_id', 'persona_principal_id', 'padrino_id', 'madrina_id']);
        });
    }
};
