<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRechazoFieldsToPrestamosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->date('fecha_rechazo')->nullable()->after('devuelto')->comment('Fecha en que se rechazó el préstamo');
            $table->string('motivo_rechazo', 255)->nullable()->after('fecha_rechazo')->comment('Motivo del rechazo del préstamo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            $table->dropColumn(['fecha_rechazo', 'motivo_rechazo']);
        });
    }
};
