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

        $year = $request->input('year', date('Y')); // Año actual como valor por defecto
        $month = $request->input('month', null); // No hay mes por defecto

        // Total de préstamos activos (que no han sido devueltos) - Solo filtro por año
        $totalPrestamosActivos = Prestamo::where('devuelto', 'No')
            ->whereYear('fecha_prestamo', $year)
            ->count();

        // Solicitudes pendientes (aquellas sin fecha de préstamo) - Solo filtro por año
        $solicitudesPendientes = Prestamo::whereNull('fecha_prestamo')
            ->whereYear('fecha_solicitud', $year)
            ->count();

        // Devoluciones pendientes (que tienen fecha de devolución pero no se han devuelto) - Solo filtro por año
        $devolucionesPendientes = Prestamo::whereNotNull('fecha_devolucion')
            ->where('devuelto', 'No')
            ->whereYear('fecha_devolucion', $year)
            ->count();

        // Préstamos completados con éxito (devueltos correctamente) - Solo filtro por año
        $prestamosCompletados = Prestamo::where('devuelto', 'Si')
            ->whereYear('fecha_devolucion', $year)
            ->count();

        // Libros más prestados (top 10) - Afectado por el filtro de mes
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

        // Préstamos por mes (siempre muestra todos los datos del año)
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

       // Tasa de devoluciones - Afectado por el filtro de mes (excepto si el mes es "Todos")
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

        // Distribución de préstamos por género (top 10 géneros) - Afectado por el filtro de mes
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

        // Préstamos por usuarios registrados vs no registrados - Afectado por el filtro de mes
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

        // Préstamos por día de la semana (año y mes filtrado si aplica) - Afectado por el filtro de mes
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $prestamosPorDia = Prestamo::select(DB::raw('DAYOFWEEK(fecha_prestamo) as dia'), DB::raw('COUNT(*) as count'))
            ->whereYear('fecha_prestamo', $year)
            ->when($month, function ($query, $month) {
                return $query->whereMonth('fecha_prestamo', $month);
            })
            ->groupBy('dia')
            ->get();

        // Inicializar los valores para cada día de la semana con cero
        $prestamosPorDiaData = array_fill(0, 7, 0);  // Llena un array de 7 elementos con valor 0, índices 0 a 6

        // Asignar los valores contados a los días correspondientes
        foreach ($prestamosPorDia as $prestamo) {
            $dia = $prestamo->dia == 1 ? 6 : $prestamo->dia - 2;  // Mapea Domingo (1) a índice 6, Lunes (2) a índice 0, etc.
            $prestamosPorDiaData[$dia] = $prestamo->count;
        }

        // Convertir los valores para la vista
        $prestamosPorDiaLabels = $diasSemana;
        $prestamosPorDiaData = array_values($prestamosPorDiaData);

        return view('estadisticas', compact(
            'totalPrestamosActivos',
            'solicitudesPendientes',
            'devolucionesPendientes',
            'prestamosCompletados',
            'librosMasPrestadosLabels',
            'librosMasPrestadosData',
            'prestamosPorMesLabels',
            'prestamosPorMesData',
            'tasaDeDevolucionesData',
            'prestamosPorGeneroLabels',
            'prestamosPorGeneroData',
            'prestamosPorTipoUsuarioData',
            'prestamosPorDiaLabels',
            'prestamosPorDiaData',
            'years',
            'year',
            'month'
        ));
    }
}
