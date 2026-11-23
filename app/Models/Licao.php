<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Licao -> tabela "licoes" (RF24)
 *   uma lição PERTENCE A um módulo
 *   uma lição TEM MUITOS exercícios
 */
class Licao extends Model
{
    // Sem isso o Laravel procuraria a tabela "licaos".
    protected $table = 'licoes';

    protected $fillable = ['modulo_id', 'titulo', 'teoria', 'pratica', 'ordem'];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function exercicios()
    {
        return $this->hasMany(Exercicio::class);
    }

    public function ehDaComunidade()
    {
        return $this->modulo->curso->ehDaComunidade();
    }

    /*
     * Teoria pronta para mostrar na tela.
     *   - Cursos oficiais: o texto JÁ é HTML (escrito pela equipe) e vai direto.
     *   - Cursos da comunidade: o professor digitou texto simples. e() escapa o HTML
     *     (assim ninguém consegue colocar um <script>) e nl2br() transforma as quebras de linha em <br>.
     * A view mostra com {!! $licao->teoriaHtml() !!}.
     */
    public function teoriaHtml()
    {
        if ($this->ehDaComunidade()) {
            return nl2br(e($this->teoria));
        }
        return $this->teoria;
    }

    // Prática: nos cursos da comunidade vira um bloco de código (<pre>), também escapado.
    public function praticaHtml()
    {
        if ($this->ehDaComunidade()) {
            return '<pre><code>' . e($this->pratica) . '</code></pre>';
        }
        return $this->pratica;
    }
}
