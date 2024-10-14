<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('nombre'); // Nombre del administrador
            $table->string('apellido')->nullable();
            $table->string('correo')->unique(); // Correo electrónico, debe ser único
            $table->text('contraseña'); // Contraseña cifrada
            $table->enum('rol', ['superadmin', 'moderador'])->default('moderador'); // Rol del administrador
            $table->timestamps(); // Timestamps (created_at y updated_at)
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('admins'); // Eliminar la tabla si se revierte la migración
    }
};
