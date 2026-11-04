<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;

if (($usuario = usuarioAtual()) !== null) {
    redirecionar(destinoInicial($usuario));
}

$email = '';
$erro = null;
if (metodoPost()) {
    validarCsrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    try {
        $usuario = $email === '' || $senha === ''
            ? null
            : (new UsuarioController(conexao()))->autenticar($email, $senha, (string) ($_SERVER['REMOTE_ADDR'] ?? ''));
        if ($usuario !== null) {
            entrar($usuario);
            redirecionar(destinoInicial($usuario));
        }
        $erro = 'Email/@ ou senha incorretos.';
    } catch (ValidationException $e) {
        $erro = $e->getMessage();
    }
}

$aviso = consumirAviso();
$tituloPagina = 'Entrar';
$descricaoPagina = 'Acesse sua conta TechnoPenguin e continue aprendendo programação.';
require __DIR__ . '/app/Views/auth_inicio.php';
?>
        <h1 id="auth-title">Entrar na sua conta</h1>
        <p class="auth-intro">Continue de onde parou.</p>

        <?php if ($aviso !== null): ?>
          <p class="auth-alert auth-alert--ok" role="status"><?= e($aviso) ?></p>
        <?php endif; ?>
        <?php if ($erro !== null): ?>
          <p class="auth-alert" role="alert"><?= e($erro) ?></p>
        <?php endif; ?>

        <form class="auth-form" action="login.php" method="post" novalidate>
          <?= campoCsrf() ?>
          <label for="email">Email ou @</label>
          <input id="email" name="email" type="text" placeholder="seu@email.com ou @seu_usuario" autocomplete="username" autocapitalize="none"
            value="<?= e($email) ?>" required <?= $email === '' ? 'autofocus' : '' ?>>

          <label for="senha">Senha</label>
          <input id="senha" name="senha" type="password" placeholder="Digite sua senha"
            autocomplete="current-password" required <?= $email !== '' ? 'autofocus' : '' ?>>

          <div class="auth-actions">
            <a class="auth-secondary" href="cadastro.php">Criar conta</a>
            <button class="auth-submit" type="submit">Entrar</button>
          </div>
        </form>
<?php require __DIR__ . '/app/Views/auth_fim.php'; ?>
