<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\usuario;
use App\Models\prestamo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


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
    
    public function reservar(Request $request)
    {
        // Validamos la fecha seleccionada
        $request->validate([
            'fecha_recoLibro' => 'required|date',
        ]);
    
        // Lógica para almacenar la reserva
        $reserva = new Reserva();
        $reserva->user_id = auth()->user()->id;  // ID del usuario autenticado
        $reserva->libro_id = $request->input('libro_id'); // Si también pasas el libro
        $reserva->fecha_recoLibro = $request->input('fecha_recoLibro');
        $reserva->save();
    
        return redirect()->route('libros.index')->with('success', 'Libro reservado con éxito.');
    }

    public function reservarLibro(Request $request)
{
    // Validar los datos
    $request->validate([
        'id_libro' => 'required|exists:libros,id',
        'fecha_recoLibro' => 'required|date',
        // Si el usuario no está autenticado, validar los datos del usuario
        'nombreUsuario' => 'required_if:guest,true',
        'apellidoUsuario' => 'required_if:guest,true',
        'correoUsuario' => 'required_if:guest,true|email',
    ]);

    // Crear el registro en la tabla de préstamos
    $prestamo = new Prestamo();
    
    if (Auth::check()) {
        // Usuario autenticado
        $prestamo->id_usuario = Auth::id(); // Guardar el ID del usuario autenticado
    } else {
        // Usuario invitado, puedes guardar datos del invitado en otra tabla si es necesario
        // o en campos adicionales en la tabla de préstamos si fuera necesario.
        // $prestamo->nombre_invitado = $request->nombreUsuario;
    }

    // Guardar el ID del libro y la fecha de solicitud
    $prestamo->id_libro = $request->id_libro;
    $prestamo->fecha_solicitud = $request->fecha_recoLibro;

    // Guardar el registro en la base de datos
    $prestamo->save();

    return redirect()->back()->with('success', 'El libro ha sido reservado correctamente.');
}


public function reservarLibro_noregis(Request $request)
{
    // Validar los datos enviados desde el formulario
    $validatedData = $request->validate([
        'nombreUsuario' => 'required|string',
        'apellidoUsuario' => 'required|string',
        'correoUsuario' => 'required|email',
        'fecha_recoLibro' => 'required|date',
        'id_libro' => 'required|integer',  // Validamos que venga el ID del libro
    ]);

    // Si no está autenticado, crear el usuario como "No Registrado"
    $usuario = Usuario::create([
        'nombre' => $validatedData['nombreUsuario'],
        'apellido' => $validatedData['apellidoUsuario'],
        'correo' => $validatedData['correoUsuario'],
        'tipo_usuario' => 'No Registrado',
       
    ]);

    // Ahora guardamos el préstamo en la base de datos
    $prestamo = Prestamo::create([
        'id_usuario' => $usuario->id,               // ID del usuario no registrado
        'id_libro' => $validatedData['id_libro'],   // ID del libro
        'fecha_solicitud' => $validatedData['fecha_recoLibro'], // Fecha de recolección
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Redireccionamos o regresamos con un mensaje de éxito
    return redirect()->back()->with('success', 'Reserva realizada con éxito');
}



}