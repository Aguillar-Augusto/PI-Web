<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    // Como você utiliza campos customizados para data (dataupload, dataatualizacao), vamos desativar os timestamps padrão do Laravel[cite: 2]
    public $timestamps = false;

    protected $fillable = [
        'name',
        'autor',
        'password',
        'genero1',
        'genero2',
        'capa_path', // NOVA COLUNA
        'pdf_path',  // NOVA COLUNA
        'sinopse',
        'dataupload',
        'users_id',
        'dataatualizacao',
    ];

    // Relacionamento: O livro pertence a um usuário que o cadastrou[cite: 2]
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Relacionamento: Usuários que favoritaram este livro[cite: 3]
    public function favoritadoPor(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos', 'livros_id', 'users_id');
    }
}