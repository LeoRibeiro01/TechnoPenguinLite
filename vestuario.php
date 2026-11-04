<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\VestuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Support\Vestuario;

/** Vestuário: vestir as peças que o usuário tem e trocar pontos pelas exclusivas. */

$usuario = exigirCadastroCompleto();
$idUsuario = (int) $usuario['id_usuario'];
$vestuario = new VestuarioController(conexao());
$erro = null;

if (metodoPost()) {
    validarCsrf();
    $slug = (string) ($_POST['item'] ?? '');
    $camada = (string) ($_POST['camada'] ?? '');
    try {
        if (($_POST['acao'] ?? '') === 'comprar') {
            $item = $vestuario->comprar($idUsuario, $slug);
            $vestuario->vestir($idUsuario, $item['camada'], $slug);
            definirAviso("Você comprou {$item['nome']}! Já está vestindo.");
        } else {
            $vestuario->vestir($idUsuario, $camada, $slug);
            definirAviso($slug === '' ? 'Peça removida.' : 'Visual atualizado!');
        }
        redirecionar('vestuario.php#' . ($camada ?: (Vestuario::item($slug)['camada'] ?? '')));
    } catch (ValidationException $e) {
        $erro = $e->getMessage();
    }
}

$disponiveis = $vestuario->disponiveis($idUsuario);

$tituloPagina = 'Vestuário';
$paginaAtual = 'vestuario.php';
require __DIR__ . '/app/Views/app_inicio.php';
$usuario = $contaLayout;
$vestindo = vestimentaAvatar($usuario);
$pontos = (int) $usuario['pontos_usuario'];
?>
<div class="vestuario">
  <section class="vestuario__perfil" aria-label="Seu pinguim">
    <h1 class="app-titulo">Vestuário</h1>
    <?= avatarDe($usuario, 'avatar--corpo avatar--palco') ?>
    <p class="vestuario__saldo">❄ <strong><?= $pontos ?></strong> pontos</p>
    <p class="campo__ajuda">Ganhe pontos respondendo questões. Quanto maior a sua meta diária, mais pontos por acerto.</p>
    <a class="botao botao--fantasma" href="personalizar.php">Mudar cores e fundo</a>
  </section>

  <div class="vestuario__categorias">
    <?php if ($erro !== null): ?>
      <p class="app-erro-msg" role="alert"><?= e($erro) ?></p>
    <?php endif; ?>

    <?php foreach (array_reverse(Vestuario::CAMADAS, true) as $camada => $rotulo): ?>
      <section class="cartao categoria" id="<?= e($camada) ?>" aria-labelledby="titulo-<?= e($camada) ?>">
        <h2 class="categoria__titulo" id="titulo-<?= e($camada) ?>">Categoria: <?= e($rotulo) ?></h2>
        <div class="grade-itens">
          <form method="post" action="vestuario.php">
            <?= campoCsrf() ?>
            <input type="hidden" name="camada" value="<?= e($camada) ?>">
            <input type="hidden" name="item" value="">
            <button class="item-card <?= $vestindo[$camada] === null ? 'item-card--vestido' : '' ?>" type="submit">
              <span class="slot-img slot-img--vazio" aria-hidden="true">∅</span>
              <span class="item-card__nome">Nenhum</span>
            </button>
          </form>
          <?php foreach (Vestuario::ITENS as $slug => $dados):
              $item = Vestuario::item($slug);
              if ($item['camada'] !== $camada) {
                  continue;
              }
              $tem = in_array($slug, $disponiveis, true);
              $vestido = $vestindo[$camada] === $slug;
          ?>
            <form method="post" action="vestuario.php">
              <?= campoCsrf() ?>
              <input type="hidden" name="camada" value="<?= e($camada) ?>">
              <input type="hidden" name="item" value="<?= e($slug) ?>">
              <input type="hidden" name="acao" value="<?= $tem ? 'vestir' : 'comprar' ?>">
              <button class="item-card <?= $vestido ? 'item-card--vestido' : '' ?> <?= $tem ? '' : 'item-card--loja' ?>" type="submit"
                <?= !$tem && $pontos < $item['preco'] ? 'disabled title="Faltam ' . ($item['preco'] - $pontos) . ' pontos"' : '' ?>
                data-camada="<?= e($camada) ?>" data-imagem="<?= e($item['imagem']) ?>" data-filtro="<?= e($item['filtro']) ?>">
                <span class="slot-img slot-img--<?= e($camada) ?>">
                  <img src="<?= e($item['imagem']) ?>" alt="" style="filter: <?= e($item['filtro'] ?: 'none') ?>" loading="lazy">
                </span>
                <span class="item-card__nome"><?= e($item['nome']) ?></span>
                <span class="item-card__preco">
                  <?= $vestido ? 'Vestindo' : ($tem ? ($item['preco'] === 0 ? 'Grátis' : 'Seu') : '❄ ' . $item['preco']) ?>
                </span>
              </button>
            </form>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
</div>

<script>
  // Passar o mouse numa peça mostra como ela fica no pinguim antes de vestir ou comprar.
  (() => {
    const avatar = document.querySelector('.avatar--palco');
    const camadas = {};
    avatar.querySelectorAll('[data-camada]').forEach((img) => {
      camadas[img.dataset.camada] = { img, src: img.getAttribute('src'), filtro: img.style.filter, oculto: img.hidden };
    });
    const restaurar = (camada) => {
      const c = camadas[camada];
      c.img.src = c.src;
      c.img.style.filter = c.filtro;
      c.img.hidden = c.oculto;
    };
    document.querySelectorAll('.item-card[data-imagem]').forEach((botao) => {
      const mostrar = () => {
        const c = camadas[botao.dataset.camada];
        c.img.src = botao.dataset.imagem;
        c.img.style.filter = botao.dataset.filtro || 'none';
        c.img.hidden = false;
      };
      botao.addEventListener('mouseenter', mostrar);
      botao.addEventListener('focus', mostrar);
      botao.addEventListener('mouseleave', () => restaurar(botao.dataset.camada));
      botao.addEventListener('blur', () => restaurar(botao.dataset.camada));
    });
  })();
</script>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
