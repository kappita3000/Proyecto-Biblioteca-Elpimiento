<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Genero;
use App\Models\autor;

class FiltrosController extends Controller

{

    
    public function filtrarPorGenero(Request $request)
{
      // Obtener todos los géneros y autores para la vista
    $generos = Genero::all();
    $autores = Autor::all();
    
    // Inicializar la consulta de libros con un join a la tabla de autores
    $libros = Libro::query()
                ->join('autores', 'libros.id_autor', '=', 'autores.id')
                ->select('libros.*', 'autores.nombre as autor_nombre',)
                ->with('genero');

    // Aplicar filtro por género si está presente
    if ($request->filled('genre')) {
        $generoId = $request->input('genre');
        $libros->where('id_genero', $generoId);
    }

    // Aplicar filtro por nombre del autor si está presente
    if ($request->filled('author_name')) {
        $authorName = $request->input('author_name');

        // Separar el nombre en palabras
        $nameParts = explode(' ', $authorName);

        $libros->where(function($query) use ($nameParts) {
            foreach ($nameParts as $part) {
                $query->where(function($subQuery) use ($part) {
                    $subQuery->where('autores.nombre', 'like', '%' . $part . '%');
                             
                });
            }
        });
    }

    // Obtener los resultados paginados
    $libros = $libros->paginate(12);

    // Retornar la vista con los libros, géneros y autores
    
    return view('libros.index', compact('libros', 'generos', 'autores'));
}
public function getUltimosLibros()
    {
        return Libro::latest()->with('autor', 'genero')->take(3)->get();
    }

    // Mostrar la vista con el carrusel
    public function index()
    {
        $ultimosLibros = $this->getUltimosLibros();
        return view('libros.index', ['ultimosLibros' => $ultimosLibros])->with('ultimosLibros', $ultimosLibros);
    }
}
?>
