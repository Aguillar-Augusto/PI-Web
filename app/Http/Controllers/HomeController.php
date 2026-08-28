<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $secoes = [];

        // 1. Carrega os livros Favoritos (se o usuário estiver logado)
        if (Auth::check()) {
            $favoritos = Auth::user()->livrosFavoritos()->take(10)->get();
            
            if ($favoritos->isNotEmpty()) {
                $secoes[] = [
                    'titulo' => 'Favoritos',
                    'livros' => $favoritos
                ];
            }
        }

        // 2. Mesma lista de gêneros que configuramos no seu app.blade.php
        $listaGeneros = [
            "Ação e Aventura",
            "Biografia",
            "Chick-Lit",
            "Clássicos",
            "Conto",
            "Crônica",
            "Distopia",
            "Drama",
            "Ensaio",
            "Fantasia",
            "Ficção Científica",
            "Ficção Histórica",
            "Ficção Policial",
            "Horror/Terror",
            "Infanto-juvenil",
            "Mistério",
            "Não Ficção",
            "Novela",
            "Poesia",
            "Realismo Mágico",
            "Religião e Espiritualidade",
            "Romance",
            "Suspense/Thriller",
            "Tragédia",
            "Young Adult (YA)"
        ];
        
        // 3. Varre todos os gêneros. Se achar livro, cria a seção.
        foreach ($listaGeneros as $genero) {
            $livrosDoGenero = Livro::where('genero1', $genero)
                                   ->orWhere('genero2', $genero)
                                   ->orderBy('dataupload', 'desc') // Os mais novos primeiro!
                                   ->take(10)
                                   ->get();

            // Se encontrou pelo menos 1 livro neste gênero, a seção vai para a tela
            if ($livrosDoGenero->isNotEmpty()) {
                $secoes[] = [
                    'titulo' => $genero,
                    'livros' => $livrosDoGenero
                ];
            }
        }

        // ATENÇÃO AQUI: Garanta que o nome dentro do view() corresponde à pasta da sua view
        // No seu web.php antigo, você usava 'pages.home'. Se o arquivo home.blade.php 
        // estiver direto na pasta views, use apenas 'home'.
        return view('pages.home', compact('secoes')); 
    }
}