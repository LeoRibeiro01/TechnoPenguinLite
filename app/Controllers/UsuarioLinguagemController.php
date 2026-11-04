<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\UsuarioLinguagem;

final class UsuarioLinguagemController extends ResourceController
{
    protected const MODEL_CLASS = UsuarioLinguagem::class;
}
