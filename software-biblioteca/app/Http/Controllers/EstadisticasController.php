<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function index(Request $request)
    {
        $years = Prestamo::select(DB::raw('YEAR(fecha_prestamo) as year'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $year = $request->input('year', date('Y')); // Año actual por defecto
        $month = $request->input('month', null);   // Mes filtrado (opcional)

        // Nuevas consultas para modals y tarjetas
        $usuariosPrestamosActivos = Prestamo::whereNull('devuelto')
            ->whereNull('fecha_rechazo')
            ->whereYear('fecha_prestamo', $year)
            ->with('usuario')
            ->get()
            ->groupBy('usuario.correo')
            ->map(function ($prestamos, $correo) {
                $tipoUsuario = optional($prestamos->first()->usuario)->tipo_usuario ?? 'Desconocido';
                return [
                    'correo' => $correo,
                    'tipo_usuario' => $tipoUsuario,
                    'prestamos_count' => $prestamos->count(),
                ];
            });

        $usuariosSolicitudesPendientes = Prestamo::whereNull('fecha_prestamo')
            ->whereNull('fecha_rechazo')
            ->whereYear('fecha_solicitud', $year)
            ->with('usuario')
            ->get()
            ->groupBy('usuario.correo')
            ->map(function ($solicitudes, $correo) {
                $tipoUsuario = optional($solicitudes->first()->usuario)->tipo_usuario ?? 'Desconocido';
                return [
                    'correo' => $correo,
                    'tipo_usuario' => $tipoUsuario,
                    'solicitudes_count' => $solicitudes->count(),
                ];
            });

        $usuariosDevolucionesPendientes = Prestamo::where('devuelto', 'No')
            ->whereYear('fecha_devolucion', $year)
            ->with('usuario')
            ->get()
            ->groupBy('usuario.correo')
            ->map(function ($devoluciones, $correo) {
                $tipoUsuario = optional($devoluciones->first()->usuario)->tipo_usuario ?? 'Desconocido';
                return [
                    'correo' => $correo,
                    'tipo_usuario' => $tipoUsuario,
                    'devoluciones_count' => $devoluciones->count(),
                ];
            });

        $usuariosPrestamosCompletados = Prestamo::where('devuelto', 'Si')
            ->whereYear('fecha_devolucion', $year)
            ->with('usuario')
            ->get()
            ->groupBy('usuario.correo')
            ->map(function ($prestamos, $correo) {
                $tipoUsuario = optional($prestamos->first()->usuario)->tipo_usuario ?? 'Desconocido';
                return [
                    'correo' => $correo,
                    'tipo_usuario' => $tipoUsuario,
                    'prestamos_completados_count' => $prestamos->count(),
                ];
            });

        $usuariosPrestamosRechazados = Prestamo::whereNotNull('fecha_rechazo')
            ->whereYear('fecha_rechazo', $year)
            ->with('usuario')
            ->get()
            ->groupBy('usuario.correo')
            ->map(function ($rechazados, $correo) {
                $tipoUsuario = optional($rechazados->first()->usuario)->tipo_usuario ?? 'Desconocido';
                return [
                    'correo' => $correo,
                    'tipo_usuario' => $tipoUsuario,
                    'rechazados_count' => $rechazados->count(),
                ];
            });

        // Lógicas originales (gráficos y datos generales)
        $totalPrestamosActivos = Prestamo::whereNull('devuelto')
            ->whereNull('fecha_rechazo')
            ->whereYear('fecha_prestamo', $year)
            ->count();

        $solicitudesPendientes = Prestamo::whereNull('fecha_prestamo')
            ->whereNull('fecha_rechazo')
            ->whereYear('fecha_solicitud', $year)
            ->count();

        $devolucionesPendientes = Prestamo::where('devuelto', 'No')
            ->whereYear('fecha_devolucion', $year)
            ->count();

        $prestamosCompletados = Prestamo::where('devuelto', 'Si')
            ->whereYear('fecha_devolucion', $year)
            ->count();

        $prestamosRechazados = Prestamo::whereNotNull('fecha_rechazo')
        ->whereYear('fecha_rechazo', $year)
        ->count();


        $librosMasPrestados = Prestamo::select('id_libro', DB::raw('COUNT(*) as count'))
            ->whereYear('fecha_prestamo', $year)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('fecha_prestamo', $month);
            })
            ->groupBy('id_libro')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $librosMasPrestadosLabels = $librosMasPrestados->map(function ($prestamo) {
            $libro = Libro::find($prestamo->id_libro);
            return $libro ? $libro->titulo : 'Desconocido';
        })->toArray();

        $librosMasPrestadosData = $librosMasPrestados->pluck('count')->toArray();

        $prestamosPorMes = Prestamo::select(DB::raw('MONTH(fecha_prestamo) as mes'), DB::raw('COUNT(*) as count'))
            ->whereYear('fecha_prestamo', $year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $prestamosPorMesLabels = array_map(function ($mes) use ($meses) {
            return $meses[$mes];
        }, $prestamosPorMes->pluck('mes')->toArray());

        $prestamosPorMesData = $prestamosPorMes->pluck('count')->toArray();

        $tasaDeDevolucionesData = [
            Prestamo::where('devuelto', 'Si')
                ->whereYear('fecha_devolucion', $year)
                ->when($month && $month != 'Todos', function ($query) use ($month) {
                    return $query->whereMonth('fecha_devolucion', $month);
                })
                ->count(),
            Prestamo::where('devuelto', 'No')
                ->whereYear('fecha_devolucion', $year)
                ->when($month && $month != 'Todos', function ($query) use ($month) {
                    return $query->whereMonth('fecha_devolucion', $month);
                })
                ->count(),
        ];

        $prestamosPorGenero = Prestamo::join('libros', 'prestamos.id_libro', '=', 'libros.id')
            ->join('generos', 'libros.id_genero', '=', 'generos.id')
            ->whereYear('fecha_prestamo', $year)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('fecha_prestamo', $month);
            })
            ->select('generos.nombre as genero', DB::raw('COUNT(*) as count'))
            ->groupBy('generos.nombre')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $prestamosPorGeneroLabels = $prestamosPorGenero->pluck('genero')->toArray();
        $prestamosPorGeneroData = $prestamosPorGenero->pluck('count')->toArray();

        $prestamosPorTipoUsuarioData = [
            'registrados' => Prestamo::whereHas('usuario', function ($query) {
                    $query->where('tipo_usuario', 'Registrado');
                })
                ->whereYear('fecha_prestamo', $year)
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('fecha_prestamo', $month);
                })
                ->count(),
            'no_registrados' => Prestamo::whereHas('usuario', function ($query) {
                    $query->where('tipo_usuario', 'No Registrado');
                })
                ->whereYear('fecha_prestamo', $year)
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('fecha_prestamo', $month);
                })
                ->count(),
        ];

        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $prestamosPorDia = Prestamo::select(DB::raw('DAYOFWEEK(fecha_prestamo) as dia'), DB::raw('COUNT(*) as count'))
            ->whereYear('fecha_prestamo', $year)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('fecha_prestamo', $month);
            })
            ->groupBy('dia')
            ->get();

        $prestamosPorDiaData = array_fill(0, 7, 0);
        foreach ($prestamosPorDia as $prestamo) {
            $dia = $prestamo->dia == 1 ? 6 : $prestamo->dia - 2;
            $prestamosPorDiaData[$dia] = $prestamo->count;
        }

        

        $prestamosPorDiaLabels = $diasSemana;

        return view('estadisticas', compact(
            'years',
            'year',
            'usuariosPrestamosActivos',
            'usuariosSolicitudesPendientes',
            'usuariosDevolucionesPendientes',
            'usuariosPrestamosCompletados',
            'usuariosPrestamosRechazados',
            'totalPrestamosActivos',
            'solicitudesPendientes',
            'devolucionesPendientes',
            'prestamosCompletados',
            'prestamosRechazados',
            'librosMasPrestadosLabels',
            'librosMasPrestadosData',
            'prestamosPorMesLabels',
            'prestamosPorMesData',
            'tasaDeDevolucionesData',
            'prestamosPorGeneroLabels',
            'prestamosPorGeneroData',
            'prestamosPorTipoUsuarioData',
            'prestamosPorDiaLabels',
            'prestamosPorDiaData'
            
        ));
    }
}
