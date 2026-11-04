<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Progresso extends Model
{
    protected const TABLE = 'progresso';
    protected const PRIMARY_KEY = 'id_progresso';
    protected const FILLABLE = [
        'status_progresso', 'data_conclusao_progresso', 'id_usuario', 'id_licao',
        'acertos_progresso', 'xp_progresso', 'pontos_progresso', 'total_questoes_progresso', 'tentativas_progresso',
    ];

    public function deUsuario(int $idUsuario): array
    {
        return $this->many('SELECT * FROM progresso WHERE id_usuario = ? ORDER BY id_licao', [$idUsuario]);
    }

    public function daLicao(int $idUsuario, int $idLicao): ?array
    {
        return $this->one('SELECT * FROM progresso WHERE id_usuario = ? AND id_licao = ? LIMIT 1', [$idUsuario, $idLicao]);
    }

    /** Lições concluídas hoje, para medir a meta diária. */
    public function concluidasHoje(int $idUsuario): int
    {
        $linha = $this->one(
            "SELECT COUNT(*) AS total FROM progresso WHERE id_usuario = ? AND status_progresso = 'Concluída' AND data_conclusao_progresso >= CURDATE()",
            [$idUsuario]
        );
        return (int) ($linha['total'] ?? 0);
    }
}
