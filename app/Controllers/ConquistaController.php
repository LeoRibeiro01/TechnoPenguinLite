<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Conquista;
use TechnoPenguin\Models\Notificacao;
use TechnoPenguin\Support\Conquistas;

final class ConquistaController extends ResourceController
{
    protected const MODEL_CLASS = Conquista::class;

    public function __construct(private readonly PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /** Garante que todas as conquistas do catálogo existam no banco (pelo código). */
    public function sincronizarCatalogo(): void
    {
        foreach (Conquistas::NOMES_ANTIGOS as $nome => $codigo) {
            $this->pdo->prepare('UPDATE conquista SET codigo_conquista = ? WHERE nome_conquista = ? AND codigo_conquista IS NULL
                AND NOT EXISTS (SELECT 1 FROM (SELECT id_conquista FROM conquista WHERE codigo_conquista = ?) x)')
                ->execute([$codigo, $nome, $codigo]);
        }
        $salvar = $this->pdo->prepare(
            'INSERT INTO conquista (codigo_conquista, nome_conquista, descricao_conquista, icone_conquista) VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE nome_conquista = VALUES(nome_conquista), descricao_conquista = VALUES(descricao_conquista),
                icone_conquista = VALUES(icone_conquista)'
        );
        foreach (Conquistas::CATALOGO as $codigo => [$nome, $descricao, $icone]) {
            $salvar->execute([$codigo, $nome, $descricao, $icone]);
        }
    }

    /** Confere todas as regras e desbloqueia (com notificação) as conquistas que faltam. */
    public function avaliar(int $idUsuario): array
    {
        $ids = $this->idsPorCodigo();
        if (count($ids) < count(Conquistas::CATALOGO)) {
            $this->sincronizarCatalogo();
            $ids = $this->idsPorCodigo();
        }
        $n = $this->metricas($idUsuario);
        $regras = [
            'primeira-licao' => $n['licoes'] >= 1,
            'dez-licoes' => $n['licoes'] >= 10,
            'cinquenta-licoes' => $n['licoes'] >= 50,
            'nota-maxima' => $n['perfeitas'] >= 1,
            'gelo-3' => $n['gelo'] >= 3,
            'gelo-7' => $n['gelo'] >= 7,
            'gelo-30' => $n['gelo'] >= 30,
            'poliglota' => $n['trilhas'] >= 3,
            'primeiro-certificado' => $n['certificados'] >= 1,
            'trilha-completa' => $n['completos'] >= 1,
            'sociavel' => $n['amigos'] >= 3,
            'fundador' => $n['iglus_criados'] >= 1,
            'em-grupo' => $n['iglus'] >= 1,
            'estiloso' => $n['itens'] >= 3,
        ];
        $jaTem = array_map('intval', array_column(
            $this->model->deUsuario($idUsuario),
            'id_conquista'
        ));
        $novas = [];
        $inserir = $this->pdo->prepare('INSERT IGNORE INTO usuario_conquista (id_usuario, id_conquista) VALUES (?, ?)');
        foreach ($regras as $codigo => $cumpriu) {
            $id = $ids[$codigo] ?? null;
            if ($cumpriu && $id !== null && !in_array($id, $jaTem, true)) {
                $inserir->execute([$idUsuario, $id]);
                [$nome, , $icone] = Conquistas::CATALOGO[$codigo];
                (new Notificacao($this->pdo))->enviar($idUsuario, 'conquista', "{$icone} Conquista desbloqueada: {$nome}!", 'perfil.php');
                $novas[] = $codigo;
            }
        }
        return $novas;
    }

    private function idsPorCodigo(): array
    {
        $linhas = $this->pdo->query('SELECT codigo_conquista, id_conquista FROM conquista WHERE codigo_conquista IS NOT NULL')->fetchAll();
        return array_map('intval', array_column($linhas, 'id_conquista', 'codigo_conquista'));
    }

    private function metricas(int $idUsuario): array
    {
        $consulta = $this->pdo->prepare(
            "SELECT
                (SELECT COUNT(*) FROM progresso WHERE id_usuario = ? AND status_progresso = 'Concluída') AS licoes,
                (SELECT COUNT(*) FROM progresso WHERE id_usuario = ? AND status_progresso = 'Concluída'
                    AND total_questoes_progresso > 0 AND acertos_progresso = total_questoes_progresso) AS perfeitas,
                (SELECT COALESCE(MAX(dias_consecutivos_sequencia), 0) FROM sequencia WHERE id_usuario = ?) AS gelo,
                (SELECT COUNT(DISTINCT m.id_curso) FROM progresso p JOIN licao l ON l.id_licao = p.id_licao
                    JOIN modulo m ON m.id_modulo = l.id_modulo WHERE p.id_usuario = ? AND p.status_progresso = 'Concluída') AS trilhas,
                (SELECT COUNT(*) FROM certificado WHERE id_usuario = ?) AS certificados,
                (SELECT COUNT(*) FROM certificado WHERE id_usuario = ? AND nivel_certificado = 'completo') AS completos,
                (SELECT COUNT(*) FROM amizade WHERE (id_solicitante = ? OR id_destinatario = ?) AND status_amizade = 'aceita') AS amigos,
                (SELECT COUNT(*) FROM iglu WHERE id_criador = ?) AS iglus_criados,
                (SELECT COUNT(*) FROM iglu_membro WHERE id_usuario = ?) AS iglus,
                (SELECT COUNT(*) FROM usuario_item WHERE id_usuario = ?) AS itens"
        );
        $consulta->execute(array_fill(0, 11, $idUsuario));
        return array_map('intval', $consulta->fetch());
    }
}
