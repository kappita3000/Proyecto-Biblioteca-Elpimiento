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
            <td>{{ ($noDevueltos->currentPage() - 1) * $noDevueltos->perPage() + $loop->iteration }}</td>
            <td>{{ $prestamo->titulo_libro }}</td>
            <td>{{ $prestamo->editorial_libro }}</td>
            <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
            <td>{{ $prestamo->usuario->correo }}</td>
            <td>{{ $prestamo->usuario->tipo_usuario }}</td>
            <td>{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d-m-Y') }}</td>
            <td>{{ $prestamo->devuelto }}</td>
            <td>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registrarDevolucionNoModal{{ $prestamo->id }}">
                    Registrar Devolución
                </button>
                <!-- Modal para registrar devolución -->
                <div class="modal fade" id="registrarDevolucionNoModal{{ $prestamo->id }}" tabindex="-1" aria-labelledby="modalRegistrarDevolucionNoLabel{{ $prestamo->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('prestamos.registrarDevolucionNo', $prestamo->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalRegistrarDevolucionNoLabel{{ $prestamo->id }}">Registrar Devolución</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="titulo_libro_no_{{ $prestamo->id }}" class="form-label">Título del Libro</label>
                                        <input type="text" class="form-control" id="titulo_libro_no_{{ $prestamo->id }}" value="{{ $prestamo->titulo_libro }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editorial_libro_no_{{ $prestamo->id }}" class="form-label">Editorial</label>
                                        <input type="text" class="form-control" id="editorial_libro_no_{{ $prestamo->id }}" value="{{ $prestamo->editorial_libro }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_prestamo_no_{{ $prestamo->id }}" class="form-label">Fecha de Préstamo</label>
                                        <input type="text" class="form-control" id="fecha_prestamo_no_{{ $prestamo->id }}" value="{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d-m-Y') }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_devolucion_actual_no_{{ $prestamo->id }}" class="form-label">Fecha de Devolución Registrada</label>
                                        <input type="text" class="form-control" id="fecha_devolucion_actual_no_{{ $prestamo->id }}" value="{{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d-m-Y') }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_devolucion_no_{{ $prestamo->id }}" class="form-label">Nueva Fecha de Devolución</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            id="fecha_devolucion_no_{{ $prestamo->id }}" 
                                            name="fecha_devolucion" 
                                            required 
                                            min="{{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
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
    {{ $noDevueltos->appends(['tab' => 'noDevueltos'])->links('pagination::bootstrap-4') }}
</div>