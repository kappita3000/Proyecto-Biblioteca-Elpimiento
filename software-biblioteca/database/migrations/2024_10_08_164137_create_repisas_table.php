<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repisas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repisas');
    }
};
