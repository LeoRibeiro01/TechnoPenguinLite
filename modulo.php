<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Models\Modulo;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Support\Texto;

/** Trilha visual de um módulo: lições em sequência (todas podem ser abertas), progresso e aprofundamento. */

$usuario = exigirCadastroCompleto();
$registro = (new Modulo(conexao()))->find((int) ($_GET['id'] ?? 0));
$curso = $registro === null ? null : (new Curso(conexao()))->find((int) $registro['id_curso']);
$trilha = $curso === null ? null : trilhaInfo((string) $curso['linguagem_curso']);
if ($trilha === null || (!$trilha['publicado'] && $trilha['id_professor'] !== (int) $usuario['id_usuario'])) {
    definirAviso('⚠ Módulo não encontrado.');
    redirecionar('painel.php');
}
$mapa = (new AprendizadoController(conexao()))->mapa($trilha['slug'], $usuario);
$posicao = array_search((int) $registro['id_modulo'], array_column($mapa['modulos'], 'id'), true);
$modulo = $mapa['modulos'][$posicao];
$anterior = $mapa['modulos'][$posicao - 1] ?? null;
$seguinte = $mapa['modulos'][$posicao + 1] ?? null;
$horas = CertificadoController::horas([$modulo]);

$tituloPagina = $modulo['titulo'];
$paginaAtual = 'painel.php';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<p class="migalhas">
  <a href="trilha.php?t=<?= e($trilha['slug']) ?>">← <?= e($trilha['titulo']) ?></a>
  · <?= e(CertificadoController::rotulo($modulo['dificuldade'])) ?>
</p>

<header class="modulo-cabecalho" style="--cor-trilha: <?= e($trilha['cor']) ?>">
  <div>
    <span class="selo nivel-selo--<?= e($modulo['dificuldade']) ?>"><?= e(CertificadoController::rotulo($modulo['dificuldade'])) ?></span>
    <h1 class="app-titulo"><?= e($modulo['titulo']) ?></h1>
    <?php if ($modulo['descricao']): ?>
      <p class="app-subtitulo"><?= e($modulo['descricao']) ?></p>
    <?php endif; ?>
  </div>
  <div class="modulo-cabecalho__numeros">
    <div class="anel" style="--pct: <?= $modulo['percentual'] ?>" role="img" aria-label="<?= $modulo['percentual'] ?>% concluído"><span><?= $modulo['percentual'] ?>%</span></div>
    <dl class="mini-stats">
      <div><dt>Falta</dt><dd><?= 100 - $modulo['percentual'] ?>%</dd></div>
      <div><dt>Acertos</dt><dd><?= $modulo['aproveitamento_feitas'] === null ? '—' : (int) floor($modulo['aproveitamento_feitas'] * 100) . '%' ?></dd></div>
      <div><dt>Carga</dt><dd>~<?= $horas ?>h</dd></div>
    </dl>
  </div>
</header>

<ol class="caminho" aria-label="Lições do módulo">
  <?php foreach ($modulo['licoes'] as $i => $licao): ?>
    <li class="caminho__passo caminho__passo--<?= e($licao['estado']) ?>">
      <a href="licao.php?id=<?= $licao['id'] ?>">
        <span class="caminho__no" aria-hidden="true"><?= ['concluida' => '✓', 'disponivel' => $i + 1, 'bloqueada' => '🔒'][$licao['estado']] ?></span>
        <span class="caminho__texto">
          <strong><?= e($licao['titulo']) ?></strong>
          <small>
            <?= ['concluida' => 'Concluída', 'disponivel' => 'Disponível', 'bloqueada' => 'Bloqueada: dá para ler a teoria; as questões abrem depois da anterior'][$licao['estado']] ?>
            <?php if ($licao['tentativas'] > 0): ?>
              · melhor resultado <?= $licao['acertos'] ?>/<?= $licao['questoes'] ?>
              <?= $licao['acertos'] < $licao['questoes'] ? '· refaça para melhorar' : '💯' ?>
            <?php endif; ?>
          </small>
        </span>
      </a>
    </li>
  <?php endforeach; ?>
  <?php if ($modulo['projeto']): ?>
    <li class="caminho__passo caminho__passo--projeto">
      <a href="#projeto">
        <span class="caminho__no" aria-hidden="true">🛠️</span>
        <span class="caminho__texto"><strong>Projeto do módulo</strong><small>Coloque em prática tudo o que aprendeu</small></span>
      </a>
    </li>
  <?php endif; ?>
</ol>

<?php if ($modulo['aprofundamento']): ?>
  <section class="cartao conteudo" aria-labelledby="titulo-aprofundamento">
    <h2 id="titulo-aprofundamento">🔎 Aprofundamento</h2>
    <?= Texto::html($modulo['aprofundamento']) ?>
    <?php if ($modulo['referencias']): ?>
      <div class="referencias"><h3>Referências</h3><?= Texto::html($modulo['referencias']) ?></div>
    <?php endif; ?>
  </section>
<?php endif; ?>

<?php if ($modulo['projeto']): ?>
  <section class="cartao conteudo projeto-bloco" id="projeto" aria-labelledby="titulo-projeto">
    <h2 id="titulo-projeto">🛠️ Projeto para fixar o módulo</h2>
    <?= Texto::html($modulo['projeto']) ?>
  </section>
<?php endif; ?>

<nav class="navegacao-par" aria-label="Outros módulos">
  <?php if ($anterior): ?>
    <a class="botao botao--fantasma" href="modulo.php?id=<?= $anterior['id'] ?>">← <?= e($anterior['titulo']) ?></a>
  <?php else: ?><span></span><?php endif; ?>
  <?php if ($seguinte): ?>
    <a class="botao botao--fantasma" href="modulo.php?id=<?= $seguinte['id'] ?>"><?= e($seguinte['titulo']) ?> →</a>
  <?php endif; ?>
</nav>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
