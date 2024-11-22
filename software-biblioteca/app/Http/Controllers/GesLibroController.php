<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GesLibro;
use App\Models\Categoria;
use App\Models\Autor;
use App\Models\Genero;
use App\Models\Repisa;
use App\Models\Editorial;

class GesLibroController extends Controller
{
    public function librosindex()
    {
        $autores = Autor::all();
        $generos = Genero::all();
        $categorias = Categoria::all();
        $repisas = Repisa::all();

        $libros = GesLibro::with(['autor', 'genero', 'categoria', 'editorial'])->get();

        return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias', 'repisas'));
    }

    public function edit($id)
    {
        $libro = GesLibro::findOrFail($id);

        $autores = Autor::all();
        $generos = Genero::all();
        $categorias = Categoria::all();
        $repisas = Repisa::all();

        return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias', 'repisas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'id_autor' => 'required|exists:autores,id',
            'id_genero' => 'required|exists:generos,id',
            'id_categoria' => 'required|exists:categorias,id',
            'id_repisa' => 'required|exists:repisas,id',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
            'editorial' => 'required|string|max:255', // La editorial ahora es obligatoria
        ]);

        // Buscar o crear la editorial
        $editorial = Editorial::firstOrCreate(['nombre' => $request->editorial]);

        GesLibro::create([
            'titulo' => $request->titulo,
            'id_autor' => $request->id_autor,
            'id_genero' => $request->id_genero,
            'id_categoria' => $request->id_categoria,
            'id_repisa' => $request->id_repisa,
            'id_editorial' => $editorial->id, // Asegurar que se guarde el ID de la editorial
            'cantidad' => $request->cantidad,
            'descripcion' => $request->descripcion,
            'disponible' => true,
        ]);

        return redirect()->route('libros.librosindex')->with('success', 'Libro creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'id_autor' => 'required|exists:autores,id',
            'id_genero' => 'required|exists:generos,id',
            'id_categoria' => 'required|exists:categorias,id',
            'id_repisa' => 'required|exists:repisas,id',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
            'editorial' => 'required|string|max:255', // La editorial ahora es obligatoria
        ]);

        $libro = GesLibro::findOrFail($id);

        // Buscar o crear la editorial
        $editorial = Editorial::firstOrCreate(['nombre' => $request->editorial]);

        $libro->update([
            'titulo' => $request->titulo,
            'id_autor' => $request->id_autor,
            'id_genero' => $request->id_genero,
            'id_categoria' => $request->id_categoria,
            'id_repisa' => $request->id_repisa,
            'id_editorial' => $editorial->id,
            'cantidad' => $request->cantidad,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('libros.librosindex')->with('success', 'Libro actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $libro = GesLibro::findOrFail($id);
        $libro->delete();

        return redirect()->route('libros.librosindex')->with('success', 'Libro eliminado exitosamente.');
    }

    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $libros = GesLibro::where('titulo', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'titulo']);
        return response()->json($libros);
    }

    public function search(Request $request)
    {
        $field = $request->input('field'); // Campo a buscar: autor, genero, etc.
        $query = $request->input('query'); // Término de búsqueda
    
        switch ($field) {
            case 'autor':
                $results = Autor::where('nombre', 'like', '%' . $query . '%')->get();
                break;
            case 'genero':
                $results = Genero::where('nombre', 'like', '%' . $query . '%')->get();
                break;
            case 'categoria':
                $results = Categoria::where('nombre', 'like', '%' . $query . '%')->get();
                break;
            case 'editorial':
                $results = Editorial::where('nombre', 'like', '%' . $query . '%')->get();
                break;
            default:
                $results = [];
        }
    
        // Retorna los resultados en un formato JSON para Select2
        return response()->json($results->map(function ($item) {
            return ['id' => $item->id, 'name' => $item->nombre];
        }));
    }
    










    






}
