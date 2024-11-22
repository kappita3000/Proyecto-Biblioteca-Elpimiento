<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    
    <!-- Bootstrap -->
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
  @php
  $admin = Auth::guard('admin')->user();
@endphp
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Biblioteca Nuevo Horizonte</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ route('inicioadmin') }}">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ route('prestamos.index') }}">Prestamos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ route('libros.librosindex') }}">Gestionar Libros</a>
        </li>
        
        @if ($admin->rol === 'superadmin')
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('estadisticas.index') }}">Estadisticas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('newAdmin') }}">Crear moderador</a>
          </li>
        @endif
      </ul>

      <!-- Sección de usuario y cerrar sesión alineada a la derecha -->
      @if (Auth::guard('admin')->check())
        @php
            $admin = Auth::guard('admin')->user();
        @endphp
        <div class="d-flex align-items-center">
            <!-- Mensaje de bienvenida -->
            <span class="navbar-text me-3">
                @if ($admin->rol === 'superadmin')
                    Bienvenido, Superadmin {{ $admin->nombre }}
                @elseif ($admin->rol === 'moderador')
                    Bienvenido, Moderador {{ $admin->nombre }}
                @endif
            </span>

            <!-- Botón para cerrar sesión -->
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-link" style="text-decoration: none;">Cerrar sesión</button>
            </form>
        </div>
      @endif
    </div>
  </div>
</nav>

    <div class="container">
        @yield('content')
    </div>
<script src="{{ asset('js/global.js') }}"></script>
</body>
</html>
