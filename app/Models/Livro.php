<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    public $timestamps = false;

    public const GENEROS = [
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
        "Filosofia",
        "Romance",
        "Suspense/Thriller",
        "Tragédia",
        "Young Adult (YA)",
    ];

    protected $fillable = [
        'name',
        'autor',
        'genero1',
        'genero2',
        'capa_path',
        'pdf_path',
        'sinopse',
        'dataupload',
        'users_id',
        'dataatualizacao',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function favoritadoPor(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos', 'livros_id', 'users_id');
    }
}