<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Notificacao extends Model
{
    protected const TABLE = 'notificacao';
    protected const PRIMARY_KEY = 'id_notificacao';
    protected const FILLABLE = ['id_usuario', 'tipo_notificacao', 'mensagem_notificacao', 'link_notificacao', 'lida_notificacao'];

    public function enviar(int $idUsuario, string $tipo, string $mensagem, ?string $link = null): void
    {
        $this->create([
            'id_usuario' => $idUsuario,
            'tipo_notificacao' => $tipo,
            'mensagem_notificacao' => mb_substr($mensagem, 0, 300),
            'link_notificacao' => $link,
        ]);
    }

    public function enviarParaVarios(array $idsUsuarios, string $tipo, string $mensagem, ?string $link = null): void
    {
        foreach ($idsUsuarios as $id) {
            $this->enviar((int) $id, $tipo, $mensagem, $link);
        }
    }

    public function deUsuario(int $idUsuario, int $limite = 50): array
    {
        return $this->many('SELECT * FROM notificacao WHERE id_usuario = ? ORDER BY criada_em DESC, id_notificacao DESC LIMIT ' . $limite, [$idUsuario]);
    }

    public function naoLidas(int $idUsuario): int
    {
        return (int) ($this->one('SELECT COUNT(*) AS total FROM notificacao WHERE id_usuario = ? AND lida_notificacao = 0', [$idUsuario])['total'] ?? 0);
    }

    public function marcarLidas(int $idUsuario): void
    {
        $this->pdo->prepare('UPDATE notificacao SET lida_notificacao = 1 WHERE id_usuario = ? AND lida_notificacao = 0')->execute([$idUsuario]);
    }
}
