<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $secoes = [];

        $meusLivros = $user->livrosCadastrados()->take(10)->get();
        if ($meusLivros->isNotEmpty()) {
            $secoes[] = [
                'titulo' => 'Meus Livros',
                'livros' => $meusLivros
            ];
        }

        $favoritos = $user->livrosFavoritos()->take(10)->get();
        if ($favoritos->isNotEmpty()) {
            $secoes[] = [
                'titulo' => 'Favoritos',
                'livros' => $favoritos
            ];
        }

        return view('pages.perfil', compact('user', 'secoes'));
    }

    public function atualizarFoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto_perfil')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            
            $uploadFoto = $cloudinary->uploadApi()->upload($request->file('foto_perfil')->getRealPath(), [
                'folder' => 'usuarios/fotos'
            ]);

            $user->foto_perfil_path = $uploadFoto['secure_url'];
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