<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Progresso -> tabela "progressos" (RF16 e RF29)
 * Guarda o resultado de um usuário numa lição.
 */
class Progresso extends Model
{
    protected $fillable = ['user_id', 'licao_id', 'acertos', 'total', 'aprovado', 'concluido_em'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function licao()
    {
        return $this->belongsTo(Licao::class);
    }
}
