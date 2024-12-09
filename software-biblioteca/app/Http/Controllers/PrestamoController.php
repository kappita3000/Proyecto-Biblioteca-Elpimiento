<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrestamoAceptado;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'solicitudes'); // Pestaña predeterminada: "solicitudes"

        // Filtrar datos según la pestaña activa
        $solicitudes = Prestamo::whereNull('fecha_prestamo')
                                ->whereNull('fecha_rechazo')
                                ->orderby('fecha_solicitud')
                                ->paginate(20);

        $prestamos = Prestamo::whereNotNull('fecha_devolucion')
                                ->whereNull('devuelto')
                                ->whereNull('fecha_rechazo')
                                ->orderby('fecha_devolucion')
                                ->paginate(20);

        $completados = Prestamo::where('devuelto', 'Si')
                                ->orderby('fecha_devolucion')
                                ->paginate(20);

        $noDevueltos = Prestamo::where('devuelto', 'No')
                                ->orderby('fecha_devolucion')
                                ->paginate(20);

        $rechazados = Prestamo::whereNotNull('fecha_rechazo')
                                ->orderby('fecha_rechazo')
                                ->paginate(20);

        return view('prestamos.index', compact('tab', 'solicitudes', 'prestamos', 'completados', 'noDevueltos', 'rechazados'));
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

        return redirect()->route('prestamos.index', ['tab' => 'prestamos'])->with('success', 'Préstamo creado correctamente');
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

        return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('success', 'Préstamo actualizado correctamente');
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

            // Enviar correo de confirmación al usuario
            Mail::to($prestamo->usuario->correo)->send(new \App\Mail\PrestamoAceptado($prestamo));

            return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('success', 'Solicitud aceptada correctamente y correo enviado.');
        } else {
            return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('error', 'No hay copias disponibles para este libro.');
        }
    }



    public function rechazar(Request $request, $id)
    {
        // Validar el motivo del rechazo
        $request->validate([
            'motivo_rechazo' => 'required|string|max:255',
        ]);

        // Encontrar el préstamo
        $prestamo = Prestamo::findOrFail($id);

        // Registrar el rechazo
        $prestamo->fecha_rechazo = now();
        $prestamo->motivo_rechazo = $request->motivo_rechazo;
        $prestamo->save();

        // Enviar correo al usuario notificando el rechazo
        $usuario = $prestamo->usuario;
        $libro = $prestamo->libro;

        Mail::send('emails.rechazo', [
            'usuario' => $usuario,
            'libro' => $libro,
            'motivo' => $prestamo->motivo_rechazo,
        ], function ($message) use ($usuario) {
            $message->to($usuario->correo);
            $message->subject('Notificación de rechazo de préstamo');
        });

        return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('success', 'Solicitud rechazada y registrada correctamente.');
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

        // Enviar correo basado en el estado de devolución
        if ($request->devuelto === 'Si') {
            Mail::to($prestamo->usuario->correo)->send(new \App\Mail\PrestamoDevuelto($prestamo));
        } else {
            Mail::to($prestamo->usuario->correo)->send(new \App\Mail\PrestamoNoDevuelto($prestamo));
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('success', 'Devolución registrada correctamente.');
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

        $libro = Libro::findOrFail($validatedData['libro_id']);

        if ($libro->disponible == 0) {
            return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('error', 'El libro seleccionado no está disponible para préstamo.');
        }

        if ($libro->cantidad > $libro->copias_prestadas) {
            $prestamo = Prestamo::create([
                'id_usuario' => $validatedData['usuario_id'],
                'id_libro' => $validatedData['libro_id'],
                'fecha_solicitud' => now()->format('Y-m-d'),
                'fecha_prestamo' => $validatedData['fecha_prestamo'],
                'fecha_devolucion' => $validatedData['fecha_devolucion'],
                'tipo_usuario' => 'Registrado',
            ]);

            $libro->copias_prestadas += 1;

            if ($libro->copias_prestadas == $libro->cantidad) {
                $libro->disponible = 0;
            }

            $libro->save();

            // Enviar correo al usuario registrado
            $usuario = $prestamo->usuario;
            Mail::to($usuario->correo)->send(new PrestamoAceptado($prestamo, $usuario, $libro));

            return redirect()->route('prestamos.index', ['tab' => 'prestamos'])->with('success', 'Préstamo creado correctamente para usuario registrado.');
        } else {
            return redirect()->route('prestamos.index', ['tab' => 'prestamos'])->with('error', 'No hay copias disponibles para este libro.');
        }
    }



    public function storeNoRegistrado(Request $request)
    {
        $validatedData = $request->validate([
            'nombreUsuario' => 'required|string|max:255',
            'apellidoUsuario' => 'required|string|max:255',
            'correoUsuario' => 'required|email|max:255',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
        ]);

        $libro = Libro::findOrFail($validatedData['libro_id']);

        if ($libro->disponible == 0) {
            return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('error', 'El libro seleccionado no está disponible para préstamo.');
        }

        $usuario = Usuario::where('correo', $validatedData['correoUsuario'])->where('tipo_usuario', 'No Registrado')->first();

        if ($usuario) {
            $usuario->update([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
            ]);
        } else {
            $usuario = Usuario::create([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
                'correo' => $validatedData['correoUsuario'],
                'tipo_usuario' => 'No Registrado',
                'contraseña' => str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT),
            ]);
        }

        $prestamo = Prestamo::create([
            'id_usuario' => $usuario->id,
            'id_libro' => $validatedData['libro_id'],
            'fecha_solicitud' => now()->format('Y-m-d'),
            'fecha_prestamo' => $validatedData['fecha_prestamo'],
            'fecha_devolucion' => $validatedData['fecha_devolucion'],
            'tipo_usuario' => 'No Registrado',
        ]);

        $libro->copias_prestadas += 1;
        if ($libro->copias_prestadas == $libro->cantidad) {
            $libro->disponible = 0;
        }
        $libro->save();

        // Enviar correo al usuario no registrado
        Mail::to($usuario->correo)->send(new PrestamoAceptado($prestamo, $usuario, $libro));

        return redirect()->route('prestamos.index', ['tab' => 'prestamos'])->with('success', 'Préstamo creado correctamente para usuario no registrado.');
    }



}





