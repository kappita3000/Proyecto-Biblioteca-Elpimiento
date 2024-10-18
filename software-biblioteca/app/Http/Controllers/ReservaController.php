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
        $generos = \App\Models\Genero::all(); // Obtener todos los géneros

        return view('libros.index', compact('libros', 'generos'));
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
    
    public function reservarLibro(Request $request)
{
    // Validar los datos enviados desde el formulario
    $validatedData = $request->validate([
        'id_libro' => 'required|exists:libros,id',
        'fecha_recoLibro' => 'required|date',
        'nombreUsuario' => 'required_if:guest,true|string',
        'apellidoUsuario' => 'required_if:guest,true|string',
        'correoUsuario' => 'required_if:guest,true|email',
    ]);

    $libro = Libro::findOrFail($request->id_libro);

    // Verificar que el libro tenga existencias disponibles
    if ($libro->cantidad <= 0) {
        return redirect()->back()->with('error', 'El libro no tiene existencias disponibles.');
    }

    // Verificar si el correo ya está en uso por un usuario registrado
    $usuarioExistente = Usuario::where('correo', $validatedData['correoUsuario'])
        ->where('tipo_usuario', 'Registrado')
        ->first();

    if ($usuarioExistente) {
        return redirect()->back()->with('error', 'Ya existe una cuenta con este correo. Inicia sesión para continuar.');
    }

    // Determinar si es un usuario autenticado o no
    if (Auth::check()) {
        // Usuario autenticado
        $usuario = Auth::user();
    } else {
        // Usuario no autenticado, crear o encontrar el usuario por correo
        $usuario = Usuario::firstOrCreate(
            ['correo' => $validatedData['correoUsuario']],
            [
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
                'tipo_usuario' => 'No Registrado',
                'solicitudes' => 0, // Inicializa en 0
            ]
        );
    }

    // Crear el registro del préstamo
    $prestamo = Prestamo::create([
        'id_usuario' => $usuario->id,
        'id_libro' => $validatedData['id_libro'],
        'fecha_solicitud' => $validatedData['fecha_recoLibro'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Decrementar la cantidad del libro en 1
    $libro->decrement('cantidad');

    // Si la cantidad llega a 0, marcar como no disponible
    if ($libro->fresh()->cantidad <= 0) {
        $libro->update(['disponible' => 0]);
    }

    // Incrementar las solicitudes del usuario
    $usuario->increment('solicitudes');

    // Redireccionar con mensaje de éxito
    return redirect()->back()->with('success', 'El libro ha sido reservado correctamente.');
}


}