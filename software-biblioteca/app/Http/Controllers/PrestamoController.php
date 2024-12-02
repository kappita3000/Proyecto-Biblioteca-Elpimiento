<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Solicitudes pendientes (sin fecha de préstamo)
        $solicitudes = Prestamo::whereNull('fecha_prestamo')->orderby('fecha_solicitud')->paginate(20);

        // Préstamos activos (con fecha de devolución, sin devuelto)
        $prestamos = Prestamo::whereNotNull('fecha_devolucion')->whereNull('devuelto')->orderby('fecha_devolucion')->paginate(20);

        // Préstamos completados (todos los campos rellenados con devuelto en 'Si')
        $completados = Prestamo::whereNotNull('fecha_prestamo')
                                ->whereNotNull('fecha_devolucion')
                                ->where('devuelto', 'Si')
                                ->orderby('fecha_devolucion')
                                ->paginate(20);

        // Préstamos no devueltos (devuelto en 'No')
        $noDevueltos = Prestamo::whereNotNull('fecha_prestamo')
                                ->whereNotNull('fecha_devolucion')
                                ->where('devuelto', 'No')
                                ->orderby('fecha_devolucion')
                                ->paginate(20);

        // Obtener todos los usuarios registrados para el select
        $usuarios = Usuario::where('tipo_usuario', 'Registrado')->orderby('correo')->get();

        // Obtener todos los libros disponibles
        $libros = Libro::all();

        // Pasar todas las variables necesarias a la vista
        return view('prestamos.index', compact('solicitudes', 'prestamos', 'completados', 'noDevueltos', 'usuarios', 'libros'));
    }

    public function store(Request $request)
    {
        // Validar el formulario
        $validatedData = $request->validate([
            'usuario_id' => 'required_if:tipo_usuario,registrado|exists:usuarios,id',
            'nombreUsuario' => 'required_if:tipo_usuario,no_registrado|string',
            'apellidoUsuario' => 'required_if:tipo_usuario,no_registrado|string',
            'correoUsuario' => 'required_if:tipo_usuario,no_registrado|email',
            'libro_id' => 'required|exists:libros,id', // Asegúrate de que el ID del libro sea válido
            // Otros campos según lo necesario
        ]);

        if ($request->tipo_usuario == 'registrado') {
            // Lógica para usuario registrado
            $prestamo = Prestamo::create([
                'id_usuario' => $validatedData['usuario_id'],
                'fecha_solicitud' => now(), // Ajusta la fecha según sea necesario
                'libro_id' => $validatedData['libro_id'],
                // Otros campos según lo necesario
            ]);
        } else {
            // Lógica para usuario no registrado
            $usuario = Usuario::create([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
                'correo' => $validatedData['correoUsuario'],
                'tipo_usuario' => 'No Registrado',
            ]);

            $prestamo = Prestamo::create([
                'id_usuario' => $usuario->id, // ID del nuevo usuario no registrado
                'fecha_solicitud' => now(), // Ajusta la fecha según sea necesario
                'libro_id' => $validatedData['libro_id'],
                // Otros campos según lo necesario
            ]);
        }

        return redirect()->back()->with('success', 'Préstamo creado correctamente');
    }


    public function update(Request $request, $id)
    {
        // Validar los campos
        $request->validate([
            'fecha_prestamo' => 'nullable|date',
            'fecha_devolucion' => 'nullable|date',
            'devolucion' => 'required|in:Si,No',
        ]);

        // Encontrar el préstamo por ID
        $prestamo = Prestamo::findOrFail($id);

        // Actualizar los campos
        $prestamo->update([
            'fecha_prestamo' => $request->fecha_prestamo,
            'fecha_devolucion' => $request->fecha_devolucion,
            'devuelto' => $request->devolucion,
        ]);

        return redirect()->route('prestamos.index')->with('success', 'Préstamo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function aceptar($id)
    {
        // Encontrar el préstamo
        $prestamo = Prestamo::findOrFail($id);
        
        // Obtener el libro correspondiente al préstamo
        $libro = $prestamo->libro;

        // Comprobar si hay copias disponibles
        if ($libro->cantidad > 0 && $libro->cantidad > $libro->copias_prestadas) {
            // Registrar la fecha de préstamo
            $prestamo->fecha_prestamo = now();
            $prestamo->fecha_devolucion = request('fecha_devolucion'); // Obtener fecha de devolución desde el formulario
            $prestamo->save();

            // Actualizar copias prestadas del libro
            $libro->copias_prestadas += 1;

            // Si la cantidad de copias prestadas es igual a la cantidad total de copias, marcar como no disponible
            if ($libro->copias_prestadas == $libro->cantidad) {
                $libro->disponible = 0;
            }

            $libro->save();

            return redirect()->route('prestamos.index')->with('success', 'Solicitud aceptada correctamente.');
        } else {
            return redirect()->route('prestamos.index')->with('error', 'No hay copias disponibles para este libro.');
        }
    }

    public function rechazar($id)
    {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->delete();

        return redirect()->route('prestamos.index')->with('success', 'Solicitud rechazada correctamente.');
    }

    public function registrarDevolucion(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'fecha_devolucion' => 'required|date',
            'devuelto' => 'required|in:Si,No',
        ]);

        // Buscar el préstamo
        $prestamo = Prestamo::findOrFail($id);

        // Actualizar los campos del préstamo
        if ($prestamo->fecha_devolucion != $request->fecha_devolucion) {
            $prestamo->fecha_devolucion = $request->fecha_devolucion;
        }
        $prestamo->devuelto = $request->devuelto;

        // Si se devolvió el libro, reducir la cantidad de copias prestadas
        if ($request->devuelto === 'Si') {
            $libro = $prestamo->libro;

            if ($libro->copias_prestadas > 0) {
                $libro->copias_prestadas -= 1;

                // Si la cantidad de copias prestadas es menor que la cantidad total de copias, marcar como disponible
                if ($libro->copias_prestadas < $libro->cantidad) {
                    $libro->disponible = 1;
                }

                $libro->save();
            }
        }

        // Guardar los cambios en el préstamo
        $prestamo->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('prestamos.index')->with('success', 'Devolución registrada correctamente.');
    }


    public function storeRegistrado(Request $request)
    {
        // Validar el formulario
        $validatedData = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
        ]);

        // Obtener el libro correspondiente al préstamo
        $libro = Libro::findOrFail($validatedData['libro_id']);

        // Comprobar si el libro está disponible
        if ($libro->disponible == 0) {
            return redirect()->route('prestamos.index')->with('error', 'El libro seleccionado no está disponible para préstamo.');
        }

        // Comprobar si hay copias disponibles
        if ($libro->cantidad > $libro->copias_prestadas) {
            // Crear el préstamo
            Prestamo::create([
                'id_usuario' => $validatedData['usuario_id'],
                'id_libro' => $validatedData['libro_id'],
                'fecha_solicitud' => now()->format('Y-m-d'),
                'fecha_prestamo' => $validatedData['fecha_prestamo'],
                'fecha_devolucion' => $validatedData['fecha_devolucion'],
                'tipo_usuario' => 'Registrado',
            ]);

            // Actualizar copias prestadas del libro
            $libro->copias_prestadas += 1;

            // Si las copias prestadas igualan a la cantidad total, marcar como no disponible
            if ($libro->copias_prestadas == $libro->cantidad) {
                $libro->disponible = 0;
            }

            $libro->save();

            return redirect()->route('prestamos.index')->with('success', 'Préstamo creado correctamente para usuario registrado.');
        } else {
            return redirect()->route('prestamos.index')->with('error', 'No hay copias disponibles para este libro.');
        }
    }

    public function storeNoRegistrado(Request $request)
    {
        // Validar el formulario
        $validatedData = $request->validate([
            'nombreUsuario' => 'required|string|max:255',
            'apellidoUsuario' => 'required|string|max:255',
            'correoUsuario' => 'required|email|max:255',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
        ]);

        // Buscar el libro por ID
        $libro = Libro::findOrFail($validatedData['libro_id']);

        // Verificar si el libro está disponible
        if ($libro->disponible == 0) {
            return redirect()->back()->with('error', 'El libro seleccionado no está disponible para préstamo.');
        }

        // Buscar si el usuario ya está registrado como no registrado
        $usuario = Usuario::where('correo', $validatedData['correoUsuario'])->where('tipo_usuario', 'No Registrado')->first();

        if ($usuario) {
            // Actualizar la información del usuario no registrado existente
            $usuario->update([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
            ]);
        } else {
            // Crear un nuevo usuario no registrado
            $usuario = Usuario::create([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
                'correo' => $validatedData['correoUsuario'],
                'tipo_usuario' => 'No Registrado',
                'contraseña' => str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT), // Contraseña aleatoria de 5 dígitos
            ]);
        }

        // Crear el préstamo
        Prestamo::create([
            'id_usuario' => $usuario->id,
            'id_libro' => $validatedData['libro_id'],
            'fecha_solicitud' => now()->format('Y-m-d'),
            'fecha_prestamo' => $validatedData['fecha_prestamo'],
            'fecha_devolucion' => $validatedData['fecha_devolucion'],
            'tipo_usuario' => 'No Registrado',
        ]);

        // Actualizar las copias prestadas y la disponibilidad del libro
        $libro->copias_prestadas += 1;
        if ($libro->copias_prestadas == $libro->cantidad) {
            $libro->disponible = 0; // Cambiar disponibilidad si todas las copias están prestadas
        }
        $libro->save();

        return redirect()->route('prestamos.index')->with('success', 'Préstamo creado correctamente para usuario no registrado.');
    }

}





