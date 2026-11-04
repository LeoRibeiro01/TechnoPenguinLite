<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Models\Certificado;

/**
 * Certificado para imprimir ou salvar em PDF. Público pelo código, para qualquer pessoa
 * conferir a autenticidade; mostra só o nome impresso, sem outros dados da conta.
 */

$codigo = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) ($_GET['codigo'] ?? '')));
$certificado = $codigo === '' ? null : (new Certificado(conexao()))->porCodigo($codigo);
$usuario = usuarioAtual();
$meu = $certificado !== null && $usuario !== null && (int) $certificado['id_usuario'] === (int) $usuario['id_usuario'];
$verificacao = sprintf(
    '%s://%s%s',
    ($_SERVER['HTTPS'] ?? 'off') !== 'off' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'] ?? 'localhost',
    strtok($_SERVER['REQUEST_URI'] ?? '/certificado.php', '?') . '?codigo=' . $codigo
);
$meses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title><?= $certificado ? e('Certificado ' . $certificado['titulo_trilha'] . ' ' . CertificadoController::rotulo($certificado['nivel_certificado'])) : 'Certificado não encontrado' ?> — TechnoPenguin</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Piedra&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/app.css">
  <link rel="stylesheet" href="assets/css/plataforma.css">
  <link rel="stylesheet" href="assets/css/certificado.css">
</head>
<body class="pagina-certificado">
  <?php if ($certificado === null): ?>
    <main class="certificado-ausente">
      <h1>Certificado não encontrado</h1>
      <p>Confira o código de verificação. Certificados de contas excluídas deixam de existir.</p>
      <a class="botao" href="index.html">Ir para o TechnoPenguin</a>
    </main>
  <?php else:
      $data = strtotime($certificado['emitido_em']);
      $rotulo = CertificadoController::rotulo($certificado['nivel_certificado']);
  ?>
    <div class="certificado-acoes">
      <?php if ($meu): ?><a class="botao botao--fantasma" href="certificados.php">← Meus certificados</a><?php endif; ?>
      <button class="botao" type="button" onclick="window.print()">Imprimir / salvar em PDF</button>
    </div>
    <main class="certificado certificado--<?= e($certificado['nivel_certificado']) ?>">
      <div class="certificado__moldura">
        <header class="certificado__topo">
          <img src="assets/imgs/logo.svg" alt="TechnoPenguin" class="certificado__logo">
          <span class="certificado__selo"><?= e($rotulo) ?></span>
        </header>
        <p class="certificado__rotulo">Certificado de conclusão</p>
        <p class="certificado__texto">Certificamos que</p>
        <h1 class="certificado__nome"><?= e($certificado['nome_aluno']) ?></h1>
        <p class="certificado__texto">
          concluiu na plataforma TechnoPenguin a trilha
          <strong><?= e($certificado['titulo_trilha']) ?> <?= e($rotulo) ?></strong>,
          com carga horária de <strong><?= (int) $certificado['horas_certificado'] ?> horas</strong>
          e aproveitamento de <strong><?= e(rtrim(rtrim(number_format((float) $certificado['aproveitamento_certificado'], 1, ',', ''), '0'), ',')) ?>%</strong>
          nas questões.
        </p>
        <footer class="certificado__rodape">
          <div>
            <strong><?= date('d', $data) ?> de <?= $meses[(int) date('n', $data) - 1] ?> de <?= date('Y', $data) ?></strong>
            <small>Data de emissão</small>
          </div>
          <div class="certificado__teco"><?= teco('avatar--corpo certificado__mascote') ?></div>
          <div>
            <strong><?= e($certificado['codigo_certificado']) ?></strong>
            <small>Código de verificação</small>
          </div>
        </footer>
        <p class="certificado__verificar">Confira a autenticidade em <?= e($verificacao) ?></p>
      </div>
    </main>
  <?php endif; ?>
  <script src="assets/js/pinguim.js"></script>
</body>
</html>
