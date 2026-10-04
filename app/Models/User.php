<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'datacadastro',
        'dataedicao',
        'foto_perfil_path',
        'bio',
    ];

    public function livrosCadastrados(): HasMany
    {
        return $this->hasMany(Livro::class, 'users_id');
    }

    public function livrosFavoritos(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'favoritos', 'users_id', 'livros_id');
    }
}