<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use RuntimeException;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\Licao;
use TechnoPenguin\Models\Progresso;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Trilhas;

/** Trilhas, módulos, lições e correção das questões, com XP, pontos, aproveitamento e gelo. */
final class AprendizadoController
{
    public const CONCLUIDA = 'Concluída';
    public const XP_POR_ACERTO = 10;
    public const XP_POR_LICAO = 20;
    public const PONTOS_POR_ACERTO = 5;
    /** Fração mínima de acertos para concluir a lição. */
    public const APROVACAO = 0.6;

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Mapa da trilha. As lições abrem em ordem (todas abertas para quem sabe "vários assuntos
     * detalhados"); lições bloqueadas podem ser lidas, mas não respondidas.
     *
     * Devolve curso, modulos (com lições e estatísticas), niveis (dificuldade => módulos),
     * totais da trilha e a próxima lição a fazer.
     */
    public function mapa(string $trilha, array $usuario): ?array
    {
        $cursos = new Curso($this->pdo);
        $curso = $cursos->porTrilha($trilha);
        if ($curso === null) {
            return null;
        }
        $livre = $usuario['nivel_conhecimento_usuario'] === 'avancado';
        $anteriorConcluida = true;
        $modulos = [];
        $proxima = null;
        foreach ($cursos->licoesComProgresso((int) $curso['id_curso'], (int) $usuario['id_usuario']) as $linha) {
            $idModulo = (int) $linha['id_modulo'];
            $modulos[$idModulo] ??= [
                'id' => $idModulo,
                'titulo' => $linha['titulo_modulo'],
                'sequencia' => (int) $linha['sequencia_modulo'],
                'dificuldade' => $linha['dificuldade_modulo'],
                'descricao' => $linha['descricao_modulo'],
                'projeto' => $linha['projeto_modulo'],
                'aprofundamento' => $linha['aprofundamento_modulo'],
                'referencias' => $linha['referencias_modulo'],
                'licoes' => [],
            ];
            if ($linha['id_licao'] === null) {
                continue;
            }
            $concluida = $linha['status_progresso'] === self::CONCLUIDA;
            $estado = $concluida ? 'concluida' : ($anteriorConcluida || $livre ? 'disponivel' : 'bloqueada');
            $licao = [
                'id' => (int) $linha['id_licao'],
                'titulo' => $linha['titulo_licao'],
                'estado' => $estado,
                'acertos' => (int) $linha['acertos_progresso'],
                'questoes' => (int) $linha['total_questoes'],
                'tentativas' => (int) $linha['tentativas_progresso'],
                'id_modulo' => $idModulo,
            ];
            $modulos[$idModulo]['licoes'][] = $licao;
            if ($proxima === null && $estado === 'disponivel') {
                $proxima = $licao + ['modulo' => $linha['titulo_modulo']];
            }
            $anteriorConcluida = $concluida;
        }

        $niveis = [];
        foreach ($modulos as &$modulo) {
            $modulo += self::estatisticas($modulo['licoes']);
            $niveis[$modulo['dificuldade']][] = $modulo;
        }
        unset($modulo);
        $ordenados = [];
        foreach (array_keys(Catalogo::DIFICULDADES) as $dificuldade) {
            if (isset($niveis[$dificuldade])) {
                $ordenados[$dificuldade] = $niveis[$dificuldade];
            }
        }
        $todas = array_merge([], ...array_map(static fn (array $m): array => $m['licoes'], array_values($modulos)));
        return [
            'curso' => $curso,
            'modulos' => array_values($modulos),
            'niveis' => $ordenados,
            'proxima' => $proxima,
        ] + self::estatisticas($todas);
    }

    /** Totais de um conjunto de lições: feitas, porcentagem concluída e aproveitamento nas questões. */
    public static function estatisticas(array $licoes): array
    {
        $total = count($licoes);
        $feitas = count(array_filter($licoes, static fn (array $l): bool => $l['estado'] === 'concluida'));
        $acertos = array_sum(array_column($licoes, 'acertos'));
        $questoes = array_sum(array_column($licoes, 'questoes'));
        $respondidas = array_sum(array_map(
            static fn (array $l): int => $l['tentativas'] > 0 ? $l['questoes'] : 0,
            $licoes
        ));
        return [
            'total_licoes' => $total,
            'feitas' => $feitas,
            'percentual' => $total > 0 ? (int) floor($feitas / $total * 100) : 0,
            'acertos' => $acertos,
            'questoes' => $questoes,
            // Aproveitamento sobre todas as questões do conjunto (as não feitas contam como erro).
            'aproveitamento' => $questoes > 0 ? $acertos / $questoes : 0.0,
            // Aproveitamento só nas lições já tentadas, para mostrar o desempenho até agora.
            'aproveitamento_feitas' => $respondidas > 0 ? $acertos / $respondidas : null,
        ];
    }

    /** Próxima lição a fazer na trilha, ou null quando a trilha acabou. */
    public function proximaLicao(string $trilha, array $usuario): ?array
    {
        return $this->mapa($trilha, $usuario)['proxima'] ?? null;
    }

    /**
     * Lição com questões, estado, lições vizinhas e dados do módulo. Lança RuntimeException se
     * a lição não existir ou pertencer a um curso da comunidade não publicado.
     */
    public function licao(int $idLicao, array $usuario): array
    {
        $licao = (new Licao($this->pdo))->comContexto($idLicao) ?? throw new RuntimeException('Lição não encontrada.');
        $trilha = Trilhas::info($this->pdo, $licao['trilha']);
        if ($trilha === null || (!$trilha['publicado'] && $trilha['id_professor'] !== (int) $usuario['id_usuario'])) {
            throw new RuntimeException('Esta lição não está disponível.');
        }
        $mapa = $this->mapa($licao['trilha'], $usuario);
        $ordem = array_merge([], ...array_map(static fn (array $m): array => $m['licoes'], $mapa['modulos']));
        foreach ($ordem as $i => $item) {
            if ($item['id'] === $idLicao) {
                $licao['estado'] = $item['estado'];
                $licao['melhor_acertos'] = $item['acertos'];
                $licao['tentativas'] = $item['tentativas'];
                $licao['anterior'] = $ordem[$i - 1] ?? null;
                $licao['seguinte'] = $ordem[$i + 1] ?? null;
                $licao['modulo'] = current(array_filter($mapa['modulos'], static fn (array $m): bool => $m['id'] === $item['id_modulo']));
            }
        }
        $licao['na_trilha'] = in_array($licao['trilha'], (new UsuarioController($this->pdo))->trilhas((int) $usuario['id_usuario']), true);
        $licao['questoes'] = array_map(static function (array $questao): array {
            $questao['alternativas'] = json_decode((string) $questao['alternativas_questao'], true) ?: [];
            return $questao;
        }, (new Licao($this->pdo))->questoes($idLicao));
        return $licao;
    }

    /**
     * Corrige as respostas ([id_questao => índice]). Guarda a melhor tentativa, para o aluno
     * poder refazer e melhorar o aproveitamento. XP e pontos só entram na primeira conclusão,
     * multiplicados pela meta diária.
     */
    public function responder(array $usuario, int $idLicao, array $respostas): array
    {
        $idUsuario = (int) $usuario['id_usuario'];
        $licao = $this->licao($idLicao, $usuario);
        if (!$licao['na_trilha']) {
            throw new RuntimeException('Adicione esta trilha ao seu perfil para responder às questões.');
        }
        if ($licao['estado'] === 'bloqueada') {
            throw new RuntimeException('Conclua a lição anterior para responder a esta.');
        }
        $correcao = [];
        $acertos = 0;
        foreach ($licao['questoes'] as $questao) {
            $resposta = filter_var($respostas[$questao['id_questao']] ?? null, FILTER_VALIDATE_INT);
            $acertou = $resposta === (int) $questao['gabarito_questao'];
            $acertos += (int) $acertou;
            $correcao[] = $questao + ['resposta' => $resposta === false ? null : $resposta, 'acertou' => $acertou];
        }
        $total = count($licao['questoes']);
        $aprovado = $total === 0 || $acertos >= (int) ceil($total * self::APROVACAO);

        $progressos = new Progresso($this->pdo);
        $existente = $progressos->daLicao($idUsuario, $idLicao);
        $jaConcluida = ($existente['status_progresso'] ?? null) === self::CONCLUIDA;
        $melhorAnterior = (int) ($existente['acertos_progresso'] ?? 0);
        $resultado = [
            'licao' => $licao, 'correcao' => $correcao, 'acertos' => $acertos, 'total' => $total,
            'aprovado' => $aprovado, 'primeira_vez' => false, 'xp' => 0, 'pontos' => 0, 'gelo' => null,
            'melhorou' => $existente !== null && $acertos > $melhorAnterior,
            'melhor' => max($acertos, $melhorAnterior),
        ];

        $this->pdo->beginTransaction();
        try {
            $dados = [
                'acertos_progresso' => max($acertos, $melhorAnterior),
                'total_questoes_progresso' => $total,
                'tentativas_progresso' => (int) ($existente['tentativas_progresso'] ?? 0) + 1,
            ];
            if ($aprovado && !$jaConcluida) {
                $xp = $acertos * self::XP_POR_ACERTO + self::XP_POR_LICAO;
                $pontos = (int) round(($acertos * self::PONTOS_POR_ACERTO + (int) $licao['pontos_licao'])
                    * Catalogo::multiplicador($usuario['meta_diaria_usuario'] === null ? null : (int) $usuario['meta_diaria_usuario']));
                $dados += [
                    'status_progresso' => self::CONCLUIDA,
                    'data_conclusao_progresso' => date('Y-m-d H:i:s'),
                    'xp_progresso' => $xp,
                    'pontos_progresso' => $pontos,
                ];
                (new Usuario($this->pdo))->somarRecompensa($idUsuario, $xp, $pontos);
                $resultado = ['primeira_vez' => true, 'xp' => $xp, 'pontos' => $pontos] + $resultado;
            }
            $existente === null
                ? $progressos->create($dados + ['id_usuario' => $idUsuario, 'id_licao' => $idLicao])
                : $progressos->update((int) $existente['id_progresso'], $dados);
            // Qualquer lição aprovada, mesmo revisada, conta para o gelo do dia.
            if ($aprovado) {
                $resultado['gelo'] = (new GeloController($this->pdo))->registrarLicao($idUsuario, $usuario['arroba']);
            }
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        (new ConquistaController($this->pdo))->avaliar($idUsuario);
        return $resultado;
    }

    /** Quem começou do zero só escolhe outras trilhas depois do primeiro módulo de lógica. */
    public function podeExplorar(array $usuario): bool
    {
        if ($usuario['nivel_conhecimento_usuario'] !== 'zero') {
            return true;
        }
        $mapa = $this->mapa(Catalogo::TRILHA_INICIAL, $usuario);
        return $mapa === null || ($mapa['modulos'][0]['percentual'] ?? 100) === 100;
    }
}
