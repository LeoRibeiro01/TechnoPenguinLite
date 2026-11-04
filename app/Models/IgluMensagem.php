<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class IgluMensagem extends Model
{
    protected const TABLE = 'iglu_mensagem';
    protected const PRIMARY_KEY = 'id_mensagem';
    protected const FILLABLE = ['id_iglu', 'id_usuario', 'texto_mensagem'];

    /** Mensagens depois de um id (polling); sem id, as últimas $limite. */
    public function depoisDe(int $idIglu, int $depois, int $limite = 50): array
    {
        $linhas = $this->many(
            'SELECT m.id_mensagem, m.id_usuario, m.texto_mensagem, m.criada_em, u.arroba, u.nome
             FROM iglu_mensagem m INNER JOIN usuario u ON u.id_usuario = m.id_usuario
             WHERE m.id_iglu = ? AND m.id_mensagem > ?
             ORDER BY m.id_mensagem DESC LIMIT ' . $limite,
            [$idIglu, $depois]
        );
        return array_reverse($linhas);
    }

    public function ultimaDoUsuario(int $idUsuario): ?string
    {
        $linha = $this->one('SELECT MAX(criada_em) AS ultima FROM iglu_mensagem WHERE id_usuario = ?', [$idUsuario]);
        return $linha['ultima'] ?? null;
    }
}
