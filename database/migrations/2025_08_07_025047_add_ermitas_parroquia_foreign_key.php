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
        Schema::table('ermitas', function (Blueprint $table) {
            $table->foreign('cve_parroquia')->references('cve_parroquia')->on('parroquias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ermitas', function (Blueprint $table) {
            $table->dropForeign(['cve_parroquia']);
        });
    }
};
