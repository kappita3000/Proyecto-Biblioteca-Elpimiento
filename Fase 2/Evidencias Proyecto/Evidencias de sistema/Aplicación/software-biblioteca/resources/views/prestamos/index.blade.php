@extends('layouts.admin')
<title>Prestamos</title>
@section('content')

    <div class="container mt-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Crear Préstamo siempre visible arriba -->
        <div class="card mb-3">
            <div class="card-header">
                <h4>Crear Préstamo</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="crearPrestamoTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="usuarioRegistrado-tab" data-bs-toggle="tab" href="#usuarioRegistrado" role="tab" aria-controls="usuarioRegistrado" aria-selected="true">Usuario Registrado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="usuarioNoRegistrado-tab" data-bs-toggle="tab" href="#usuarioNoRegistrado" role="tab" aria-controls="usuarioNoRegistrado" aria-selected="false">Usuario No Registrado</a>
                    </li>
                </ul>
                <div class="tab-content" id="crearPrestamoTabContent">
                    <div class="tab-pane fade show active" id="usuarioRegistrado" role="tabpanel" aria-labelledby="usuarioRegistrado-tab">
                        @include('prestamos.form_registrado')
                    </div>
                    <div class="tab-pane fade" id="usuarioNoRegistrado" role="tabpanel" aria-labelledby="usuarioNoRegistrado-tab">
                        @include('prestamos.form_no_registrado')
                    </div>
                </div>
            </div>
        </div>
        <h3>Gestión de Préstamos</h3>
        <!-- Formulario de búsqueda
        <div class="mb-3">
            <form action="{{ route('prestamos.index') }}" method="GET">
                <div class="input-group">
                    <input type="hidden" name="tab" value="{{ request('tab', 'solicitudes') }}">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por correo o nombre" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        </div>
         -->
        <!-- Pestañas principales debajo de "Crear Préstamo" -->

        <form method="GET" action="{{ route('prestamos.index') }}" class="mb-4">
            <input type="hidden" name="tab" id="activeTab" value="{{ request('tab', 'solicitudes') }}">
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
        
        
        <div>
            <ul class="nav nav-tabs" id="prestamosTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'solicitudes' ? 'active' : '' }}" href="{{ route('prestamos.index', ['tab' => 'solicitudes']) }}">Solicitudes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'prestamos' ? 'active' : '' }}" href="{{ route('prestamos.index', ['tab' => 'prestamos']) }}">Préstamos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'completados' ? 'active' : '' }}" href="{{ route('prestamos.index', ['tab' => 'completados']) }}">Completados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'noDevueltos' ? 'active' : '' }}" href="{{ route('prestamos.index', ['tab' => 'noDevueltos']) }}">No Devueltos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') == 'rechazados' ? 'active' : '' }}" href="{{ route('prestamos.index', ['tab' => 'rechazados']) }}">Rechazados</a>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="prestamosTabsContent">
            <div class="tab-pane fade {{ request('tab') == 'solicitudes' ? 'show active' : '' }}" id="solicitudes" role="tabpanel">
                @include('prestamos.solicitudes')
            </div>
            <div class="tab-pane fade {{ request('tab') == 'prestamos' ? 'show active' : '' }}" id="prestamos" role="tabpanel">
                @include('prestamos.prestamos')
            </div>
            <div class="tab-pane fade {{ request('tab') == 'completados' ? 'show active' : '' }}" id="completados" role="tabpanel">
                @include('prestamos.completados')
            </div>
            <div class="tab-pane fade {{ request('tab') == 'noDevueltos' ? 'show active' : '' }}" id="noDevueltos" role="tabpanel">
                @include('prestamos.no_devueltos')
            </div>
            <div class="tab-pane fade {{ request('tab') == 'rechazados' ? 'show active' : '' }}" id="rechazados" role="tabpanel">
                @include('prestamos.rechazados')
            </div>
        </div>
        <script src="{{ asset('js/prestamo.js') }}"></script>
    </div>
@endsection
