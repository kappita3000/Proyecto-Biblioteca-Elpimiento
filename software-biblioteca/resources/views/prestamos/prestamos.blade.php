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
                <td>{{ ($prestamos->currentPage() - 1) * $prestamos->perPage() + $loop->iteration }}</td>
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
    {{ $prestamos->appends(['tab' => 'prestamos'])->links('pagination::bootstrap-4') }}
</div>