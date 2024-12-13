<!-- Formulario usuario No registrado -->
<form action="{{ route('prestamos.store.no_registrado') }}" method="POST" id="prestamoFormNoRegistrado">
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
        <input type="text" id="libro_search_no_registrado" class="form-control" placeholder="Buscar libro por título" autocomplete="off">
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