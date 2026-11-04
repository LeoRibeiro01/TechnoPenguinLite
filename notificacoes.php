<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Models\Notificacao;

$usuario = exigirCadastroCompleto();
$notificacoes = new Notificacao(conexao());
$lista = $notificacoes->deUsuario((int) $usuario['id_usuario']);

$tituloPagina = 'Notificações';
$paginaAtual = 'notificacoes.php';
require __DIR__ . '/app/Views/app_inicio.php';
// Marca como lidas depois de o cabeçalho contar as novas.
$notificacoes->marcarLidas((int) $usuario['id_usuario']);
?>
<h1 class="app-titulo">Notificações</h1>
<p class="app-subtitulo">Pedidos de amizade, convites de iglus e avisos do seu gelo.</p>

<?php if ($lista === []): ?>
  <p class="vazio">Nada por aqui ainda.</p>
<?php else: ?>
  <ul class="notificacoes">
    <?php foreach ($lista as $n): ?>
      <li class="notificacao notificacao--<?= e($n['tipo_notificacao']) ?> <?= $n['lida_notificacao'] ? '' : 'notificacao--nova' ?>">
        <span class="notificacao__icone" aria-hidden="true"><?= ['gelo' => '🧊', 'iglu' => '🏠', 'amizade' => '👋', 'certificado' => '📜', 'conquista' => '🏅', 'pergunta' => '💬'][$n['tipo_notificacao']] ?? '🔔' ?></span>
        <div>
          <p>
            <?php if ($n['link_notificacao']): ?>
              <a href="<?= e($n['link_notificacao']) ?>"><?= e($n['mensagem_notificacao']) ?></a>
            <?php else: ?>
              <?= e($n['mensagem_notificacao']) ?>
            <?php endif; ?>
          </p>
          <time datetime="<?= e($n['criada_em']) ?>"><?= e(date('d/m/Y H:i', strtotime($n['criada_em']))) ?></time>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
