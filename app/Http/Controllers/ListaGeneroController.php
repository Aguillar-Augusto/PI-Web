<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListaGeneroController extends Controller
{
    public function show($genero)
    {
        if (strtolower($genero) === 'favoritos') {
            $livros = Auth::check() ? Auth::user()->livrosFavoritos()->paginate(10) : collect();
        } elseif (strtolower($genero) === 'meus livros') {
            $livros = Auth::check() ? Auth::user()->livrosCadastrados()->paginate(10) : collect();
        } else {
            $livros = Livro::where('genero1', $genero)
                           ->orWhere('genero2', $genero)
                           ->paginate(10);
        }

        return view('pages.lista-estendida', compact('genero', 'livros'));
    }
}