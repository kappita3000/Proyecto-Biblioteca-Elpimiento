<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GesLibro;
use App\Models\Categoria;
use App\Models\Autor;
use App\Models\Genero;

class GesLibroController extends Controller
{

    public function librosindex()
    {
            // Cargar autores, géneros y categorías
    $autores = Autor::all();
    $generos = Genero::all();
    $categorias = Categoria::all();

    // Cargar libros junto con los autores, géneros y categorías

    $libros = GesLibro::with(['autor', 'genero', 'categoria'])->get();
    return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias'));
    }

public function edit($id)
{
    $libros = GesLibro::all(); // Obtener todos los libros
    $libro = GesLibro::findOrFail($id); // Buscar el libro por su ID
    return view('libros.librosindex', compact('libros', 'libro')); // Pasar tanto $libros como $libro a la vista
}


    public function store(Request $request)
    {
    // Validación de los datos
    $request->validate([
        'titulo' => 'required|string|max:255',
        'autor' => 'required|string',  // Aquí usamos el nombre del autor, no el ID
        'genero' => 'required|string',
        'categoria' => 'required|string',
        'id_repisa' => 'required|integer',
        'cantidad' => 'required|integer|min:1',
        'disponible' => 'boolean',
    ]);

    // Buscar el autor por nombre, o crearlo si no existe
    $autor = Autor::firstOrCreate(['nombre' => $request->autor]);

    // Buscar el género por nombre, o crearlo si no existe
    $genero = Genero::firstOrCreate(['nombre' => $request->genero]);

    // Buscar la categoría por nombre, o crearla si no existe
    $categoria = Categoria::firstOrCreate(['nombre' => $request->categoria]);

    // Crear el libro con los IDs correspondientes
    GesLibro::create([
        'titulo' => $request->titulo,
        'id_autor' => $autor->id,  // Guardar el ID del autor
        'id_genero' => $genero->id,  // Guardar el ID del género
        'id_categoria' => $categoria->id,  // Guardar el ID de la categoría
        'id_repisa' => $request->id_repisa,
        'cantidad' => $request->cantidad,
        'disponible' => $request->disponible ?? 1,
        'descripcion' => $request->descripcion,
        'fecha_publicacion' => $request->fecha_publicacion,
        'id_editorial' => $request->id_editorial,
    ]);

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
