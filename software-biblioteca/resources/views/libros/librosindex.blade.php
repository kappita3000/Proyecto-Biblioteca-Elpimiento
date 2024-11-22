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
            <input id="titulo" type="text" name="titulo" class="form-control" value="{{ $libro->titulo ?? old('titulo') }}" placeholder="Nombre del libro" required>
        </div>

        <!-- Select para Autor -->
<!-- Input para Autor con Autocompletado -->
<div class="col" style="position: relative;">
    <label for="autor" class="form-label">Autor</label>
    <input type="text" id="autor" class="form-control" placeholder="Escribe el nombre del autor" required>
    <input type="hidden" id="id_autor" name="id_autor" required>
</div>

<!-- Contenedor para sugerencias -->
<div id="autor-suggestions" class="dropdown-menu" style="display: none; position: absolute;"></div>

    </div>
<br>
    <div class="row g-3">
            <!-- Select para Género -->
            <div class="col">
                <label for="genero" class="form-label">Género</label>
                <input type="text" id="genero" class="form-control" placeholder="Escribe el género" required>
                <input type="hidden" id="id_genero" name="id_genero" required>
            </div>

<div id="genero-suggestions" class="dropdown-menu" style="display: none; position: absolute;"></div>

            <!-- Select para Categoría -->
  <div class="col">
    <label for="categoria" class="form-label">Categoría</label>
    <input type="text" id="categoria" class="form-control" placeholder="Escribe la categoría" required>
    <input type="hidden" id="id_categoria" name="id_categoria" required>
  </div>

  <div id="categoria-suggestions" class="dropdown-menu" style="display: none; position: absolute;"></div>




</div>




<div class="row g-3" style=" padding-top: 20px;">

            <!-- Select para Repisa -->
            <div class="col">
            <label for="repisa" class="form-label">Repisa</label>
                <select id="repisa" name="id_repisa" class="form-control" placeholder="Elija la repisa" required>
                    @foreach($repisas as $repisa)
                    <option value="{{ $repisa->id }}" {{ isset($libro) && $libro->id_repisa == $repisa->id ? 'selected' : '' }}>
                        {{ $repisa->numero }}
                    </option>
                    @endforeach
                </select>
        </div>


<div class="col" >
    <label for="editorial" class="form-label">Editorial</label>
    <input type="text" id="editorial" class="form-control" placeholder="Escribe la editorial" required>
    <input type="hidden" id="id_editorial" name="id_editorial" required>
</div>

<div id="editorial-suggestions" class="dropdown-menu" style="display: none; position: absolute;"></div>




    <div class="col" >
        <label for="cantidad" class="form-label">Cantidad</label>
        <input id="cantidad" type="number" name="cantidad" class="form-control" value="{{ $libro->cantidad ?? old('cantidad') }}" required min="1" required>
    </div>
</div>





    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control">{{ $libro->descripcion ?? old('descripcion') }}</textarea>
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
            <th class="col-titulo">Título</th>
            <th class="col-autor">Autor</th>
            <th class="col-genero">Género</th>
            <th class="col-categoria">Categoría</th>
                @if ($admin->rol === 'superadmin')
                <th>Acciones</th>
                @endif
                
            </tr>
        </thead>
        <tbody>
            @foreach($libros as $libro)
            <tr>
                <td class="col-titulo">{{ $libro->titulo }}</td>
                <td class="col-autor">{{ $libro->autor->nombre }}</td>
                <td class="col-genero">{{ $libro->genero->nombre }}</td>
                <td class="col-categoria">{{ $libro->categoria->nombre }}</td>
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


    <!-- Paginación -->
    <div class="d-flex justify-content-center" style="padding-top: 40px;">
        {{ $libros->links('pagination::bootstrap-5') }}
    </div>

</div>


