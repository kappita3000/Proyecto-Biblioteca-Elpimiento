@extends('layouts.admin')
<title>Prestamos</title>
@section('content')

    <div class="container">
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
        <h1>Gestión de Préstamos</h1>

        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="solicitudes-tab" data-bs-toggle="tab" data-bs-target="#solicitudes" type="button" role="tab" aria-controls="solicitudes" aria-selected="true">Solicitudes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="prestamos-tab" data-bs-toggle="tab" data-bs-target="#prestamos" type="button" role="tab" aria-controls="prestamos" aria-selected="false">Préstamos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completados-tab" data-bs-toggle="tab" data-bs-target="#completados" type="button" role="tab" aria-controls="completados" aria-selected="false">Completados</button>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="crearPrestamo-tab" data-bs-toggle="tab" href="#crearPrestamo" role="tab" aria-controls="crearPrestamo" aria-selected="false">Crear Préstamo</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Primera pestaña: Solicitudes -->
            <div class="tab-pane fade show active" id="solicitudes" role="tabpanel" aria-labelledby="solicitudes-tab">
                <h3>Solicitudes Pendientes</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Libro</th>
                            <th>Nombre del Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Fecha Solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud->libro->titulo }}</td>
                                <td>{{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellido }}</td>
                                <td>{{ $solicitud->usuario->correo }}</td>
                                <td>{{ $solicitud->fecha_solicitud }}</td>
                                <td>
                                    <form action="{{ route('prestamos.aceptar', $solicitud->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-warning">Aceptar</button>
                                    </form>
                                    <form action="{{ route('prestamos.rechazar', $solicitud->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Rechazar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Segunda pestaña: Préstamos Activos -->
            <div class="tab-pane fade" id="prestamos" role="tabpanel" aria-labelledby="prestamos-tab">
                <h3>Préstamos Activos</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Libro</th>
                            <th>Nombre del Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestamos as $prestamo)
                            <tr>
                                <td>{{ $prestamo->libro->titulo }}</td>
                                <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                                <td>{{ $prestamo->usuario->correo }}</td>
                                <td>{{ $prestamo->fecha_prestamo }}</td>
                                <td>{{ $prestamo->fecha_devolucion ?? 'Pendiente' }}</td>
                                <td>
                                    <!-- Botón para abrir el modal de "Registrar Devolución" -->
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#devolucionModal{{ $prestamo->id }}">Registrar Devolución</button>
                                </td>
                            </tr>
            
                            <!-- Modal de Registrar Devolución -->
                            <div class="modal fade" id="devolucionModal{{ $prestamo->id }}" tabindex="-1" aria-labelledby="devolucionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="devolucionModalLabel">Registrar Devolución</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Formulario dentro del modal -->
                                            <form action="{{ route('prestamos.registrarDevolucion', $prestamo->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="form-group">
                                                    <label for="fecha_devolucion">Fecha de Devolución</label>
                                                    <input type="date" name="fecha_devolucion" class="form-control" id="fecha_devolucion" required>
                                                </div>
            
                                                <div class="form-group">
                                                    <label for="devuelto">Devuelto</label>
                                                    <select name="devuelto" class="form-control" id="devuelto" required>
                                                        <option value="Si">Sí</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
            
                                                <button type="submit" class="btn btn-success">Registrar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tercera pestaña: Préstamos Completados -->
            <div class="tab-pane fade" id="completados" role="tabpanel" aria-labelledby="completados-tab">
                <h3>Préstamos Completados</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Libro</th>
                            <th>Nombre del Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Fecha Solicitud</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Devuelto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completados as $completado)
                            <tr>
                                <td>{{ $completado->libro->titulo }}</td>
                                <td>{{ $completado->usuario->nombre }} {{ $completado->usuario->apellido }}</td>
                                <td>{{ $completado->usuario->correo }}</td>
                                <td>{{ $completado->fecha_solicitud }}</td>
                                <td>{{ $completado->fecha_prestamo }}</td>
                                <td>{{ $completado->fecha_devolucion }}</td>
                                <td>{{ $completado->devuelto }}</td> <!-- Asegúrate de mostrar el campo devuelto -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="nav-tabContent">
                <!-- Pestaña para solicitudes -->
                <div class="tab-pane fade show active" id="solicitudes" role="tabpanel" aria-labelledby="solicitudes-tab">

                    <table class="table">
                        <!-- Contenido de la tabla de solicitudes -->
                    </table>
                </div>
            
                <!-- Pestaña para préstamos activos -->
                <div class="tab-pane fade" id="prestamos" role="tabpanel" aria-labelledby="prestamos-tab">
                    <h3>Préstamos Activos</h3>
                    <table class="table">
                        <!-- Contenido de la tabla de préstamos activos -->
                    </table>
                </div>
            
                <!-- Nueva pestaña para crear préstamo -->
                <div class="tab-pane fade" id="crearPrestamo" role="tabpanel" aria-labelledby="crearPrestamo-tab">
                   
                    <h3>Crear Préstamo</h3>
                    
                    <div>
                        <p>Tipo de Usuario</p>
                        <div>
                            <input type="radio" id="tipoUsuarioRegistrado" name="tipo_usuario" value="registrado" onclick="toggleForms()">
                            <label for="tipoUsuarioRegistrado">Registrado</label>
                    
                            <input type="radio" id="tipoUsuarioNoRegistrado" name="tipo_usuario" value="no_registrado" onclick="toggleForms()">
                            <label for="tipoUsuarioNoRegistrado">No Registrado</label>
                        </div>
                    </div>
                    <form action="{{ route('prestamos.store.registrado') }}" method="POST" id="prestamoFormRegistrado" style="display: none;">
                        @csrf
                        <div class="form-group">
                            <label for="usuario_id">Seleccionar Usuario</label>
                            <select name="usuario_id" id="usuario_id" class="form-control" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->correo }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label for="libro_id">Seleccionar Libro</label>
                            <select name="libro_id" id="libro_id" class="form-control" required>
                                <option value="">Seleccione un libro</option>
                                @foreach($libros as $libro)
                                    <option value="{{ $libro->id }}">{{ $libro->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha Solicitud</label>
                            <input type="date" name="fecha_solicitud" class="form-control" id="fecha_solicitud" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="fecha_prestamo">Fecha Préstamo</label>
                            <input type="date" name="fecha_prestamo" class="form-control" id="fecha_prestamo" required>
                        </div>
                    
                        <input type="hidden" name="tipo_usuario" value="Registrado">
                        <button type="submit" class="btn btn-success">Crear Préstamo</button>
                    </form>
                    <form action="{{ route('prestamos.store.no_registrado') }}" method="POST" id="prestamoFormNoRegistrado" style="display: none;">
                        @csrf
                    
                        <div class="form-group">
                            <label for="nombreUsuario">Nombre</label>
                            <input type="text" name="nombreUsuario" class="form-control" id="nombreUsuario" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="apellidoUsuario">Apellido</label>
                            <input type="text" name="apellidoUsuario" class="form-control" id="apellidoUsuario" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="correoUsuario">Correo</label>
                            <input type="email" name="correoUsuario" class="form-control" id="correoUsuario" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="libro_id">Seleccionar Libro</label>
                            <select name="libro_id" id="libro_id" class="form-control" required>
                                <option value="">Seleccione un libro</option>
                                @foreach($libros as $libro)
                                    <option value="{{ $libro->id }}">{{ $libro->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha Solicitud</label>
                            <input type="date" name="fecha_solicitud" class="form-control" id="fecha_solicitud" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="fecha_prestamo">Fecha Préstamo</label>
                            <input type="date" name="fecha_prestamo" class="form-control" id="fecha_prestamo" required>
                        </div>
                    
                        <input type="hidden" name="tipo_usuario" value="No Registrado">
                        <button type="submit" class="btn btn-success">Crear Préstamo</button>
                    </form>
                    <script src="{{ asset('js/prestamo.js') }}"></script>
                   
                </div>
            </div>
            
            
        </div>
    </div>
@endsection
