<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Support\Catalogo;

/** Questionário inicial guiado pelo Teco: nível, trilhas, objetivo e meta diária. */

$usuario = exigirLogin();
$idUsuario = (int) $usuario['id_usuario'];
$usuarios = new UsuarioController(conexao());
$etapas = UsuarioController::ETAPAS;

$pendente = $usuarios->etapaPendente($usuario);
if ($pendente === null) {
    redirecionar('painel.php');
}
// Pode voltar a uma etapa já respondida, mas não pular as que faltam.
$pedida = $_GET['etapa'] ?? $pendente;
$etapa = in_array($pedida, $etapas, true) && array_search($pedida, $etapas, true) <= array_search($pendente, $etapas, true)
    ? $pedida
    : $pendente;
if ($etapa === 'avatar') {
    redirecionar('personalizar.php');
}

$erro = null;
if (metodoPost()) {
    validarCsrf();
    try {
        match ($etapa) {
            'dados' => $usuarios->salvarDados($idUsuario, [
                'nascimento' => (string) ($_POST['nascimento'] ?? ''),
                'aceite' => !empty($_POST['aceite']),
                'email_responsavel' => (string) ($_POST['email_responsavel'] ?? ''),
                'consentimento_responsavel' => !empty($_POST['consentimento_responsavel']),
            ]),
            'nivel' => $usuarios->salvarNivel($idUsuario, $_POST['nivel'] ?? null),
            'trilhas' => $usuario['nivel_conhecimento_usuario'] === 'zero'
                ? null
                : $usuarios->salvarTrilhas($idUsuario, $_POST['trilhas'] ?? []),
            'objetivo' => $usuarios->salvarObjetivo($idUsuario, $_POST['objetivo'] ?? null),
            'meta' => $usuarios->salvarMeta($idUsuario, $_POST['meta'] ?? null),
        };
        $usuario = usuarioAtual(true);
        if ($etapa === 'dados') {
            redirecionar(destinoInicial($usuario));
        }
        $proxima = $etapas[array_search($etapa, $etapas, true) + 1];
        // Quem começa do zero vê o aviso da trilha de lógica antes de seguir.
        if ($etapa === 'nivel' && $usuario['nivel_conhecimento_usuario'] === 'zero') {
            $proxima = 'trilhas';
        }
        redirecionar($proxima === 'avatar' ? 'personalizar.php' : 'boas-vindas.php?etapa=' . $proxima);
    } catch (ValidationException $e) {
        $erro = $e->getMessage();
    }
}

$nivel = $usuario['nivel_conhecimento_usuario'];
$doZero = $nivel === 'zero';
$escolhidas = $usuarios->trilhas($idUsuario);
$primeiroNome = explode(' ', trim($usuario['nome']))[0];
$falas = [
    'dados' => ["Olá, {$primeiroNome}! Que bom te ver de novo 🐧", 'Atualizamos a plataforma e precisamos de duas coisinhas: sua data de nascimento (o chat dos iglus é só para maiores de 18) e o seu aceite dos novos Termos de Uso e da Política de Privacidade.'],
    'nivel' => ["Olá, {$primeiroNome}! Eu sou o Teco 🐧", 'Antes de começarmos, me conta: o quanto você sabe sobre programação?'],
    'trilhas' => $doZero
        ? ['Ótimo, vamos começar do zero!', 'Todo programador começou pela lógica de programação. É por ela que você vai começar: algoritmos, variáveis, decisões e repetições. Depois do primeiro módulo, você poderá explorar outras linguagens.']
        : ['Muito bem!', 'O que você gostaria de aprender? Pode escolher quantas linguagens e assuntos quiser. Eles vão aparecer no seu painel.'],
    'objetivo' => ['Anotado!', 'Você quer aprender programação para quê?'],
    'meta' => ['Quase lá!', 'Qual a sua meta de tempo de estudos por dia? Quanto mais tempo, mais pontos você ganha em cada questão.'],
];
[$titulo, $fala] = $falas[$etapa];
$indice = array_search($etapa, $etapas, true) - 1;

$tituloPagina = 'Boas-vindas';
$paginaAtual = 'boas-vindas.php';
$semMenu = true;
require __DIR__ . '/app/Views/app_inicio.php';
?>
<?php if ($etapa !== 'dados'): ?>
<ol class="etapas" aria-label="Primeiros passos">
  <?php foreach (['Conhecimento', 'Trilhas', 'Objetivo', 'Meta diária', 'Seu pinguim'] as $i => $rotulo): ?>
    <li <?= $i < $indice ? 'class="etapas__feita"' : '' ?> <?= $i === $indice ? 'aria-current="step"' : '' ?>>
      <span><?= $i < $indice ? '✓' : $i + 1 ?></span> <?= e($rotulo) ?>
    </li>
  <?php endforeach; ?>
</ol>
<?php endif; ?>

