<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::orderBy('dataupload', 'desc')->get();
        
        return response()->json($livros);
    }

    public function favoritar(Request $request, $id)
    {
        $user = $request->user();
        
        $user->livrosFavoritos()->toggle($id);

        return response()->json(['message' => 'Status de favorito atualizado com sucesso!']);
    }

    public function favoritos(Request $request)
    {
        $livros = $request->user()->livrosFavoritos()->orderBy('dataupload', 'desc')->get();
        return response()->json($livros);
    }

    public function meusLivros(Request $request)
    {
        $livros = $request->user()->livrosCadastrados()->orderBy('dataupload', 'desc')->get();
        return response()->json($livros);
    }
}