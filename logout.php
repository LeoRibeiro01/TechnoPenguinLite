<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

if (!metodoPost()) {
    redirecionar('painel.php');
}
validarCsrf();
sair();
definirAviso('Sessão terminada. Até breve!');
redirecionar('login.php');
