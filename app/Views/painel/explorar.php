<?php
/** Aba Explorar: linguagens e assuntos que o usuário ainda não escolheu. Espera $usuario. */

use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Support\Catalogo;

$escolhidas = (new UsuarioController(conexao()))->trilhas((int) $usuario['id_usuario']);
$liberado = (new AprendizadoController(conexao()))->podeExplorar($usuario);
$grupos = ['base' => 'Fundamentos', 'linguagem' => 'Linguagens', 'assunto' => 'Assuntos'];
$restantes = array_diff_key(Catalogo::TRILHAS, array_flip($escolhidas));
?>
<h1 class="app-titulo">Explorar</h1>
<p class="app-subtitulo">Todas as linguagens e assuntos que você ainda não está estudando. Marque os que quiser e adicione ao seu perfil.</p>

<?php if (!$liberado): ?>
  <div class="cartao bloqueio">
    <?= teco('avatar--rosto avatar--balao') ?>
    <p><strong>Primeiro, a base!</strong> Como você está começando do zero, conclua o primeiro módulo de
      <a href="trilha.php?t=<?= e(Catalogo::TRILHA_INICIAL) ?>">Lógica de Programação</a> para liberar as outras trilhas.</p>
  </div>
<?php endif; ?>

<?php if ($restantes === []): ?>
  <p class="vazio">Você já adicionou todas as trilhas disponíveis. Que fome de aprender! 🐧</p>
<?php else: ?>
  <form method="post" action="painel.php?aba=explorar">
    <?= campoCsrf() ?>
    <input type="hidden" name="acao" value="adicionar_trilhas">
    <?php foreach ($grupos as $tipo => $rotulo):
        $lista = array_intersect_key(Catalogo::trilhasPorTipo($tipo), $restantes);
        if ($lista === []) {
            continue;
        }
    ?>
      <fieldset class="escolha-trilhas" <?= $liberado ? '' : 'disabled' ?>>
        <legend><?= e($rotulo) ?></legend>
        <div class="grade-explorar">
          <?php foreach ($lista as $slug => $trilha): ?>
            <label class="cartao-explorar" style="--cor-trilha: <?= e($trilha['cor']) ?>">
              <input type="checkbox" name="trilhas[]" value="<?= e($slug) ?>">
              <?= iconeTrilha($slug) ?>
              <span>
                <strong><?= e($trilha['titulo']) ?></strong>
                <small><?= e($trilha['descricao']) ?></small>
              </span>
              <a class="cartao-explorar__ver" href="trilha.php?t=<?= e($slug) ?>">Ver apresentação e módulos</a>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>
    <?php endforeach; ?>
    <?php if ($liberado): ?>
      <div class="barra-acoes">
        <button class="botao" type="submit">Adicionar ao meu perfil</button>
      </div>
    <?php endif; ?>
  </form>
<?php endif; ?>
