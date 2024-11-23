<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\genero;
use App\Models\usuario;
use App\Models\prestamo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PortadasController;
use Illuminate\Support\Facades\Storage;






class ReservaController extends Controller
{

    // Mostrar todos los libros
    public function index()
    {
        $libros = Libro::paginate(12);
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
        
        // Cargar todos los géneros para el formulario de filtros
        $generos = Genero::all();
    
        // Retornar la vista con los libros encontrados y los géneros
        return view('libros.index', compact('libros', 'generos'));
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
    
        // Lógica para usuarios autenticados
        if (Auth::check()) {
            $usuario = Auth::user(); // Usuario autenticado
        } else {
            // Verificar si el correo ya existe en la base de datos para usuarios no registrados
            $usuarioExistente = Usuario::where('correo', $validatedData['correoUsuario'])->first();
    
            if ($usuarioExistente) {
                // Si el correo ya está registrado, mostrar mensaje de error
                return redirect()->back()->with('error', 'Ya existe una cuenta con este correo. Inicia sesión para continuar.');
            }
    
            // Generar una contraseña aleatoria de 5 dígitos
        $contraseñaRandom = rand(10000, 99999);

        // Crear usuario como "No Registrado"
        $usuario = Usuario::create([
            'nombre' => $validatedData['nombreUsuario'],
            'apellido' => $validatedData['apellidoUsuario'],
            'correo' => $validatedData['correoUsuario'],
            'tipo_usuario' => 'No Registrado',
            'solicitudes' => 0,
            'contraseña' => bcrypt($contraseñaRandom), // Se guarda la contraseña encriptada
        ]);
        }
    
        // Crear el registro del préstamo
        $prestamo = Prestamo::create([
            'id_usuario' => $usuario->id,
            'id_libro' => $validatedData['id_libro'],
            'fecha_solicitud' => $validatedData['fecha_recoLibro'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        
    
        // Si la cantidad llega a 0, marcar como no disponible
        if ($libro->cantidad <= 0) {
            $libro->disponible = 0;
            $libro->save();
        }
    
        // Incrementar las solicitudes del usuario
        $usuario->increment('solicitudes');
    
        // Redireccionar con mensaje de éxito
        return redirect()->back()->with('success', 'El libro ha sido reservado correctamente.');
    }
    


}