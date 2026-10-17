<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Models\Livro;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('listaGeneros', Livro::GENEROS);
    }
}