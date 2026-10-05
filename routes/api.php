<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LivroController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/livros', [LivroController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/livros/{id}/favoritar', [LivroController::class, 'favoritar']);
    Route::get('/user/favoritos', [LivroController::class, 'favoritos']);
    Route::get('/user/meus-livros', [LivroController::class, 'meusLivros']);
    Route::get('/livros/{id}/check-favorito', [LivroController::class, 'checkFavorito']);

    Route::post('/logout', [AuthController::class, 'logout']);

});