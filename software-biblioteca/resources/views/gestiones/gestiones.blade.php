@extends('layouts.admin')
<title>Gestión de Biblioteca</title>
@section('content')

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
            <a class="nav-link active" id="autores-tab" data-bs-toggle="tab" href="#autores" role="tab" aria-controls="autores" aria-selected="true">Autores</a>
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
    <div class="tab-content" id="gestionesTabContent">
        <!-- Pestaña de Autores -->
        <div class="tab-pane fade show active" id="autores" role="tabpanel" aria-labelledby="autores-tab">
            <form action="{{ url('gestiones/autor') }}" method="POST">
                @csrf
                <input type="text" name="nombre" placeholder="Nombre del autor" required>
                <input type="text" name="nacionalidad" placeholder="Nacionalidad" required>
                <button type="submit">Agregar Autor</button>
            </form>
            <ul>
                @foreach($autores as $autor)
                    <li>{{ $autor->nombre }} ({{ $autor->nacionalidad }}) 
                        <form action="{{ url('gestiones/autor/'.$autor->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Pestaña de Géneros -->
        <div class="tab-pane fade" id="generos" role="tabpanel" aria-labelledby="generos-tab">
            <form action="{{ url('gestiones/genero') }}" method="POST">
                @csrf
                <input type="text" name="nombre" placeholder="Nombre del género" required>
                <button type="submit">Agregar Género</button>
            </form>
            <ul>
                @foreach($generos as $genero)
                    <li>{{ $genero->nombre }}
                        <form action="{{ url('gestiones/genero/'.$genero->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Pestaña de Categorías -->
        <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
            <form action="{{ url('gestiones/categoria') }}" method="POST">
                @csrf
                <input type="text" name="nombre" placeholder="Nombre de la categoría" required>
                <button type="submit">Agregar Categoría</button>
            </form>
            <ul>
                @foreach($categorias as $categoria)
                    <li>{{ $categoria->nombre }}
                        <form action="{{ url('gestiones/categoria/'.$categoria->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Pestaña de Repisas -->
        <div class="tab-pane fade" id="repisas" role="tabpanel" aria-labelledby="repisas-tab">
            <form action="{{ url('gestiones/repisa') }}" method="POST">
                @csrf
                <input type="number" name="numero" placeholder="Número de repisa" required>
                <button type="submit">Agregar Repisa</button>
            </form>
            <ul>
                @foreach($repisas as $repisa)
                    <li>{{ $repisa->numero }}
                        <form action="{{ url('gestiones/repisa/'.$repisa->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Pestaña de Editoriales -->
        <div class="tab-pane fade" id="editoriales" role="tabpanel" aria-labelledby="editoriales-tab">
            <form action="{{ url('gestiones/editorial') }}" method="POST">
                @csrf
                <input type="text" name="nombre" placeholder="Nombre de la editorial" required>
                <input type="text" name="pais" placeholder="País" required>
                <button type="submit">Agregar Editorial</button>
            </form>
            <ul>
                @foreach($editoriales as $editorial)
                    <li>{{ $editorial->nombre }} ({{ $editorial->pais }})
                        <form action="{{ url('gestiones/editorial/'.$editorial->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection