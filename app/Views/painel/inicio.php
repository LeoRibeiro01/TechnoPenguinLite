<?php
/** Aba inicial do painel: perfil, gelo, meta do dia e trilhas escolhidas. Espera $usuario. */

use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\GeloController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Models\Amizade;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\IgluConvite;
use TechnoPenguin\Models\Progresso;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Gelo;

$idUsuario = (int) $usuario['id_usuario'];
$gelos = new GeloController(conexao());
$gelo = $gelos->resumo($idUsuario);
$alerta = $gelos->alertaDoDia($usuario);
$aprendizado = new AprendizadoController(conexao());
$trilhas = (new UsuarioController(conexao()))->trilhas($idUsuario);
$resumo = (new Curso(conexao()))->resumoTrilhas($idUsuario);
$meta = (int) $usuario['meta_diaria_usuario'];
$minutosHoje = (new Progresso(conexao()))->concluidasHoje($idUsuario) * Catalogo::MINUTOS_POR_LICAO;
$xp = (int) $usuario['xp_usuario'];
$pedidosAmizade = count((new Amizade(conexao()))->pedidosRecebidos($idUsuario));
$convitesIglu = count((new IgluConvite(conexao()))->recebidos($idUsuario));

$mensagensGelo = [
    Gelo::APAGADO => ['Gelo apagado', 'Faça uma lição para ligar o seu Gelo. Cada dia com lição deixa ele maior!'],
    Gelo::FEITO_HOJE => ['Gelo firme!', 'Você já estudou hoje. Volte amanhã para o gelo crescer.'],
    Gelo::PENDENTE => ['Seu gelo está esperando', 'Você ainda não fez a lição de hoje. ' . ($gelo['dias_ate_derreter'] === 0
        ? 'Se não estudar hoje, ele derrete amanhã!'
        : 'Ele derrete se você ficar mais ' . plural($gelo['dias_ate_derreter'] + 1, 'dia', 'dias') . ' sem lições.')],
    Gelo::DERRETIDO => ['O gelo derreteu', 'Faça uma lição para ligar um novo Gelo.'],
];
[$tituloGelo, $textoGelo] = $mensagensGelo[$gelo['estado']];
?>
<?php if ($alerta !== null): ?>
  <dialog class="alerta-gelo" id="alerta-gelo" open aria-labelledby="alerta-gelo-titulo">
    <div class="gelo gelo--<?= e($alerta['estado']) ?>" aria-hidden="true"><span><?= (int) $alerta['dias'] ?></span></div>
    <h2 id="alerta-gelo-titulo">
      <?= $alerta['estado'] === Gelo::PENDENTE ? 'Não deixe seu gelo derreter!' : 'Seu gelo está apagado' ?>
    </h2>
    <p>
      <?= $alerta['estado'] === Gelo::PENDENTE
          ? 'Você está com ' . e(plural($alerta['dias'], 'dia', 'dias')) . ' de gelo. Faça a lição de hoje para mantê-lo.'
          : 'Faça uma lição hoje para ligar o seu Gelo e começar uma nova ofensiva.' ?>
    </p>
    <form method="dialog"><button class="botao">Bora estudar!</button></form>
  </dialog>
<?php endif; ?>

