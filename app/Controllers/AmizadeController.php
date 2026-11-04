<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Amizade;
use TechnoPenguin\Models\Notificacao;
use TechnoPenguin\Models\Usuario;

final class AmizadeController extends ResourceController
{
    protected const MODEL_CLASS = Amizade::class;

    public function __construct(private readonly PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /** Usuários encontrados pelo @ ou nome, cada um com a relação de amizade atual. */
    public function pesquisar(string $termo, int $idUsuario): array
    {
        $termo = UsuarioController::normalizarArroba($termo);
        if (mb_strlen($termo) < 2) {
            return [];
        }
        $relacoes = $this->amizades()->relacoes($idUsuario);
        return array_map(
            static fn (array $u): array => $u + ['relacao' => $relacoes[(int) $u['id_usuario']] ?? null],
            (new Usuario($this->pdo))->pesquisar($termo, $idUsuario)
        );
    }

    /** Envia o pedido; se o outro já tinha pedido, a amizade é aceita na hora. */
    public function solicitar(array $usuario, int $idOutro): string
    {
        $idUsuario = (int) $usuario['id_usuario'];
        $outro = (new Usuario($this->pdo))->find($idOutro);
        if ($outro === null || $idOutro === $idUsuario) {
            throw new ValidationException(['amizade' => 'Usuário não encontrado.']);
        }
        $existente = $this->amizades()->entre($idUsuario, $idOutro);
        if ($existente !== null) {
            if ($existente['status_amizade'] === 'pendente' && (int) $existente['id_destinatario'] === $idUsuario) {
                return $this->responder($usuario, (int) $existente['id_amizade'], true);
            }
            throw new ValidationException(['amizade' => $existente['status_amizade'] === 'aceita'
                ? "Você e @{$outro['arroba']} já são amigos."
                : "Você já enviou um pedido para @{$outro['arroba']}."]);
        }
        $this->amizades()->create(['id_solicitante' => $idUsuario, 'id_destinatario' => $idOutro]);
        (new Notificacao($this->pdo))->enviar($idOutro, 'amizade', "@{$usuario['arroba']} quer ser seu amigo.", 'painel.php?aba=amigos');
        return "Pedido de amizade enviado para @{$outro['arroba']}.";
    }

    public function responder(array $usuario, int $idAmizade, bool $aceitar): string
    {
        $amizade = $this->amizades()->find($idAmizade);
        if ($amizade === null || (int) $amizade['id_destinatario'] !== (int) $usuario['id_usuario'] || $amizade['status_amizade'] !== 'pendente') {
            throw new ValidationException(['amizade' => 'Este pedido não existe mais.']);
        }
        if (!$aceitar) {
            $this->amizades()->delete($idAmizade);
            return 'Pedido recusado.';
        }
        $this->amizades()->update($idAmizade, ['status_amizade' => 'aceita']);
        $conquistas = new ConquistaController($this->pdo);
        $conquistas->avaliar((int) $usuario['id_usuario']);
        $conquistas->avaliar((int) $amizade['id_solicitante']);
        (new Notificacao($this->pdo))->enviar((int) $amizade['id_solicitante'], 'amizade', "@{$usuario['arroba']} aceitou seu pedido de amizade!", 'painel.php?aba=amigos');
        return 'Agora vocês são amigos!';
    }

    /** Desfaz a amizade ou cancela um pedido enviado. */
    public function remover(int $idUsuario, int $idAmizade): void
    {
        $amizade = $this->amizades()->find($idAmizade);
        if ($amizade !== null && in_array($idUsuario, [(int) $amizade['id_solicitante'], (int) $amizade['id_destinatario']], true)) {
            $this->amizades()->delete($idAmizade);
        }
    }

    private function amizades(): Amizade
    {
        return $this->model;
    }
}
