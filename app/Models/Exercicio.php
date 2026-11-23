<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Exercicio -> tabela "exercicios" (RF25 a RF28)
 * Exercício de múltipla escolha com 2 a 4 alternativas (a, b, c, d). C e D podem ficar vazias.
 */
class Exercicio extends Model
{
    protected $fillable = [
        'licao_id', 'enunciado', 'alternativa_a', 'alternativa_b', 'alternativa_c', 'alternativa_d',
        'resposta_correta', 'explicacao',
    ];

    public function licao()
    {
        return $this->belongsTo(Licao::class);
    }

    // As alternativas preenchidas como lista ['a' => '...', 'b' => '...', ...], para a view fazer um @foreach.
    public function alternativas()
    {
        $todas = [
            'a' => $this->alternativa_a,
            'b' => $this->alternativa_b,
            'c' => $this->alternativa_c,
            'd' => $this->alternativa_d,
        ];

        $preenchidas = [];
        foreach ($todas as $letra => $texto) {
            if ($texto !== null && $texto !== '') {
                $preenchidas[$letra] = $texto;
            }
        }
        return $preenchidas;
    }

    // Enunciado pronto para a tela (mesma ideia de Licao::teoriaHtml()).
    public function enunciadoHtml()
    {
        if ($this->licao->ehDaComunidade()) {
            return nl2br(e($this->enunciado));
        }
        return $this->enunciado;
    }
}
