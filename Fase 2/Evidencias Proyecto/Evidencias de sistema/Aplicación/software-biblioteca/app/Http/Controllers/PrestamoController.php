<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrestamoAceptado;
use Illuminate\Support\Facades\Hash;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'solicitudes'); // Pestaña predeterminada: "solicitudes"
        $year = $request->get('year', now()->year);
        $month = $request->get('month', null);

        // Obtener años únicos de la tabla Prestamos
        $years = Prestamo::selectRaw('YEAR(fecha_solicitud) as year')
            ->union(
                Prestamo::selectRaw('YEAR(fecha_devolucion) as year')
            )
            ->union(
                Prestamo::selectRaw('YEAR(fecha_rechazo) as year')
            )
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Filtrar datos según la pestaña activa
        $solicitudes = Prestamo::whereNull('fecha_prestamo')
            ->whereNull('fecha_rechazo')
            ->when($year, function ($query, $year) {
                $query->whereYear('fecha_solicitud', $year);
            })
            ->when($month, function ($query, $month) {
                $query->whereMonth('fecha_solicitud', $month);
            })
            ->orderby('fecha_solicitud')
            ->paginate(20);

        $prestamos = Prestamo::whereNotNull('fecha_devolucion')
            ->whereNull('devuelto')
            ->whereNull('fecha_rechazo')
            ->when($year, function ($query, $year) {
                $query->whereYear('fecha_devolucion', $year);
            })
            ->when($month, function ($query, $month) {
                $query->whereMonth('fecha_devolucion', $month);
            })
            ->orderby('fecha_devolucion')
            ->paginate(20);

        $completados = Prestamo::where('devuelto', 'Si')
            ->when($year, function ($query, $year) {
                $query->whereYear('fecha_devolucion', $year);
            })
            ->when($month, function ($query, $month) {
                $query->whereMonth('fecha_devolucion', $month);
            })
            ->orderby('fecha_devolucion')
            ->paginate(20);

        $noDevueltos = Prestamo::where('devuelto', 'No')
            ->when($year, function ($query, $year) {
                $query->whereYear('fecha_devolucion', $year);
            })
            ->when($month, function ($query, $month) {
                $query->whereMonth('fecha_devolucion', $month);
            })
            ->orderby('fecha_devolucion')
            ->paginate(20);

        $rechazados = Prestamo::whereNotNull('fecha_rechazo')
            ->when($year, function ($query, $year) {
                $query->whereYear('fecha_rechazo', $year);
            })
            ->when($month, function ($query, $month) {
                $query->whereMonth('fecha_rechazo', $month);
            })
            ->orderby('fecha_rechazo')
            ->paginate(20);

        return view('prestamos.index', compact('tab', 'years', 'solicitudes', 'prestamos', 'completados', 'noDevueltos', 'rechazados'));
    }


    public function aceptar($id)
    {
        // Encontrar el préstamo
        $prestamo = Prestamo::findOrFail($id);

        // Verificar si el libro asociado existe
        if ($prestamo->id_libro && $prestamo->libro) {
            $libro = $prestamo->libro;

            // Comprobar si hay copias disponibles
            if ($libro->cantidad > 0 && $libro->cantidad > $libro->copias_prestadas) {
                // Registrar la fecha de préstamo
                $prestamo->fecha_prestamo = now();
                $prestamo->fecha_devolucion = request('fecha_devolucion'); // Obtener fecha de devolución desde el formulario
                $prestamo->save();

                // Actualizar copias prestadas del libro
                $libro->copias_prestadas += 1;

                // Si las copias prestadas igualan la cantidad total, marcar como no disponible
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
        } else {
            return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('error', 'El libro asociado no existe. No se puede aceptar el préstamo.');
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

        Mail::send('emails.rechazo', [
            'usuario' => $usuario,
            'prestamo' => $prestamo,
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

        // Si se devolvió el libro, intentar reducir la cantidad de copias prestadas
        if ($request->devuelto === 'Si' && !is_null($prestamo->id_libro)) {
            $libro = Libro::find($prestamo->id_libro);

            if ($libro && $libro->copias_prestadas > 0) {
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
        $correo = $request->devuelto === 'Si'
            ? new \App\Mail\PrestamoDevuelto($prestamo)
            : new \App\Mail\PrestamoNoDevuelto($prestamo);

        Mail::to($prestamo->usuario->correo)->send($correo);

        // Redirigir con mensaje de éxito
        return redirect()->route('prestamos.index', ['tab' => 'prestamos'])->with('success', 'Devolución registrada correctamente.');
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
                'titulo_libro' => $libro->titulo, // Asignar título del libro
                'editorial_libro' => $libro->editorial->nombre ?? 'Sin Editorial', // Asignar nombre de la editorial o valor por defecto
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

        // Verificar disponibilidad del libro
        if ($libro->disponible == 0) {
            return redirect()->route('prestamos.index', ['tab' => 'solicitudes'])->with('error', 'El libro seleccionado no está disponible para préstamo.');
        }

        // Buscar o crear el usuario no registrado
        $usuario = Usuario::where('correo', $validatedData['correoUsuario'])->where('tipo_usuario', 'No Registrado')->first();

        if ($usuario) {
            // Actualizar datos del usuario no registrado existente
            $usuario->update([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
            ]);
        } else {
            // Generar una contraseña aleatoria de 5 dígitos y encriptarla
            $contraseñaRandom = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

            // Crear un nuevo usuario no registrado
            $usuario = Usuario::create([
                'nombre' => $validatedData['nombreUsuario'],
                'apellido' => $validatedData['apellidoUsuario'],
                'correo' => $validatedData['correoUsuario'],
                'tipo_usuario' => 'No Registrado',
                'contraseña' => Hash::make($contraseñaRandom), // Encriptar contraseña
            ]);
        }

        // Crear el préstamo y asignar los campos adicionales
        $prestamo = Prestamo::create([
            'id_usuario' => $usuario->id,
            'id_libro' => $validatedData['libro_id'],
            'titulo_libro' => $libro->titulo,
            'editorial_libro' => $libro->editorial->nombre ?? 'Editorial desconocida', // Manejar caso de editorial nula
            'fecha_solicitud' => now()->format('Y-m-d'),
            'fecha_prestamo' => $validatedData['fecha_prestamo'],
            'fecha_devolucion' => $validatedData['fecha_devolucion'],
            'tipo_usuario' => 'No Registrado',
        ]);

        // Actualizar cantidad de copias prestadas
        $libro->copias_prestadas += 1;
        if ($libro->copias_prestadas == $libro->cantidad) {
            $libro->disponible = 0; // Marcar como no disponible si todas las copias están prestadas
        }
        $libro->save();

        // Enviar correo al usuario no registrado
        Mail::to($usuario->correo)->send(new PrestamoAceptado($prestamo, $usuario, $libro));

        return redirect()->route('prestamos.index', ['tab' => 'prestamos'])->with('success', 'Préstamo creado correctamente para usuario no registrado.');
    }

    public function registrarDevolucionNo(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fecha_devolucion' => 'required|date|after_or_equal:' . Prestamo::findOrFail($id)->fecha_prestamo,
        ]);

        $prestamo = Prestamo::findOrFail($id);
        $prestamo->fecha_devolucion = $validatedData['fecha_devolucion'];
        $prestamo->devuelto = 'Si';

        // Actualizar las copias prestadas del libro asociado
        if (!is_null($prestamo->id_libro)) {
            $libro = Libro::find($prestamo->id_libro);
            if ($libro) {
                $libro->copias_prestadas -= 1;
                if ($libro->copias_prestadas < $libro->cantidad) {
                    $libro->disponible = 1;
                }
                $libro->save();
            }
        }

        $prestamo->save();

        // Reutilizar el mailable PrestamoDevuelto
        try {
            Mail::to($prestamo->usuario->correo)->send(new \App\Mail\PrestamoDevuelto($prestamo));
        } catch (\Exception $e) {
            return redirect()->route('prestamos.index', ['tab' => 'noDevueltos'])
                ->with('error', 'Devolución registrada correctamente, pero no se pudo enviar el correo.');
        }

        return redirect()->route('prestamos.index', ['tab' => 'noDevueltos'])->with('success', 'Devolución registrada correctamente y correo enviado.');
    }


}