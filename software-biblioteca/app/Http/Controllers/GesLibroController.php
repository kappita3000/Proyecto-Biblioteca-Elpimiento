<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GesLibro;

class GesLibroController extends Controller
{

    public function librosindex()
    {
        $libros = GesLibro::all();
        return view('libros.librosindex', compact('libros'));
    }

public function edit($id)
{
    $libros = GesLibro::all(); // Obtener todos los libros
    $libro = GesLibro::findOrFail($id); // Buscar el libro por su ID
    return view('libros.librosindex', compact('libros', 'libro')); // Pasar tanto $libros como $libro a la vista
}


    public function store(Request $request)
    {
        $libro = new GesLibro();
        $libro->titulo = $request->input('titulo');
        $libro->id_autor = $request->input('id_autor');
        $libro->id_genero = $request->input('id_genero');
        $libro->id_categoria = $request->input('id_categoria');
        $libro->id_repisa = $request->input('id_repisa');
        $libro->id_editorial = $request->input('id_editorial');
        $libro->fecha_publicacion = $request->input('fecha_publicacion');
        $libro->disponible = $request->input('disponible') ? 1 : 0;
        $libro->cantidad = $request->input('cantidad');
        $libro->descripcion = $request->input('descripcion');
        
        $libro->save();

        return redirect()->route('libros.librosindex')->with('success', 'Libro creado exitosamente');
    }

    public function update(Request $request, $id)
    {
        $libro = GesLibro::findOrFail($id);
        $libro->update($request->all());
        return redirect()->route('libros.librosindex')->with('success', 'Libro actualizado con éxito.');
    }
    public function destroy($id)
    {
        $libro = GesLibro::findOrFail($id);
        $libro->delete();
        return redirect()->route('libros.librosindex')->with('success', 'Libro eliminado con éxito.');
    }
}
