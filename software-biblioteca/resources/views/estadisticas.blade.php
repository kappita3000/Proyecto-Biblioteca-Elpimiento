@extends('layouts.admin')
<title>Estadísticas</title>
@section('content')

    <div class="container my-5">
        <h2 class="mb-4 text-center">Dashboard de Estadísticas de Préstamos</h2>

        {{-- Tarjetas Resumidas --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Solicitudes Pendientes</h5>
                        <p class="card-text">{{ $solicitudesPendientes ?? '0' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total de Préstamos Activos</h5>
                        <p class="card-text">{{ $totalPrestamosActivos ?? '0' }}</p>
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
    <script>
        window.librosMasPrestadosLabels = {!! json_encode($librosMasPrestadosLabels ?? []) !!};
        window.librosMasPrestadosData = {!! json_encode($librosMasPrestadosData ?? []) !!};
        window.prestamosPorMesLabels = {!! json_encode($prestamosPorMesLabels ?? []) !!};
        window.prestamosPorMesData = {!! json_encode($prestamosPorMesData ?? []) !!};
        window.tasaDeDevolucionesData = {!! json_encode($tasaDeDevolucionesData ?? [0, 0]) !!};
        window.prestamosPorGeneroLabels = {!! json_encode($prestamosPorGeneroLabels ?? []) !!};
        window.prestamosPorGeneroData = {!! json_encode($prestamosPorGeneroData ?? []) !!};
        window.prestamosPorTipoUsuarioData = [{{ $prestamosPorTipoUsuarioData['registrados'] ?? 0 }}, {{ $prestamosPorTipoUsuarioData['no_registrados'] ?? 0 }}];
        window.prestamosPorDiaLabels = {!! json_encode($prestamosPorDiaLabels ?? []) !!};
        window.prestamosPorDiaData = {!! json_encode($prestamosPorDiaData ?? []) !!};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/estadisticas.js') }}"></script>
@endsection
