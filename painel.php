<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\AmizadeController;
use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;

/** Painel do usuário em abas: início (gelo e trilhas), explorar, amigos e iglus. */

$usuario = exigirCadastroCompleto();
$idUsuario = (int) $usuario['id_usuario'];
$abas = ['inicio' => 'Minhas trilhas', 'explorar' => 'Explorar', 'comunidade' => 'Comunidade', 'amigos' => 'Amigos', 'iglus' => 'Iglus'];
$aba = isset($abas[$_GET['aba'] ?? '']) ? $_GET['aba'] : 'inicio';
$erro = null;

if (metodoPost()) {
    validarCsrf();
    $acao = (string) ($_POST['acao'] ?? '');
    $amizades = new AmizadeController(conexao());
    try {
        $mensagem = match ($acao) {
            'adicionar_trilhas' => (function () use ($usuario, $idUsuario): string {
                if (!(new AprendizadoController(conexao()))->podeExplorar($usuario)) {
                    throw new ValidationException(['trilhas' => 'Conclua o primeiro módulo de Lógica de Programação para liberar outras trilhas.']);
                }
                (new UsuarioController(conexao()))->adicionarTrilhas($idUsuario, $_POST['trilhas'] ?? []);
                return 'Trilhas adicionadas ao seu perfil!';
            })(),
            'amizade_pedir' => $amizades->solicitar($usuario, (int) ($_POST['id_usuario'] ?? 0)),
            'amizade_aceitar' => $amizades->responder($usuario, (int) ($_POST['id_amizade'] ?? 0), true),
            'amizade_recusar' => $amizades->responder($usuario, (int) ($_POST['id_amizade'] ?? 0), false),
            'amizade_remover' => (function () use ($amizades, $idUsuario): string {
                $amizades->remover($idUsuario, (int) ($_POST['id_amizade'] ?? 0));
                return 'Pronto.';
            })(),
            default => throw new ValidationException(['acao' => 'Ação desconhecida.']),
        };
        definirAviso($mensagem);
        $destino = $acao === 'adicionar_trilhas' && $aba !== 'comunidade' ? 'painel.php' : 'painel.php?aba=' . $aba;
        if ($aba === 'amigos' && ($_POST['q'] ?? '') !== '') {
            $destino .= '&q=' . rawurlencode((string) $_POST['q']);
        }
        redirecionar($destino);
    } catch (ValidationException $e) {
        $erro = $e->getMessage();
    }
}

$tituloPagina = 'Painel';
$paginaAtual = $aba === 'inicio' ? 'painel.php' : 'painel.php?aba=' . $aba;
require __DIR__ . '/app/Views/app_inicio.php';
$usuario = $contaLayout;
?>
<nav class="abas" aria-label="Seções do painel">
  <?php foreach ($abas as $chave => $rotulo): ?>
    <a href="painel.php<?= $chave === 'inicio' ? '' : '?aba=' . $chave ?>" <?= $chave === $aba ? 'aria-current="page"' : '' ?>><?= e($rotulo) ?></a>
  <?php endforeach; ?>
</nav>

<?php if ($erro !== null): ?>
  <p class="app-erro-msg" role="alert"><?= e($erro) ?></p>
<?php endif; ?>

<?php require __DIR__ . '/app/Views/painel/' . $aba . '.php'; ?>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