<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookLabel" aria-hidden="true">
  <div class="modal-dialog custom-modal-width">
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
          
          <!-- Título y Autor en la misma línea -->
          <div class="row mb-3">
            <div class="col">
              <label for="editTitulo" class="form-label">Título</label>
              <input id="editTitulo" type="text" class="form-control" name="titulo" required>
            </div>
            <div class="col">
              <label for="editAutor" class="form-label">Autor</label>
              <input id="editAutor" type="text" class="form-control" name="id_autor" required>
              <ul id="autorSuggestions" class="list-group" style="display: none;"></ul>
            </div>
          </div>

          <!-- Género y Categoría en la misma línea -->
          <div class="row mb-3">
            <div class="col">
              <label for="editGenero" class="form-label">Género</label>
              <input id="editGenero" type="text" class="form-control" name="id_genero" required>
              <ul id="generoSuggestions" class="list-group" style="display: none;"></ul>
            </div>
            <div class="col">
              <label for="editCategoria" class="form-label">Categoría</label>
              <input id="editCategoria" type="text" class="form-control" name="id_categoria" required>
              <ul id="categoriaSuggestions" class="list-group" style="display: none;"></ul>
            </div>
          </div>

          <!-- Editorial y Repisa en la misma línea -->
          <div class="row mb-3">
            <div class="col">
              <label for="editEditorial" class="form-label">Editorial</label>
              <input id="editEditorial" type="text" class="form-control" name="id_editorial" required>
              <ul id="editorialSuggestions" class="list-group" style="display: none;"></ul>
            </div>
            <div class="col">
              <label for="editRepisa" class="form-label">Repisa</label>
              <input id="editRepisa" type="text" class="form-control" name="id_repisa" required>
              <ul id="repisaSuggestions" class="list-group" style="display: none;"></ul>
            </div>
          </div>

          <!-- Cantidad -->
          <div class="mb-3">
            <label for="editCantidad" class="form-label">Cantidad</label>
            <input id="editCantidad" type="number" class="form-control" name="cantidad" required min="1">
          </div>

          <!-- Disponible -->
          <div class="mb-3">
            <label for="editDisponible" class="form-label">Disponible</label>
            <input id="editDisponible" type="checkbox" class="form-check-input" name="disponible">
          </div>

          <!-- Descripción -->
          <div class="mb-3">
            <label for="editDescripcion" class="form-label">Descripción</label>
            <textarea id="editDescripcion" class="form-control" name="descripcion"></textarea>
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

<script src="{{ asset('js/Geslibros.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = [
        { field: "autor", type: "autor" },
        { field: "genero", type: "genero" },
        { field: "categoria", type: "categoria" },
        { field: "editorial", type: "editorial" },
    ];

    inputs.forEach((input) => {
        const textInput = document.getElementById(input.field);
        const hiddenInput = document.getElementById(`id_${input.field}`);
        const suggestionsBox = document.getElementById(`${input.field}-suggestions`);

        textInput.addEventListener("input", function () {
            const query = this.value;

            if (query.length > 1) {
                // Hacer solicitud al backend
                fetch(`/autocomplete?type=${input.type}&q=${query}`)
                    .then((response) => response.json())
                    .then((data) => {
                        suggestionsBox.innerHTML = ""; // Limpiar las sugerencias anteriores
                        if (data.length > 0) {
                            data.forEach((item) => {
                                const suggestionItem = document.createElement("div");
                                suggestionItem.className = "dropdown-item";
                                suggestionItem.textContent = item.text;
                                suggestionItem.dataset.id = item.id;

                                suggestionItem.addEventListener("click", function () {
                                    textInput.value = this.textContent;
                                    hiddenInput.value = this.dataset.id;
                                    suggestionsBox.style.display = "none"; // Ocultar sugerencias
                                });

                                suggestionsBox.appendChild(suggestionItem);
                            });
                            suggestionsBox.style.display = "block"; // Mostrar sugerencias
                        } else {
                            suggestionsBox.style.display = "none";
                        }
                    });
            } else {
                suggestionsBox.style.display = "none";
            }
        });

        textInput.addEventListener("input", function () {
    const query = this.value;

    if (query.length > 1) {
        // Ajustar el ancho y la posición del cuadro de sugerencias
        suggestionsBox.style.width = `${textInput.offsetWidth}px`;
        suggestionsBox.style.left = `${textInput.getBoundingClientRect().left + window.scrollX}px`;
        suggestionsBox.style.top = `${textInput.getBoundingClientRect().bottom + window.scrollY}px`;

        // Hacer solicitud al backend
        fetch(`/autocomplete?type=${input.type}&q=${query}`)
            .then((response) => response.json())
            .then((data) => {
                suggestionsBox.innerHTML = ""; // Limpiar las sugerencias anteriores
                if (data.length > 0) {
                    data.forEach((item) => {
                        const suggestionItem = document.createElement("div");
                        suggestionItem.className = "dropdown-item";
                        suggestionItem.textContent = item.text;
                        suggestionItem.dataset.id = item.id;

                        suggestionItem.addEventListener("click", function () {
                            textInput.value = this.textContent;
                            hiddenInput.value = this.dataset.id;
                            suggestionsBox.style.display = "none"; // Ocultar sugerencias
                        });

                        suggestionsBox.appendChild(suggestionItem);
                    });
                    suggestionsBox.style.display = "block"; // Mostrar sugerencias
                } else {
                    suggestionsBox.style.display = "none";
                }
            });
    } else {
        suggestionsBox.style.display = "none";
    }
});


        // Ocultar sugerencias si se hace clic fuera
        document.addEventListener("click", function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== textInput) {
                suggestionsBox.style.display = "none";
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
        // Detectar el cambio de página
        const paginatorLinks = document.querySelectorAll('.pagination a');
        
        paginatorLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                
                const url = this.getAttribute('href');

                // Obtener la posición del scroll
                const scrollPosition = window.scrollY;

                // Realizar la petición a la nueva página
                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        // Actualizar el contenido de la tabla
                        document.querySelector('table').innerHTML = new DOMParser().parseFromString(data, 'text/html').querySelector('table').innerHTML;
                        
                        // Mantener la posición de scroll
                        window.scrollTo(0, scrollPosition);
                    });
            });
        });
    });


