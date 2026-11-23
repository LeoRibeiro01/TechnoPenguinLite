<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DO PAINEL E DA COMUNIDADE
 *   index()      -> página inicial de quem está logado:
 *                   RF16 (progresso), RF18 (pontos), RF19 (sequência), RF20 (meta), RF22 (cursos oficiais)
 *   comunidade() -> aba Comunidade: cursos criados por professores e já publicados
 */
class PainelController extends Controller
{
    // GET /painel
    public function index()
    {
        $user = Auth::user();

        // Só os cursos oficiais (os da comunidade ficam na aba Comunidade).
        // withCount('licoes') cria o campo licoes_count (total de lições de cada curso).
        $cursos = Curso::whereNull('professor_id')->withCount('licoes')->orderBy('titulo')->get();
        $this->calcularProgresso($cursos);

        return view('painel', [
            'user'            => $user,
            'cursos'          => $cursos,
            // O curso da linguagem escolhida no perfil (RF10), mostrado em destaque.
            'meuCurso'        => $cursos->firstWhere('linguagem_id', $user->linguagem_id),
            'feitasHoje'      => $user->licoesHoje(),
            'totalConquistas' => $user->conquistas()->count(),
        ]);
    }

    // GET /comunidade
    public function comunidade()
    {
        // with('professor') já traz o professor de cada curso junto (evita uma consulta por curso).
        $cursos = Curso::whereNotNull('professor_id')
            ->where('publicado', true)
            ->with('professor')
            ->withCount('licoes')
            ->orderBy('titulo')
            ->get();
        $this->calcularProgresso($cursos);

        return view('comunidade', ['cursos' => $cursos, 'user' => Auth::user()]);
    }

    /*
     * Para cada curso, conta quantas lições o usuário já fez e a porcentagem (RF16).
     * "private" = só este controller usa.
     */
    private function calcularProgresso($cursos)
    {
        // Ids das lições em que o usuário já foi aprovado.
        $aprovadas = Auth::user()->progressos()->where('aprovado', true)->pluck('licao_id');

        foreach ($cursos as $curso) {
            $curso->feitas = $curso->licoes()->whereIn('licoes.id', $aprovadas)->count();
            // Curso sem lições daria divisão por zero, por isso o "if".
            if ($curso->licoes_count > 0) {
                $curso->porcentagem = round($curso->feitas / $curso->licoes_count * 100);
            } else {
                $curso->porcentagem = 0;
            }
        }
    }
}
