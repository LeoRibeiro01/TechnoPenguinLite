<?php

namespace App\Http\Controllers;

use App\Models\Licao;
use App\Models\Progresso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DE LIÇÃO - a parte principal do aprendizado
 *   RF24 -> mostra a lição (teoria + prática)
 *   RF25 -> o usuário responde os exercícios
 *   RF26 -> o sistema corrige automaticamente
 *   RF27 -> diz se cada resposta está certa
 *   RF28 -> mostra a resposta certa (e a explicação) quando o usuário erra
 *   RF29 -> só libera a próxima lição se acertar pelo menos 60%
 *   RF17, RF18, RF19 -> dá pontos, atualiza a sequência e verifica conquistas
 */
class LicaoController extends Controller
{
    // GET /licoes/{licao} -> mostra a lição com os exercícios
    public function show(Licao $licao)
    {
        if (!$licao->modulo->curso->visivelPara(Auth::user())) {
            abort(404);
        }

        $posicao = $this->posicaoNoCurso($licao);

        // RF29: lição bloqueada -> volta para o curso.
        if (!$posicao['atual']->liberada) {
            return redirect()->route('cursos.show', $licao->modulo->curso_id)
                ->with('aviso', 'Conclua a lição anterior para abrir esta.');
        }

        return view('licoes.show', [
            'licao'     => $licao,
            'anterior'  => $posicao['anterior'],
            'proxima'   => $posicao['proxima'],
            'resultado' => null,   // ainda não respondeu
        ]);
    }

    // POST /licoes/{licao} -> corrige as respostas
    public function responder(Request $request, Licao $licao)
    {
        $user = Auth::user();
        if (!$licao->modulo->curso->visivelPara($user)) {
            abort(404);
        }

        $posicao = $this->posicaoNoCurso($licao);

        if (!$posicao['atual']->liberada) {
            return redirect()->route('cursos.show', $licao->modulo->curso_id)
                ->with('aviso', 'Conclua a lição anterior para abrir esta.');
        }

        // ---------- RF26/RF27: corrige cada exercício ----------
        // O formulário manda respostas[ID_DO_EXERCICIO] = 'a', 'b', 'c' ou 'd'.
        $respostas = $request->input('respostas', []);
        $acertos = 0;
        $correcao = [];   // $correcao[id_do_exercicio] = ['resposta' => 'b', 'acertou' => true/false]

        foreach ($licao->exercicios as $exercicio) {
            $resposta = $respostas[$exercicio->id] ?? null;   // null se não marcou nada
            $acertou = ($resposta === $exercicio->resposta_correta);
            if ($acertou) {
                $acertos++;
            }
            $correcao[$exercicio->id] = ['resposta' => $resposta, 'acertou' => $acertou];
        }

        $total = $licao->exercicios->count();
        // Lição sem exercícios (rascunho de professor) conta como 100%, para não dividir por zero.
        $porcentagem = $total > 0 ? round($acertos / $total * 100) : 100;
        $aprovado = $porcentagem >= User::NOTA_MINIMA;   // RF29

        // ---------- Salva o progresso ----------
        // firstOrNew: busca o progresso desse usuário nessa lição; se não existir, cria um novo (ainda sem salvar).
        $progresso = Progresso::firstOrNew(['user_id' => $user->id, 'licao_id' => $licao->id]);
        $jaEstavaAprovado = (bool) $progresso->aprovado;

        $progresso->total = $total;
        $progresso->acertos = max($progresso->acertos ?? 0, $acertos);   // guarda o melhor resultado

        $pontosGanhos = 0;
        // Pontos só na PRIMEIRA aprovação (refazer a lição não dá pontos de novo).
        if ($aprovado && !$jaEstavaAprovado) {
            $progresso->aprovado = true;
            $progresso->concluido_em = now();
            $pontosGanhos = $acertos * User::PONTOS_POR_ACERTO;   // RF18
            $user->pontos = $user->pontos + $pontosGanhos;
            $user->save();
        }
        $progresso->save();

        if ($aprovado) {
            $user->registrarEstudo();   // RF19: sequência de dias
        }
        $novasConquistas = $user->verificarConquistas();   // RF17

        return view('licoes.show', [
            'licao'     => $licao,
            'anterior'  => $posicao['anterior'],
            'proxima'   => $posicao['proxima'],
            'resultado' => [
                'correcao'    => $correcao,
                'acertos'     => $acertos,
                'total'       => $total,
                'porcentagem' => $porcentagem,
                'aprovado'    => $aprovado,
                'pontos'      => $pontosGanhos,
                'conquistas'  => $novasConquistas,
            ],
        ]);
    }

    /*
     * Encontra a lição na lista ordenada do curso e devolve:
     *   'atual'    -> a lição (com ->liberada e ->aprovada)
     *   'anterior' -> a lição de antes (ou null se for a primeira)
     *   'proxima'  -> a lição seguinte (ou null se for a última)
     * "private" = só pode ser usada dentro deste controller.
     */
    private function posicaoNoCurso(Licao $licao)
    {
        $lista = $licao->modulo->curso->licoesDoUsuario(Auth::user());

        $ids = $lista->pluck('id')->all();          // ex.: [10, 11, 12, 13...]
        $i = array_search($licao->id, $ids);        // posição desta lição na lista

        return [
            'atual'    => $lista[$i],
            'anterior' => $lista[$i - 1] ?? null,
            'proxima'  => $lista[$i + 1] ?? null,
        ];
    }
}
