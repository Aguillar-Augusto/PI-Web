@extends('layouts.app') 

@section('conteudo')

@php
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
@endphp

<div class="container mt-5 mb-5">

    <a href="{{ route('home') }}" class="text-dark me-3 mb-4 d-inline-block">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
        </svg>
    </a>
    
    <div class="row">
        <div class="col-md-3 text-center mb-4">
            @if ($livro->capa_path)
                <img src="{{ $livro->capa_path }}" alt="Capa" class="img-fluid rounded shadow-sm mb-3" style="width: 300px; height: 450px; object-fit: cover;">
            @else
                <img src="https://picsum.photos/seed/livro{{ $livro->id }}/300/450" alt="Capa Padrão" class="img-fluid rounded shadow-sm mb-3" style="width: 300px; height: 450px; object-fit: cover;">
            @endif

            @if($livro->pdf_path)
                <a href="{{ $livro->pdf_path }}" target="_blank" download="{{ $livro->name }}.pdf" class="btn btn-brand w-100 py-2 fs-5 mb-2">
                    Baixar livro
                </a>
            @else
                <button class="btn btn-secondary w-100 py-2 fs-5 mb-2" disabled>Arquivo indisponível</button>
            @endif

            @auth
                @php
                    // Verifica se o livro atual está dentro da lista de favoritos do usuário
                    $isFavorito = Auth::user()->livrosFavoritos->contains($livro->id);
                @endphp
                
                <form action="{{ route('livros.favoritar', $livro->id) }}" method="POST" class="mb-3">
                    @csrf
                    {{-- A classe muda de btn-danger para btn-outline-danger dependendo do status --}}
                    <button type="submit" class="btn {{ $isFavorito ? 'btn-danger' : 'btn-outline-danger' }} w-100 py-2 fw-bold d-flex align-items-center justify-content-center">
                        {{-- Ícone de Coração --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill me-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                        </svg>
                        {{ $isFavorito ? 'Remover Favorito' : 'Favoritar Obra' }}
                    </button>
                </form>
            @endauth

            @if(Auth::check() && Auth::id() === $livro->users_id)
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-outline-primary w-50 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEditarLivro">Editar</button>
                    
                    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" class="w-50" onsubmit="return confirm('Tem certeza que deseja apagar esta obra permanentemente? Esta ação não pode ser desfeita.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 fw-bold">Apagar</button>
                    </form>
                </div>
            @endif

        </div>
        
        <div class="col-md-8 offset-md-1">
            <h1 class="fw-bold">{{ $livro->name }}</h1>
            <p class="text-muted">Por <strong>{{ $livro->autor }}</strong></p>
            <div class="mb-4">
                <span class="badge bg-success me-1">{{ $livro->genero1 }}</span>
                @if($livro->genero2)
                    <span class="badge bg-success me-1">{{ $livro->genero2 }}</span>
                @endif
            </div>
            <h5>Sinopse</h5>
            <p>{{ $livro->sinopse }}</p>
        </div>
    </div>
</div>

@if(Auth::check() && Auth::id() === $livro->users_id)
<div class="modal fade" id="modalEditarLivro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-brand">Editar Obra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="formEditarLivro" action="{{ route('livros.update', $livro->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Título da Obra</label>

                            <input type="text" name="name" class="form-control" value="{{ $livro->name }}" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Gênero Principal</label>
                            <select class="form-select" name="genero1" required>
                                @foreach ($listaGeneros as $genero)
                                    <option value="{{$genero}}" {{ $livro->genero1 == $genero ? 'selected' : '' }}>{{$genero}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Gênero Secundário</label>
                            <select class="form-select" name="genero2">
                                <option value="">Nenhum (Opcional)</option>
                                @foreach ($listaGeneros as $genero)
                                    <option value="{{$genero}}" {{ $livro->genero2 == $genero ? 'selected' : '' }}>{{$genero}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Sinopse</label>
                        {{-- Preenchendo a textarea colocando a variável DENTRO da tag --}}
                        <textarea class="form-control" name="sinopse" rows="4" required>{{ $livro->sinopse }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Atualizar Capa (Opcional)</label>
                            <input class="form-control" type="file" name="capa" accept="image/*">
                            <div class="form-text">Deixe em branco para manter a capa atual.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Atualizar Arquivo PDF (Opcional)</label>
                            <input class="form-control" type="file" name="pdf" accept="application/pdf">
                            <div class="form-text">Deixe em branco para manter o PDF atual.</div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarLivro" class="btn btn-brand px-4 rounded-pill shadow-sm">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection