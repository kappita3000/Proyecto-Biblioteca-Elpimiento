<table class="table mt-3">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($generos as $genero)
            <tr>
                <td>{{ $genero->nombre }}</td>
                <td>
                    <!-- Botón de Editar -->
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editGeneroModal-{{ $genero->id }}">Editar</button>

                    <!-- Botón de Eliminar -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGeneroModal-{{ $genero->id }}">Eliminar</button>
                </td>
            </tr>

            <!-- Modal de Edición -->
            <div class="modal fade" id="editGeneroModal-{{ $genero->id }}" tabindex="-1" aria-labelledby="editGeneroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="formEditarGenero" action="{{ url('gestiones/genero/'.$genero->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Género</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="nombre" class="form-control" value="{{ $genero->nombre }}" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal de Eliminación -->
            <div class="modal fade" id="deleteGeneroModal-{{ $genero->id }}" tabindex="-1" aria-labelledby="deleteGeneroLabel-{{ $genero->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar Género</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ url('gestiones/genero/'.$genero->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>¿Estás seguro de que deseas eliminar este género?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </tbody>
</table>