<div class="quiz">
  <aside class="quiz__teco">
    <?= teco() ?>
  </aside>

  <section class="quiz__conteudo" aria-labelledby="quiz-titulo">
    <div class="balao">
      <h1 id="quiz-titulo"><?= e($titulo) ?></h1>
      <p><?= e($fala) ?></p>
    </div>

    <?php if ($erro !== null): ?>
      <p class="app-erro-msg" role="alert"><?= e($erro) ?></p>
    <?php endif; ?>

    <form method="post" action="boas-vindas.php?etapa=<?= e($etapa) ?>" class="quiz__form">
      <?= campoCsrf() ?>

      <?php if ($etapa === 'dados'): ?>
        <div class="dados-conta">
          <label class="campo">
            <span>Data de nascimento</span>
            <input type="date" name="nascimento" required max="<?= date('Y-m-d') ?>" value="<?= e($usuario['data_nascimento'] ?? '') ?>">
          </label>
          <div class="cartao aviso-responsavel" id="responsavel" hidden>
            <p>Para menores de <?= Catalogo::IDADE_CRIANCA ?> anos, a LGPD exige a autorização de um responsável.</p>
            <label class="campo"><span>Email do responsável</span><input type="email" name="email_responsavel" maxlength="50"></label>
            <label class="caixa"><input type="checkbox" name="consentimento_responsavel" value="1"> Meu responsável autorizou o uso da plataforma.</label>
          </div>
          <label class="caixa">
            <input type="checkbox" name="aceite" value="1" required>
            Li e aceito os <a href="termos.php" target="_blank">Termos de Uso</a> e a <a href="privacidade.php" target="_blank">Política de Privacidade</a>.
          </label>
        </div>
        <script>
          (() => {
            const campo = document.querySelector('input[name="nascimento"]');
            const caixa = document.querySelector('#responsavel');
            const conferir = () => {
              if (!campo.value) return;
              const data = new Date(campo.value + 'T00:00');
              const hoje = new Date();
              let idade = hoje.getFullYear() - data.getFullYear();
              if (hoje < new Date(hoje.getFullYear(), data.getMonth(), data.getDate())) idade--;
              caixa.hidden = idade >= <?= Catalogo::IDADE_CRIANCA ?>;
            };
            campo.addEventListener('change', conferir);
            conferir();
          })();
        </script>

      <?php elseif ($etapa === 'nivel'): ?>
        <fieldset class="opcoes">
          <legend class="sr-only">O quanto você sabe sobre programação?</legend>
          <?php foreach (Catalogo::NIVEIS as $valor => $rotulo): ?>
            <label class="opcao">
              <input type="radio" name="nivel" value="<?= e($valor) ?>" <?= $nivel === $valor ? 'checked' : '' ?> required>
              <span><?= e($rotulo) ?></span>
            </label>
          <?php endforeach; ?>
        </fieldset>

      <?php elseif ($etapa === 'trilhas' && $doZero): ?>
        <div class="trilha-destaque">
          <?= iconeTrilha(Catalogo::TRILHA_INICIAL, 'icone-trilha--grande') ?>
          <div>
            <strong><?= e(Catalogo::trilha(Catalogo::TRILHA_INICIAL)['titulo']) ?></strong>
            <p><?= e(Catalogo::trilha(Catalogo::TRILHA_INICIAL)['descricao']) ?></p>
          </div>
        </div>

      <?php elseif ($etapa === 'trilhas'): ?>
        <?php foreach (['linguagem' => 'Linguagens', 'assunto' => 'Assuntos'] as $tipo => $grupo): ?>
          <fieldset class="escolha-trilhas">
            <legend><?= e($grupo) ?></legend>
            <div class="escolha-trilhas__grade">
              <?php foreach (Catalogo::trilhasPorTipo($tipo) as $slug => $trilha): ?>
                <label class="chip-trilha">
                  <input type="checkbox" name="trilhas[]" value="<?= e($slug) ?>" <?= in_array($slug, $escolhidas, true) ? 'checked' : '' ?>>
                  <?= iconeTrilha($slug) ?>
                  <span><?= e($trilha['titulo']) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>
        <?php endforeach; ?>
        <label class="chip-trilha chip-trilha--largo">
          <input type="checkbox" name="trilhas[]" value="<?= e(Catalogo::TRILHA_INICIAL) ?>" <?= in_array(Catalogo::TRILHA_INICIAL, $escolhidas, true) ? 'checked' : '' ?>>
          <?= iconeTrilha(Catalogo::TRILHA_INICIAL) ?>
          <span>Também quero revisar Lógica de Programação</span>
        </label>

      <?php elseif ($etapa === 'objetivo'): ?>
        <fieldset class="opcoes opcoes--grade">
          <legend class="sr-only">Você quer aprender programação para quê?</legend>
          <?php foreach (Catalogo::OBJETIVOS as $valor => [$rotulo, $emoji]): ?>
            <label class="opcao">
              <input type="radio" name="objetivo" value="<?= e($valor) ?>" <?= $usuario['objetivo_usuario'] === $valor ? 'checked' : '' ?> required>
              <span><b aria-hidden="true"><?= $emoji ?></b> <?= e($rotulo) ?></span>
            </label>
          <?php endforeach; ?>
        </fieldset>

      <?php else: ?>
        <fieldset class="opcoes opcoes--grade">
          <legend class="sr-only">Meta de tempo de estudos por dia</legend>
          <?php foreach (Catalogo::METAS as $minutos => [$rotulo, $multiplicador]): ?>
            <label class="opcao">
              <input type="radio" name="meta" value="<?= $minutos ?>" <?= (int) $usuario['meta_diaria_usuario'] === $minutos ? 'checked' : '' ?> required>
              <span>
                <?= e($rotulo) ?>
                <small class="opcao__bonus"><?= $multiplicador > 1 ? '+' . round(($multiplicador - 1) * 100) . '% pontos' : 'pontos normais' ?></small>
              </span>
            </label>
          <?php endforeach; ?>
        </fieldset>
      <?php endif; ?>

      <div class="quiz__acoes">
        <?php if ($indice > 0): ?>
          <a class="botao botao--fantasma" href="boas-vindas.php?etapa=<?= e($etapas[$indice]) ?>">Voltar</a>
        <?php endif; ?>
        <button class="botao" type="submit">Continuar</button>
      </div>
    </form>
  </section>
</div>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
