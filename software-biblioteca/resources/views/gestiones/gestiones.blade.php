@extends('layouts.admin')
<title>Gestión de Biblioteca</title>
@section('content')
<style>
    .tab-pane:not(.show) {
        display: none;
    }

</style>
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <h1>Gestión de Biblioteca</h1>

    <ul class="nav nav-tabs" id="gestionesTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="autores-tab" data-bs-toggle="tab" href="#autores" role="tab" aria-controls="autores" aria-selected="false">Autores</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="generos-tab" data-bs-toggle="tab" href="#generos" role="tab" aria-controls="generos" aria-selected="false">Géneros</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="categorias-tab" data-bs-toggle="tab" href="#categorias" role="tab" aria-controls="categorias" aria-selected="false">Categorías</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="repisas-tab" data-bs-toggle="tab" href="#repisas" role="tab" aria-controls="repisas" aria-selected="false">Repisas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="editoriales-tab" data-bs-toggle="tab" href="#editoriales" role="tab" aria-controls="editoriales" aria-selected="false">Editoriales</a>
        </li>
    </ul>
    <th>#</th>
<td>{{ $index + 1 }}</td>
<!-- Pestaña de Autores -->
<div class="tab-pane fade show active" id="autores" role="tabpanel" aria-labelledby="autores-tab">
    <form action="{{ url('gestiones/autor') }}" method="POST" class="mb-3">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre del autor" required>
        <input type="text" name="nacionalidad" placeholder="Nacionalidad" required>
        <button type="submit" class="btn btn-primary">Agregar Autor</button>
    </form>
    <form action="{{ route('autores.bulk-delete') }}" method="POST" id="bulkDeleteAutoresForm">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Eliminar Seleccionados</button>
        <table class="table mt-3">
            <thead>
                <tr>
                    
                    <th><input type="checkbox" id="selectAllAutores"></th>
                    <th>Nombre</th>
                    <th>Nacionalidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($autores as $autor)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $autor->id }}"></td>
                    <td>{{ $autor->nombre }}</td>
                    <td>{{ $autor->nacionalidad }}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editAutorModal-{{ $autor->id }}">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAutorModal-{{ $autor->id }}">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

<!-- Pestaña de Géneros -->
<div class="tab-pane fade @if(session('activeTab') == 'generos') show active @endif" id="generos" role="tabpanel" aria-labelledby="generos-tab">
    <form action="{{ url('gestiones/genero') }}" method="POST" class="mb-3">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre del género" required>
        <button type="submit" class="btn btn-primary">Agregar Género</button>
    </form>
    <form action="{{ route('generos.bulk-delete') }}" method="POST" id="bulkDeleteGenerosForm">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Eliminar Seleccionados</button>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllGeneros"></th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($generos as $genero)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $genero->id }}"></td>
                    <td>{{ $genero->nombre }}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editGeneroModal-{{ $genero->id }}">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGeneroModal-{{ $genero->id }}">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>




<!-- Pestaña de Categorías -->
<div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
    <form action="{{ url('gestiones/categoria') }}" method="POST" class="mb-3">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre de la categoría" required>
        <button type="submit" class="btn btn-primary">Agregar Categoría</button>
    </form>
    <form action="{{ route('categorias.bulk-delete') }}" method="POST" id="bulkDeleteCategoriasForm">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Eliminar Seleccionados</button>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllCategorias"></th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $categoria)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $categoria->id }}"></td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoriaModal-{{ $categoria->id }}">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoriaModal-{{ $categoria->id }}">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>


<!-- Pestaña de Repisas -->
<div class="tab-pane fade" id="repisas" role="tabpanel" aria-labelledby="repisas-tab">
    <form action="{{ url('gestiones/repisa') }}" method="POST" class="mb-3">
        @csrf
        <input type="number" name="numero" placeholder="Número de la repisa" required>
        <button type="submit" class="btn btn-primary">Agregar Repisa</button>
    </form>
    <form action="{{ route('repisas.bulk-delete') }}" method="POST" id="bulkDeleteRepisasForm">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Eliminar Seleccionados</button>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllRepisas"></th>
                    <th>Número</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repisas as $repisa)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $repisa->id }}"></td>
                    <td>{{ $repisa->numero }}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editRepisaModal-{{ $repisa->id }}">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRepisaModal-{{ $repisa->id }}">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>



<!-- Pestaña de Editoriales -->
<div class="tab-pane fade" id="editoriales" role="tabpanel" aria-labelledby="editoriales-tab">
    <form action="{{ url('gestiones/editorial') }}" method="POST" class="mb-3">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre de la editorial" required>
        <input type="text" name="pais" placeholder="País de la editorial" required>
        <button type="submit" class="btn btn-primary">Agregar Editorial</button>
    </form>
    <form action="{{ route('editoriales.bulk-delete') }}" method="POST" id="bulkDeleteEditorialesForm">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">Eliminar Seleccionados</button>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllEditoriales"></th>
                    <th>Nombre</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($editoriales as $editorial)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $editorial->id }}"></td>
                    <td>{{ $editorial->nombre }}</td>
                    <td>{{ $editorial->pais }}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#editEditorialModal-{{ $editorial->id }}">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteEditorialModal-{{ $editorial->id }}">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>




        <!-- Modal para editar Autores -->
        @foreach($autores as $autor)
        <div class="modal fade" id="editAutorModal-{{ $autor->id }}" tabindex="-1" aria-labelledby="editAutorLabel-{{ $autor->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAutorLabel-{{ $autor->id }}">Editar Autor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/autor/'.$autor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nombre-{{ $autor->id }}" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre-{{ $autor->id }}" name="nombre" value="{{ $autor->nombre }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nacionalidad-{{ $autor->id }}" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control" id="nacionalidad-{{ $autor->id }}" name="nacionalidad" value="{{ $autor->nacionalidad }}" required>
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
        @endforeach

        <!-- Modal de Género -->
        @foreach($generos as $genero)
