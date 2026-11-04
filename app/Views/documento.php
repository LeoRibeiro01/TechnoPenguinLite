<?php /** Layout simples para documentos públicos (política e termos). Espera $tituloPagina e $conteudo (HTML). */ ?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($tituloPagina) ?> — TechnoPenguin</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/app.css">
  <link rel="stylesheet" href="assets/css/plataforma.css">
</head>
<body class="app-page">
  <header class="app-header">
    <a class="app-header__brand" href="index.html"><img src="assets/imgs/logo.svg" alt="TechnoPenguin"></a>
    <nav class="app-nav"><a href="<?= usuarioAtual() ? 'painel.php' : 'login.php' ?>"><?= usuarioAtual() ? 'Voltar ao painel' : 'Entrar' ?></a></nav>
  </header>
  <main class="app-main documento">
    <h1 class="app-titulo"><?= e($tituloPagina) ?></h1>
    <p class="app-subtitulo">Última atualização: 3 de novembro de 2026.</p>
    <div class="cartao conteudo"><?= $conteudo ?></div>
  </main>
</body>
</html>
