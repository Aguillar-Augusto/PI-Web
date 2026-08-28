<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papiro Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-brand fw-bold">Papiro Digital</h2>
                        </div>
                        <form action="{{ route('login.authenticate') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">E-mail</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Senha</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-brand w-100 py-2">Entrar</button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="small">Não tem conta? <a href="{{ route('cadastro') }}" class="text-brand fw-bold">Cadastre-se</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>