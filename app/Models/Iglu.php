<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Iglu extends Model
{
    protected const TABLE = 'iglu';
    protected const PRIMARY_KEY = 'id_iglu';
    protected const FILLABLE = [
        'nome_iglu', 'descricao_iglu', 'id_criador',
        'dias_consecutivos_iglu', 'ultima_atividade_iglu', 'inicio_sequencia_iglu',
        'cor_iglu', 'icone_iglu', 'aberto_iglu', 'chat_ativo',
    ];

    /** Iglus do usuário com o papel dele e o número de membros. */
    public function doUsuario(int $idUsuario): array
    {
        return $this->many(
            'SELECT i.*, me.papel_membro, (SELECT COUNT(*) FROM iglu_membro x WHERE x.id_iglu = i.id_iglu) AS total_membros
             FROM iglu i INNER JOIN iglu_membro me ON me.id_iglu = i.id_iglu AND me.id_usuario = ?
             ORDER BY i.nome_iglu',
            [$idUsuario]
        );
    }

    public function pesquisar(string $termo, int $limite = 20): array
    {
        $termo = addcslashes($termo, '%_\\');
        return $this->many(
            'SELECT i.*, (SELECT COUNT(*) FROM iglu_membro x WHERE x.id_iglu = i.id_iglu) AS total_membros
             FROM iglu i WHERE i.nome_iglu LIKE ? OR i.descricao_iglu LIKE ?
             ORDER BY i.dias_consecutivos_iglu DESC, i.nome_iglu LIMIT ' . $limite,
            ["%{$termo}%", "%{$termo}%"]
        );
    }

    public function membros(int $idIglu): array
    {
        return $this->many(
            "SELECT " . Usuario::PUBLICO . ", me.papel_membro, me.entrou_em
             FROM iglu_membro me INNER JOIN usuario u ON u.id_usuario = me.id_usuario
             WHERE me.id_iglu = ?
             ORDER BY FIELD(me.papel_membro, 'dono', 'admin', 'membro'), u.xp_usuario DESC",
            [$idIglu]
        );
    }

    public function papel(int $idIglu, int $idUsuario): ?string
    {
        $linha = $this->one('SELECT papel_membro FROM iglu_membro WHERE id_iglu = ? AND id_usuario = ?', [$idIglu, $idUsuario]);
        return $linha['papel_membro'] ?? null;
    }

    public function adicionarMembro(int $idIglu, int $idUsuario, string $papel = 'membro'): void
    {
        $this->pdo->prepare('INSERT IGNORE INTO iglu_membro (id_iglu, id_usuario, papel_membro) VALUES (?, ?, ?)')
            ->execute([$idIglu, $idUsuario, $papel]);
    }

    public function alterarPapel(int $idIglu, int $idUsuario, string $papel): void
    {
        $this->pdo->prepare('UPDATE iglu_membro SET papel_membro = ? WHERE id_iglu = ? AND id_usuario = ?')
            ->execute([$papel, $idIglu, $idUsuario]);
    }

    public function removerMembro(int $idIglu, int $idUsuario): void
    {
        $this->pdo->prepare('DELETE FROM iglu_membro WHERE id_iglu = ? AND id_usuario = ?')->execute([$idIglu, $idUsuario]);
    }

    /** trilha => obrigatória (bool). */
    public function trilhas(int $idIglu): array
    {
        $linhas = $this->many('SELECT trilha, obrigatoria FROM iglu_trilha WHERE id_iglu = ? ORDER BY obrigatoria DESC, trilha', [$idIglu]);
        return array_map('boolval', array_column($linhas, 'obrigatoria', 'trilha'));
    }

    public function substituirTrilhas(int $idIglu, array $trilhas): void
    {
        $this->pdo->prepare('DELETE FROM iglu_trilha WHERE id_iglu = ?')->execute([$idIglu]);
        $inserir = $this->pdo->prepare('INSERT INTO iglu_trilha (id_iglu, trilha, obrigatoria) VALUES (?, ?, ?)');
        foreach ($trilhas as $trilha => $obrigatoria) {
            $inserir->execute([$idIglu, $trilha, (int) $obrigatoria]);
        }
    }

    /** XP ganho por cada membro nas trilhas do iglu desde que entrou. */
    public function ranking(int $idIglu): array
    {
        return $this->many(
            "SELECT u.id_usuario, u.nome, u.arroba, COALESCE(SUM(p.xp_progresso), 0) AS xp_iglu, COUNT(p.id_progresso) AS licoes_iglu
             FROM iglu_membro me
             INNER JOIN usuario u ON u.id_usuario = me.id_usuario
             LEFT JOIN progresso p ON p.id_usuario = me.id_usuario AND p.status_progresso = 'Concluída'
                AND p.data_conclusao_progresso >= me.entrou_em
                AND p.id_licao IN (
                    SELECT l.id_licao FROM licao l
                    INNER JOIN modulo m ON m.id_modulo = l.id_modulo
                    INNER JOIN curso c ON c.id_curso = m.id_curso
                    INNER JOIN iglu_trilha it ON it.trilha = c.linguagem_curso AND it.id_iglu = me.id_iglu
                )
             WHERE me.id_iglu = ?
             GROUP BY u.id_usuario
             ORDER BY xp_iglu DESC, u.arroba",
            [$idIglu]
        );
    }

    /** Últimas lições concluídas pelos membros nas trilhas do iglu. */
    public function atividade(int $idIglu, int $limite = 12): array
    {
        return $this->many(
            "SELECT u.arroba, l.titulo_licao, c.linguagem_curso AS trilha, p.data_conclusao_progresso
             FROM progresso p
             INNER JOIN iglu_membro me ON me.id_usuario = p.id_usuario AND me.id_iglu = ?
             INNER JOIN usuario u ON u.id_usuario = p.id_usuario
             INNER JOIN licao l ON l.id_licao = p.id_licao
             INNER JOIN modulo m ON m.id_modulo = l.id_modulo
             INNER JOIN curso c ON c.id_curso = m.id_curso
             INNER JOIN iglu_trilha it ON it.id_iglu = me.id_iglu AND it.trilha = c.linguagem_curso
             WHERE p.status_progresso = 'Concluída'
             ORDER BY p.data_conclusao_progresso DESC
             LIMIT " . $limite,
            [$idIglu]
        );
    }

    public function idsMembros(int $idIglu): array
    {
        return array_map('intval', array_column($this->many('SELECT id_usuario FROM iglu_membro WHERE id_iglu = ?', [$idIglu]), 'id_usuario'));
    }

    /** id_usuario => trilha => [feitas, total]: o quanto cada membro avançou nas trilhas do iglu. */
    public function progressoMembros(int $idIglu): array
    {
        $linhas = $this->many(
            "SELECT me.id_usuario, it.trilha, COUNT(DISTINCT l.id_licao) AS total, COUNT(DISTINCT p.id_licao) AS feitas
             FROM iglu_membro me
             INNER JOIN iglu_trilha it ON it.id_iglu = me.id_iglu
             INNER JOIN curso c ON c.linguagem_curso = it.trilha
             INNER JOIN modulo m ON m.id_curso = c.id_curso
             INNER JOIN licao l ON l.id_modulo = m.id_modulo
             LEFT JOIN progresso p ON p.id_licao = l.id_licao AND p.id_usuario = me.id_usuario AND p.status_progresso = 'Concluída'
             WHERE me.id_iglu = ?
             GROUP BY me.id_usuario, it.trilha",
            [$idIglu]
        );
        $resultado = [];
        foreach ($linhas as $linha) {
            $resultado[(int) $linha['id_usuario']][$linha['trilha']] = [(int) $linha['feitas'], (int) $linha['total']];
        }
        return $resultado;
    }

    /** Lições concluídas pelos membros nos últimos 7 dias, em qualquer trilha. */
    public function licoesDaSemana(int $idIglu): int
    {
        $linha = $this->one(
            "SELECT COUNT(*) AS total FROM progresso p
             INNER JOIN iglu_membro me ON me.id_usuario = p.id_usuario AND me.id_iglu = ?
             WHERE p.status_progresso = 'Concluída' AND p.data_conclusao_progresso >= NOW() - INTERVAL 7 DAY",
            [$idIglu]
        );
        return (int) ($linha['total'] ?? 0);
    }
}
