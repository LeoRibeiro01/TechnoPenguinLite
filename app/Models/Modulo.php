<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Modulo -> tabela "modulos" (RF23)
 *   um módulo PERTENCE A um curso
 *   um módulo TEM MUITAS lições
 */
class Modulo extends Model
{
    protected $fillable = [
        'curso_id', 'titulo', 'descricao', 'dificuldade', 'ordem', 'projeto', 'aprofundamento', 'referencias',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function licoes()
    {
        return $this->hasMany(Licao::class)->orderBy('ordem');
    }
}
