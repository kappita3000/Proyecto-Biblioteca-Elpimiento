<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <!-- Bootstrap -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    <style>
        body {
            background-image: url('{{ asset('img/patron-libros4.jpg') }}');
            !important;
            backdrop-filter: blur(3px);
            position: relative;
            /* Necesario para que el overlay se posicione bien */
            /* Para centrar la imagen */
            background-repeat: repeat;
            /* Evitar repetir la imagen si es más pequeña que la pantalla */
        }


        /* Estilo general del navbar */
        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Añadir una sombra para elevar el header */
            padding: 1rem 1.5rem;
            /* Añadir relleno para crear espacio interno */
            position: sticky;
            /* Fijar el header en la parte superior de la página */
            top: 0;
            z-index: 1000;
            /* Asegurar que esté siempre visible */
        }

        /* Logo del sitio */
        .navbar-brand {
            font-size: 1.75rem;
            /* Tamaño de fuente más grande para destacarlo */
            font-weight: bold;
            /* Texto en negrita */
            color: #333;
            /* Color del texto oscuro para buen contraste */
            transition: color 0.3s ease;
        }

        .navbar-brand:hover {
            color: #000;
            /* Cambiar el color al hacer hover para destacar el logo */
            text-decoration: none;
            /* Eliminar el subrayado */
        }

        /* Estilo de los enlaces de navegación */
        .nav-link {
            font-size: 1.1rem;
            /* Ajustar el tamaño de fuente */
            color: #333;
            /* Color oscuro para el texto */
            display: flex;
            /* Utilizar flexbox para alinear el icono con el texto */
            align-items: center;
            /* Centrar verticalmente el icono con el texto */
            transition: color 0.3s ease, transform 0.3s ease;
            /* Transición suave */
        }

        .nav-link i {
            margin-right: 8px;
            /* Añadir espacio entre el icono y el texto */
        }

        .nav-link:hover {
            color: #1e73be;
            /* Cambiar el color al pasar el ratón */
            transform: translateY(-2px);
            /* Elevar el enlace al hacer hover */
        }

        .navbar-text {
            color: #333;
            font-weight: bold;
        }

        .btn-link {
            color: #333;
            font-weight: bold;
            text-decoration: none;
        }

        /* Botón del navbar (hamburger) */
        .navbar-toggler {
            border: none;
            /* Eliminar borde predeterminado */
        }

        .navbar-toggler-icon {
            background-color: #333;
            /* Cambiar color del icono para mejor visibilidad */
        }

        /* Estilo del formulario de búsqueda */
        .form-control {
            border-radius: 20px 0 0 20px;
            /* Bordes redondeados para el input */
            border: 1px solid #ccc;
            /* Borde gris claro */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Sombra para resaltar el input */
            transition: box-shadow 0.3s ease;
            /* Transición suave para el foco */
        }

        .form-control:focus {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra más pronunciada cuando el input tiene el foco */
            outline: none;
            /* Quitar el borde azul predeterminado */
        }

        /* Botón de búsqueda */
        .btn-outline-success {
            background-color: #d1d9a6;
            /* Mantener el color verde suave */
            color: #333;
            /* Texto oscuro para buen contraste */
            border: none;
            /* Eliminar borde predeterminado */
            border-radius: 0 20px 20px 0;
            /* Bordes redondeados para el botón */
            padding: 8px 16px;
            /* Añadir relleno para mejor apariencia */
            transition: background-color 0.3s ease, transform 0.3s ease;
            /* Transición suave para el color y el movimiento */
        }

        .btn-outline-success:hover {
            background-color: #b5be8a;
            /* Cambiar a un tono más oscuro al hacer hover */
            color: #fff;
            /* Cambiar el color del texto a blanco para mejor contraste */
            transform: translateY(-2px);
            /* Elevar un poco el botón al hacer hover */
        }

        /* Estilo del título principal en el header */
        .titulo-principal {
            font-size: 1.5rem;
            /* Tamaño grande para destacarlo */
            font-weight: bold;
            /* Negrita para hacerlo más visible */
            color: #333;
            /* Color oscuro para buen contraste */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            /* Sombra ligera para mayor profundidad */
            text-transform: uppercase;
            /* Convertir el texto a mayúsculas */
            letter-spacing: 2px;
            /* Añadir espacio entre letras */
            transition: color 0.3s ease, transform 0.3s ease;
            /* Transiciones suaves para efectos */
        }

        /* Estilo al hacer hover sobre el título */
        .titulo-principal:hover {
            color: #1e73be;
            /* Cambiar color al hacer hover */
            transform: scale(1.05);
            /* Aumentar ligeramente el tamaño al pasar el ratón */
        }
    </style>

    
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color:#E8F5E9 !important;">
        <div class="container-fluid">
            <a class="navbar-brand titulo-principal" href="/">
                <i class="fas fa-book"></i> Nuevo Horizonte
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('index') }}">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('login') }}">
                            <i class="fas fa-user"></i> Iniciar sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('signin') }}">
                            <i class="fas fa-user-plus"></i> Crear cuenta
                        </a>
                    </li>
                </ul>

                <!-- Información del usuario y botón de cerrar sesión -->
                @if (Auth::guard('admin')->check() || Auth::guard('web')->check())
                    <div class="d-flex align-items-center user-info ms-4">
                        <!-- Mensaje de bienvenida -->
                        <span class="navbar-text me-3 fw-bold">
                            @if (Auth::guard('admin')->check())
                                @if (Auth::guard('admin')->user()->rol === 'superadmin')
                                    Bienvenido, {{ Auth::guard('admin')->user()->nombre }} (Superadmin)
                                @elseif (Auth::guard('admin')->user()->rol === 'moderador')
                                    Bienvenido, {{ Auth::guard('admin')->user()->nombre }} (Moderador)
                                @endif
                            @elseif (Auth::guard('web')->check())
                                Bienvenido, {{ Auth::guard('web')->user()->nombre }}
                            @endif
                        </span>

                        <!-- Botón para cerrar sesión -->
                        <form action="{{ route('logout') }}" method="POST" class="d-inline ms-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm"
                                onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?')">
                                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                @endif




                <form class="d-flex" action="{{ route('libros.search') }}" method="GET" style="padding-left: 10px">
                    <input class="form-control me-2" type="search" placeholder="Buscar libros" name="query"
                        aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>

            </div>

        </div>

    </nav>

    <div class="container">
        @yield('content')
    </div>
    <script src="{{ asset('js/global.js') }}"></script>
</body>

</html>
