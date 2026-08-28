@extends('layouts.app') 

@section('conteudo')

    <div class="bg-light border-bottom pt-5 pb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    {{-- O botão da foto que criamos anteriormente continua aqui --}}
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalFotoPerfil">
                        @if ($user->foto_perfil_path)
                            <img src="{{ asset('storage/' . $user->foto_perfil_path) }}" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <img src="https://picsum.photos/seed/user{{ $user->id }}/120/120" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                    </a>
                </div>
                <div class="col-md-10 mt-3 mt-md-0">
                    <h2 class="fw-bold mb-0">{{ $user->name }}</h2>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    
                    {{-- A BIO COM O ÍCONE DE LÁPIS --}}
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <p class="mb-0 me-2">{{ $user->bio ?? 'Escrevendo no Papiro Digital!' }}</p>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalBio" class="text-brand">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- CORREÇÃO DAS CAPAS NOS LIVROS DO PERFIL --}}
    @foreach ($secoes as $secao)
        <div class="container mt-5">
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">{{ $secao['titulo'] }}</h4>
                </div>
                <div class="scrolling-wrapper" style="cursor: grab;">
                    @forelse ($secao['livros'] as $livro)
                        <a href="{{ route('descricao', ['id' => $livro->id]) }}" class="story-card">
                            {{-- Lógica da capa aplicada aqui --}}
                            @if ($livro->capa_path)
                                <img src="{{ asset('storage/' . $livro->capa_path) }}" class="story-cover" style="width: 150px; height: 220px; object-fit: cover;">
                            @else
                                <img src="https://picsum.photos/seed/livro{{ $livro->id }}/200/300" class="story-cover" style="width: 150px; height: 220px; object-fit: cover;">
                            @endif
                            <div class="story-title">{{ $livro->name }}</div>
                        </a>
                    @empty
                        <p class="text-muted small">Nenhum livro nesta seção.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL PARA EDITAR A BIO --}}
    <div class="modal fade" id="modalBio" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand">Editar Biografia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formBio" action="{{ route('perfil.atualizarBio') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Conte um pouco sobre você</label>
                            <textarea class="form-control" name="bio" rows="3" required>{{ $user->bio }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-link text-muted fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formBio" class="btn btn-brand px-4 rounded-pill shadow-sm">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFotoPerfil" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand">Atualizar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Formulário aponta para uma nova rota e envia arquivos --}}
                    <form id="formFotoPerfil" action="{{ route('perfil.atualizarFoto') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Escolha uma nova imagem</label>
                            <input class="form-control" type="file" name="foto_perfil" accept="image/*" required>
                            <div class="form-text">Formatos recomendados: JPG ou PNG. Máximo de 2MB.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formFotoPerfil" class="btn btn-brand px-4 rounded-pill shadow-sm">Salvar Foto</button>
                </div>
            </div>
        </div>

@endsection