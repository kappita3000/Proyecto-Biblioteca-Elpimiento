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
                <td>{{ ($solicitudes->currentPage() - 1) * $solicitudes->perPage() + $loop->iteration }}</td>
                <td>{{ $solicitud->titulo_libro }}</td>
                <td>{{ $solicitud->editorial_libro }}</td>
                <td>{{ $solicitud->usuario->nombre }} {{ $solicitud->usuario->apellido }}</td>
                <td>{{ $solicitud->usuario->correo }}</td>
                <td>{{ $solicitud->usuario->tipo_usuario }}</td>
                <td>{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d-m-Y') }}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <!-- Botón para abrir el modal de aceptar solicitud -->
                        <button type="button" class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#aceptarSolicitudModal{{ $solicitud->id }}">
                            Aceptar
                        </button>
                
                        <!-- Modal para aceptar solicitud -->
                        <div class="modal fade" id="aceptarSolicitudModal{{ $solicitud->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $solicitud->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $solicitud->id }}">Aceptar Solicitud</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('prestamos.aceptar', $solicitud->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body text-start">
                                            @if ($solicitud->libro)
                                                <!-- Si el libro existe -->
                                                <div class="form-group">
                                                    <label for="cantidad_disponible" class="form-label">Cantidad Disponible</label>
                                                    <input type="number" id="cantidad_disponible" class="form-control" 
                                                        value="{{ $solicitud->libro->cantidad - $solicitud->libro->copias_prestadas }}" readonly>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="fecha_prestamo_{{ $solicitud->id }}" class="form-label">Fecha de Préstamo</label>
                                                    <input type="date" name="fecha_prestamo" id="fecha_prestamo_{{ $solicitud->id }}" 
                                                        class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="fecha_devolucion_{{ $solicitud->id }}" class="form-label">Fecha de Devolución</label>
                                                    <input type="date" name="fecha_devolucion" id="fecha_devolucion_{{ $solicitud->id }}" 
                                                        class="form-control" required 
                                                        min="{{ now()->format('Y-m-d') }}">
                                                </div>
                                            @else
                                                <!-- Si el libro ha sido eliminado -->
                                                <div class="alert alert-danger">
                                                    <strong>Libro no disponible:</strong> Este libro ha sido eliminado y no puede aceptarse la solicitud.
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                            @if ($solicitud->libro)
                                                <button type="submit" class="btn btn-primary btn-sm">Aceptar Solicitud</button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                
                        <!-- Botón para abrir el modal de rechazo -->
                        <button type="button" class="btn btn-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#rechazarModal-{{ $solicitud->id }}">
                            Rechazar
                        </button>
                
                        <!-- Modal para ingresar el motivo del rechazo -->
                        <div class="modal fade" id="rechazarModal-{{ $solicitud->id }}" tabindex="-1" aria-labelledby="rechazarModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('prestamos.rechazar', $solicitud->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rechazarModalLabel">Rechazar Solicitud</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if (!$solicitud->libro)
                                                <div class="alert alert-warning">
                                                    <strong>Libro no disponible:</strong> Este libro ha sido eliminado, pero aún puedes rechazar la solicitud.
                                                </div>
                                            @endif
                                            <p>Por favor, ingresa el motivo del rechazo para notificar al solicitante:</p>
                                            <div class="form-group">
                                                <label for="motivo_rechazo" class="form-label">Motivo:</label>
                                                <textarea name="motivo_rechazo" id="motivo_rechazo" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                        </div>
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
    {{ $solicitudes->appends(['tab' => 'solicitudes'])->links('pagination::bootstrap-4') }}
</div>