<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios'); // Llave foránea a 'usuarios'
            $table->foreignId('id_libro')->constrained('libros'); // Llave foránea a 'libros'
            $table->date('fecha_solicitud'); // Nuevo campo de fecha de solicitud
            $table->date('fecha_prestamo')->nullable()->change();
            $table->date('fecha_devolucion')->nullable();
            $table->boolean('devuelto')->default(false);
            $table->timestamps();
            Schema::table('prestamos', function (Blueprint $table) {
                
            });
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestamos');
    }
};
