<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Models\Certificado;

$usuario = exigirCadastroCompleto();
$certificados = (new Certificado(conexao()))->deUsuario((int) $usuario['id_usuario']);

$tituloPagina = 'Meus certificados';
$paginaAtual = 'certificados.php';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<h1 class="app-titulo">Meus certificados</h1>
<p class="app-subtitulo">
  Cada linguagem e assunto tem certificados de nível Iniciante, Intermediário e Avançado, e um da trilha completa.
  Emita pela página da trilha quando concluir 100% das lições do nível com pelo menos 70% de acertos.
</p>

<?php if ($certificados === []): ?>
  <p class="vazio">Você ainda não emitiu certificados. Conclua um nível de uma trilha para emitir o primeiro! 📜</p>
<?php endif; ?>

<div class="grade-certificados">
  <?php foreach ($certificados as $certificado):
      $trilha = trilhaInfo($certificado['trilha']);
  ?>
    <a class="cartao-certificado cartao-certificado--<?= e($certificado['nivel_certificado']) ?>" href="certificado.php?codigo=<?= e($certificado['codigo_certificado']) ?>">
      <?= $trilha ? iconeTrilha($certificado['trilha']) : '' ?>
      <div>
        <strong><?= e($certificado['titulo_trilha']) ?> <?= e(CertificadoController::rotulo($certificado['nivel_certificado'])) ?></strong>
        <small><?= (int) $certificado['horas_certificado'] ?> horas · emitido em <?= e(date('d/m/Y', strtotime($certificado['emitido_em']))) ?></small>
        <small>Código <?= e($certificado['codigo_certificado']) ?></small>
      </div>
    </a>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
