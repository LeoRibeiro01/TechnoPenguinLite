<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class IgluConvite extends Model
{
    protected const TABLE = 'iglu_convite';
    protected const PRIMARY_KEY = 'id_convite';
    protected const FILLABLE = ['id_iglu', 'id_usuario', 'id_remetente', 'tipo_convite', 'status_convite'];

    public function pendente(int $idIglu, int $idUsuario): ?array
    {
        return $this->one(
            "SELECT * FROM iglu_convite WHERE id_iglu = ? AND id_usuario = ? AND status_convite = 'pendente' LIMIT 1",
            [$idIglu, $idUsuario]
        );
    }

    /** Convites recebidos pelo usuário, com dados do iglu e de quem convidou. */
    public function recebidos(int $idUsuario): array
    {
        return $this->many(
            "SELECT ic.*, i.nome_iglu, i.descricao_iglu, r.arroba AS arroba_remetente
             FROM iglu_convite ic
             INNER JOIN iglu i ON i.id_iglu = ic.id_iglu
             LEFT JOIN usuario r ON r.id_usuario = ic.id_remetente
             WHERE ic.id_usuario = ? AND ic.tipo_convite = 'convite' AND ic.status_convite = 'pendente'
             ORDER BY ic.criado_em DESC",
            [$idUsuario]
        );
    }

    /** Pedidos para entrar no iglu, à espera de um admin. */
    public function pedidos(int $idIglu): array
    {
        return $this->many(
            "SELECT ic.*, " . Usuario::PUBLICO . "
             FROM iglu_convite ic INNER JOIN usuario u ON u.id_usuario = ic.id_usuario
             WHERE ic.id_iglu = ? AND ic.tipo_convite = 'pedido' AND ic.status_convite = 'pendente'
             ORDER BY ic.criado_em",
            [$idIglu]
        );
    }

    public function convidadosPendentes(int $idIglu): array
    {
        return array_map('intval', array_column($this->many(
            "SELECT id_usuario FROM iglu_convite WHERE id_iglu = ? AND status_convite = 'pendente'",
            [$idIglu]
        ), 'id_usuario'));
    }
}