// Función para mostrar las sugerencias
function showSuggestions(inputId, suggestionsId, url, modelName) {
  const input = document.getElementById(inputId);
  const suggestions = document.getElementById(suggestionsId);
  
  input.addEventListener('input', function() {
    const query = input.value;

    if (query.length > 2) {
      // Hacer la solicitud AJAX
      fetch(`/search/${modelName}?q=${query}`)
        .then(response => response.json())
        .then(data => {
          suggestions.innerHTML = '';
          data.forEach(item => {
            const suggestionItem = document.createElement('li');
            suggestionItem.classList.add('list-group-item');
            suggestionItem.textContent = item.nombre;
            suggestionItem.onclick = () => {
              input.value = item.nombre;
              document.getElementById(inputId).value = item.id; // Asignar el ID correspondiente
              suggestions.innerHTML = '';
            };
            suggestions.appendChild(suggestionItem);
          });

          // Mostrar las sugerencias
          suggestions.style.display = data.length ? 'block' : 'none';
        });
    } else {
      suggestions.style.display = 'none';
    }
  });
}

// Inicializa las sugerencias para Autor, Género, Categoría, y Editorial
showSuggestions('editAutor', 'autorSuggestions', '/search/autor', 'autor');
showSuggestions('editGenero', 'generoSuggestions', '/search/genero', 'genero');
showSuggestions('editCategoria', 'categoriaSuggestions', '/search/categoria', 'categoria');
showSuggestions('editEditorial', 'editorialSuggestions', '/search/editorial', 'editorial');
showSuggestions('editRepisa', 'repisaSuggestions', '/search/repisa', 'repisa');


document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("editBookModal");
    const inputs = [
        { field: "autor", suggestions: "autor-suggestions" },
        { field: "genero", suggestions: "genero-suggestions" },
        { field: "categoria", suggestions: "categoria-suggestions" },
        { field: "editorial", suggestions: "editorial-suggestions" }
    ];

    // Función para ajustar tamaño y posición de las sugerencias
    function adjustSuggestionsBox(input, suggestionsBox) {
        const rect = input.getBoundingClientRect();
        suggestionsBox.style.width = `${rect.width}px`;
        suggestionsBox.style.position = "absolute";
        suggestionsBox.style.left = `${rect.left + window.scrollX}px`;
        suggestionsBox.style.top = `${rect.bottom + window.scrollY}px`;
        suggestionsBox.style.zIndex = "1050"; // Asegura que se muestre sobre el modal
    }

    // Inicializa el autocompletado para cada input
    inputs.forEach(({ field, suggestions }) => {
        const input = document.getElementById(field);
        const suggestionsBox = document.getElementById(suggestions);

        // Ajustar ancho y posición al abrir el modal
        modal.addEventListener("shown.bs.modal", function () {
            adjustSuggestionsBox(input, suggestionsBox);
        });

        // Mostrar sugerencias dinámicamente al escribir
        input.addEventListener("input", function () {
            const query = this.value;

            if (query.length > 1) {
                adjustSuggestionsBox(input, suggestionsBox);

                // Simulación de solicitud al backend
                fetch(`/autocomplete?type=${field}&q=${query}`)
                    .then((response) => response.json())
                    .then((data) => {
                        suggestionsBox.innerHTML = ""; // Limpia las sugerencias anteriores
                        if (data.length > 0) {
                            data.forEach((item) => {
                                const suggestionItem = document.createElement("div");
                                suggestionItem.className = "dropdown-item";
                                suggestionItem.textContent = item.text;
                                suggestionItem.dataset.id = item.id;

                                // Al hacer clic en una sugerencia
                                suggestionItem.addEventListener("click", function () {
                                    input.value = this.textContent;
                                    document.getElementById(`id_${field}`).value = this.dataset.id;
                                    suggestionsBox.style.display = "none"; // Oculta las sugerencias
                                });

                                suggestionsBox.appendChild(suggestionItem);
                            });
                            suggestionsBox.style.display = "block"; // Muestra sugerencias
                        } else {
                            suggestionsBox.style.display = "none";
                        }
                    });
            } else {
                suggestionsBox.style.display = "none"; // Oculta si el input tiene menos de 2 caracteres
            }
        });

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener("click", function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== input) {
                suggestionsBox.style.display = "none";
            }
        });

        // Ajustar dinámicamente al redimensionar la ventana
        window.addEventListener("resize", function () {
            adjustSuggestionsBox(input, suggestionsBox);
        });
    });
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