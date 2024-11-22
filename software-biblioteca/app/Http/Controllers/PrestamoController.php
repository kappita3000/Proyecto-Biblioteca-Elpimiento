<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Solicitudes pendientes (sin fecha de préstamo)
        $solicitudes = Prestamo::whereNull('fecha_prestamo')->orderby('fecha_solicitud')
                                                            ->get();

        // Préstamos activos (con fecha de préstamo, sin fecha de devolución)
        $prestamos = Prestamo::whereNotNull('fecha_prestamo')->whereNull('fecha_devolucion')
                                                            ->orderby('fecha_prestamo')
                                                            ->get();

        // Préstamos completados (todos los campos rellenados)
        $completados = Prestamo::whereNotNull('fecha_prestamo')
                                ->whereNotNull('fecha_devolucion')
                                ->whereNotNull('devuelto')
                                ->orderby('fecha_devolucion')
                                ->get();

        // Obtener todos los usuarios registrados para el select
        $usuarios = Usuario::where('tipo_usuario','Registrado')
                            ->orderby('correo')
                            ->get();
        // Obtener todos los libros disponibles
        $libros = Libro::all();
        return view('prestamos.index', compact('solicitudes', 'prestamos', 'completados', 'usuarios', 'libros'));
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
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->fecha_prestamo = now();
        $prestamo->save();

        return redirect()->route('prestamos.index')->with('success', 'Solicitud aceptada correctamente.');
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

        // Actualizar los campos
        $prestamo->fecha_devolucion = $request->fecha_devolucion;
        $prestamo->devuelto = $request->devuelto;

        // Guardar los cambios
        $prestamo->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('prestamos.index')->with('success', 'Devolución registrada correctamente');
    }

    public function storeRegistrado(Request $request)
    {
        // Validar el formulario
        $validatedData = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_solicitud' => 'required|date',
            'fecha_prestamo' => 'required|date',
        ]);

        // Crear el préstamo
        Prestamo::create([
            'id_usuario' => $validatedData['usuario_id'],
            'id_libro' => $validatedData['libro_id'],
            'fecha_solicitud' => $validatedData['fecha_solicitud'],
            'fecha_prestamo' => $validatedData['fecha_prestamo'],
        ]);

        return redirect()->route('prestamos.index')->with('success', 'Préstamo creado correctamente para usuario registrado.');
    }

    public function storeNoRegistrado(Request $request)
    {
        // Validar el formulario
        $validatedData = $request->validate([
            'nombreUsuario' => 'required|string',
            'apellidoUsuario' => 'required|string',
            'correoUsuario' => 'required|email',
            'libro_id' => 'required|exists:libros,id',
            'fecha_solicitud' => 'required|date',
            'fecha_prestamo' => 'required|date',
        ]);

        // Verificar si el correo ya está registrado
        $usuarioExistente = Usuario::where('correo', $validatedData['correoUsuario'])->first();

        if ($usuarioExistente) {
            if ($usuarioExistente->tipo_usuario === 'No Registrado') {
                // Actualizar el usuario existente
                $usuarioExistente->update([
                    'nombre' => $validatedData['nombreUsuario'],
                    'apellido' => $validatedData['apellidoUsuario'],
                    'solicitudes' => $usuarioExistente->solicitudes + 1,
                ]);

                // Crear el préstamo
                Prestamo::create([
                    'id_usuario' => $usuarioExistente->id,
                    'id_libro' => $validatedData['libro_id'],
                    'fecha_solicitud' => $validatedData['fecha_solicitud'],
                    'fecha_prestamo' => $validatedData['fecha_prestamo'],
                ]);

                return redirect()->route('prestamos.index')->with('success', 'Préstamo creado correctamente para el usuario no registrado.');
            } else {
                return back()->withErrors(['correoUsuario' => 'Ya existe una cuenta con este correo registrada. Por favor, inicie sesión.']);
            }
        } else {
            // Crear un nuevo usuario no registrado
            $usuario = Usuario::create([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
                'correo' => $validatedData['correoUsuario'],
                'tipo_usuario' => 'No Registrado',
            ]);

            // Crear el préstamo
            Prestamo::create([
                'id_usuario' => $usuario->id,
                'id_libro' => $validatedData['libro_id'],
                'fecha_solicitud' => $validatedData['fecha_solicitud'],
                'fecha_prestamo' => $validatedData['fecha_prestamo'],
            ]);

            return redirect()->route('prestamos.index')->with('success', 'Préstamo creado correctamente para el nuevo usuario no registrado.');
        }
    }
}





