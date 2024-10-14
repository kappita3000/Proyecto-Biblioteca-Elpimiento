<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFechaPrestamoInPrestamosTable extends Migration
{
    public function up()
    {
        Schema::table('prestamos', function (Blueprint $table) {
            // Modificar la columna fecha_prestamo para que sea nullable
            $table->date('fecha_prestamo')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('prestamos', function (Blueprint $table) {
            // Revertir la columna fecha_prestamo a su estado anterior (sin nullable)
            $table->date('fecha_prestamo')->nullable(false)->change();
        });
    }
}

