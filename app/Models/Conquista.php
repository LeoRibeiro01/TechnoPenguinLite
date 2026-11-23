<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Conquista -> tabela "conquistas" (RF17)
 * As conquistas e medalhas que existem. Quem ganhou cada uma fica na tabela pivô "conquista_user".
 */
class Conquista extends Model
{
    protected $fillable = ['nome', 'descricao', 'icone', 'tipo', 'meta'];

    // Relação N:N: os usuários que já ganharam esta conquista.
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
