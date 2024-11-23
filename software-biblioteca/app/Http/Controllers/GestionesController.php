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
        // Paginación para cada modelo
        $autores = Autor::paginate(10);
        $generos = Genero::paginate(10);
        $categorias = Categoria::paginate(10);
        $repisas = Repisa::paginate(10);
        $editoriales = Editorial::paginate(10);

        // Retornamos la vista con los datos paginados
        return view('gestiones.gestiones', compact('autores', 'generos', 'categorias', 'repisas', 'editoriales'));
    }
    

    // Crear autor
    public function storeAutor(Request $request)
{
    Autor::create($request->all());
    return redirect()->route('gestiones.gestiones')->with('success', 'Autor agregado con éxito')->with('activeTab', 'autores');
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



    public function storeGenero(Request $request)
    {
        Genero::create($request->all());
    
        return redirect()->route('gestiones.gestiones')
            ->with('success', 'Género agregado con éxito')
            ->with('activeTab', 'generos');
    }
    
    public function updateGenero(Request $request, $id)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
    
        $genero = Genero::findOrFail($id);
        $genero->nombre = $request->input('nombre');
        $genero->save();
    
        return redirect()->route('gestiones.gestiones')
            ->with('success', 'Género actualizado con éxito')
            ->with('activeTab', 'generos');
    }
    
    public function deleteGenero($id)
    {
        $genero = Genero::findOrFail($id);
        $genero->delete();
    
        return redirect()->route('gestiones.gestiones')
            ->with('success', 'Género eliminado con éxito')
            ->with('activeTab', 'generos');
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












    public function bulkDeleteAutores(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Autor::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Autores eliminados correctamente.');
        }
        return redirect()->back()->with('error', 'Selecciona al menos un autor para eliminar.');
    }
    
    public function bulkDeleteGeneros(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Genero::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Géneros eliminados correctamente.');
        }
        return redirect()->back()->with('error', 'Selecciona al menos un género para eliminar.');
    }
    
    public function bulkDeleteCategorias(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Categoria::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Categorías eliminadas correctamente.');
        }
        return redirect()->back()->with('error', 'Selecciona al menos una categoría para eliminar.');
    }
    
    public function bulkDeleteRepisas(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Repisa::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Repisas eliminadas correctamente.');
        }
        return redirect()->back()->with('error', 'Selecciona al menos una repisa para eliminar.');
    }
    
    public function bulkDeleteEditoriales(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Editorial::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Editoriales eliminadas correctamente.');
        }
        return redirect()->back()->with('error', 'Selecciona al menos una editorial para eliminar.');
    }
    
    




}
