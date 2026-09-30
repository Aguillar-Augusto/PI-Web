@extends('layouts.app') 

@section('conteudo')

<div class="container mt-4 mb-5">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ url()->previous() }}" class="text-dark me-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
        </a>
        <h2 class="fw-bold mb-0">{{ $genero }}</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="list-group shadow-sm border-0 rounded-3">
                @forelse ($livros as $livro)
                    <a href="{{ route('descricao', ['id' => $livro->id]) }}" class="list-group-item list-group-item-action d-flex align-items-center p-3 border-bottom">
                        @if ($livro->capa_path)
                            <img src="{{ $livro->capa }}" class="rounded shadow-sm" alt="Capa do Livro" style="width: 50px; height: 75px; object-fit: cover;">
                        @else
                            <img src="https://picsum.photos/seed/livro{{ $livro->id }}/50/75" class="rounded shadow-sm" alt="Capa do Livro" style="width: 50px; height: 75px; object-fit: cover;">
                        @endif
                        <div class="ms-3 flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $livro->name }}</h6>
                            <p class="mb-0 text-muted small text-truncate" style="max-width: 250px;">{{ $livro->sinopse }}</p>
                        </div>
                        
                        <div class="text-muted ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        <p class="mb-0">Nenhum livro encontrado nesta categoria ainda.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $livros->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection