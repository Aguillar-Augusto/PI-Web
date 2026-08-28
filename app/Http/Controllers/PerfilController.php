<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    // Método para exibir o perfil do usuário logado
    public function index()
    {
        // Pega o usuário logado
        $user = Auth::user();

        // Se o usuário não estiver logado, redireciona para login
        if (!$user) {
            return redirect()->route('login');
        }

        $secoes = [];

        // 1. Carrega os "Meus Livros" (Livros que o usuário cadastrou) usando o relacionamento[cite: 2]
        $meusLivros = $user->livrosCadastrados()->take(10)->get();
        if ($meusLivros->isNotEmpty()) {
            $secoes[] = [
                'titulo' => 'Meus Livros',
                'livros' => $meusLivros
            ];
        }

        // 2. Carrega os "Favoritos" do usuário logado[cite: 3]
        $favoritos = $user->livrosFavoritos()->take(10)->get();
        if ($favoritos->isNotEmpty()) {
            $secoes[] = [
                'titulo' => 'Favoritos',
                'livros' => $favoritos
            ];
        }

        // Retorna a view de perfil passando o usuário e as seções
        return view('pages.perfil', compact('user', 'secoes'));
    }

    public function atualizarFoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto_perfil')) {
            
            if ($user->foto_perfil_path) {
                Storage::disk('public')->delete($user->foto_perfil_path);
            }

            $caminhoFoto = $request->file('foto_perfil')->store('usuarios/fotos', 'public');

            $user->foto_perfil_path = $caminhoFoto;
            $user->dataedicao = now(); 
            $user->save();
        }

        return back()->with('sucesso');
    }

    public function atualizarBio(Request $request){
        $request->validate(['bio' => 'required|string']);
        $user = \Illuminate\Support\Facades\Auth::user();
        $user->bio = $request->bio;
        $user->save();
        return back()->with('sucesso', 'Bio atualizada com sucesso!');
    }

}