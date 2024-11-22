<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->string('caratula')->nullable()->after('titulo');
        });
    }
    
    public function down()
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->dropColumn('caratula');
        });
    }
    
};
