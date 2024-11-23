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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
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
                <button class="nav-link" id="noDevueltos-tab" data-bs-toggle="tab" data-bs-target="#noDevueltos" type="button" role="tab" aria-controls="noDevueltos" aria-selected="false">No devueltos</button>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="crearPrestamo-tab" data-bs-toggle="tab" href="#crearPrestamo" role="tab" aria-controls="crearPrestamo" aria-selected="false">Crear Préstamo</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Pestaña de Solicitudes Pendientes -->
            <div class="tab-pane fade show active" id="solicitudes" role="tabpanel" aria-labelledby="solicitudes-tab">
                <h3>Solicitudes Pendientes</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Libro</th>
                            <th>Editorial</th>
                            <th>Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Tipo de Usuario</th>
                            <th>Fecha de Solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $index => $solicitud)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $solicitud->libro->titulo }}</td>
                                <td>{{ $solicitud->libro->editorial->nombre }}</td>
                                <td>{{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellido }}</td>
                                <td>{{ $solicitud->usuario->correo }}</td>
                                <td>{{ $solicitud->usuario->tipo_usuario }}</td>
                                <td>{{ $solicitud->fecha_solicitud }}</td>
                                <td>
                                    <!-- Botón para abrir el modal de aceptar solicitud -->
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aceptarSolicitudModal{{ $solicitud->id }}">
                                        Aceptar Solicitud
                                    </button>

                                    <!-- Modal para aceptar la solicitud -->
                                    <div class="modal fade" id="aceptarSolicitudModal{{ $solicitud->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $solicitud->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $solicitud->id }}">Aceptar Solicitud</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('prestamos.aceptar', $solicitud->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="cantidad_disponible">Cantidad Disponible</label>
                                                            <input type="number" id="cantidad_disponible" class="form-control" value="{{ $solicitud->libro->cantidad - $solicitud->libro->copias_prestadas }}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fecha_prestamo_{{ $solicitud->id }}">Fecha de Préstamo</label>
                                                            <input type="date" name="fecha_prestamo" id="fecha_prestamo_{{ $solicitud->id }}" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <label for="fecha_devolucion_{{ $solicitud->id }}">Fecha de Devolución</label>
                                                            <input type="date" name="fecha_devolucion" id="fecha_devolucion_{{ $solicitud->id }}" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Aceptar Solicitud</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botón para rechazar solicitud -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rechazarSolicitudModal{{ $solicitud->id }}">
                                        Rechazar Solicitud
                                    </button>

                                    <!-- Modal para rechazar solicitud -->
                                    <div class="modal fade" id="rechazarSolicitudModal{{ $solicitud->id }}" tabindex="-1" aria-labelledby="rechazarSolicitudModalLabel{{ $solicitud->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rechazarSolicitudModalLabel{{ $solicitud->id }}">Rechazar Solicitud</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas rechazar esta solicitud?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('prestamos.rechazar', $solicitud->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Rechazar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $solicitudes->links('pagination::bootstrap-4') }}
                </div>
            </div>


            <!-- Pestaña de Préstamos Activos -->
            <div class="tab-pane fade" id="prestamos" role="tabpanel" aria-labelledby="prestamos-tab">
                <h3>Préstamos Activos</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Libro</th>
                            <th>Editorial</th>
                            <th>Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Tipo de Usuario</th>
                            <th>Fecha Devolución</th>
                            <th>Devuelto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestamos as $index => $prestamo)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $prestamo->libro->titulo }}</td>
                                <td>{{ $prestamo->libro->editorial->nombre }}</td>
                                <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                                <td>{{ $prestamo->usuario->correo }}</td>
                                <td>{{ $prestamo->usuario->tipo_usuario }}</td>
                                <td>{{ $prestamo->fecha_devolucion }}</td>
                                <td>
                                    @if (Carbon\Carbon::now()->greaterThan($prestamo->fecha_devolucion))
                                        Atrasado
                                    @else
                                        Pendiente
                                    @endif
                                </td>
                                <td>
                                    <!-- Botón para abrir el modal de registro de devolución -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarDevolucionModal{{ $prestamo->id }}">
                                        Registrar Devolución
                                    </button>

                                    <!-- Modal para registrar devolución -->
                                    <div class="modal fade" id="registrarDevolucionModal{{ $prestamo->id }}" tabindex="-1" aria-labelledby="modalRegistrarDevolucionLabel{{ $prestamo->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('prestamos.registrarDevolucion', $prestamo->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalRegistrarDevolucionLabel{{ $prestamo->id }}">Registrar Devolución</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="fecha_devolucion_{{ $prestamo->id }}" class="form-label">Fecha de Devolución</label>
                                                            <input type="date" class="form-control" id="fecha_devolucion_{{ $prestamo->id }}" name="fecha_devolucion" value="{{ now()->format('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="devuelto_{{ $prestamo->id }}" class="form-label">Devuelto</label>
                                                            <select class="form-select" id="devuelto_{{ $prestamo->id }}" name="devuelto" required>
                                                                <option value="Si">Si</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                    </div>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $prestamos->links('pagination::bootstrap-4') }}
                </div>
            </div>


            <!-- Tercera pestaña: Préstamos Completados -->
            <div class="tab-pane fade" id="completados" role="tabpanel" aria-labelledby="completados-tab">
                <h3>Préstamos Completados</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Libro</th>
                            <th>Editorial</th>
                            <th>Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Tipo de Usuario</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Devuelto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completados as $index => $prestamo)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $prestamo->libro->titulo }}</td>
                                <td>{{ $prestamo->libro->editorial->nombre }}</td>
                                <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                                <td>{{ $prestamo->usuario->correo }}</td>
                                <td>{{ $prestamo->usuario->tipo_usuario }}</td>
                                <td>{{ $prestamo->fecha_prestamo }}</td>
                                <td>{{ $prestamo->fecha_devolucion }}</td>
                                <td>{{ $prestamo->devuelto }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $completados->links('pagination::bootstrap-4') }}
                </div>
            </div>

            <!-- Pestaña de Préstamos No Devueltos -->
            <div class="tab-pane fade" id="noDevueltos" role="tabpanel" aria-labelledby="noDevueltos-tab">
                <h3>Préstamos No Devueltos</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Libro</th>
                            <th>Editorial</th>
                            <th>Solicitante</th>
                            <th>Correo del Solicitante</th>
                            <th>Tipo de Usuario</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Devuelto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($noDevueltos as $index => $prestamo)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $prestamo->libro->titulo }}</td>
                            <td>{{ $prestamo->libro->editorial->nombre }}</td>
                            <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                            <td>{{ $prestamo->usuario->correo }}</td>
                            <td>{{ $prestamo->usuario->tipo_usuario }}</td>
                            <td>{{ $prestamo->fecha_prestamo }}</td>
                            <td>{{ $prestamo->fecha_devolucion }}</td>
                            <td>{{ $prestamo->devuelto }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $noDevueltos->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <div class="tab-content" id="nav-tabContent">          
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
                    <!-- Formulario usuario registrado -->
                    <form action="{{ route('prestamos.store.registrado') }}" method="POST" id="prestamoFormRegistrado" style="display: none;">
                        @csrf
                        <!-- Campo oculto para la fecha de solicitud (automáticamente la fecha actual) -->
                        <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d') }}">
                    
                        <div class="form-group">
                            <label for="usuario_search">Seleccionar Usuario</label>
                            <input type="text" id="usuario_search" class="form-control" placeholder="Buscar usuario por correo, nombre o apellido" autocomplete="off" required>
                            <input type="hidden" name="usuario_id" id="usuario_id">
                            <div id="usuario_results" class="dropdown-menu"></div>
                        </div>
                    
                        <div class="form-group">
                            <label for="libro_search">Seleccionar Libro</label>
                            <input type="text" id="libro_search" class="form-control" placeholder="Buscar libro por título" autocomplete="off" required>
                            <input type="hidden" name="libro_id" id="libro_id">
                            <div id="libro_results" class="dropdown-menu"></div>
                        </div>
                    
                        <div class="form-group">
                            <label for="fecha_prestamo">Fecha Préstamo</label>
                            <input type="date" name="fecha_prestamo" class="form-control" id="fecha_prestamo" required>
                        </div>
                    
                        <div class="form-group">
                            <label for="fecha_devolucion">Fecha Devolución</label>
                            <input type="date" name="fecha_devolucion" class="form-control" id="fecha_devolucion" required>
                        </div>
                    
                        <input type="hidden" name="tipo_usuario" value="Registrado">
                        <button type="submit" class="btn btn-success">Crear Préstamo</button>
                    </form>
                    <!-- Formulario usuario No registrado -->
                    <form action="{{ route('prestamos.store.no_registrado') }}" method="POST" id="prestamoFormNoRegistrado" style="display: none;">
                        @csrf
                    
                        <!-- Campo para ingresar datos del usuario -->
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
                    
                        <!-- Campo para buscar libro -->
                        <div class="form-group">
                            <label for="libro_search_no_registrado">Seleccionar Libro</label>
                            <input type="text" id="libro_search_no_registrado" class="form-control" placeholder="Buscar libro por título..." autocomplete="off">
                            <div id="libro_results_no_registrado" class="dropdown-menu"></div>
                            <input type="hidden" id="libro_id_no_registrado" name="libro_id">
                        </div>
                    
                        <!-- Campo oculto para la fecha de solicitud -->
                        <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d') }}">
                    
                        <!-- Campo visible para la fecha de préstamo -->
                        <div class="form-group">
                            <label for="fecha_prestamo">Fecha Préstamo</label>
                            <input type="date" name="fecha_prestamo" class="form-control" id="fecha_prestamo" required>
                        </div>
                    
                        <!-- Campo visible para la fecha de devolución -->
                        <div class="form-group">
                            <label for="fecha_devolucion">Fecha Devolución</label>
                            <input type="date" name="fecha_devolucion" class="form-control" id="fecha_devolucion" required>
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
