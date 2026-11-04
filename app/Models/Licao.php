<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Licao extends Model
{
    protected const TABLE = 'licao';
    protected const PRIMARY_KEY = 'id_licao';
    protected const FILLABLE = ['titulo_licao', 'pontos_licao', 'sequencia_licao', 'id_modulo', 'teoria_licao', 'pratica_licao'];

    public function questoes(int $idLicao): array
    {
        return $this->many('SELECT * FROM questao WHERE id_licao = ? ORDER BY sequencia_questao, id_questao', [$idLicao]);
    }

    /** Lição com o módulo e a trilha a que pertence. */
    public function comContexto(int $idLicao): ?array
    {
        return $this->one(
            'SELECT l.*, m.titulo_modulo, m.sequencia_modulo, m.projeto_modulo, c.id_curso, c.linguagem_curso AS trilha
             FROM licao l
             INNER JOIN modulo m ON m.id_modulo = l.id_modulo
             INNER JOIN curso c ON c.id_curso = m.id_curso
             WHERE l.id_licao = ? LIMIT 1',
            [$idLicao]
        );
    }
}
