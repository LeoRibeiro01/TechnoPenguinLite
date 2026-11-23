<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * MODEL Curso -> tabela "cursos" (RF22)
 * Relacionamentos:
 *   um curso PERTENCE A uma linguagem (belongsTo)
 *   um curso da comunidade PERTENCE A um professor (belongsTo User)
 *   um curso TEM MUITOS módulos (hasMany)
 *   um curso TEM MUITAS lições ATRAVÉS dos módulos (hasManyThrough)
 */
class Curso extends Model
{
    protected $fillable = [
        'linguagem_id', 'professor_id', 'publicado',
        'titulo', 'tipo', 'sigla', 'cor', 'descricao', 'historia', 'hoje', 'referencias',
    ];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    public function linguagem()
    {
        return $this->belongsTo(Linguagem::class);
    }

    // Como a coluna se chama professor_id (e não user_id), avisamos o nome dela.
    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    // Curso criado por um professor (e não um dos cursos oficiais do seeder)?
    public function ehDaComunidade()
    {
        return $this->professor_id !== null;
    }

    /*
     * O usuário pode ver este curso?
     *   - cursos oficiais e cursos publicados: todo mundo;
     *   - rascunho (não publicado): só o professor dono e os admins.
     */
    public function visivelPara(User $user)
    {
        return $this->publicado || $user->podeEditar($this);
    }

    // $curso->modulos  -> os módulos do curso, já na ordem certa
    public function modulos()
    {
        return $this->hasMany(Modulo::class)->orderBy('ordem');
    }

    // $curso->licoes  -> todas as lições do curso (passando pela tabela modulos)
    public function licoes()
    {
        return $this->hasManyThrough(Licao::class, Modulo::class);
    }

    /*
     * Todas as lições do curso, NA ORDEM (módulo 1, lição 1, 2, 3; módulo 2...),
     * e para cada uma diz se está aprovada e se está liberada para o usuário (RF29):
     *   - a primeira lição sempre está liberada;
     *   - as outras só depois que a anterior foi aprovada.
     * Usada no CursoController (lista de lições) e no LicaoController (anterior/próxima).
     */
    public function licoesDoUsuario(User $user)
    {
        // Ids das lições em que o usuário já foi aprovado.
        $aprovadas = $user->progressos()->where('aprovado', true)->pluck('licao_id')->all();

        $licoes = Licao::join('modulos', 'modulos.id', '=', 'licoes.modulo_id')
            ->where('modulos.curso_id', $this->id)
            ->orderBy('modulos.ordem')
            ->orderBy('licoes.ordem')
            ->select('licoes.*')
            ->get();

        $anteriorAprovada = true;
        foreach ($licoes as $licao) {
            $licao->aprovada = in_array($licao->id, $aprovadas);
            $licao->liberada = $anteriorAprovada;
            $anteriorAprovada = $licao->aprovada;
        }
        return $licoes;
    }
}
