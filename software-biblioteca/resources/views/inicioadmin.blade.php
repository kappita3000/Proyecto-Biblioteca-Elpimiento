<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
      

    @if (Auth::guard('admin')->check())
        @php
            $admin = Auth::guard('admin')->user();
        @endphp

        <div class="container">
            <h1>Página Exclusiva para Administradores</h1>
            
            <!-- Tarjetas de funciones -->
            <div class="row mt-4 justify-content-center">
                <!-- Tarjeta 1: Gestión de Usuarios -->
                <div class="col-md-4 mb-4">
                    <a href="" class="card-link" style="text-decoration: none;">
                        <div class="card text-center">
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <img src="{{ asset('img/libros.png') }}" class="card-img-top" alt="Libros" style="width: 250px; height: 250px; object-fit: contain;">
                            </div>    
                            <div class="card-body">
                                <h5 class="card-title">Gestión de Libros</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Tarjeta 2: Reportes -->
                <div class="col-md-4 mb-4">
                    <a href="{{ route('prestamos.index') }}" class="card-link" style="text-decoration: none;">
                        <div class="card text-center">
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <img src="{{ asset('img/prestamos.png') }}" class="card-img-top" alt="Prestamos" style="width: 250px; height: 250px; object-fit: contain;">
                            </div>    
                            <div class="card-body">
                                <h5 class="card-title">Gestion de Prestamos</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Tarjeta 3: Configuración -->
                @if ($admin->rol === 'superadmin')
                <div class="col-md-4 mb-4" style="text-decoration: none">
                    <a href="{{ route('newAdmin') }}" class="card-link" style="text-decoration: none;">
                        <div class="card text-center">
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <img src="{{ asset('img/nusuario.png') }}" class="card-img-top" alt="Moderadores" style="width: 250px; height: 250px; object-fit: contain;">
                            </div>    
                            <div class="card-body">
                                <h5 class="card-title" >Crear Moderador</h5>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                 <!-- Tarjeta 4: Funciones Secundarias -->
                <div class="col-md-4 mb-4">
                    <a href="{{ route('gestiones.gestiones') }}" class="card-link" style="text-decoration: none;">
                        <div class="card text-center">
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <img src="{{ asset('img/secundarias.png') }}" alt="Secundarias" style="width: 250px; height: 250px; object-fit: contain;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Funciones Secundarias</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>