<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Throwable;

class LivroController extends Controller
{
    public function show($id)
    {
        $livro = Livro::findOrFail($id);

        $isFavorito = Auth::check()
            ? Auth::user()->livrosFavoritos()->whereKey($livro->id)->exists()
            : false;

        return view('pages.descricao', compact('livro', 'isFavorito'));
    }

    public function store(Request $request, CloudinaryService $cloudinaryService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'genero1' => ['required', 'string', Rule::in(Livro::GENEROS)],
            'genero2' => ['nullable', 'string', Rule::in(Livro::GENEROS)],
            'sinopse' => ['required', 'string'],
            'capa' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        try {
            $caminhoCapa = $request->hasFile('capa')
                ? $cloudinaryService->uploadImagem($request->file('capa'), 'livros/capas')
                : null;

            $caminhoPdf = $cloudinaryService->uploadPdf($request->file('pdf'), $validated['name']);

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
        } catch (Throwable $e) {
            report($e);
            return back()->with('erro', 'Falha ao enviar os arquivos da obra. Tente novamente.');
        }
    }

    public function pesquisar(Request $request)
    {
        $termo = trim((string) $request->input('busca', ''));

        if ($termo === '') {
            return redirect()->route('home');
        }

        $livros = Livro::where(function ($query) use ($termo) {
                $query->where('name', 'LIKE', '%' . $termo . '%')
                      ->orWhere('autor', 'LIKE', '%' . $termo . '%')
                      ->orWhere('genero1', 'LIKE', '%' . $termo . '%')
                      ->orWhere('genero2', 'LIKE', '%' . $termo . '%');
            })
            ->orderBy('dataupload', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.lista-estendida', [
            'genero' => 'Resultados para: ' . $termo,
            'livros' => $livros,
        ]);
    }

    public function update(Request $request, $id, CloudinaryService $cloudinaryService)
    {
        $livro = Livro::findOrFail($id);

        if ($livro->users_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar este livro.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'genero1' => ['required', 'string', Rule::in(Livro::GENEROS)],
            'genero2' => ['nullable', 'string', Rule::in(Livro::GENEROS)],
            'sinopse' => ['required', 'string'],
            'capa' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        try {
            if ($request->hasFile('capa')) {
                $livro->capa_path = $cloudinaryService->uploadImagem($request->file('capa'), 'livros/capas');
            }

            if ($request->hasFile('pdf')) {
                $livro->pdf_path = $cloudinaryService->uploadPdf($request->file('pdf'), $validated['name']);
            }

            $livro->name = $validated['name'];
            $livro->genero1 = $validated['genero1'];
            $livro->genero2 = $validated['genero2'] ?? '';
            $livro->sinopse = $validated['sinopse'];
            $livro->dataatualizacao = now();
            $livro->save();

            return back()->with('sucesso', 'Livro atualizado com sucesso!');
        } catch (Throwable $e) {
            report($e);
            return back()->with('erro', 'Erro ao atualizar os arquivos do livro. Tente novamente.');
        }
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
        $livro = Livro::findOrFail($id);
        Auth::user()->livrosFavoritos()->toggle($livro->id);

        return back()->with('sucesso', 'Lista de favoritos atualizada!');
    }
}