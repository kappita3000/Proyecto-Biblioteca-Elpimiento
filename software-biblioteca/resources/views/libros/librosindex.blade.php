@extends('layouts.admin')

@section('title', 'Gestión de Libros')

@section('content')
@php
  $admin = Auth::guard('admin')->user();
@endphp

@if ($admin->rol === 'superadmin')
<h1>Gestión de Libros</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Formulario para Crear/Editar un Libro -->
<h1>Gestión de Libros</h1>

<form action="{{ route('libros.store') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col-6">
            <label for="titulo" class="form-label">Título</label>
            <input id="titulo" 
                   type="text" 
                   class="form-control @error('titulo') is-invalid @enderror" 
                   name="titulo" 
                   placeholder="Nombre del libro" 
                   value="{{ old('titulo') }}" 
                   required>
            @error('titulo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label for="autor" class="form-label">Autor</label>
            <input id="autor" 
                   type="text" 
                   class="form-control @error('autor') is-invalid @enderror" 
                   name="autor" 
                   placeholder="Escribe el nombre del autor" 
                   value="{{ old('autor') }}" 
                   required>
            @error('autor')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <label for="genero" class="form-label">Género</label>
            <input id="genero" 
                   type="text" 
                   class="form-control @error('genero') is-invalid @enderror" 
                   name="genero" 
                   placeholder="Escribe el género" 
                   value="{{ old('genero') }}" 
                   required>
            @error('genero')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label for="categoria" class="form-label">Categoría</label>
            <input id="categoria" 
                   type="text" 
                   class="form-control @error('categoria') is-invalid @enderror" 
                   name="categoria" 
                   placeholder="Escribe la categoría" 
                   value="{{ old('categoria') }}" 
                   required>
            @error('categoria')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <label for="repisa" class="form-label">Repisa</label>
            <input id="repisa" 
                   type="text" 
                   class="form-control @error('repisa') is-invalid @enderror" 
                   name="repisa" 
                   value="{{ old('repisa') }}" 
                   required>
            @error('repisa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label for="editorial" class="form-label">Editorial</label>
            <input id="editorial" 
                   type="text" 
                   class="form-control @error('editorial') is-invalid @enderror" 
                   name="editorial" 
                   placeholder="Escribe la editorial" 
                   value="{{ old('editorial') }}" 
                   required>
            @error('editorial')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input id="cantidad" 
                   type="number" 
                   class="form-control @error('cantidad') is-invalid @enderror" 
                   name="cantidad" 
                   value="{{ old('cantidad') }}" 
                   required min="1">
            @error('cantidad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" 
                      class="form-control @error('descripcion') is-invalid @enderror" 
                      name="descripcion">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-success">Crear Libro</button>
</form>

<hr>

<!-- Tabla de Libros -->
<h2>Lista de Libros</h2>
@if($libros->isNotEmpty())
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-titulo">Título</th>
            <th class="col-autor">Autor</th>
            <th class="col-genero">Género</th>
            <th class="col-categoria">Categoría</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($libros as $libro)
        <tr>
            <td>{{ $libro->titulo }}</td>
            <td>{{ $libro->autor->nombre }}</td>
            <td>{{ $libro->genero->nombre }}</td>
            <td>{{ $libro->categoria->nombre }}</td>
            <td>
            <button type="button" class="btn btn-outline-info"
        data-bs-toggle="modal"
        data-bs-target="#editBookModal"
        data-id="{{ $libro->id }}"
        data-titulo="{{ $libro->titulo }}"
        data-autor="{{ $libro->autor->nombre }}"
        data-genero="{{ $libro->genero->nombre }}"
        data-categoria="{{ $libro->categoria->nombre }}"
        data-editorial="{{ $libro->editorial->nombre }}"
        data-repisa="{{ $libro->repisa->numero }}"
        data-cantidad="{{ $libro->cantidad }}"
        data-descripcion="{{ $libro->descripcion }}"
        data-disponible="{{ $libro->disponible ? '1' : '0' }}">
    Editar
</button>


                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteBookModal" data-id="{{ $libro->id }}">
                    Eliminar
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $libros->links('pagination::bootstrap-4') }}
</div>

<!-- Modal para Editar Libro -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Tamaño del modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookLabel">Editar Libro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editBookForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <!-- Campo oculto para ID -->
          <input type="hidden" id="editBookId" name="id">

          <!-- Fila para Título y Autor -->
          <div class="row mb-3">
            <div class="col-6">
              <label for="editTitulo" class="form-label">Título</label>
              <input id="editTitulo" 
                     type="text" 
                     class="form-control @error('titulo') is-invalid @enderror" 
                     name="titulo" 
                     value="{{ old('titulo') }}" 
                     required>
              @error('titulo')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
            <div class="col-6">
              <label for="editAutor" class="form-label">Autor</label>
              <input id="editAutor" 
                     type="text" 
                     class="form-control @error('autor') is-invalid @enderror" 
                     name="autor" 
                     value="{{ old('autor') }}" 
                     required>
              @error('autor')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
          </div>

          <!-- Fila para Género y Categoría -->
          <div class="row mb-3">
            <div class="col-6">
              <label for="editGenero" class="form-label">Género</label>
              <input id="editGenero" 
                     type="text" 
                     class="form-control @error('genero') is-invalid @enderror" 
                     name="genero" 
                     value="{{ old('genero') }}" 
                     required>
              @error('genero')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
            <div class="col-6">
              <label for="editCategoria" class="form-label">Categoría</label>
              <input id="editCategoria" 
                     type="text" 
                     class="form-control @error('categoria') is-invalid @enderror" 
                     name="categoria" 
                     value="{{ old('categoria') }}" 
                     required>
              @error('categoria')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
          </div>

          <!-- Fila para Editorial y Repisa -->
          <div class="row mb-3">
            <div class="col-6">
              <label for="editEditorial" class="form-label">Editorial</label>
              <input id="editEditorial" 
                     type="text" 
                     class="form-control @error('editorial') is-invalid @enderror" 
                     name="editorial" 
                     value="{{ old('editorial') }}" 
                     required>
              @error('editorial')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
            <div class="col-6">
              <label for="editRepisa" class="form-label">Repisa</label>
              <input id="editRepisa" 
                     type="text" 
                     class="form-control @error('repisa') is-invalid @enderror" 
                     name="repisa" 
                     value="{{ old('repisa') }}" 
                     required>
              @error('repisa')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
          </div>

          <!-- Fila para Cantidad y Disponible -->
          <div class="row mb-3">
            <div class="col-6">
              <label for="editCantidad" class="form-label">Cantidad</label>
              <input id="editCantidad" 
                     type="number" 
                     class="form-control @error('cantidad') is-invalid @enderror" 
                     name="cantidad" 
                     value="{{ old('cantidad') }}" 
                     required min="1">
              @error('cantidad')
                  <div class="invalid-feedback">
                      {{ $message }}
                  </div>
              @enderror
            </div>
            <div class="col-6 d-flex align-items-center">
              <div class="form-check">
                <input id="editDisponible" 
                       type="checkbox" 
                       class="form-check-input" 
                       name="disponible" 
                       @if(old('disponible')) checked @endif>
                <label for="editDisponible" class="form-check-label">Disponible</label>
              </div>
            </div>
          </div>

          <!-- Campo para Descripción -->
          <div class="mb-3">
            <label for="editDescripcion" class="form-label">Descripción</label>
            <textarea id="editDescripcion" 
                      class="form-control @error('descripcion') is-invalid @enderror" 
                      name="descripcion">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
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





<!-- Modal para Eliminar Libro -->
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



<script src="{{ asset('js/Geslibros.js') }}"></script>



@else
    <p>No hay libros disponibles.</p>
@endif
@endif

@endsection


