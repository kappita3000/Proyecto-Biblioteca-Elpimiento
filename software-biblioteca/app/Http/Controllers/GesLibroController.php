<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GesLibro;
use App\Models\Categoria;
use App\Models\Autor;
use App\Models\Genero;
use App\Models\Repisa;
use App\Models\Editorial;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GesLibroController extends Controller
{
    // Listar libros con datos relacionados
    public function librosindex()
    {
        $autores = Autor::all();
        $generos = Genero::all();
        $categorias = Categoria::all();
        $repisas = Repisa::all();
        $editoriales = Editorial::all();

        $libros = GesLibro::with(['autor', 'genero', 'categoria', 'editorial'])->paginate(20);

        return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias', 'repisas', 'editoriales'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $libro = GesLibro::findOrFail($id);
        $autores = Autor::all();
        $generos = Genero::all();
        $categorias = Categoria::all();
        $repisas = Repisa::all();
        $editoriales = Editorial::all();

        return view('libros.librosindex', compact('libro', 'autores', 'generos', 'categorias', 'repisas', 'editoriales'));
    }

    // Crear un nuevo libro
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'autor' => 'required|exists:autores,nombre', // Verifica que el autor exista
            'genero' => 'required|exists:generos,nombre', // Verifica que el género exista
            'categoria' => 'required|exists:categorias,nombre', // Verifica que la categoría exista
            'repisa' => 'required|exists:repisas,numero', // Verifica que la repisa exista
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string',
            'editorial' => 'required|exists:editoriales,nombre', // Verifica que la editorial exista
        ], [
            // Mensajes personalizados
            'autor.exists' => 'El autor ingresado no existe.',
            'genero.exists' => 'El género ingresado no existe.',
            'categoria.exists' => 'La categoría ingresada no existe.',
            'repisa.exists' => 'La repisa ingresada no existe.',
            'editorial.exists' => 'La editorial ingresada no existe.',
        ]);
    
        // Crear el libro
        $autor = Autor::where('nombre', $request->autor)->first();
        $genero = Genero::where('nombre', $request->genero)->first();
        $categoria = Categoria::where('nombre', $request->categoria)->first();
        $repisa = Repisa::where('numero', $request->repisa)->first();
        $editorial = Editorial::where('nombre', $request->editorial)->first();
    
        GesLibro::create([
            'titulo' => $request->titulo,
            'id_autor' => $autor->id,
            'id_genero' => $genero->id,
            'id_categoria' => $categoria->id,
            'id_repisa' => $repisa->id,
            'id_editorial' => $editorial->id,
            'cantidad' => $request->cantidad,
            'descripcion' => $request->descripcion,
            'disponible' => true, // Siempre disponible
        ]);
    
        return redirect()->route('libros.librosindex')->with('success', 'Libro creado exitosamente.');
    }
    
    

    // Actualizar un libro existente
    public function update(Request $request, $id)
    {
        try {
            // Validación de datos
            $request->validate([
                'titulo' => 'required|max:255',
                'autor' => 'required|string',
                'genero' => 'required|string',
                'categoria' => 'required|string',
                'repisa' => 'required|string',
                'cantidad' => 'required|integer|min:1',
                'descripcion' => 'nullable|string',
                'editorial' => 'required|string|max:255',
            ]);
    
            // Buscar el libro
            $libro = GesLibro::findOrFail($id);
    
            // Validar entidades relacionadas
            $autor = Autor::where('nombre', $request->autor)->first();
            if (!$autor) {
                return back()->withErrors(['autor' => 'El autor especificado no existe.'])->withInput();
            }
    
            $genero = Genero::where('nombre', $request->genero)->first();
            if (!$genero) {
                return back()->withErrors(['genero' => 'El género especificado no existe.'])->withInput();
            }
    
            $categoria = Categoria::where('nombre', $request->categoria)->first();
            if (!$categoria) {
                return back()->withErrors(['categoria' => 'La categoría especificada no existe.'])->withInput();
            }
    
            $repisa = Repisa::where('numero', $request->repisa)->first();
            if (!$repisa) {
                return back()->withErrors(['repisa' => 'La repisa especificada no existe.'])->withInput();
            }
    
            $editorial = Editorial::firstOrCreate(['nombre' => $request->editorial]);
    
            // Actualizar el libro
            $libro->update([
                'titulo' => $request->titulo,
                'id_autor' => $autor->id,
                'id_genero' => $genero->id,
                'id_categoria' => $categoria->id,
                'id_repisa' => $repisa->id,
                'id_editorial' => $editorial->id,
                'cantidad' => $request->cantidad,
                'descripcion' => $request->descripcion,
                'disponible' => $request->has('disponible'),
            ]);
    
            return redirect()->route('libros.librosindex')->with('success', 'Libro actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error inesperado.'])->withInput();
        }
    }
    
    
    

    // Eliminar libro
    public function destroy($id)
    {
        $libro = GesLibro::findOrFail($id);
        $libro->delete();

        return redirect()->route('libros.librosindex')->with('success', 'Libro eliminado exitosamente.');
    }

    // Búsqueda de libros
    public function buscar(Request $request)
    {
        $query = $request->input('q');
        $libros = GesLibro::where('titulo', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'titulo']);
        return response()->json($libros);
    }

    // Autocompletado de entidades
    public function autocomplete(Request $request)
    {
        $type = $request->query('type');
        $query = $request->query('q');
    
        if (!$type || !$query) {
            return response()->json([]);
        }
    
        $results = match ($type) {
            'autor' => Autor::where('nombre', 'LIKE', "%$query%")->get(['id', 'nombre as text']),
            'genero' => Genero::where('nombre', 'LIKE', "%$query%")->get(['id', 'nombre as text']),
            'categoria' => Categoria::where('nombre', 'LIKE', "%$query%")->get(['id', 'nombre as text']),
            'editorial' => Editorial::where('nombre', 'LIKE', "%$query%")->get(['id', 'nombre as text']),
            'repisa' => Repisa::where('numero', 'LIKE', "%$query%")->get(['id', 'numero as text']),
            default => [],
        };
    
        return response()->json($results);
    }
    

    // Métodos específicos para cada entidad
    public function buscarAutores(Request $request)
    {
        $query = $request->input('q');
        $autores = Autor::where('nombre', 'like', "%$query%")->get();
        return response()->json($autores);
    }

    public function buscarGeneros(Request $request)
    {
        $query = $request->input('q');
        $generos = Genero::where('nombre', 'like', "%$query%")->get();
        return response()->json($generos);
    }

    public function buscarCategorias(Request $request)
    {
        $query = $request->input('q');
        $categorias = Categoria::where('nombre', 'like', "%$query%")->get();
        return response()->json($categorias);
    }

    public function buscarEditoriales(Request $request)
    {
        $query = $request->input('q');
        $editoriales = Editorial::where('nombre', 'like', "%$query%")->get();
        return response()->json($editoriales);
    }

    public function buscarRepisa(Request $request)
    {
        $query = $request->input('q');
        $repisas = Repisa::where('numero', 'like', "%$query%")->get(['id', 'numero']);
        return response()->json($repisas);
    }
}
