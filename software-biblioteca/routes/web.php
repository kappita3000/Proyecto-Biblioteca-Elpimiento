<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('singin');
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



