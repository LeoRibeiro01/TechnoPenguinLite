<?php
declare(strict_types=1);

/** As aulas agora ficam no painel (Minhas trilhas); mantém links antigos funcionando. */

require_once __DIR__ . '/app/web.php';

redirecionar('painel.php');