<div class="modal fade" id="editGeneroModal-{{ $genero->id }}" tabindex="-1" aria-labelledby="editGeneroLabel-{{ $genero->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGeneroLabel-{{ $genero->id }}">Editar Género</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('gestiones/genero/'.$genero->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="text" name="nombre" class="form-control" value="{{ $genero->nombre }}" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach



        <!-- Modal de Categorías -->
        @foreach($categorias as $categoria)
        <div class="modal fade" id="editCategoriasModal-{{ $categoria->id }}" tabindex="-1" aria-labelledby="editCategoriaLabel-{{ $categoria->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoriaLabel-{{ $categoria->id }}">Editar Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/categoria/'.$categoria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nombre-{{ $categoria->id }}" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre-{{ $categoria->id }}" name="nombre" value="{{ $categoria->nombre }}" required>
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
        @endforeach

        <!-- Modal de Repisas -->
        @foreach($repisas as $repisa)
        <div class="modal fade" id="editRepisaModal-{{ $repisa->id }}" tabindex="-1" aria-labelledby="editRepisaLabel-{{ $repisa->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRepisaLabel-{{ $repisa->id }}">Editar Repisa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
            <form action="{{ url('gestiones/repisa/'.$repisa->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numero-{{ $repisa->id }}" class="form-label">Número de Repisa</label>
                        <input type="number" class="form-control" id="numero-{{ $repisa->id }}" name="numero" value="{{ $repisa->numero }}" required>
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
        @endforeach

        <!-- Modal de Editoriales -->
        @foreach($editoriales as $editorial)
        <div class="modal fade" id="editEditorialModal-{{ $editorial->id }}" tabindex="-1" aria-labelledby="editEditorialLabel-{{ $editorial->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEditorialLabel-{{ $editorial->id }}">Editar Editorial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/editorial/'.$editorial->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nombre-{{ $editorial->id }}" class="form-label">Nombre de la Editorial</label>
                                <input type="text" class="form-control" id="nombre-{{ $editorial->id }}" name="nombre" value="{{ $editorial->nombre }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="pais-{{ $editorial->id }}" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais-{{ $editorial->id }}" name="pais" value="{{ $editorial->pais }}" required>
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
        @endforeach


        <!-- Modal para eliminar Autor -->
        @foreach($autores as $autor)
        <div class="modal fade" id="deleteAutorModal-{{ $autor->id }}" tabindex="-1" aria-labelledby="deleteAutorLabel-{{ $autor->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAutorLabel-{{ $autor->id }}">Eliminar Autor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/autor/'.$autor->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar este autor?</p>
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


        <!-- Modal para eliminar Género -->
        @foreach($generos as $genero)
<div class="modal fade" id="deleteGeneroModal-{{ $genero->id }}" tabindex="-1" aria-labelledby="deleteGeneroLabel-{{ $genero->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGeneroLabel-{{ $genero->id }}">Eliminar Género</h5>
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

        <!-- Modal para eliminar Categoría -->
        @foreach($categorias as $categoria)
        <div class="modal fade" id="deleteCategoriaModal-{{ $categoria->id }}" tabindex="-1" aria-labelledby="deleteCategoriaLabel-{{ $categoria->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCategoriaLabel-{{ $categoria->id }}">Eliminar Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/categoria/'.$categoria->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar esta categoría?</p>
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

        <!-- Modal para eliminar Repisa -->
        @foreach($repisas as $repisa)
        <div class="modal fade" id="deleteRepisaModal-{{ $repisa->id }}" tabindex="-1" aria-labelledby="deleteRepisaLabel-{{ $repisa->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRepisaLabel-{{ $repisa->id }}">Eliminar Repisa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/repisa/'.$repisa->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar esta repisa?</p>
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

        <!-- Modal para eliminar Editorial -->
        @foreach($editoriales as $editorial)
        <div class="modal fade" id="deleteEditorialModal-{{ $editorial->id }}" tabindex="-1" aria-labelledby="deleteEditorialLabel-{{ $editorial->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEditorialLabel-{{ $editorial->id }}">Eliminar Editorial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('gestiones/editorial/'.$editorial->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar esta editorial?</p>
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

</div>


@if(session('activeTab'))
    <script>
        localStorage.setItem("activeTab", "{{ session('activeTab') }}");
    </script>
    @endif

    <script src="{{ asset('js/gestiones.js') }}"></script>



@endsection

