<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

/** Trilhas (linguagens e assuntos) que o usuário estuda. */
final class UsuarioLinguagem extends Model
{
    protected const TABLE = 'usuario_linguagem';
    protected const PRIMARY_KEY = ['id_usuario', 'linguagem'];
    protected const FILLABLE = ['id_usuario', 'linguagem', 'data_escolha'];

    public function deUsuario(int $idUsuario): array
    {
        return $this->many('SELECT linguagem FROM usuario_linguagem WHERE id_usuario = ? ORDER BY data_escolha, linguagem', [$idUsuario]);
    }

    /** Substitui as trilhas escolhidas pelo usuário numa única transação. */
    public function substituir(int $idUsuario, array $linguagens): void
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare('DELETE FROM usuario_linguagem WHERE id_usuario = ?')->execute([$idUsuario]);
            $this->adicionar($idUsuario, $linguagens);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /** Acrescenta trilhas sem duplicar as que o usuário já tem. */
    public function adicionar(int $idUsuario, array $linguagens): void
    {
        $inserir = $this->pdo->prepare('INSERT IGNORE INTO usuario_linguagem (id_usuario, linguagem) VALUES (?, ?)');
        foreach ($linguagens as $linguagem) {
            $inserir->execute([$idUsuario, $linguagem]);
        }
    }
}
