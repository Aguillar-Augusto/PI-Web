<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $secoes = [];

        $meusLivros = $user->livrosCadastrados()->orderBy('dataupload', 'desc')->take(10)->get();
        if ($meusLivros->isNotEmpty()) {
            $secoes[] = [
                'titulo' => 'Meus Livros',
                'livros' => $meusLivros,
            ];
        }

        $favoritos = $user->livrosFavoritos()->orderBy('dataupload', 'desc')->take(10)->get();
        if ($favoritos->isNotEmpty()) {
            $secoes[] = [
                'titulo' => 'Favoritos',
                'livros' => $favoritos,
            ];
        }

        return view('pages.perfil', compact('user', 'secoes'));
    }

    public function atualizarFoto(Request $request, CloudinaryService $cloudinaryService)
    {
        $request->validate([
            'foto_perfil' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = Auth::user();

        try {
            $user->foto_perfil_path = $cloudinaryService->uploadImagem(
                $request->file('foto_perfil'),
                'usuarios/fotos'
            );
            $user->dataedicao = now();
            $user->save();

            return back()->with('sucesso', 'Foto de perfil atualizada com sucesso!');
        } catch (Throwable $e) {
            report($e);
            return back()->with('erro', 'Não foi possível enviar a foto no momento. Tente novamente.');
        }
    }

    public function atualizarBio(Request $request)
    {
        $validated = $request->validate([
            'bio' => ['required', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $user->bio = $validated['bio'];
        $user->dataedicao = now();
        $user->save();

        return back()->with('sucesso', 'Bio atualizada com sucesso!');
    }
}