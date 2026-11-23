<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Exercicio;
use App\Models\Licao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * ÁREA DO PROFESSOR - EXERCÍCIOS DE UMA LIÇÃO
 * O formulário de exercício novo e a lista com o botão "Excluir" ficam na tela de editar a lição.
 *
 *   store   POST   /professor/licoes/{licao}/exercicios  cria um exercício
 *   destroy DELETE /professor/exercicios/{exercicio}     exclui um exercício
 */
class ExercicioController extends Controller
{
    public function store(Request $request, Licao $licao)
    {
        $this->verificarPermissao($licao->modulo->curso);

        $dados = $request->validate([
            'enunciado'        => 'required',
            'alternativa_a'    => 'required|max:255',
            'alternativa_b'    => 'required|max:255',
            'alternativa_c'    => 'nullable|max:255',   // C e D são opcionais
            'alternativa_d'    => 'nullable|max:255',
            'resposta_correta' => 'required|in:a,b,c,d',
            'explicacao'       => 'required',
        ]);

        // A resposta certa precisa ser uma alternativa preenchida (não dá para marcar "d" com a D vazia).
        if (empty($dados['alternativa_' . $dados['resposta_correta']])) {
            return back()->withErrors(['resposta_correta' => 'A resposta correta precisa ser uma alternativa preenchida.'])->withInput();
        }

        $licao->exercicios()->create($dados);

        return redirect()->route('professor.licoes.edit', $licao)->with('aviso', 'Exercício adicionado!');
    }

    public function destroy(Exercicio $exercicio)
    {
        $licao = $exercicio->licao;
        $this->verificarPermissao($licao->modulo->curso);

        $exercicio->delete();

        return redirect()->route('professor.licoes.edit', $licao)->with('aviso', 'Exercício excluído.');
    }

    private function verificarPermissao(Curso $curso)
    {
        if (!Auth::user()->podeEditar($curso)) {
            abort(403);
        }
    }
}
