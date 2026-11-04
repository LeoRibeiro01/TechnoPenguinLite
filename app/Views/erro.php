<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?> — TechnoPenguin</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="app-page">
  <main class="app-main app-erro">
    <img src="assets/imgs/teco-thinking.png" alt="" width="140">
    <h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p>
    <a class="button button--primary" href="index.html">Voltar ao início</a>
  </main>
</body>
</html>
