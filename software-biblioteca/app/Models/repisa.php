<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repisa extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla si no sigue la convención de Laravel (opcional)
    protected $table = 'repisas';

    // Definir los campos que se pueden rellenar
    protected $fillable = ['numero'];
}

