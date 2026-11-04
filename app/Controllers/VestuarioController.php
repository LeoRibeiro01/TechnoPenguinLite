<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Models\UsuarioItem;
use TechnoPenguin\Support\Vestuario;

final class VestuarioController
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** Itens que o usuário pode vestir: os grátis e os comprados. */
    public function disponiveis(int $idUsuario): array
    {
        $gratis = array_keys(array_filter(Vestuario::ITENS, static fn (array $item): bool => $item[4] === 0));
        return array_values(array_unique([...$gratis, ...(new UsuarioItem($this->pdo))->deUsuario($idUsuario)]));
    }

    public function comprar(int $idUsuario, string $slug): array
    {
        $item = Vestuario::item($slug) ?? throw new ValidationException(['item' => 'Item não encontrado.']);
        if (in_array($slug, $this->disponiveis($idUsuario), true)) {
            throw new ValidationException(['item' => 'Você já tem este item.']);
        }
        $this->pdo->beginTransaction();
        try {
            if (!(new Usuario($this->pdo))->gastarPontos($idUsuario, $item['preco'])) {
                throw new ValidationException(['item' => "Faltam pontos: {$item['nome']} custa {$item['preco']} pontos."]);
            }
            (new UsuarioItem($this->pdo))->create(['id_usuario' => $idUsuario, 'item' => $slug]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        (new ConquistaController($this->pdo))->avaliar($idUsuario);
        return $item;
    }

    /** Veste o item na camada dele; com $slug vazio, tira o que estiver na camada. */
    public function vestir(int $idUsuario, string $camada, string $slug): void
    {
        if (!isset(Vestuario::CAMADAS[$camada])) {
            throw new ValidationException(['item' => 'Camada inválida.']);
        }
        if ($slug !== '') {
            $item = Vestuario::item($slug);
            if ($item === null || $item['camada'] !== $camada || !in_array($slug, $this->disponiveis($idUsuario), true)) {
                throw new ValidationException(['item' => 'Você ainda não tem este item.']);
            }
        }
        (new Usuario($this->pdo))->update($idUsuario, ["{$camada}_avatar" => $slug === '' ? null : $slug]);
    }
}
