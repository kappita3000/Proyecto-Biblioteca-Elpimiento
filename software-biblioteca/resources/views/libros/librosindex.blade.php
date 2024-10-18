@extends('layouts.lib')

@section('title', 'Gestión de Libros')

@section('content')
<h1>Gestión de Libros</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Formulario para crear o editar un libro -->  
<form action="{{ isset($libro) ? route('libros.update', $libro->id) : route('libros.store') }}" method="POST">
    @csrf
    @if (isset($libro))
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control" value="{{ $libro->titulo ?? old('titulo') }}" required>
    </div>

    <div class="mb-3">
    <label for="autor" class="form-label">Autor</label>
    <input type="text" name="autor" class="form-control" value="{{ $libro->autor->nombre ?? old('autor') }}" required>
    </div>

    <div class="mb-3">
    <label for="genero" class="form-label">Género</label>
    <input type="text" name="genero" class="form-control" value="{{ $libro->genero->nombre ?? old('genero') }}" required>
    </div>

    <div class="mb-3">
    <label for="categoria" class="form-label">Categoría</label>
    <input type="text" name="categoria" class="form-control" value="{{ $libro->Categoria->nombre ?? old('categoria') }}" required>
    </div>



    <div class="mb-3">
        <label for="id_repisa" class="form-label">Repisa</label>
        <input type="number" name="id_repisa" class="form-control" value="{{ $libro->id_repisa ?? old('id_repisa') }}" required>
    </div>

    <div class="mb-3">
        <label for="id_editorial" class="form-label">Editorial (opcional)</label>
        <input type="number" name="id_editorial" class="form-control" value="{{ $libro->id_editorial ?? old('id_editorial') }}">
    </div>

    <div class="mb-3">
        <label for="fecha_publicacion" class="form-label">Fecha de Publicación</label>
        <input type="date" name="fecha_publicacion" class="form-control" value="{{ $libro->fecha_publicacion ?? old('fecha_publicacion') }}">
    </div>

    <div class="mb-3">
        <label for="disponible" class="form-label">Disponible</label>
        <input type="checkbox" name="disponible" class="form-check-input" value="1" {{ isset($libro) && $libro->disponible ? 'checked' : '' }}>
    </div>

    <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad</label>
        <input type="number" name="cantidad" class="form-control" value="{{ $libro->cantidad ?? old('cantidad') }}" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control">{{ $libro->descripcion ?? old('descripcion') }}</textarea>
    </div>

    <button type="submit" class="btn btn-outline-success">{{ isset($libro) ? 'Actualizar Libro' : 'Crear Libro' }}</button>
</form>

<hr>
<div class="container"></div>
<h2>Lista de Libros</h2>
@if($libros->isNotEmpty())
    <!-- Tabla de libros -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Género</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($libros as $libro)
            <tr>
                <td>{{ $libro->titulo }}</td>
                <td>{{ $libro->autor->nombre }}</td>
                <td>{{ $libro->genero->nombre }}</td> 
                <td>{{ $libro->Categoria->nombre }}</td>
                <td>
                    <!-- Botón para abrir modal de editar -->
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#editBookModal" 
                            data-id="{{ $libro->id }}" 
                            data-titulo="{{ $libro->titulo }}">
                        Editar
                    </button>
                    
                    <!-- Botón para abrir modal de eliminar -->
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteBookModal" 
                            data-id="{{ $libro->id }}">
                        Eliminar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Editar Libro -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookLabel">Editar Libro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editBookForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <input type="hidden" id="editBookId">
          <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="editTitulo" name="titulo" required>
          </div>
          <!-- Agrega aquí los demás campos: autor, género, categoría, etc. -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Eliminar Libro -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteBookLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este libro?
      </div>
      <div class="modal-footer">
        <form id="deleteBookForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Script para los modales -->
<script>
    // Rellenar el modal de edición con los datos correctos
    const editBookModal = document.getElementById('editBookModal');
    editBookModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const titulo = button.getAttribute('data-titulo');
        
        const modalTitle = editBookModal.querySelector('.modal-title');
        const modalBodyInput = editBookModal.querySelector('#editTitulo');
        const modalForm = editBookModal.querySelector('#editBookForm');

        modalTitle.textContent = `Editar Libro: ${titulo}`;
        modalBodyInput.value = titulo;
        modalForm.action = `/libros/${id}`;
    });

    // Rellenar el modal de eliminación con el ID correcto
    const deleteBookModal = document.getElementById('deleteBookModal');
    deleteBookModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        
        const modalForm = deleteBookModal.querySelector('#deleteBookForm');
        modalForm.action = `/libros/${id}`;
    });
</script>

@else
    <p>No hay libros disponibles.</p>
@endif

@endsection



<!-- Formulario para crear o editar un libro 
<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Género</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($libros as $libro)
            <tr>
                <td>{{ $libro->titulo }}</td>
                <td>{{ $libro->id_autor }}</td>
                <td>{{ $libro->id_genero }}</td>
                <td>
                    <a href="{{ route('libros.edit', $libro->id) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('libros.destroy', $libro->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table> 
-->