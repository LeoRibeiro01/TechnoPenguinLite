<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Linguagem -> tabela "linguagens" (RF10)
 *
 * Model = classe que representa uma tabela. Com ela não precisamos escrever SQL:
 *   Linguagem::all()          -> SELECT * FROM linguagens
 *   Linguagem::find(3)        -> SELECT * FROM linguagens WHERE id = 3
 *   Linguagem::orderBy('nome')->get()
 */
class Linguagem extends Model
{
    // O Laravel acharia que a tabela se chama "linguagems" (plural em inglês), então avisamos o nome certo.
    protected $table = 'linguagens';

    // Colunas que podem ser preenchidas com Linguagem::create([...]).
    protected $fillable = ['nome', 'sigla', 'cor'];
}
