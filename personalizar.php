<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Support\Catalogo;

$usuario = exigirLogin();
$usuarios = new UsuarioController(conexao());
$pendente = $usuarios->etapaPendente($usuario);
if ($pendente !== null && $pendente !== 'avatar') {
    redirecionar('boas-vindas.php');
}
$primeiraVez = $pendente === 'avatar';
$cores = coresAvatar($usuario);
$erro = null;

if (metodoPost()) {
    validarCsrf();
    $cores = [];
    foreach (['corpo', 'barriga', 'detalhe', 'fundo'] as $parte) {
        $cores[$parte] = (string) ($_POST[$parte] ?? '');
    }
    try {
        $usuarios->salvarAvatar((int) $usuario['id_usuario'], $cores);
        definirAviso($primeiraVez
            ? 'Seu pinguim está pronto! Faça sua primeira lição para ligar o Gelo 🧊'
            : 'Seu pinguim foi atualizado.');
        redirecionar('painel.php');
    } catch (ValidationException) {
        $erro = 'Escolha uma cor válida para cada parte do pinguim.';
        $cores = coresAvatar($usuario);
    }
}

$partes = [
    'corpo' => ['Pele', 'As penas da cabeça, das costas e das asas.'],
    'barriga' => ['Barriga e rosto', 'A parte clara da frente do pinguim.'],
    'detalhe' => ['Bico e patas', 'O toque de cor nas pontas.'],
    'fundo' => ['Fundo', 'A cor atrás do seu pinguim no perfil.'],
];

$tituloPagina = $primeiraVez ? 'Crie o seu pinguim' : 'Meu pinguim';
$paginaAtual = 'personalizar.php';
$semMenu = $primeiraVez;
require __DIR__ . '/app/Views/app_inicio.php';
?>
<?php if ($primeiraVez): ?>
  <ol class="etapas" aria-label="Primeiros passos">
    <?php foreach (['Conhecimento', 'Trilhas', 'Objetivo', 'Meta diária'] as $i => $rotulo): ?>
      <li class="etapas__feita"><span>✓</span> <?= e($rotulo) ?></li>
    <?php endforeach; ?>
    <li aria-current="step"><span>5</span> Seu pinguim</li>
  </ol>
<?php endif; ?>

<div class="personalizar">
  <section class="personalizar__preview" aria-label="Pré-visualização">
    <?= avatarDe($usuario, 'avatar--corpo avatar--palco') ?>
    <p class="personalizar__nome">@<?= e($usuario['arroba']) ?></p>
    <?php if (!$primeiraVez): ?>
      <a class="botao botao--fantasma" href="vestuario.php">Trocar roupas</a>
    <?php endif; ?>
  </section>

  <section class="personalizar__painel">
    <?php if ($primeiraVez): ?>
      <div class="balao balao--compacto">
        <h1 class="app-titulo">Por último, personalize seu pinguim!</h1>
        <p>Escolha as cores que mais combinam com você. Dá para mudar quando quiser.</p>
      </div>
    <?php else: ?>
      <h1 class="app-titulo">Personalize seu pinguim</h1>
      <p class="app-subtitulo">Escolha as cores que mais combinam com você.</p>
    <?php endif; ?>

    <?php if ($erro !== null): ?>
      <p class="app-erro-msg" role="alert"><?= e($erro) ?></p>
    <?php endif; ?>

    <form action="personalizar.php" method="post" id="form-pinguim">
      <?= campoCsrf() ?>
      <?php foreach ($partes as $parte => [$rotulo, $ajuda]):
          $paleta = Catalogo::PALETAS_AVATAR[$parte];
          $atual = strtolower($cores[$parte]);
          $personalizada = !isset($paleta[$atual]);
      ?>
        <fieldset class="cores" data-parte="<?= e($parte) ?>">
          <legend><?= e($rotulo) ?> <small><?= e($ajuda) ?></small></legend>
          <div class="cores__lista">
            <?php foreach ($paleta as $hex => $nomeCor): ?>
              <label class="cor" title="<?= e($nomeCor) ?>">
                <input type="radio" name="<?= e($parte) ?>" value="<?= e($hex) ?>" <?= $hex === $atual ? 'checked' : '' ?>>
                <span class="cor__amostra" style="--cor: <?= e($hex) ?>"></span>
                <span class="sr-only"><?= e($nomeCor) ?></span>
              </label>
            <?php endforeach; ?>
            <label class="cor cor--livre" title="Outra cor">
              <input type="radio" name="<?= e($parte) ?>" value="<?= e($atual) ?>" data-livre <?= $personalizada ? 'checked' : '' ?>>
              <span class="cor__amostra" style="--cor: <?= e($atual) ?>">
                <input type="color" value="<?= e($atual) ?>" aria-label="Escolher outra cor para <?= e(mb_strtolower($rotulo)) ?>">
              </span>
            </label>
          </div>
        </fieldset>
      <?php endforeach; ?>

      <div class="personalizar__acoes">
        <button class="botao botao--fantasma" type="button" id="aleatorio">Surpreenda-me</button>
        <button class="botao botao--fantasma" type="button" id="original">Cores originais</button>
        <?php if (!$primeiraVez): ?>
          <a class="botao botao--fantasma" href="painel.php">Cancelar</a>
        <?php endif; ?>
        <button class="botao" type="submit"><?= $primeiraVez ? 'Concluir' : 'Salvar alterações' ?></button>
      </div>
    </form>
  </section>
</div>

<script>
  (() => {
    const avatar = document.querySelector('.avatar--palco');
    const foto = avatar.querySelector('img[data-pinguim]');
    const form = document.querySelector('#form-pinguim');
    const padrao = <?= json_encode(Catalogo::AVATAR_PADRAO) ?>;
    const valor = (parte) => form.querySelector(`input[name="${parte}"]:checked`)?.value;

    const atualizar = () => {
      avatar.style.setProperty('--fundo', valor('fundo'));
      foto.dataset.pinguim = ['corpo', 'barriga', 'detalhe'].map(valor).join(',');
      window.Pinguim?.pintar(foto);
    };

    const marcar = (grupo, cor) => {
      const opcao = [...grupo.querySelectorAll('input[type="radio"]:not([data-livre])')].find((i) => i.value === cor);
      if (opcao) {
        opcao.checked = true;
        return;
      }
      const livre = grupo.querySelector('[data-livre]');
      livre.value = cor;
      livre.checked = true;
      livre.nextElementSibling.style.setProperty('--cor', cor);
    };

    form.querySelectorAll('.cores').forEach((grupo) => {
      const livre = grupo.querySelector('[data-livre]');
      const seletor = grupo.querySelector('input[type="color"]');
      grupo.addEventListener('change', (evento) => {
        if (evento.target.type === 'radio') atualizar();
      });
      seletor.addEventListener('input', () => {
        livre.value = seletor.value;
        livre.checked = true;
        livre.nextElementSibling.style.setProperty('--cor', seletor.value);
        atualizar();
      });
      seletor.addEventListener('click', () => {
        livre.checked = true;
        atualizar();
      });
    });

    document.querySelector('#aleatorio').addEventListener('click', () => {
      form.querySelectorAll('.cores').forEach((grupo) => {
        const opcoes = [...grupo.querySelectorAll('input[type="radio"]:not([data-livre])')];
        opcoes[Math.floor(Math.random() * opcoes.length)].checked = true;
      });
      atualizar();
    });

    document.querySelector('#original').addEventListener('click', () => {
      form.querySelectorAll('.cores').forEach((grupo) => marcar(grupo, padrao[grupo.dataset.parte]));
      atualizar();
    });
  })();
</script>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
