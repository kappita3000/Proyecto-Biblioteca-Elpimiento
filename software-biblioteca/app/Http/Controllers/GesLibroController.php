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
        $editoriales = Editorial::all(); // Obtener todas las editoriales
    
        $libros = GesLibro::with(['autor', 'genero', 'categoria', 'editorial'])->paginate(20); // Cambia el 10 a la cantidad de libros por página que desees
    
        return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias', 'repisas', 'editoriales'));
    }

    public function edit($id)
    {
        $libro = GesLibro::findOrFail($id);
    
        $autores = Autor::all();
        $generos = Genero::all();
        $categorias = Categoria::all();
        $repisas = Repisa::all();
        $editoriales = Editorial::all(); // Obtener todas las editoriales
    
        return view('libros.librosindex', compact('libro', 'autores', 'generos', 'categorias', 'repisas', 'editoriales'));
    }
    
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'titulo' => 'required|max:255',
            'id_autor' => 'required|exists:autores,id',
            'id_genero' => 'required|exists:generos,id',
            'id_categoria' => 'required|exists:categorias,id',
            'id_repisa' => 'required|exists:repisas,id',
            'id_editorial' => 'required|exists:editoriales,id',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
        ]);
    
        // Insertar libro con estado disponible por defecto
        GesLibro::create([
            'titulo' => $request->input('titulo'),
            'id_autor' => $request->input('id_autor'),
            'id_genero' => $request->input('id_genero'),
            'id_categoria' => $request->input('id_categoria'),
            'id_repisa' => $request->input('id_repisa'),
            'id_editorial' => $request->input('id_editorial'),
            'cantidad' => $request->input('cantidad'),
            'descripcion' => $request->input('descripcion'),
            'disponible' => 1, // Por defecto, disponible
        ]);
    
        // Redirigir con mensaje de éxito
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
    










    public function autocomplete(Request $request)
{
    $type = $request->get('type');
    $query = $request->get('q');

    $results = [];
    switch ($type) {
        case 'autor':
            $results = Autor::where('nombre', 'like', "%$query%")->get(['id', 'nombre as text']);
            break;
        case 'genero':
            $results = Genero::where('nombre', 'like', "%$query%")->get(['id', 'nombre as text']);
            break;
        case 'categoria':
            $results = Categoria::where('nombre', 'like', "%$query%")->get(['id', 'nombre as text']);
            break;
        case 'editorial':
            $results = Editorial::where('nombre', 'like', "%$query%")->get(['id', 'nombre as text']);
            break;
    }

    return response()->json($results);
}





public function buscarAutores(Request $request)
{
    $query = $request->input('q');
    $autores = Autor::where('nombre', 'like', "%$query%")->get();
    return response()->json($autores);
}


// Método para obtener géneros por nombre
public function buscarGeneros(Request $request)
{
    $query = $request->input('q');
    $generos = Genero::where('nombre', 'like', "%$query%")->get();
    return response()->json($generos);
}

// Método para obtener categorías por nombre
public function buscarCategorias(Request $request)
{
    $query = $request->input('q');
    $categorias = Categoria::where('nombre', 'like', "%$query%")->get();
    return response()->json($categorias);
}

// Método para obtener editoriales por nombre
public function buscarEditoriales(Request $request)
{
    $query = $request->input('q');
    $editoriales = Editorial::where('nombre', 'like', "%$query%")->get();
    return response()->json($editoriales);
}








    // Función para buscar autores para autocompletar
    public function buscarAutor(Request $request)
    {
        $query = $request->input('q');
        $autores = Autor::where('nombre', 'like', "%$query%")
            ->get(['id', 'nombre']);
        return response()->json($autores);
    }

    // Función para buscar géneros para autocompletar
    public function buscarGenero(Request $request)
    {
        $query = $request->input('q');
        $generos = Genero::where('nombre', 'like', "%$query%")
            ->get(['id', 'nombre']);
        return response()->json($generos);
    }

    // Función para buscar categorías para autocompletar
    public function buscarCategoria(Request $request)
    {
        $query = $request->input('q');
        $categorias = Categoria::where('nombre', 'like', "%$query%")
            ->get(['id', 'nombre']);
        return response()->json($categorias);
    }

    // Función para buscar editoriales para autocompletar
    public function buscarEditorial(Request $request)
    {
        $query = $request->input('q');
        $editoriales = Editorial::where('nombre', 'like', "%$query%")
            ->get(['id', 'nombre']);
        return response()->json($editoriales);
    }

    // Función para buscar repisas para autocompletar
    public function buscarRepisa(Request $request)
    {
        $query = $request->input('q');
        $repisas = Repisa::where('numero', 'like', "%$query%")
            ->get(['id', 'numero']);
        return response()->json($repisas);
    }
}
