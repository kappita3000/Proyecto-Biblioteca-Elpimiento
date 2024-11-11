<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Categoria;
use App\Models\Repisa;
use App\Models\Editorial;
use Illuminate\Http\Request;

class GestionesController extends Controller
{
    // Mostrar la vista con los formularios
    public function index()
    {
        $autores = Autor::all();
        $generos = Genero::all();
        $categorias = Categoria::all();
        $repisas = Repisa::all();
        $editoriales = Editorial::all();

        return view('gestiones.gestiones', compact('autores', 'generos', 'categorias', 'repisas', 'editoriales'));
    }

    // Crear autor
    public function storeAutor(Request $request)
    {
        Autor::create($request->all());
        return back();
    }

    // Crear género
    public function storeGenero(Request $request)
    {
        Genero::create($request->all());
        return back();
    }

    // Crear categoría
    public function storeCategoria(Request $request)
    {
        Categoria::create($request->all());
        return back();
    }

    // Crear repisa
    public function storeRepisa(Request $request)
    {
        Repisa::create($request->all());
        return back();
    }

    // Crear editorial
    public function storeEditorial(Request $request)
    {
        Editorial::create($request->all());
        return back();
    }

    // Eliminar autor
    public function deleteAutor($id)
    {
        Autor::find($id)->delete();
        return back();
    }

    // Eliminar género
    public function deleteGenero($id)
    {
        Genero::find($id)->delete();
        return back();
    }

    // Eliminar categoría
    public function deleteCategoria($id)
    {
        Categoria::find($id)->delete();
        return back();
    }

    // Eliminar repisa
    public function deleteRepisa($id)
    {
        Repisa::find($id)->delete();
        return back();
    }

    // Eliminar editorial
    public function deleteEditorial($id)
    {
        Editorial::find($id)->delete();
        return back();
    }
}
