<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Genero;
use App\Models\autor;
use App\Models\Categoria;

class FiltrosController extends Controller

{

    public function filtrarPorCategoria(Request $request)
{
    // Obtener todas las categorías para la vista
    $categorias = Categoria::all();

    // Inicializar la consulta de libros
    $libros = Libro::query()->with('categoria', 'autor', 'genero');

    // Aplicar filtro por categoría si está presente
    if ($request->filled('category')) {
        $libros->where('id_categoria', $request->input('category'));
    }

    // Obtener los resultados paginados
    $libros = $libros->paginate(12);

    // Retornar la vista con los libros filtrados y las categorías
    return view('libros.index', compact('libros', 'categorias'));
}

    public function filtrarPorGenero(Request $request)
{
  // Obtener todos los géneros, autores y categorías para el formulario de filtros
  $generos = Genero::all();
  $autores = Autor::all();
  $categorias = Categoria::all();

  // Inicializar la consulta de libros
        $libros = Libro::query()
        ->join('autores', 'libros.id_autor', '=', 'autores.id')
        ->select('libros.*', 'autores.nombre as autor_nombre',)
        ->with('genero')
        ->with('categoria');

        
  // Aplicar filtro por género si está presente
  if ($request->filled('genre')) {
      $libros->where('id_genero', $request->input('genre'));
  }

  // Aplicar filtro por nombre del autor si está presente
  if ($request->filled('author_name')) {
      $authorName = $request->input('author_name');
      $nameParts = explode(' ', $authorName);
      $libros->where(function ($query) use ($nameParts) {
          foreach ($nameParts as $part) {
              $query->where('autores.nombre', 'like', '%' . $part . '%');
          }
      });
  }

  // Aplicar filtro por categoría si está presente
  if ($request->filled('category')) {
      $libros->where('id_categoria', $request->input('category'));
  }

  // Obtener los resultados paginados
  $libros = $libros->paginate(12);

  // Retornar la vista con los datos necesarios
  return view('libros.index', compact('libros', 'generos', 'autores', 'categorias'));
}
public function getUltimosLibros()
    {
        return Libro::latest()->with('autor', 'genero')->take(3)->get();
    }

    // Mostrar la vista con el carrusel
    public function index()
    {
        $ultimosLibros = $this->getUltimosLibros(); // Libros más recientes
        $categorias = Categoria::all(); // Todas las categorías
        $generos = Genero::all(); // Todos los géneros
        $autores = Autor::all(); // Todos los autores
    
        return view('libros.index', [
            'ultimosLibros' => $ultimosLibros,
            'categorias' => $categorias,
            'generos' => $generos,
            'autores' => $autores,
        ]);
    }
    
}
?>
