<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido')->nullable();
            $table->string('correo')->unique(); // Asegura que el correo no se repita
            $table->text('contraseña'); // Almacenar las contraseñas cifradas
            $table->enum('tipo_usuario', ['Registrado', 'No Registrado']);
            $table->integer('solicitudes')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
