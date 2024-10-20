<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\GesLibro;
use App\Models\Categoria;
use App\Models\Autor;
use App\Models\Genero;
use App\Models\Repisa;

class GesLibroController extends Controller
{

    public function librosindex()
    {
            // Cargar autores, géneros y categorías
            $autores = Autor::all();
            $generos = Genero::all();
            $categorias = Categoria::all();
            $repisas = Repisa::all(); // Cargar las repisas, incluyendo el número de la repisa
        
            $libros = GesLibro::with(['autor', 'genero', 'categoria'])->get();
        
            return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias', 'repisas'));
        }
        public function edit($id)
        {
            // Obtener el libro por ID
            $libro = GesLibro::findOrFail($id);
        
            // Cargar autores, géneros, categorías y repisas
            $autores = Autor::all();
            $generos = Genero::all();
            $categorias = Categoria::all();
            $repisas = Repisa::all();
        
            // Pasar los datos a la vista para ser usados en los select
            return view('libros.librosindex', compact('libros', 'autores', 'generos', 'categorias', 'repisas'));
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
                'cantidad' => 'required|integer|min:1',
                'fecha_publicacion' => 'nullable|date',
                'descripcion' => 'nullable|string',
                'id_editorial' => 'nullable|integer|exists:editoriales,id',
            ]);
        
            // Convertir el valor de 'disponible' a 1 o 0
            $request->merge([
                'disponible' => $request->has('disponible') ? 1 : 0,
            ]);
        
            // Crear un nuevo libro con los datos del request
            GesLibro::create($request->all());
        
            // Redirigir a la página de libros con un mensaje de éxito
            return redirect()->route('libros.librosindex')->with('success', 'Libro creado exitosamente.');
        }
        

        public function update(Request $request, $id)
        {
            // Validar los datos de entrada
            $request->validate([
                'titulo' => 'required|max:255',
                'id_autor' => 'required|exists:autores,id',
                'id_genero' => 'required|exists:generos,id',
                'id_categoria' => 'required|exists:categorias,id',
                'id_repisa' => 'required|exists:repisas,id',
                'cantidad' => 'required|integer|min:1',
                'fecha_publicacion' => 'nullable|date',
                'descripcion' => 'nullable|string',
                'id_editorial' => 'nullable|integer|exists:editoriales,id',
            ]);
        
            // Encuentra el libro por su ID
            $libro = GesLibro::findOrFail($id);
        
            // Convertir el valor de 'disponible' a 1 o 0
            $request->merge([
                'disponible' => $request->has('disponible') ? 1 : 0,
            ]);
        
            // Actualiza los datos del libro con los datos del request
            $libro->update($request->all());
        
            // Redirigir a la página de libros con un mensaje de éxito
            return redirect()->route('libros.librosindex')->with('success', 'Libro actualizado exitosamente.');
        }
        public function destroy($id)
        {
             // Encuentra el libro por su ID
            $libro = GesLibro::findOrFail($id);

            // Elimina el libro
            $libro->delete();

            // Redirige a la lista de libros con un mensaje de éxito
            return redirect()->route('libros.librosindex')->with('success', 'Libro eliminado exitosamente.');
        }

        
}
