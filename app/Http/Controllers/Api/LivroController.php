<?php
// app/Http/Controllers/Api/LivroController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $query = Livro::query()->orderBy('dataupload', 'desc');

        if ($request->filled('genero')) {
            $genero = $request->query('genero');
            $query->where(function ($q) use ($genero) {
                $q->where('genero1', $genero)
                  ->orWhere('genero2', $genero);
            });
        }

        if ($request->filled('busca')) {
            $termo = $request->query('busca');
            $query->where(function ($q) use ($termo) {
                $q->where('name', 'LIKE', '%' . $termo . '%')
                  ->orWhere('autor', 'LIKE', '%' . $termo . '%')
                  ->orWhere('genero1', 'LIKE', '%' . $termo . '%')
                  ->orWhere('genero2', 'LIKE', '%' . $termo . '%');
            });
        }

        $user = $request->user('sanctum');
        $favoritosIds = $user
            ? $user->livrosFavoritos()->pluck('livros.id')->flip()
            : collect();

        $anexarFavorito = function (Livro $livro) use ($favoritosIds) {
            $livro->setAttribute('favorito', $favoritosIds->has($livro->id));
            return $livro;
        };

        if ($request->filled('per_page')) {
            $perPage = min(max((int) $request->query('per_page', 20), 1), 100);
            $paginado = $query->paginate($perPage);
            $paginado->getCollection()->transform($anexarFavorito);
            return response()->json($paginado);
        }

        $livros = $query->get()->map($anexarFavorito);

        return response()->json($livros);
    }

    public function favoritar(Request $request, $id)
    {
        $livro = Livro::findOrFail($id);
        $user = $request->user();

        $resultado = $user->livrosFavoritos()->toggle($livro->id);
        $isFavorito = count($resultado['attached']) > 0;

        return response()->json([
            'message' => 'Status de favorito atualizado com sucesso!',
            'favorito' => $isFavorito,
        ]);
    }

    public function favoritos(Request $request)
    {
        $livros = $request->user()
            ->livrosFavoritos()
            ->orderBy('dataupload', 'desc')
            ->get()
            ->each(fn (Livro $livro) => $livro->setAttribute('favorito', true));

        return response()->json($livros);
    }

    public function meusLivros(Request $request)
    {
        $user = $request->user();
        $favoritosIds = $user->livrosFavoritos()->pluck('livros.id')->flip();

        $livros = $user->livrosCadastrados()
            ->orderBy('dataupload', 'desc')
            ->get()
            ->each(fn (Livro $livro) => $livro->setAttribute('favorito', $favoritosIds->has($livro->id)));

        return response()->json($livros);
    }

    public function checkFavorito(Request $request, $id)
    {
        $livro = Livro::findOrFail($id);
        $isFavorito = $request->user()->livrosFavoritos()->whereKey($livro->id)->exists();

        return response()->json(['favorito' => $isFavorito]);
    }
}