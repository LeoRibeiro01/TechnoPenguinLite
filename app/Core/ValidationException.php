<?php
declare(strict_types=1);

namespace TechnoPenguin\Core;

use InvalidArgumentException;

/** Erro de validação com mensagens por campo, para exibição em formulários. */
final class ValidationException extends InvalidArgumentException
{
    public function __construct(public readonly array $erros)
    {
        parent::__construct((string) (array_values($erros)[0] ?? 'Dados inválidos.'));
    }
}
