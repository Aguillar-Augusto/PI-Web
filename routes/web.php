<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ListaGeneroController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CadastroController;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/pesquisa', [LivroController::class, 'pesquisar'])->name('pesquisa');
Route::get('/descricao/{id}', [LivroController::class, 'show'])->name('descricao');
Route::get('/lista-estendida/{genero}', [ListaGeneroController::class, 'show'])->name('lista.genero');



Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::get('/cadastro', [CadastroController::class, 'create'])->name('cadastro');
    Route::post('/cadastro', [CadastroController::class, 'store'])->name('register.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
    Route::put('/livros/{id}', [LivroController::class, 'update'])->name('livros.update');
    Route::delete('/livros/{id}', [LivroController::class, 'destroy'])->name('livros.destroy');
    Route::post('/livros/{id}/favoritar', [LivroController::class, 'favoritar'])->name('livros.favoritar');
    Route::put('/perfil/foto', [PerfilController::class, 'atualizarFoto'])->name('perfil.atualizarFoto');
    Route::put('/perfil/bio', [PerfilController::class, 'atualizarBio'])->name('perfil.atualizarBio');
});

Route::get('/criar-link', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Link criado com sucesso!';
});