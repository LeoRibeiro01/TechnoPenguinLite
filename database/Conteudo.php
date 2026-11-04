<?php
declare(strict_types=1);

/**
 * Lê o conteúdo de uma trilha em database/conteudo/<slug>.md. Formato:
 *
 *   # Título do módulo [intermediario]   (dificuldade opcional; sem ela, vale a posição: 1º iniciante, 2º intermediário, 3º+ avançado)
 *   > Descrição do módulo (opcional)
 *   ## Projeto
 *   Sugestão de projeto para fixar o módulo
 *   ## Título da lição
 *   ### Teoria
 *   ...
 *   ### Prática
 *   ...
 *   ### Questão
 *   Enunciado (pode ter blocos ```código```)
 *   - [ ] alternativa errada
 *   - [x] alternativa correta
 *   > Explicação mostrada na correção
 *
 * Linhas dentro de blocos ``` nunca são lidas como títulos.
 */
function lerConteudoTrilha(string $arquivo): array
{
    $modulos = [];
    $modulo = null;
    $licao = null;
    $secao = null;
    $questao = null;
    $dentroDeCodigo = false;

    $fecharQuestao = static function () use (&$questao, &$licao): void {
        if ($questao !== null) {
            $questao['enunciado'] = trim($questao['enunciado']);
            if ($questao['correta'] === null || count($questao['alternativas']) < 2) {
                throw new RuntimeException("Questão sem alternativas ou sem resposta correta: {$questao['enunciado']}");
            }
            $licao['questoes'][] = $questao;
            $questao = null;
        }
    };
    $fecharLicao = static function () use (&$licao, &$modulo, $fecharQuestao): void {
        $fecharQuestao();
        if ($licao !== null) {
            $licao['teoria'] = trim($licao['teoria']);
            $licao['pratica'] = trim($licao['pratica']);
            $modulo['licoes'][] = $licao;
            $licao = null;
        }
    };
    $fecharModulo = static function () use (&$modulo, &$modulos, $fecharLicao): void {
        $fecharLicao();
        if ($modulo !== null) {
            $modulo['projeto'] = trim($modulo['projeto']);
            $modulos[] = $modulo;
            $modulo = null;
        }
    };

    foreach (file($arquivo, FILE_IGNORE_NEW_LINES) as $linha) {
        $linha = rtrim($linha, "\r");
        if (str_starts_with($linha, '```')) {
            $dentroDeCodigo = !$dentroDeCodigo;
        } elseif (!$dentroDeCodigo) {
            if (preg_match('/^# (.+?)(?:\s+\[(iniciante|intermediario|avancado)\])?$/u', $linha, $m)) {
                $fecharModulo();
                $modulo = ['titulo' => trim($m[1]), 'dificuldade' => $m[2] ?? null, 'descricao' => '', 'projeto' => '', 'licoes' => []];
                $secao = 'modulo';
                continue;
            }
            if (preg_match('/^## (.+)$/u', $linha, $m)) {
                $fecharLicao();
                if (mb_strtolower(trim($m[1])) === 'projeto') {
                    $secao = 'projeto';
                } else {
                    $licao = ['titulo' => trim($m[1]), 'teoria' => '', 'pratica' => '', 'questoes' => []];
                    $secao = 'licao';
                }
                continue;
            }
            if (preg_match('/^### (.+)$/u', $linha, $m)) {
                $fecharQuestao();
                $nome = mb_strtolower(trim($m[1]));
                $secao = match (true) {
                    str_starts_with($nome, 'teoria') => 'teoria',
                    str_starts_with($nome, 'prát'), str_starts_with($nome, 'prat') => 'pratica',
                    str_starts_with($nome, 'quest') => 'questao',
                    default => throw new RuntimeException("Seção desconhecida: {$m[1]}"),
                };
                if ($secao === 'questao') {
                    $questao = ['enunciado' => '', 'alternativas' => [], 'correta' => null, 'explicacao' => ''];
                }
                continue;
            }
        }

        match ($secao) {
            'modulo' => str_starts_with($linha, '> ') ? $modulo['descricao'] .= ' ' . substr($linha, 2) : null,
            'projeto' => $modulo['projeto'] .= $linha . "\n",
            'teoria' => $licao['teoria'] .= $linha . "\n",
            'pratica' => $licao['pratica'] .= $linha . "\n",
            'questao' => (static function () use ($linha, $dentroDeCodigo, &$questao): void {
                if (!$dentroDeCodigo && preg_match('/^- \[( |x)\] (.+)$/u', $linha, $m)) {
                    if ($m[1] === 'x') {
                        $questao['correta'] = count($questao['alternativas']);
                    }
                    $questao['alternativas'][] = trim($m[2]);
                } elseif (!$dentroDeCodigo && str_starts_with($linha, '> ')) {
                    $questao['explicacao'] = trim($questao['explicacao'] . ' ' . substr($linha, 2));
                } else {
                    $questao['enunciado'] .= $linha . "\n";
                }
            })(),
            default => null,
        };
    }
    $fecharModulo();
    foreach ($modulos as &$m) {
        $m['descricao'] = trim($m['descricao']);
    }
    return $modulos;
}

/**
 * Lê a apresentação da trilha em database/conteudo/<slug>.sobre.md. Seções começam com "@":
 *
 *   @historia        como a linguagem/assunto surgiu
 *   @hoje            como é usado hoje
 *   @iniciante       lista "- ..." do que se aprende no nível (idem @intermediario e @avancado)
 *   @referencias     livros e links para a trilha inteira
 *   @modulo 2        aprofundamento do 2º módulo
 *   @fontes 2        referências do 2º módulo
 */
function lerApresentacaoTrilha(string $arquivo): array
{
    $secoes = [];
    $atual = null;
    foreach (file($arquivo, FILE_IGNORE_NEW_LINES) as $linha) {
        if (preg_match('/^@([a-z]+)(?:\s+(\d+))?\s*$/', rtrim($linha), $m)) {
            $atual = $m[1] . (isset($m[2]) ? ':' . $m[2] : '');
            $secoes[$atual] = '';
            continue;
        }
        if ($atual !== null) {
            $secoes[$atual] .= rtrim($linha, "\r") . "\n";
        }
    }
    $secoes = array_map('trim', $secoes);
    $objetivos = [];
    foreach (['iniciante', 'intermediario', 'avancado'] as $nivel) {
        $itens = array_filter(array_map(
            static fn (string $l): string => trim(preg_replace('/^-\s*/', '', $l)),
            explode("\n", $secoes[$nivel] ?? '')
        ));
        if ($itens !== []) {
            $objetivos[$nivel] = array_values($itens);
        }
    }
    $modulos = [];
    foreach ($secoes as $chave => $texto) {
        if (preg_match('/^(modulo|fontes):(\d+)$/', $chave, $m)) {
            $modulos[(int) $m[2]][$m[1] === 'modulo' ? 'aprofundamento' : 'referencias'] = $texto;
        }
    }
    return [
        'historia' => $secoes['historia'] ?? null,
        'hoje' => $secoes['hoje'] ?? null,
        'objetivos' => $objetivos,
        'referencias' => $secoes['referencias'] ?? null,
        'modulos' => $modulos,
    ];
}
