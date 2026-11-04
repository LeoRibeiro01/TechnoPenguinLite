<?php /** Abre o layout de login/cadastro. Espera $tituloPagina e $descricaoPagina. */ ?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#1a2e46">
  <meta name="description" content="<?= e($descricaoPagina) ?>">
  <title><?= e($tituloPagina) ?> — TechnoPenguin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Piedra&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body class="auth-page">
  <a class="auth-back" href="index.html">← Voltar</a>
  <main class="auth-layout">
    <section class="auth-panel" aria-labelledby="auth-title">
      <a class="auth-logo" href="index.html" aria-label="Voltar ao início TechnoPenguin">
        <img src="assets/imgs/logo.svg" alt="TechnoPenguin">
      </a>
      <div class="auth-form-wrap">
