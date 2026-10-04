<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'autor',
        'password',
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