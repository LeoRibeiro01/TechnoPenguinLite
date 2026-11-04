<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\ChatController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Iglu;

/** API JSON do chat: GET lista mensagens depois de um id; POST envia ou apaga (com token CSRF). */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$responder = static function (array $dados, int $status = 200): never {
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
};

$usuario = usuarioAtual();
if ($usuario === null) {
    $responder(['erro' => 'Sessão expirada. Entre de novo.'], 401);
}
$iglu = (new Iglu(conexao()))->find((int) ($_GET['id'] ?? 0));
if ($iglu === null) {
    $responder(['erro' => 'Iglu não encontrado.'], 404);
}
$chat = new ChatController(conexao());

try {
    if (metodoPost()) {
        $token = $_POST['csrf'] ?? null;
        if (!is_string($token) || !hash_equals(csrfToken(), $token)) {
            $responder(['erro' => 'Sessão expirada. Recarregue a página.'], 419);
        }
        match ((string) ($_POST['acao'] ?? '')) {
            'enviar' => $chat->enviar($usuario, $iglu, (string) ($_POST['texto'] ?? '')),
            'apagar' => $chat->apagar($usuario, $iglu, (int) ($_POST['id_mensagem'] ?? 0)),
            default => throw new ValidationException(['acao' => 'Ação desconhecida.']),
        };
        $responder(['ok' => true]);
    }
    $responder(['mensagens' => $chat->mensagens($usuario, $iglu, max(0, (int) ($_GET['depois'] ?? 0)))]);
} catch (ValidationException $e) {
    $responder(['erro' => $e->getMessage()], 422);
}
