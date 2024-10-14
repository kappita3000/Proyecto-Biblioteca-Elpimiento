<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\usuario;
use App\Models\prestamo;

class ReservaController extends Controller
{
    // Mostrar todos los libros
    public function index()
    {
        $libros = Libro::paginate(9);
        
        return view('libros.index', compact('libros'));
    }
   
    // Mostrar un libro específico
    public function show($id)
    {
        $libro = Libro::findOrFail($id); // Obtiene el libro por ID
        return view('libros.show', compact('libro'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query'); // Obtener el texto de la búsqueda
        
        // Filtrar los libros que coincidan con el título o partes del título
        $libros = Libro::where('titulo', 'like', "%$query%")->paginate(9);
        
        // Retornar la vista con los libros encontrados
        return view('libros.index', compact('libros'));
    }
    


}