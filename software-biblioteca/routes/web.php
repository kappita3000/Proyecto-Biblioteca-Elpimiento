<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('singin');
});

Route::get('/libros', function () {
    return view('app');
});

Route::get('/singin', function () {
    return view('singin');
});

route::post('/singin', [UsuarioController::class, 'store'])->name('usuario');

Route::get('/login', function () {
    return view('login');
});