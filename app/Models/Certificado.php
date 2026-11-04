<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Certificado extends Model
{
    protected const TABLE = 'certificado';
    protected const PRIMARY_KEY = 'id_certificado';
    protected const FILLABLE = [
        'codigo_certificado', 'id_usuario', 'trilha', 'titulo_trilha', 'nivel_certificado',
        'nome_aluno', 'horas_certificado', 'aproveitamento_certificado',
    ];

    public function porCodigo(string $codigo): ?array
    {
        return $this->one('SELECT * FROM certificado WHERE codigo_certificado = ? LIMIT 1', [strtoupper($codigo)]);
    }

    public function deUsuario(int $idUsuario): array
    {
        return $this->many('SELECT * FROM certificado WHERE id_usuario = ? ORDER BY emitido_em DESC', [$idUsuario]);
    }

    /** nível => certificado já emitido, para uma trilha. */
    public function daTrilha(int $idUsuario, string $trilha): array
    {
        $linhas = $this->many('SELECT * FROM certificado WHERE id_usuario = ? AND trilha = ?', [$idUsuario, $trilha]);
        return array_column($linhas, null, 'nivel_certificado');
    }
}
