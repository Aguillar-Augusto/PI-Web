<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $secoes = [];

        if (Auth::check()) {
            $favoritos = Auth::user()
                ->livrosFavoritos()
                ->orderBy('dataupload', 'desc')
                ->take(10)
                ->get();

            if ($favoritos->isNotEmpty()) {
                $secoes[] = [
                    'titulo' => 'Favoritos',
                    'livros' => $favoritos,
                ];
            }
        }

        // Busca única ordenada para evitar 26 queries N+1 no carregamento da Home
        $todosLivros = Livro::orderBy('dataupload', 'desc')->get();

        foreach (Livro::GENEROS as $genero) {
            $livrosDoGenero = $todosLivros
                ->filter(fn (Livro $livro) => $livro->genero1 === $genero || $livro->genero2 === $genero)
                ->take(10)
                ->values();

            if ($livrosDoGenero->isNotEmpty()) {
                $secoes[] = [
                    'titulo' => $genero,
                    'livros' => $livrosDoGenero,
                ];
            }
        }

        return view('pages.home', compact('secoes'));
    }
}