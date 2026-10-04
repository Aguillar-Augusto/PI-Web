<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LivroController extends Controller
{
    public function show($id)
    {
        $livro = Livro::findOrFail($id);

        return view('pages.descricao', compact('livro'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genero1' => 'required|string',
            'genero2' => 'nullable|string',
            'sinopse' => 'required|string',
            'capa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pdf' => 'required|mimes:pdf|max:10000',
        ]);

        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
        $caminhoCapa = null;

        if ($request->hasFile('capa')) {
            $uploadCapa = $cloudinary->uploadApi()->upload($request->file('capa')->getRealPath(), [
                'folder' => 'livros/capas'
            ]);
            $caminhoCapa = $uploadCapa['secure_url'];
        }

        $uploadPdf = $cloudinary->uploadApi()->upload($request->file('pdf')->getRealPath(), [
            'folder' => 'livros/pdfs',
            'resource_type' => 'auto'
        ]);
        $urlOriginal = $uploadPdf['secure_url'];
        $nomeDoArquivo = Str::slug($validated['name']);
        $caminhoPdf = str_replace('/upload/', '/upload/fl_attachment:' . $nomeDoArquivo . '/', $urlOriginal);
        

        Livro::create([
            'name' => $validated['name'],
            'autor' => Auth::user()->name,
            'genero1' => $validated['genero1'],
            'genero2' => $validated['genero2'] ?? '',
            'sinopse' => $validated['sinopse'],
            'capa_path' => $caminhoCapa,
            'pdf_path' => $caminhoPdf, 
            'dataupload' => now(),
            'users_id' => Auth::id(),
        ]);

        return back()->with('sucesso', 'Obra publicada com sucesso!');
    }

    public function pesquisar(Request $request)
    {
        $termo = $request->input('busca');

        if (!$termo) {
            return redirect()->route('home');
        }

        $livros = Livro::where('name', 'LIKE', '%' . $termo . '%')
                    ->orWhere('autor', 'LIKE', '%' . $termo . '%')
                    ->orWhere('genero1', 'LIKE', '%' . $termo . '%')
                    ->orWhere('genero2', 'LIKE', '%' . $termo . '%')
                    ->paginate(10);

        return view('pages.lista-estendida', [
            'genero' => 'Resultados para: ' . $termo,
            'livros' => $livros
        ]);
    }

    public function update(Request $request, $id)
    {
        $livro = Livro::findOrFail($id);

        if ($livro->users_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar este livro.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genero1' => 'required|string',
            'genero2' => 'nullable|string',
            'sinopse' => 'required|string',
            'capa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10000',
        ]);

        $livro->name = $validated['name'];
        $livro->genero1 = $validated['genero1'];
        $livro->genero2 = $validated['genero2'] ?? '';
        $livro->sinopse = $validated['sinopse'];
        $livro->dataatualizacao = now();

        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        if ($request->hasFile('capa')) {
            $uploadCapa = $cloudinary->uploadApi()->upload($request->file('capa')->getRealPath(), [
                'folder' => 'livros/capas'
            ]);
            $livro->capa_path = $uploadCapa['secure_url'];
        }

        if ($request->hasFile('pdf')) {
            $uploadPdf = $cloudinary->uploadApi()->upload($request->file('pdf')->getRealPath(), [
                'folder' => 'livros/pdfs',
                'resource_type' => 'auto'
            ]);
            $urlOriginal = $uploadPdf['secure_url'];
            $nomeDoArquivo = Str::slug($validated['name']);
            $caminhoPdf = str_replace('/upload/', '/upload/fl_attachment:' . $nomeDoArquivo . '/', $urlOriginal);
            $livro->pdf_path = $caminhoPdf;
        }

        $livro->save();

        return back()->with('sucesso', 'Livro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $livro = Livro::findOrFail($id);

        if ($livro->users_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para apagar este livro.');
        }

        $livro->delete();

        return redirect()->route('home')->with('sucesso', 'Obra deletada permanentemente.');
    }

    public function favoritar($id)
    {
        $livro = \App\Models\Livro::findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();

        $user->livrosFavoritos()->toggle($livro->id);

        return back()->with('sucesso');
    }
}