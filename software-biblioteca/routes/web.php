<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\ReservaController;

Route::get('/libros', function () {
    return view('libros');
})->name('libros');


Route::get('/libros', [ReservaController::class, 'index'])->name('libros');

Route::get('/libros', [ReservaController::class, 'index'])->name('libros.index'); // Muestra todos los libros
Route::get('/libros/{id}', [ReservaController::class, 'show'])->name('libros.show'); // Muestra un libro específico
Route::post('/reservar', [ReservaController::class, 'store'])->name('reservar');
Route::post('/reservar-libro', [ReservaController::class, 'store'])->name('reservar.libro');

Route::get('/signin', function () {
    return view('signin');
})->name('signin');

route::post('/signin', [UsuarioController::class, 'store'])->name('usuario');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
