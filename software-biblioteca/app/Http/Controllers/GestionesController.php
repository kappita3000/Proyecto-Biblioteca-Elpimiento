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
    return redirect()->route('gestiones.gestiones')->with('success', 'Autor agregado con éxito')->with('activeTab', 'autores');
    }

    // Crear género
    public function storeGenero(Request $request)
    {
        Genero::create($request->all());
     // return redirect()->route('gestiones.gestiones')->with('success', 'Género agregado con éxito')->with('activeTab', '#generos');
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





    public function getGenerosTabla()
    {
        $generos = Genero::all();
        return view('gestiones.partials.generos_tabla', compact('generos'))->render();
    }
    public function tablaGeneros()
    {
        $generos = Genero::all(); // Obtén los datos actualizados
        return view('gestiones.partials.generos_tabla', compact('generos')); // Retorna la vista parcial
    }
    




    public function updateAutor(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
        ]);
    
        // Encuentra el autor a actualizar
        $autor = Autor::findOrFail($id);
        $autor->nombre = $request->input('nombre');
        $autor->nacionalidad = $request->input('nacionalidad');
        $autor->save();
    
        // Redirige con éxito
        return redirect()->route('gestiones.gestiones')->with('success', 'Autor actualizado con éxito.')->with('activeTab', 'autores');
        
    }
    
    public function updateGenero(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
    
        // Encuentra el género a actualizar
        $genero = Genero::findOrFail($id);
        $genero->nombre = $request->input('nombre');
        $genero->save();
    
        // Redirige con éxito
    //return redirect()->route('gestiones.gestiones')->with('success', 'Género editado con éxito')->with('activeTab', '#generos');
    }
    
    public function updateCategoria(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
    
        // Encuentra la categoría a actualizar
        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->input('nombre');
        $categoria->save();
    
        // Redirige con éxito
        return redirect()->route('gestiones.gestiones')->with('success', 'Categoría actualizada con éxito.');
    }
    
    public function updateRepisa(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'numero' => 'required|numeric',
        ]);
    
        // Encuentra la repisa a actualizar
        $repisa = Repisa::findOrFail($id);
        $repisa->numero = $request->input('numero');
        $repisa->save();
    
        // Redirige con éxito
        return redirect()->route('gestiones.gestiones')->with('success', 'Repisa actualizada con éxito.');
    }
    
    public function updateEditorial(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
        ]);

        // Encuentra la editorial a actualizar
        $editorial = Editorial::findOrFail($id);
        $editorial->nombre = $request->input('nombre');
        $editorial->pais = $request->input('pais');
        $editorial->save();

        // Redirige con éxito
        return redirect()->route('gestiones.gestiones')->with('success', 'Editorial actualizada con éxito.');
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
    $genero = Genero::findOrFail($id);
    $genero->delete();

    return response()->json(['success' => true]);
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
