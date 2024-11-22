<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Categoria;
use App\Models\Genero;
use App\Models\Autor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Compartir datos con todas las vistas
        View::composer('*', function ($view) {
            $view->with('categorias', Categoria::all());
            $view->with('generos', Genero::all());
            $view->with('autores', Autor::all());
        });
    }
}
