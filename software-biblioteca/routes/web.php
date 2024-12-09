<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GesLibroController;
use App\Http\Controllers\NewAdminController;
use App\Http\Controllers\ReservaController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\GestionesController;
use App\Http\Controllers\FiltrosController;
use App\Http\Controllers\PortadasController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\CateInicioController;

Route::group(['middleware' => ['auth:admin', 'role:superadmin,moderador']], function () {

Route::get('/glibros', [GesLibroController::class, 'librosindex'])->name('libros.librosindex');
Route::post('/glibros', [GesLibroController::class, 'store'])->name('libros.store');
Route::get('/glibros/{id}/edit', [GesLibroController::class, 'edit'])->name('libros.edit');
Route::put('/glibros/{id}', [GesLibroController::class, 'update'])->name('libros.update');  
Route::delete('/glibros/{id}', [GesLibroController::class, 'destroy'])->name('libros.destroy');  

Route::get('/glibros/search', [GesLibroController::class, 'search'])->name('libros.search');


Route::get('/gestiones', [GestionesController::class, 'index'])->name('gestiones.gestiones');
Route::post('/gestiones/genero', [GestionesController::class, 'storeGenero']);
Route::put('/gestiones/genero/{id}', [GestionesController::class, 'updateGenero']);
Route::delete('/gestiones/genero/{id}', [GestionesController::class, 'deleteGenero']);

// Agrega rutas para autores, categorías, repisas y editoriales


Route::get('gestiones', [GestionesController::class, 'index'])->name('gestiones.gestiones');
Route::post('gestiones/autor', [GestionesController::class, 'storeAutor']);
Route::post('gestiones/genero', [GestionesController::class, 'storeGenero']);
Route::post('gestiones/categoria', [GestionesController::class, 'storeCategoria']);
Route::post('gestiones/repisa', [GestionesController::class, 'storeRepisa']);
Route::post('gestiones/editorial', [GestionesController::class, 'storeEditorial']);
Route::delete('gestiones/autor/{id}', [GestionesController::class, 'deleteAutor']);

Route::delete('/gestiones/genero/{id}', [GestionesController::class, 'deleteGenero'])->name('genero.delete');

Route::delete('gestiones/categoria/{id}', [GestionesController::class, 'deleteCategoria']);
Route::delete('gestiones/repisa/{id}', [GestionesController::class, 'deleteRepisa']);
Route::delete('gestiones/editorial/{id}', [GestionesController::class, 'deleteEditorial']);
Route::put('gestiones/autor/{id}', [GestionesController::class, 'updateAutor'])->name('gestiones.updateAutor');
Route::put('gestiones/genero/{id}', [GestionesController::class, 'updateGenero'])->name('gestiones.updateGenero');
Route::put('gestiones/categoria/{id}', [GestionesController::class, 'updateCategoria'])->name('gestiones.updateCategoria');
Route::put('gestiones/repisa/{id}', [GestionesController::class, 'updateRepisa'])->name('gestiones.updateRepisa');
Route::put('gestiones/editorial/{id}', [GestionesController::class, 'updateEditorial'])->name('gestiones.updateEditorial');



Route::get('/autocomplete', [GesLibroController::class, 'autocomplete'])->name('autocomplete');

Route::get('/search/autor', [GesLibroController::class, 'buscarAutor']);
Route::get('/search/genero', [GesLibroController::class, 'buscarGenero']);
Route::get('/search/categoria', [GesLibroController::class, 'buscarCategoria']);
Route::get('/search/editorial', [GesLibroController::class, 'buscarEditorial']);
Route::get('/search/repisa', [GesLibroController::class, 'buscarRepisa']);



// Rutas para eliminación masiva de Autores
Route::delete('gestiones/autores/bulk-delete', [GestionesController::class, 'bulkDeleteAutores'])->name('autores.bulk-delete');

// Rutas para eliminación masiva de Géneros
Route::delete('gestiones/generos/bulk-delete', [GestionesController::class, 'bulkDeleteGeneros'])->name('generos.bulk-delete');

// Rutas para eliminación masiva de Categorías
Route::delete('gestiones/categorias/bulk-delete', [GestionesController::class, 'bulkDeleteCategorias'])->name('categorias.bulk-delete');

// Rutas para eliminación masiva de Repisas
Route::delete('gestiones/repisas/bulk-delete', [GestionesController::class, 'bulkDeleteRepisas'])->name('repisas.bulk-delete');

// Rutas para eliminación masiva de Editoriales
Route::delete('gestiones/editoriales/bulk-delete', [GestionesController::class, 'bulkDeleteEditoriales'])->name('editoriales.bulk-delete');


});


