<?php
/** Aba Amigos: pesquisa por @, pedidos recebidos/enviados e amigos. Espera $usuario. */

use TechnoPenguin\Controllers\AmizadeController;
use TechnoPenguin\Models\Amizade;
use TechnoPenguin\Models\Iglu;

$idUsuario = (int) $usuario['id_usuario'];
$amizades = new Amizade(conexao());
$termo = trim((string) ($_GET['q'] ?? ''));
$resultados = $termo === '' ? [] : (new AmizadeController(conexao()))->pesquisar($termo, $idUsuario);
$recebidos = $amizades->pedidosRecebidos($idUsuario);
$enviados = $amizades->pedidosEnviados($idUsuario);
$amigos = $amizades->amigos($idUsuario);
$meusIglus = (new Iglu(conexao()))->doUsuario($idUsuario);

/** Formulário de uma ação de amizade com um único botão. */
$botao = static function (string $acao, string $campo, int $id, string $rotulo, string $classe = 'botao botao--pequeno') use ($termo): string {
    return '<form method="post" action="painel.php?aba=amigos">' . campoCsrf()
        . '<input type="hidden" name="acao" value="' . e($acao) . '">'
        . '<input type="hidden" name="' . e($campo) . '" value="' . $id . '">'
        . '<input type="hidden" name="q" value="' . e($termo) . '">'
        . '<button class="' . e($classe) . '" type="submit">' . e($rotulo) . '</button></form>';
};
$pessoa = static function (array $u): string {
    return avatarDe($u, 'avatar--rosto avatar--lista')
        . '<div class="pessoa__info"><a class="pessoa__nome" href="' . e(linkPerfil($u)) . '"><strong>' . e($u['nome']) . '</strong></a>'
        . '<span>@' . e($u['arroba']) . ' · Nível ' . nivelXp((int) $u['xp_usuario']) . '</span></div>'
        . '<span class="online ' . (estaOnline($u) ? 'online--sim' : '') . '" title="' . (estaOnline($u) ? 'Online agora' : 'Offline') . '"></span>';
};
?>
<h1 class="app-titulo">Amigos</h1>
<p class="app-subtitulo">Encontre outros pinguins pelo @ e estudem juntos.</p>

<form class="busca" method="get" action="painel.php" role="search">
  <input type="hidden" name="aba" value="amigos">
  <label class="sr-only" for="busca-amigos">Pesquisar por @</label>
  <input id="busca-amigos" type="search" name="q" value="<?= e($termo) ?>" placeholder="Pesquisar @usuario" minlength="2" autocapitalize="none">
  <button class="botao" type="submit">Pesquisar</button>
</form>

<?php if ($termo !== ''): ?>
  <section class="cartao" aria-label="Resultados da pesquisa">
    <h2 class="cartao__titulo">Resultados para “<?= e($termo) ?>”</h2>
    <?php if ($resultados === []): ?>
      <p class="vazio">Nenhum pinguim encontrado com esse @.</p>
    <?php endif; ?>
    <ul class="pessoas">
      <?php foreach ($resultados as $u): ?>
        <li class="pessoa">
          <?= $pessoa($u) ?>
          <div class="pessoa__acoes">
            <?= match ($u['relacao']) {
                'amigo' => '<span class="selo">Amigos ✓</span>',
                'enviado' => '<span class="selo">Pedido enviado</span>',
                'recebido' => $botao('amizade_pedir', 'id_usuario', (int) $u['id_usuario'], 'Aceitar pedido'),
                default => $botao('amizade_pedir', 'id_usuario', (int) $u['id_usuario'], '+ Adicionar'),
            } ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>

<?php if ($recebidos !== []): ?>
  <section class="cartao" aria-label="Pedidos recebidos">
    <h2 class="cartao__titulo">Pedidos de amizade</h2>
    <ul class="pessoas">
      <?php foreach ($recebidos as $u): ?>
        <li class="pessoa">
          <?= $pessoa($u) ?>
          <div class="pessoa__acoes">
            <?= $botao('amizade_aceitar', 'id_amizade', (int) $u['id_amizade'], 'Aceitar') ?>
            <?= $botao('amizade_recusar', 'id_amizade', (int) $u['id_amizade'], 'Recusar', 'botao botao--pequeno botao--fantasma') ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>

<section class="cartao" aria-label="Seus amigos">
  <h2 class="cartao__titulo">Seus amigos (<?= count($amigos) ?>)</h2>
  <?php if ($amigos === []): ?>
    <p class="vazio">Você ainda não adicionou nenhum amigo. Pesquise pelo @ de alguém acima!</p>
  <?php endif; ?>
  <ul class="pessoas">
    <?php foreach ($amigos as $u): ?>
      <li class="pessoa">
        <?= $pessoa($u) ?>
        <div class="pessoa__acoes">
          <?php if ($meusIglus !== []): ?>
            <form method="post" action="iglu.php" class="convite-rapido">
              <?= campoCsrf() ?>
              <input type="hidden" name="acao" value="convidar">
              <input type="hidden" name="arroba" value="<?= e($u['arroba']) ?>">
              <input type="hidden" name="voltar" value="painel.php?aba=amigos">
              <label class="sr-only" for="iglu-<?= (int) $u['id_usuario'] ?>">Iglu</label>
              <select id="iglu-<?= (int) $u['id_usuario'] ?>" name="id">
                <?php foreach ($meusIglus as $iglu): ?>
                  <option value="<?= (int) $iglu['id_iglu'] ?>"><?= e($iglu['nome_iglu']) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="botao botao--pequeno" type="submit">Convidar</button>
            </form>
          <?php endif; ?>
          <?= $botao('amizade_remover', 'id_amizade', (int) $u['id_amizade'], 'Remover', 'botao botao--pequeno botao--fantasma') ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</section>

<?php if ($enviados !== []): ?>
  <section class="cartao" aria-label="Pedidos enviados">
    <h2 class="cartao__titulo">Pedidos enviados</h2>
    <ul class="pessoas">
      <?php foreach ($enviados as $u): ?>
        <li class="pessoa">
          <?= $pessoa($u) ?>
          <div class="pessoa__acoes">
            <?= $botao('amizade_remover', 'id_amizade', (int) $u['id_amizade'], 'Cancelar', 'botao botao--pequeno botao--fantasma') ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>
