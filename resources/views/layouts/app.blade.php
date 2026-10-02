@php
    // Lista de gêneros declarada dinamicamente para substituir o include estático
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papiro Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand text-brand fs-3" href="{{route('home')}}"><strong>Papiro Digital</strong></a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <form action="{{ route('pesquisa') }}" method="GET" class="d-flex mx-auto w-50">
                    <input name="busca" class="form-control rounded-pill bg-light border-0" type="search" placeholder="Pesquisar no Papiro Digital...">
                </form>
                
                <ul class="navbar-nav ms-auto align-items-center">
                
                @auth
                    <li class="nav-item"><a class="nav-link fw-bold" href="{{route('lista.genero', ['genero' => 'Meus livros'])}}">Meus Livros</a></li>
                    
                    <li class="nav-item">
                        <button class="btn btn-outline-success rounded-pill ms-2 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalSubirLivro">
                            Subir livro
                        </button>
                    </li>
                @endauth

                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="perfilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-circle text-brand" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="perfilDropdown">
                            @guest <li><a class="dropdown-item" href="{{route('login')}}">Entrar</a></li> @endguest
                            
                            @auth
                                <li><a class="dropdown-item" href="{{route('perfil')}}">Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sair
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alertas de Feedback -->
    <div class="container mt-3">
        @if (session('sucesso'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('sucesso') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('erro'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('erro') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    

    <main>
        @yield('conteudo')
    </main>

<div class="modal fade" id="modalSubirLivro" tabindex="-1" aria-labelledby="modalSubirLivroLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-brand" id="modalSubirLivroLabel">Cadastrar Novo Livro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Adicionado Action, Method, Enctype e Token CSRF --}}
                    <form id="formCadastroLivro" action="{{ route('livros.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="titulo" class="form-label small fw-bold">Título da Obra</label>
                                {{-- Adicionado name="name" --}}
                                <input type="text" name="name" class="form-control" id="titulo" placeholder="Ex: O Segredo do Papiro" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="generoPrincipal" class="form-label small fw-bold">Gênero Principal</label>
                                {{-- Adicionado name="genero1" --}}
                                <select class="form-select" name="genero1" id="generoPrincipal" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    @foreach ($listaGeneros as $genero)
                                    <option value="{{$genero}}">{{$genero}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="generoSecundario" class="form-label small fw-bold">Gênero Secundário</label>
                                {{-- Adicionado name="genero2" --}}
                                <select class="form-select" name="genero2" id="generoSecundario">
                                    <option value="" selected>Nenhum (Opcional)</option>
                                    @foreach ($listaGeneros as $genero)
                                    <option value="{{$genero}}">{{$genero}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sinopse" class="form-label small fw-bold">Sinopse</label>
                            {{-- Adicionado name="sinopse" --}}
                            <textarea class="form-control" name="sinopse" id="sinopse" rows="4" placeholder="Conte um pouco sobre a história..." required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capa" class="form-label small fw-bold">Capa do Livro (Imagem)</label>
                                {{-- Adicionado name="capa" --}}
                                <input class="form-control" type="file" name="capa" id="capa" accept="image/*">
                                <div class="form-text">Formatos sugeridos: JPG ou PNG.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pdf" class="form-label small fw-bold">Arquivo da Obra (PDF)</label>
                                {{-- Adicionado name="pdf" --}}
                                <input class="form-control border-success" type="file" name="pdf" id="pdf" accept="application/pdf" required>
                                <div class="form-text text-success">O arquivo deve estar no formato PDF.</div>
                            </div>
                        </div>

                        <div class="mb-0 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="termos" required>
                                <label class="form-check-label small text-muted" for="termos">
                                    Declaro que possuo os direitos autorais desta obra.
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    {{-- Type alterado para submit e onclick removido conforme instrução --}}
                    <button type="submit" form="formCadastroLivro" class="btn btn-brand px-4 rounded-pill shadow-sm">Publicar Obra</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>