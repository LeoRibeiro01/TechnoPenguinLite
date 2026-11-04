<?php
/** Aba Iglus: iglus do usuário, convites, pesquisa e criação. Espera $usuario. */

use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\IgluConvite;
use TechnoPenguin\Support\Gelo;

$idUsuario = (int) $usuario['id_usuario'];
$iglus = new Iglu(conexao());
$meus = $iglus->doUsuario($idUsuario);
$convites = (new IgluConvite(conexao()))->recebidos($idUsuario);
$termo = trim((string) ($_GET['q'] ?? ''));
$encontrados = $termo === '' ? [] : $iglus->pesquisar($termo);
$idsMeus = array_map('intval', array_column($meus, 'id_iglu'));
$papeis = ['dono' => 'Dono', 'admin' => 'Admin', 'membro' => 'Membro'];

$cartaoIglu = static function (array $iglu, string $extra = ''): string {
    $estado = Gelo::estado((int) $iglu['dias_consecutivos_iglu'], $iglu['ultima_atividade_iglu']);
    return '<a class="cartao-iglu" href="iglu.php?id=' . (int) $iglu['id_iglu'] . '" style="--cor-iglu:' . e($iglu['cor_iglu']) . '">'
        . '<span class="iglu-emblema" aria-hidden="true">' . e($iglu['icone_iglu']) . '</span>'
        . '<div><strong>' . e($iglu['nome_iglu']) . '</strong>'
        . '<small>' . e(plural((int) $iglu['total_membros'], 'membro', 'membros')) . $extra
        . ($iglu['aberto_iglu'] ? '' : ' · 🔒 só convite') . '</small>'
        . '<p>' . e(mb_strimwidth($iglu['descricao_iglu'], 0, 110, '…')) . '</p></div>'
        . '<div class="gelo gelo--mini gelo--' . e($estado) . '" title="Dias de gelo"><span>' . (int) $iglu['dias_consecutivos_iglu'] . '</span></div></a>';
};
?>
<div class="secao-cabecalho secao-cabecalho--topo">
  <h1 class="app-titulo">Iglus</h1>
  <a class="botao" href="iglu.php?novo=1">+ Criar iglu</a>
</div>
<p class="app-subtitulo">Iglus são grupos de estudo: aprendam juntos e mantenham o gelo do iglu firme. Se ninguém estudar por mais de <?= Gelo::DIAS_TOLERANCIA ?> dias, ele derrete!</p>

<?php if ($convites !== []): ?>
  <section class="cartao" aria-label="Convites recebidos">
    <h2 class="cartao__titulo">Convites para você</h2>
    <ul class="convites">
      <?php foreach ($convites as $convite): ?>
        <li>
          <span><strong><?= e($convite['nome_iglu']) ?></strong>
            <?= $convite['arroba_remetente'] !== null ? '· convite de @' . e($convite['arroba_remetente']) : '' ?></span>
          <a class="botao botao--pequeno" href="iglu.php?id=<?= (int) $convite['id_iglu'] ?>&entrar=1">Ver convite</a>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>

<section aria-labelledby="titulo-meus-iglus">
  <h2 class="secao-titulo" id="titulo-meus-iglus">Seus iglus</h2>
  <?php if ($meus === []): ?>
    <p class="vazio">Você ainda não está em nenhum iglu. Crie um ou procure um para entrar!</p>
  <?php endif; ?>
  <div class="grade-iglus">
    <?php foreach ($meus as $iglu): ?>
      <?= $cartaoIglu($iglu, ' · ' . $papeis[$iglu['papel_membro']]) ?>
    <?php endforeach; ?>
  </div>
</section>

<section aria-labelledby="titulo-buscar-iglus">
  <h2 class="secao-titulo" id="titulo-buscar-iglus">Procurar iglus</h2>
  <form class="busca" method="get" action="painel.php" role="search">
    <input type="hidden" name="aba" value="iglus">
    <label class="sr-only" for="busca-iglus">Nome do iglu</label>
    <input id="busca-iglus" type="search" name="q" value="<?= e($termo) ?>" placeholder="Nome ou tema do iglu">
    <button class="botao" type="submit">Pesquisar</button>
  </form>
  <?php if ($termo !== ''): ?>
    <?php if ($encontrados === []): ?>
      <p class="vazio">Nenhum iglu encontrado.</p>
    <?php endif; ?>
    <div class="grade-iglus">
      <?php foreach ($encontrados as $iglu): ?>
        <?= $cartaoIglu($iglu, in_array((int) $iglu['id_iglu'], $idsMeus, true) ? ' · você participa' : '') ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

