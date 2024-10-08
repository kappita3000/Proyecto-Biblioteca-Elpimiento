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
      
        // Cargar libros con todas las relaciones
        $libros = Libro::with(['autor', 'genero', 'categoria', 'repisa', 'editorial'])->get();
        return view('libros.index', compact('libros'));
    }

    // Mostrar un libro específico
    public function show($id)
    {
        $libro = Libro::findOrFail($id); // Obtiene el libro por ID
        return view('libros.show', compact('libro'));
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|max:100',
            'fecha' => 'required|date',
            'libro_id' => 'required|exists:libros,ID',
        ]);

        // Guardar el usuario
        $usuario = Usuario::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Correo' => $request->correo,
        ]);

        // Guardar el préstamo
        $prestamo = Prestamo::create([
            'ID_Usuario' => $usuario->ID,
            'ID_Libro' => $request->libro_id,
            'fecha_recoLibro' => $request->fecha,
        ]);

        // Actualizar la cantidad de libros y disponibilidad
        $libro = Libro::findOrFail($request->libro_id);
        $libro->Cantidad -= 1; // Restar una unidad a la cantidad de libros

        // Si la cantidad de libros es 0, marcar como no disponible
        if ($libro->Cantidad <= 0) {
            $libro->Disponible = 0;
        }

        $libro->save();

        return redirect()->back()->with('success', '¡Reserva realizada con éxito!');
    }
}