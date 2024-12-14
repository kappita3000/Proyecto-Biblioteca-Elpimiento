@extends('layouts.admin')
<title>Estadísticas</title>
@section('content')

    <div class="container my-5">
        <h2 class="mb-4 text-center">Dashboard de Estadísticas de Préstamos</h2>

        <!-- Tarjetas del Dashboard -->
        {{-- Tarjetas de resumen --}}
        <div class="container mt-4">
            <div class="row justify-content-center">
                <!-- Tarjeta 1 -->
                <div class="col-md-4 mb-3">
                    <div class="card text-center bg-warning text-white" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSolicitudesPendientes">
                        <div class="card-body">
                            <h5 class="card-title">Solicitudes Pendientes</h5>
                            <p class="card-text">{{ $solicitudesPendientes }}</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 2 -->
                <div class="col-md-4 mb-3">
                    <div class="card text-center bg-primary text-white" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalPrestamosActivos">
                        <div class="card-body">
                            <h5 class="card-title">Préstamos Activos</h5>
                            <p class="card-text">{{ $totalPrestamosActivos }}</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 3 -->
                <div class="col-md-4 mb-3">
                    <div class="card text-center bg-success text-white" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalPrestamosCompletados">
                        <div class="card-body">
                            <h5 class="card-title">Préstamos Completados</h5>
                            <p class="card-text">{{ $prestamosCompletados }}</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 4 -->
                <div class="col-md-4 mb-3">
                    <div class="card text-center bg-danger text-white" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalDevolucionesPendientes">
                        <div class="card-body">
                            <h5 class="card-title">Devoluciones Pendientes</h5>
                            <p class="card-text">{{ $devolucionesPendientes }}</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta 5 -->
                <div class="col-md-4 mb-3">
                    <div class="card text-center bg-secondary text-white" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalPrestamosRechazados">
                        <div class="card-body">
                            <h5 class="card-title">Préstamos Rechazados</h5>
                            <p class="card-text">{{ $prestamosRechazados }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        {{-- Modals --}}
        <!-- Modal Solicitudes Pendientes -->
        <!-- Modal Solicitudes Pendientes -->
        <div class="modal fade" id="modalSolicitudesPendientes" tabindex="-1" aria-labelledby="modalSolicitudesPendientesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #ffc107; color: white;">
                        <h5 class="modal-title" id="modalSolicitudesPendientesLabel">Solicitudes Pendientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Correo</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosSolicitudesPendientes as $usuario)
                                    <tr>
                                        <td>{{ $usuario['correo'] }}</td>
                                        <td>{{ $usuario['tipo_usuario'] }}</td>
                                        <td>{{ $usuario['solicitudes_count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('prestamos.index', ['tab' => 'solicitudes']) }}" class="btn btn-primary">Ver Detalle</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Préstamos Activos -->
        <div class="modal fade" id="modalPrestamosActivos" tabindex="-1" aria-labelledby="modalPrestamosActivosLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #007bff; color: white;">
                        <h5 class="modal-title" id="modalPrestamosActivosLabel">Préstamos Activos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Correo</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosPrestamosActivos as $usuario)
                                    <tr>
                                        <td>{{ $usuario['correo'] }}</td>
                                        <td>{{ $usuario['tipo_usuario'] }}</td>
                                        <td>{{ $usuario['prestamos_count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('prestamos.index', ['tab' => 'prestamos']) }}" class="btn btn-primary">Ver Detalle</a>
                    </div>
                </div>
            </div>
        </div>
        
        

        <!-- Modal Préstamos Completados -->
        <div class="modal fade" id="modalPrestamosCompletados" tabindex="-1" aria-labelledby="modalPrestamosCompletadosLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #28a745; color: white;">
                        <h5 class="modal-title" id="modalPrestamosCompletadosLabel">Préstamos Completados</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Correo</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosPrestamosCompletados as $usuario)
                                    <tr>
                                        <td>{{ $usuario['correo'] }}</td>
                                        <td>{{ $usuario['tipo_usuario'] }}</td>
                                        <td>{{ $usuario['prestamos_completados_count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('prestamos.index', ['tab' => 'completados']) }}" class="btn btn-primary">Ver Detalle</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Devoluciones Pendientes -->
        <div class="modal fade" id="modalDevolucionesPendientes" tabindex="-1" aria-labelledby="modalDevolucionesPendientesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #dc3545; color: white;">
                        <h5 class="modal-title" id="modalDevolucionesPendientesLabel">Devoluciones Pendientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Correo</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosDevolucionesPendientes as $usuario)
                                    <tr>
                                        <td>{{ $usuario['correo'] }}</td>
                                        <td>{{ $usuario['tipo_usuario'] }}</td>
                                        <td>{{ $usuario['devoluciones_count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('prestamos.index', ['tab' => 'noDevueltos']) }}" class="btn btn-primary">Ver Detalle</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Préstamos Rechazados -->
        <div class="modal fade" id="modalPrestamosRechazados" tabindex="-1" aria-labelledby="modalPrestamosRechazadosLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #6c757d; color: white;">
                        <h5 class="modal-title" id="modalPrestamosRechazadosLabel">Préstamos Rechazados</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Correo</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosPrestamosRechazados as $usuario)
                                    <tr>
                                        <td>{{ $usuario['correo'] }}</td>
                                        <td>{{ $usuario['tipo_usuario'] }}</td>
                                        <td>{{ $usuario['rechazados_count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('prestamos.index', ['tab' => 'rechazados']) }}" class="btn btn-primary">Ver Detalle</a>
                    </div>
                </div>
            </div>
        </div>

        

        {{-- Formulario de Filtro --}}
        <form method="GET" action="{{ route('estadisticas.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="yearFilter" class="form-label">Año:</label>
                    <select name="year" id="yearFilter" class="form-select">
                        @foreach ($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ request('year', date('Y')) == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="monthFilter" class="form-label">Mes:</label>
                    <select name="month" id="monthFilter" class="form-select">
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
                    <button type="submit" class="btn btn-primary w-100">Aplicar Filtros</button>
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
