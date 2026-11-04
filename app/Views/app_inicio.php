<?php
/**
 * Abre o layout das páginas logadas. Espera $tituloPagina e $paginaAtual;
 * $semMenu esconde a navegação (questionário inicial).
 */
use TechnoPenguin\Models\Notificacao;

$aviso = consumirAviso();
$contaLayout = usuarioAtual(true);
$naoLidas = $contaLayout === null ? 0 : (new Notificacao(conexao()))->naoLidas((int) $contaLayout['id_usuario']);
$links = [
    'painel.php' => 'Início',
    'painel.php?aba=explorar' => 'Explorar',
    'painel.php?aba=comunidade' => 'Comunidade',
    'painel.php?aba=amigos' => 'Amigos',
    'painel.php?aba=iglus' => 'Iglus',
    'vestuario.php' => 'Vestuário',
];
if ($contaLayout !== null && ($contaLayout['papel_usuario'] ?? '') === 'professor') {
    $links['professor.php'] = 'Professor';
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#1a2e46">
  <title><?= e($tituloPagina) ?> — TechnoPenguin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Piedra&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/app.css">
  <link rel="stylesheet" href="assets/css/plataforma.css">
</head>

<body class="app-page">
  <header class="app-header">
    <a class="app-header__brand" href="<?= empty($semMenu) ? 'painel.php' : 'index.html' ?>" aria-label="TechnoPenguin">
      <img src="assets/imgs/logo.svg" alt="TechnoPenguin">
    </a>
    <nav class="app-nav" aria-label="Navegação da conta">
      <?php if (empty($semMenu)): ?>
        <?php foreach ($links as $href => $rotulo): ?>
          <a href="<?= e($href) ?>" <?= $paginaAtual === $href ? 'aria-current="page"' : '' ?>><?= e($rotulo) ?></a>
        <?php endforeach; ?>
        <?php if ($contaLayout !== null): ?>
          <span class="app-nav__pontos" title="Pontos para trocar por roupas">❄ <?= (int) $contaLayout['pontos_usuario'] ?></span>
          <a class="app-nav__sino" href="notificacoes.php" <?= $paginaAtual === 'notificacoes.php' ? 'aria-current="page"' : '' ?>
            aria-label="Notificações<?= $naoLidas > 0 ? " ({$naoLidas} novas)" : '' ?>">
            🔔<?php if ($naoLidas > 0): ?><span class="app-nav__contador"><?= $naoLidas > 99 ? '99+' : $naoLidas ?></span><?php endif; ?>
          </a>
          <a class="app-nav__icone" href="conta.php" title="Minha conta e privacidade" aria-label="Minha conta"
            <?= $paginaAtual === 'conta.php' ? 'aria-current="page"' : '' ?>>⚙</a>
          <a class="app-nav__perfil" href="<?= e(linkPerfil($contaLayout)) ?>" title="Meu perfil">
            <?= avatarDe($contaLayout, 'avatar--rosto avatar--mini') ?>
          </a>
        <?php endif; ?>
      <?php endif; ?>
      <form action="logout.php" method="post">
        <?= campoCsrf() ?>
        <button class="app-nav__sair" type="submit">Sair</button>
      </form>
    </nav>
  </header>
  <main class="app-main">
    <?php if ($aviso !== null && str_starts_with($aviso, '⚠')): ?>
      <p class="app-erro-msg" role="alert"><?= e($aviso) ?></p>
    <?php elseif ($aviso !== null): ?>
      <p class="app-aviso" role="status"><?= e($aviso) ?></p>
    <?php endif; ?>