<section class="painel-topo">
  <article class="cartao perfil">
    <?= avatarDe($usuario, 'avatar--rosto avatar--perfil') ?>
    <div class="perfil__info">
      <h1 class="app-titulo"><?= e($usuario['nome']) ?></h1>
      <p class="perfil__arroba">@<?= e($usuario['arroba']) ?></p>
      <div class="xp" title="<?= $xp ?> XP">
        <span>Nível <?= nivelXp($xp) ?></span>
        <div class="barra"><span style="width: <?= $xp % 100 ?>%"></span></div>
        <small><?= $xp % 100 ?>/100 XP</small>
      </div>
      <p class="perfil__links">
        <a href="personalizar.php">Editar pinguim</a> · <a href="vestuario.php">Vestuário</a>
      </p>
    </div>
  </article>

  <article class="cartao cartao-gelo cartao-gelo--<?= e($gelo['estado']) ?>">
    <div class="gelo gelo--<?= e($gelo['estado']) ?>" aria-hidden="true"><span><?= $gelo['dias'] ?></span></div>
    <div>
      <h2><?= e($tituloGelo) ?></h2>
      <p class="cartao-gelo__dias"><?= e(plural($gelo['dias'], 'dia de gelo', 'dias de gelo')) ?></p>
      <p><?= e($textoGelo) ?></p>
    </div>
  </article>

  <article class="cartao cartao-meta">
    <h2>Meta de hoje</h2>
    <p class="cartao-meta__numero"><?= min($minutosHoje, $meta) ?><small>/<?= $meta ?> min</small></p>
    <div class="barra barra--meta"><span style="width: <?= min(100, (int) round($minutosHoje / max(1, $meta) * 100)) ?>%"></span></div>
    <p class="cartao-meta__texto">
      <?= $minutosHoje >= $meta ? 'Meta cumprida! 🎉' : 'Cada lição conta ' . Catalogo::MINUTOS_POR_LICAO . ' minutos.' ?>
      Bônus da meta: <strong>×<?= rtrim(rtrim(number_format(Catalogo::multiplicador($meta), 2, ',', ''), '0'), ',') ?></strong> pontos.
    </p>
    <dl class="mini-stats">
      <div><dt>Pontos</dt><dd>❄ <?= (int) $usuario['pontos_usuario'] ?></dd></div>
      <div><dt>XP total</dt><dd><?= $xp ?></dd></div>
    </dl>
  </article>
</section>

<?php if ($pedidosAmizade > 0 || $convitesIglu > 0): ?>
  <div class="lembretes">
    <?php if ($pedidosAmizade > 0): ?>
      <a href="painel.php?aba=amigos">👋 <?= e(plural($pedidosAmizade, 'pedido de amizade', 'pedidos de amizade')) ?></a>
    <?php endif; ?>
    <?php if ($convitesIglu > 0): ?>
      <a href="painel.php?aba=iglus">🏠 <?= e(plural($convitesIglu, 'convite para iglu', 'convites para iglus')) ?></a>
    <?php endif; ?>
  </div>
<?php endif; ?>

<section aria-labelledby="titulo-trilhas">
  <div class="secao-cabecalho">
    <h2 class="app-titulo" id="titulo-trilhas">Suas linguagens e assuntos</h2>
    <a class="botao botao--fantasma" href="painel.php?aba=explorar">+ Adicionar</a>
  </div>
  <div class="grade-trilhas">
    <?php foreach ($trilhas as $slug):
        $trilha = trilhaInfo($slug);
        if ($trilha === null) {
            continue;
        }
        $total = (int) ($resumo[$slug]['total_licoes'] ?? 0);
        $feitas = (int) ($resumo[$slug]['licoes_concluidas'] ?? 0);
        $proxima = $aprendizado->proximaLicao($slug, $usuario);
    ?>
      <article class="cartao-trilha" style="--cor-trilha: <?= e($trilha['cor']) ?>">
        <header>
          <?= iconeTrilha($slug) ?>
          <div>
            <h3><a href="trilha.php?t=<?= e($slug) ?>"><?= e($trilha['titulo']) ?></a></h3>
            <small><?= $trilha['comunidade'] ? 'Comunidade · @' . e($trilha['arroba_professor'] ?? '') : ($trilha['tipo'] === 'assunto' ? 'Assunto' : ($trilha['tipo'] === 'base' ? 'Fundamentos' : 'Linguagem')) ?></small>
          </div>
        </header>
        <div class="barra"><span style="width: <?= $total > 0 ? (int) floor($feitas / $total * 100) : 0 ?>%"></span></div>
        <p class="cartao-trilha__progresso"><?= $total > 0 ? (int) floor($feitas / $total * 100) : 0 ?>% concluído · <?= $feitas ?>/<?= $total ?> lições</p>
        <?php if ($proxima !== null): ?>
          <a class="botao botao--pequeno" href="licao.php?id=<?= $proxima['id'] ?>">
            <?= $feitas === 0 ? 'Começar' : 'Continuar' ?>: <?= e($proxima['titulo']) ?>
          </a>
        <?php elseif ($total > 0): ?>
          <a class="botao botao--pequeno botao--fantasma" href="trilha.php?t=<?= e($slug) ?>">Trilha concluída ✓ Revisar</a>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
</section>
