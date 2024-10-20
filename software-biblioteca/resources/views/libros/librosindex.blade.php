@extends('layouts.admin')

@section('title', 'Gestión de Libros')

@section('content')
@php
  $admin = Auth::guard('admin')->user();
@endphp

@if ($admin->rol === 'superadmin')

<h1>Gestión de Libros</h1>

@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Formulario para crear o editar un libro -->  

@if ($admin->rol === 'superadmin')


<form action="{{ isset($libro) ? route('libros.update', $libro->id) : route('libros.store') }}" method="POST">
    @csrf
    @if (isset($libro))
        @method('PUT')
    @endif
    <div class="row g-3">
        <div class="col">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ $libro->titulo ?? old('titulo') }}" required>
        </div>

        <!-- Select para Autor -->
        <div class="col">
            <label for="autor" class="form-label">Autor</label>
                <select name="id_autor" class="form-control" required>
                    @foreach($autores as $autor)
                    <option value="{{ $autor->id }}" {{ isset($libro) && $libro->id_autor == $autor->id ? 'selected' : '' }}>
                        {{ $autor->nombre }}
                    </option>
                    @endforeach
                </select>
        </div>
    </div>
<br>
    <div class="row g-3">
            <!-- Select para Género -->
        <div class="col">
            <label for="genero" class="form-label">Género</label>
                <select name="id_genero" class="form-control" required>
                    @foreach($generos as $genero)
                    <option value="{{ $genero->id }}" {{ isset($libro) && $libro->id_genero == $genero->id ? 'selected' : '' }}>
                        {{ $genero->nombre }}
                    </option>
                    @endforeach
                </select>
        </div>

            <!-- Select para Categoría -->
        <div class="col">
            <label for="categoria" class="form-label">Categoría</label>
                <select name="id_categoria" class="form-control" required>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ isset($libro) && $libro->id_categoria == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                    @endforeach
                </select>
        </div>

            <!-- Select para Repisa -->
        <div class="col">
            <label for="repisa" class="form-label">Repisa</label>
                <select name="id_repisa" class="form-control" required>
                    @foreach($repisas as $repisa)
                    <option value="{{ $repisa->id }}" {{ isset($libro) && $libro->id_repisa == $repisa->id ? 'selected' : '' }}>
                        {{ $repisa->numero }}
                    </option>
                    @endforeach
                </select>
        </div>

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

@endif

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
                @if ($admin->rol === 'superadmin')
                <th>Acciones</th>
                @endif
                
            </tr>
        </thead>
        <tbody>
            @foreach($libros as $libro)
            <tr>
                <td>{{ $libro->titulo }}</td>
                <td>{{ $libro->autor->nombre }}</td>
                <td>{{ $libro->genero->nombre }}</td> 
                <td>{{ $libro->Categoria->nombre }}</td>
                @if ($admin->rol === 'superadmin')

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
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


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
          <input type="hidden" id="editBookId" name="id">
          
          <!-- Título -->
          <div class="mb-3">
            <label for="editTitulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="editTitulo" name="titulo" required>
          </div>

          <!-- Autor -->
          <div class="mb-3">
            <label for="editAutor" class="form-label">Autor</label>
            <select class="form-select" id="editAutor" name="id_autor" required>
              <option value="" disabled>Seleccione un autor</option>
              @foreach($autores as $autor)
                <option value="{{ $autor->id }}">{{ $autor->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Género -->
          <div class="mb-3">
            <label for="editGenero" class="form-label">Género</label>
            <select class="form-select" id="editGenero" name="id_genero" required>
              <option value="" disabled>Seleccione un género</option>
              @foreach($generos as $genero)
                <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Categoría -->
          <div class="mb-3">
            <label for="editCategoria" class="form-label">Categoría</label>
            <select class="form-select" id="editCategoria" name="id_categoria" required>
              <option value="" disabled>Seleccione una categoría</option>
              @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
              @endforeach
            </select>
          </div>

          <!-- Repisa -->
          <div class="mb-3">
            <label for="editRepisa" class="form-label">Repisa</label>
            <select class="form-select" id="editRepisa" name="id_repisa" required>
              <option value="" disabled>Seleccione una repisa</option>
              @foreach($repisas as $repisa)
                <option value="{{ $repisa->id }}">{{ $repisa->numero }}</option>
              @endforeach
            </select>
          </div>
          
          <!-- Otros campos -->
          <div class="mb-3">
            <label for="editCantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="editCantidad" name="cantidad" required>
          </div>

          <div class="mb-3">
            <label for="editDisponible" class="form-label">Disponible</label>
            <input type="checkbox" class="form-check-input" id="editDisponible" name="disponible">
          </div>

          <div class="mb-3">
            <label for="editDescripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="editDescripcion" name="descripcion"></textarea>
          </div>

          <div class="mb-3">
            <label for="editFechaPublicacion" class="form-label">Fecha de Publicación</label>
            <input type="date" class="form-control" id="editFechaPublicacion" name="fecha_publicacion">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal para eliminar libro -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteBookLabel">Eliminar Libro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="deleteBookForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p>¿Estás seguro de que deseas eliminar este libro?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Script para manejar la edición de libros
    const editBookModal = document.getElementById('editBookModal');
    editBookModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const titulo = button.getAttribute('data-titulo');
        
        const modalForm = editBookModal.querySelector('#editBookForm');
        
        modalForm.action = `/glibros/${id}`;
        modalForm.querySelector('#editTitulo').value = titulo;
        // Rellenar el resto de campos con atributos data-
    });

    // Script para manejar la eliminación de libros
    const deleteBookModal = document.getElementById('deleteBookModal');
    deleteBookModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');

        const modalForm = deleteBookModal.querySelector('#deleteBookForm');
        modalForm.action = `/glibros/${id}`;
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