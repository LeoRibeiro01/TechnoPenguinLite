<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Amizade extends Model
{
    protected const TABLE = 'amizade';
    protected const PRIMARY_KEY = 'id_amizade';
    protected const FILLABLE = ['id_solicitante', 'id_destinatario', 'status_amizade'];

    /** O registro entre dois usuários, em qualquer direção. */
    public function entre(int $idA, int $idB): ?array
    {
        return $this->one(
            'SELECT * FROM amizade WHERE (id_solicitante = ? AND id_destinatario = ?) OR (id_solicitante = ? AND id_destinatario = ?) LIMIT 1',
            [$idA, $idB, $idB, $idA]
        );
    }

    public function amigos(int $idUsuario): array
    {
        return $this->many(
            "SELECT " . Usuario::PUBLICO . ", a.id_amizade
             FROM amizade a
             INNER JOIN usuario u ON u.id_usuario = IF(a.id_solicitante = ?, a.id_destinatario, a.id_solicitante)
             WHERE (a.id_solicitante = ? OR a.id_destinatario = ?) AND a.status_amizade = 'aceita'
             ORDER BY u.ultimo_acesso_usuario DESC",
            [$idUsuario, $idUsuario, $idUsuario]
        );
    }

    public function pedidosRecebidos(int $idUsuario): array
    {
        return $this->many(
            "SELECT " . Usuario::PUBLICO . ", a.id_amizade, a.criada_em
             FROM amizade a INNER JOIN usuario u ON u.id_usuario = a.id_solicitante
             WHERE a.id_destinatario = ? AND a.status_amizade = 'pendente'
             ORDER BY a.criada_em DESC",
            [$idUsuario]
        );
    }

    public function pedidosEnviados(int $idUsuario): array
    {
        return $this->many(
            "SELECT " . Usuario::PUBLICO . ", a.id_amizade, a.criada_em
             FROM amizade a INNER JOIN usuario u ON u.id_usuario = a.id_destinatario
             WHERE a.id_solicitante = ? AND a.status_amizade = 'pendente'
             ORDER BY a.criada_em DESC",
            [$idUsuario]
        );
    }

    /** id do outro usuário => status visto por $idUsuario: amigo, enviado ou recebido. */
    public function relacoes(int $idUsuario): array
    {
        $relacoes = [];
        $linhas = $this->many('SELECT * FROM amizade WHERE id_solicitante = ? OR id_destinatario = ?', [$idUsuario, $idUsuario]);
        foreach ($linhas as $linha) {
            $enviado = (int) $linha['id_solicitante'] === $idUsuario;
            $outro = (int) ($enviado ? $linha['id_destinatario'] : $linha['id_solicitante']);
            $relacoes[$outro] = $linha['status_amizade'] === 'aceita' ? 'amigo' : ($enviado ? 'enviado' : 'recebido');
        }
        return $relacoes;
    }
}
