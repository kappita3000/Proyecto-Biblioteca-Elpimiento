@extends('layouts.admin')
<title>Estadísticas</title>
@section('content')

    <div class="container my-5">
        <h2 class="mb-4 text-center">Dashboard de Estadísticas de Préstamos</h2>

        {{-- Formulario de Filtro --}}
        <form action="{{ route('estadisticas.index') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="year" class="form-label">Año:</label>
                    <select name="year" id="year" class="form-select">
                        @foreach ($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ request('year', date('Y')) == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="month" class="form-label">Mes:</label>
                    <select name="month" id="month" class="form-select">
                        <option value="">Todos</option>
                        @foreach ([
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ] as $num => $mes)
                            <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $mes }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        {{-- Tarjetas Resumidas --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total de Préstamos Activos</h5>
                        <p class="card-text">{{ $totalPrestamosActivos ?? '0' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Solicitudes Pendientes</h5>
                        <p class="card-text">{{ $solicitudesPendientes ?? '0' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Devoluciones Pendientes</h5>
                        <p class="card-text">{{ $devolucionesPendientes ?? '0' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Préstamos Completados</h5>
                        <p class="card-text">{{ $prestamosCompletados ?? '0' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">10 Libros Más Prestados</h5>
                        <canvas id="librosMasPrestadosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Préstamos por Mes</h5>
                        <canvas id="prestamosPorMesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Tasa de Devoluciones</h5>
                        <canvas id="tasaDeDevolucionesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Distribución de Préstamos por Género</h5>
                        <canvas id="prestamosPorGeneroChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Préstamos por Usuarios Registrados vs No Registrados</h5>
                        <canvas id="prestamosPorTipoUsuarioChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Préstamos por Día de la Semana</h5>
                        <canvas id="prestamosPorDiaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cargar Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Libros Más Prestados
            var ctxLibrosMasPrestados = document.getElementById('librosMasPrestadosChart').getContext('2d');
            var librosMasPrestadosChart = new Chart(ctxLibrosMasPrestados, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($librosMasPrestadosLabels ?? []) !!},
                    datasets: [{
                        label: 'Veces Prestado',
                        data: {!! json_encode($librosMasPrestadosData ?? []) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    }]
                }
            });

            // Préstamos por Mes
            var ctxPrestamosPorMes = document.getElementById('prestamosPorMesChart').getContext('2d');
            var prestamosPorMesChart = new Chart(ctxPrestamosPorMes, {
                type: 'line',
                data: {
                    labels: {!! json_encode($prestamosPorMesLabels ?? []) !!},
                    datasets: [{
                        label: 'Préstamos',
                        data: {!! json_encode($prestamosPorMesData ?? []) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false,
                    }]
                }
            });

            // Tasa de Devoluciones
            var ctxTasaDeDevoluciones = document.getElementById('tasaDeDevolucionesChart').getContext('2d');
            var tasaDeDevolucionesChart = new Chart(ctxTasaDeDevoluciones, {
                type: 'pie',
                data: {
                    labels: ['Devuelto', 'No Devuelto'],
                    datasets: [{
                        data: {!! json_encode($tasaDeDevolucionesData ?? [0, 0]) !!},
                        backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                    }]
                }
            });

            // Distribución de Préstamos por Género
            var ctxPrestamosPorGenero = document.getElementById('prestamosPorGeneroChart').getContext('2d');
            var prestamosPorGeneroChart = new Chart(ctxPrestamosPorGenero, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($prestamosPorGeneroLabels ?? []) !!},
                    datasets: [{
                        label: 'Préstamos',
                        data: {!! json_encode($prestamosPorGeneroData ?? []) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    }]
                }
            });

            // Préstamos por Usuarios Registrados vs No Registrados
            var ctxPrestamosPorTipoUsuario = document.getElementById('prestamosPorTipoUsuarioChart').getContext('2d');
            var prestamosPorTipoUsuarioChart = new Chart(ctxPrestamosPorTipoUsuario, {
                type: 'bar',
                data: {
                    labels: ['Registrados', 'No Registrados'],
                    datasets: [{
                        label: 'Préstamos',
                        data: [{{ $prestamosPorTipoUsuarioData['registrados'] ?? 0 }}, {{ $prestamosPorTipoUsuarioData['no_registrados'] ?? 0 }}],
                        backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)'],
                    }]
                }
            });

            // Préstamos por Día de la Semana
            var ctxPrestamosPorDia = document.getElementById('prestamosPorDiaChart').getContext('2d');
            var prestamosPorDiaChart = new Chart(ctxPrestamosPorDia, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($prestamosPorDiaLabels ?? []) !!},
                    datasets: [{
                        label: 'Préstamos',
                        data: {!! json_encode($prestamosPorDiaData ?? []) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...{!! json_encode($prestamosPorDiaData ?? []) !!}) + 5 // Añadir un poco de margen superior
                        }
                    }
                }
            });
        });
    </script>
@endsection
