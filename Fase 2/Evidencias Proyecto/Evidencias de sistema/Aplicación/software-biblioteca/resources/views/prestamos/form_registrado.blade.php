<!-- Formulario usuario registrado -->
<form action="{{ route('prestamos.store.registrado') }}" method="POST" id="prestamoFormRegistrado">
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