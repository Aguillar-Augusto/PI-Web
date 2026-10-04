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

        if (Auth::check()) {
            $favoritos = Auth::user()->livrosFavoritos()->take(10)->get();
            
            if ($favoritos->isNotEmpty()) {
                $secoes[] = [
                    'titulo' => 'Favoritos',
                    'livros' => $favoritos
                ];
            }
        }

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
        
        foreach ($listaGeneros as $genero) {
            $livrosDoGenero = Livro::where('genero1', $genero)
                                   ->orWhere('genero2', $genero)
                                   ->orderBy('dataupload', 'desc')
                                   ->take(10)
                                   ->get();

            if ($livrosDoGenero->isNotEmpty()) {
                $secoes[] = [
                    'titulo' => $genero,
                    'livros' => $livrosDoGenero
                ];
            }
        }

        return view('pages.home', compact('secoes')); 
    }
}