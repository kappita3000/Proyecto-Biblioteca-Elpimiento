<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;

class CateInicioController extends Controller
{
    public function index()
    {
        // Obtén todas las categorías
        $categorias = Categoria::all();

        // Obtén los últimos 5 libros agregados
        $ultimosLibros = Libro::latest()->take(5)->get();

        // Retorna la vista con las categorías y los últimos libros
        return view('index', compact('categorias', 'ultimosLibros'));
    }
    public function show(Libro $libro)
    {
        // Retorna la vista con los detalles del libro
        return view('libros.show', compact('libro'));
    }
}
