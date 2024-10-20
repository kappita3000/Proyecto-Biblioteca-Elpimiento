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




Route::get('/glibros', [GesLibroController::class, 'librosindex'])->name('libros.librosindex');
Route::post('/glibros', [GesLibroController::class, 'store'])->name('libros.store');
Route::get('/glibros/{id}/edit', [GesLibroController::class, 'edit'])->name('libros.edit');
Route::put('/glibros/{id}', [GesLibroController::class, 'update'])->name('libros.update');  
Route::delete('/glibros/{id}', [GesLibroController::class, 'destroy'])->name('libros.destroy');  











Route::get('/libros', [ReservaController::class, 'index'])->name('libros.index'); // Muestra todos los libros
Route::get('/libros/{id}', [ReservaController::class, 'show'])->name('libros.show'); // Muestra un libro específico

Route::get('/buscar', [ReservaController::class, 'search'])->name('libros.search');

Route::post('/reservar-libro', [ReservaController::class, 'reservar'])->name('reservar.libro');

Route::post('/reservar-libro', [ReservaController::class, 'reservarLibro'])->name('reservar.libro');
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
    Route::get('/probando', function () {
        return view('probando');
    })->middleware('role:superadmin,moderador');
});


Route::get('/newAdmin', function () {
    return view('newAdmin');
})->name('newAdmin');

route::post('/newAdmin', [NewAdminController::class, 'store'])->name('newAdmin');