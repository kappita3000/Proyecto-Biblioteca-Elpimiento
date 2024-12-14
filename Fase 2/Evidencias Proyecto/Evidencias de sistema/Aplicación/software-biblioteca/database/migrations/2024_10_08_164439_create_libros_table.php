<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrosTable extends Migration
{
    public function up()
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('caratula')->nullable();
            $table->foreignId('id_autor')->constrained('autores');
            $table->foreignId('id_genero')->constrained('generos');
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->foreignId('id_repisa')->constrained('repisas');
            $table->foreignId('id_editorial')->nullable()->constrained('editoriales');
            $table->boolean('disponible')->default(1);
            $table->integer('cantidad')->default(1);
            $table->integer('copias_prestadas')->default(0);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('libros');
    }
}
