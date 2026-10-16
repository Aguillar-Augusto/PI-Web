<?php
// app/Http/Controllers/ListaGeneroController.php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Support\Facades\Auth;

class ListaGeneroController extends Controller
{
    public function show(string $genero)
    {
        $generoNormalizado = mb_strtolower($genero, 'UTF-8');

        if (in_array($generoNormalizado, ['favoritos', 'meus livros'], true) && !Auth::check()) {
            return redirect()->route('login');
        }

        if ($generoNormalizado === 'favoritos') {
            $livros = Auth::user()->livrosFavoritos()->orderBy('dataupload', 'desc')->paginate(30);
        } elseif ($generoNormalizado === 'meus livros') {
            $livros = Auth::user()->livrosCadastrados()->orderBy('dataupload', 'desc')->paginate(30);
        } else {
            $livros = Livro::where(function ($query) use ($genero) {
                    $query->where('genero1', $genero)
                          ->orWhere('genero2', $genero);
                })
                ->orderBy('dataupload', 'desc')
                ->paginate(10);
        }

        return view('pages.lista-estendida', compact('genero', 'livros'));
    }
}