<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class UsuarioItem extends Model
{
    protected const TABLE = 'usuario_item';
    protected const PRIMARY_KEY = ['id_usuario', 'item'];
    protected const FILLABLE = ['id_usuario', 'item'];

    public function deUsuario(int $idUsuario): array
    {
        return array_column($this->many('SELECT item FROM usuario_item WHERE id_usuario = ?', [$idUsuario]), 'item');
    }
}
