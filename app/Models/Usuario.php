<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Usuario extends Model
{
    protected const TABLE = 'usuario';
    protected const PRIMARY_KEY = 'id_usuario';
    protected const FILLABLE = [
        'nome', 'arroba', 'email', 'senha', 'total_progresso_usuario',
        'cor_corpo_avatar', 'cor_barriga_avatar', 'cor_detalhe_avatar', 'cor_fundo_avatar',
        'chapeu_avatar', 'oculos_avatar', 'pescoco_avatar', 'roupa_avatar',
        'nivel_conhecimento_usuario', 'objetivo_usuario', 'meta_diaria_usuario',
        'ultimo_alerta_gelo', 'data_nascimento', 'aceite_termos_em', 'email_responsavel',
        'perfil_visibilidade', 'aparecer_busca', 'mostrar_online', 'papel_usuario',
    ];

    /** Colunas seguras para mostrar o perfil de outro usuário. */
    public const PUBLICO = 'u.id_usuario, u.nome, u.arroba, u.xp_usuario, u.ultimo_acesso_usuario,
        u.cor_corpo_avatar, u.cor_barriga_avatar, u.cor_detalhe_avatar, u.cor_fundo_avatar,
        u.chapeu_avatar, u.oculos_avatar, u.pescoco_avatar, u.roupa_avatar,
        u.perfil_visibilidade, u.mostrar_online, u.papel_usuario';

    public function buscarPorEmail(string $email): ?array
    {
        return $this->one('SELECT * FROM usuario WHERE email = ? LIMIT 1', [$email]);
    }

    public function buscarPorArroba(string $arroba): ?array
    {
        return $this->one('SELECT * FROM usuario WHERE arroba = ? LIMIT 1', [$arroba]);
    }

    /** Perfis cujo @ começa (ou contém) o termo, com os online primeiro. */
    public function pesquisar(string $termo, int $idExcluir, int $limite = 20): array
    {
        $termo = addcslashes($termo, '%_\\');
        return $this->many(
            'SELECT ' . self::PUBLICO . ' FROM usuario u
             WHERE u.id_usuario <> ? AND u.aparecer_busca = 1 AND (u.arroba LIKE ? OR u.nome LIKE ?)
             ORDER BY (u.arroba LIKE ?) DESC, u.ultimo_acesso_usuario DESC
             LIMIT ' . $limite,
            [$idExcluir, "%{$termo}%", "%{$termo}%", "{$termo}%"]
        );
    }

    public function registrarAcesso(int $idUsuario): void
    {
        $this->pdo->prepare('UPDATE usuario SET ultimo_acesso_usuario = NOW() WHERE id_usuario = ?')->execute([$idUsuario]);
    }

    public function somarRecompensa(int $idUsuario, int $xp, int $pontos): void
    {
        $this->pdo->prepare('UPDATE usuario SET xp_usuario = xp_usuario + ?, pontos_usuario = pontos_usuario + ? WHERE id_usuario = ?')
            ->execute([$xp, $pontos, $idUsuario]);
    }

    /** Desconta os pontos só se houver saldo; devolve false quando não há. */
    public function gastarPontos(int $idUsuario, int $pontos): bool
    {
        $consulta = $this->pdo->prepare('UPDATE usuario SET pontos_usuario = pontos_usuario - ? WHERE id_usuario = ? AND pontos_usuario >= ?');
        $consulta->execute([$pontos, $idUsuario, $pontos]);
        return $consulta->rowCount() === 1;
    }

    public function iniciarSequencia(int $idUsuario): void
    {
        $this->pdo->prepare('INSERT IGNORE INTO sequencia (id_usuario) VALUES (?)')->execute([$idUsuario]);
    }

    public function sequencia(int $idUsuario): ?array
    {
        return $this->one('SELECT * FROM sequencia WHERE id_usuario = ? LIMIT 1', [$idUsuario]);
    }

    public function progresso(int $idUsuario): array
    {
        return $this->many('SELECT * FROM progresso WHERE id_usuario = ? ORDER BY id_licao', [$idUsuario]);
    }

    public function conquistas(int $idUsuario): array
    {
        return $this->many('SELECT c.*, uc.data_desbloqueio FROM conquista c INNER JOIN usuario_conquista uc ON uc.id_conquista = c.id_conquista WHERE uc.id_usuario = ? ORDER BY uc.data_desbloqueio DESC', [$idUsuario]);
    }

    public function desafios(int $idUsuario): array
    {
        return $this->many('SELECT d.*, ud.status_participacao FROM desafio d INNER JOIN usuario_desafio ud ON ud.id_desafio = d.id_desafio WHERE ud.id_usuario = ? ORDER BY d.data_expiracao_desafio', [$idUsuario]);
    }
}