Route::get('/', function () {return view('info');});
Route::get('/categorias', [CateInicioController::class, 'index'])->name('categorias.index');
Route::get('/inicio', function () {return view('info');});

Route::get('/libros/filtrar', [FiltrosController::class, 'filtrarPorCategoria'])->name('filtrarPorCategoria');


Route::get('/repertorio', [ReservaController::class, 'index'])->name('libros.index'); // Muestra todos los libros
// Página principal - muestra los libros recientes
Route::get('/repertorio2', [ReservaController::class, 'index'])->name('index');

// Mostrar todos los libros con filtros (manejado por FiltrosController)
Route::get('/libros', [FiltrosController::class, 'filtrarPorGenero'])->name('libros.filtro');

// Mostrar un libro específico (por ID)
Route::get('/libros/{id}', [ReservaController::class, 'show'])->name('libros.show');

// Búsqueda de libros por palabra clave
Route::get('/buscar', [ReservaController::class, 'search'])->name('libros.search');

// Reservar un libro
Route::post('/reservar-libro', [ReservaController::class, 'reservarLibro'])->name('reservar.libro');



Route::get('/signin', function () {
    return view('signin');
})->name('signin');

route::post('/signin', [UsuarioController::class, 'store'])->name('usuario');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::guard('admin')->logout(); // Cerrar sesión de admin
    Auth::guard('web')->logout(); // Cerrar sesión de usuario normal
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
})->name('logout');


Route::aliasMiddleware('role', RoleMiddleware::class);

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', function () {
        return view('inicioadmin');
    })->middleware('role:superadmin,moderador')->name('inicioadmin'); // Asignar nombre a la ruta
});


Route::get('/newAdmin', function () {
    return view('newAdmin');
})->name('newAdmin');

route::post('/newAdmin', [NewAdminController::class, 'store'])->name('newAdmin');

Route::group(['middleware' => ['auth:admin', 'role:superadmin,moderador']], function () {
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::post('/prestamos/aceptar/{id}', [PrestamoController::class, 'aceptar'])->name('prestamos.aceptar');
    Route::post('/prestamos/rechazar/{id}', [PrestamoController::class, 'rechazar'])->name('prestamos.rechazar');
    Route::get('/prestamos/{id}/edit', [PrestamoController::class, 'edit'])->name('prestamos.edit');
    Route::put('/prestamos/{id}', [PrestamoController::class, 'update'])->name('prestamos.update');
    Route::put('/prestamos/registrarDevolucion/{id}', [PrestamoController::class, 'registrarDevolucion'])->name('prestamos.registrarDevolucion');
    Route::post('/prestamos/store/registrado', [PrestamoController::class, 'storeRegistrado'])->name('prestamos.store.registrado');
    Route::post('/prestamos/store/no_registrado', [PrestamoController::class, 'storeNoRegistrado'])->name('prestamos.store.no_registrado');
    Route::get('/buscar-libros', [GesLibroController::class, 'buscar'])->name('libros.buscar');
    Route::get('/buscar-usuarios', [UsuarioController::class, 'buscar'])->name('usuarios.buscar');
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas.index');
});