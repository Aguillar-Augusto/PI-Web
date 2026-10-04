@extends('layouts.app') 
@section('conteudo')
    @foreach ($secoes as $secao)
        <div class="container mt-5">
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">{{ $secao['titulo'] }}</h4>
                    <a href="{{ route('lista.genero', ['genero' => $secao['titulo']]) }}" class="text-brand text-decoration-none fw-bold small">Estender lista</a>
                </div>
                <div class="scrolling-wrapper">
                    @foreach ($secao['livros'] as $livro)
                        <a href="{{ route('descricao', ['id' => $livro->id]) }}" class="story-card">
                            
                            @if ($livro->capa_path)
                                <img src="{{ $livro->capa_path }}" class="story-cover" style="width: 150px; height: 220px; object-fit: cover;">
                            @else
                                <img src="https://picsum.photos/seed/livro{{ $livro->id }}/200/300" class="story-cover">
                            @endif
                            
                            <div class="story-title">{{ $livro->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection