<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('id_autor')->constrained('autores'); // Llave foránea a 'autores'
            $table->foreignId('id_genero')->constrained('generos'); // Llave foránea a 'generos'
            $table->foreignId('id_categoria')->constrained('categorias'); // Llave foránea a 'categorias'
            $table->foreignId('id_repisa')->constrained('repisas'); // Llave foránea a 'repisas'
            $table->foreignId('id_editorial')->nullable()->constrained('editoriales'); // Llave foránea a 'editoriales'
            $table->date('fecha_publicacion')->nullable();
            $table->boolean('disponible')->default(true);
            $table->integer('cantidad')->default(1);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('libros');
    }
};
