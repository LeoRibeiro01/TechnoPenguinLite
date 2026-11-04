<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Support\Texto;

/**
 * Lição em três partes (teoria, prática e questões). Qualquer lição pode ser lida; as questões
 * só abrem em ordem. Refazer guarda a melhor tentativa.
 */

$usuario = exigirCadastroCompleto();
$aprendizado = new AprendizadoController(conexao());
$idLicao = (int) ($_GET['id'] ?? 0);

try {
    $licao = $aprendizado->licao($idLicao, $usuario);
} catch (RuntimeException $e) {
    definirAviso('⚠ ' . $e->getMessage());
    redirecionar('painel.php');
}

$resultado = null;
if (metodoPost()) {
    validarCsrf();
    $respostas = $_POST['resposta'] ?? [];
    try {
        $resultado = $aprendizado->responder($usuario, $idLicao, is_array($respostas) ? $respostas : []);
        $licao = $aprendizado->licao($idLicao, usuarioAtual(true));
    } catch (RuntimeException $e) {
        definirAviso('⚠ ' . $e->getMessage());
        redirecionar('licao.php?id=' . $idLicao);
    }
}
$trilha = trilhaInfo($licao['trilha']);
$modulo = $licao['modulo'];
$proxima = $resultado !== null && $resultado['aprovado'] ? $aprendizado->proximaLicao($licao['trilha'], usuarioAtual(true)) : null;
$letras = ['A', 'B', 'C', 'D', 'E'];
$totalQuestoes = count($licao['questoes']);

$tituloPagina = $licao['titulo_licao'];
$paginaAtual = 'painel.php';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<p class="migalhas">
  <a href="trilha.php?t=<?= e($licao['trilha']) ?>"><?= e($trilha['titulo']) ?></a>
  › <a href="modulo.php?id=<?= (int) $licao['id_modulo'] ?>"><?= e($licao['titulo_modulo']) ?></a>
  · <?= e(CertificadoController::rotulo($modulo['dificuldade'])) ?> · <?= $modulo['percentual'] ?>% do módulo
</p>

<?php if ($resultado !== null): ?>
  <section class="cartao resultado resultado--<?= $resultado['aprovado'] ? 'ok' : 'tentar' ?>" aria-live="polite">
    <?= teco('avatar--rosto avatar--balao') ?>
    <div>
      <h1 class="app-titulo">
        <?= $resultado['aprovado'] ? 'Mandou bem!' : 'Quase lá!' ?>
        Você acertou <?= $resultado['acertos'] ?> de <?= $resultado['total'] ?>.
      </h1>
      <?php if ($resultado['primeira_vez']): ?>
        <p class="resultado__ganhos">
          <span>+<?= $resultado['xp'] ?> XP</span>
          <span>+<?= $resultado['pontos'] ?> ❄ pontos</span>
        </p>
      <?php elseif ($resultado['melhorou']): ?>
        <p>📈 Novo recorde nesta lição: <?= $resultado['melhor'] ?>/<?= $resultado['total'] ?>. Seu aproveitamento para o certificado subiu!</p>
      <?php elseif ($resultado['aprovado']): ?>
        <p>Lição revisada! Seu melhor resultado continua <?= $resultado['melhor'] ?>/<?= $resultado['total'] ?>. A revisão também conta para o seu gelo de hoje.</p>
      <?php else: ?>
        <p>Você precisa acertar pelo menos <?= (int) ceil($resultado['total'] * AprendizadoController::APROVACAO) ?> questões para concluir. Releia as explicações e tente de novo!</p>
      <?php endif; ?>
      <?php if ($resultado['gelo'] !== null && $resultado['gelo']['ligou']): ?>
        <p class="resultado__gelo">🧊 <strong>Seu Gelo foi ligado!</strong> Estude pelo menos uma lição por dia para ele crescer. Mais de 3 dias sem lições e ele derrete.</p>
      <?php elseif ($resultado['gelo'] !== null): ?>
        <p class="resultado__gelo">🧊 Gelo: <?= e(plural($resultado['gelo']['dias'], 'dia', 'dias')) ?>.</p>
      <?php endif; ?>
      <div class="barra-acoes barra-acoes--esquerda">
        <?php if ($proxima !== null): ?>
          <a class="botao" href="licao.php?id=<?= $proxima['id'] ?>">Próxima lição: <?= e($proxima['titulo']) ?></a>
        <?php elseif ($resultado['aprovado']): ?>
          <a class="botao" href="trilha.php?t=<?= e($licao['trilha']) ?>#certificados">Ver trilha e certificados</a>
        <?php endif; ?>
        <a class="botao botao--fantasma" href="licao.php?id=<?= $idLicao ?>#questoes">Refazer</a>
      </div>
    </div>
  </section>

  <section class="cartao" aria-labelledby="titulo-correcao">
    <h2 class="cartao__titulo" id="titulo-correcao">Correção</h2>
    <ol class="questoes">
      <?php foreach ($resultado['correcao'] as $questao): ?>
        <li class="questao questao--<?= $questao['acertou'] ? 'certa' : 'errada' ?>">
          <div class="questao__enunciado"><?= Texto::html($questao['enunciado_questao']) ?></div>
          <ul class="alternativas alternativas--corrigidas">
            <?php foreach ($questao['alternativas'] as $i => $alternativa): ?>
              <li class="<?= $i === (int) $questao['gabarito_questao'] ? 'correta' : ($i === $questao['resposta'] ? 'marcada' : '') ?>">
                <b><?= $letras[$i] ?></b> <?= e($alternativa) ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <?php if ($questao['explicacao_questao']): ?>
            <p class="questao__explicacao"><?= $questao['acertou'] ? '✓' : '✗' ?> <?= e($questao['explicacao_questao']) ?></p>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </section>
<?php else: ?>
  <article class="licao">
    <header>
      <h1 class="app-titulo"><?= e($licao['titulo_licao']) ?></h1>
      <?php if ($licao['estado'] === 'concluida'): ?>
        <span class="selo selo--novo">Concluída ✓ · melhor resultado <?= $licao['melhor_acertos'] ?>/<?= $totalQuestoes ?></span>
      <?php elseif ($licao['estado'] === 'bloqueada'): ?>
        <span class="selo">🔒 Leitura liberada; questões depois da lição anterior</span>
      <?php endif; ?>
    </header>

    <nav class="licao__passos" aria-label="Partes da lição">
      <a href="#teoria">1. Teoria</a>
      <a href="#pratica">2. Na prática</a>
      <?php if ($modulo['aprofundamento']): ?><a href="#aprofundamento">3. Aprofundamento</a><?php endif; ?>
      <a href="#questoes"><?= $modulo['aprofundamento'] ? '4' : '3' ?>. Questões</a>
    </nav>

    <section class="cartao conteudo" id="teoria" aria-labelledby="titulo-teoria">
      <h2 id="titulo-teoria">📘 Teoria</h2>
      <?= Texto::html((string) $licao['teoria_licao']) ?>
    </section>

    <?php if (trim((string) $licao['pratica_licao']) !== ''): ?>
      <section class="cartao conteudo" id="pratica" aria-labelledby="titulo-pratica">
        <h2 id="titulo-pratica">💻 Na prática</h2>
        <?= Texto::html((string) $licao['pratica_licao']) ?>
      </section>
    <?php endif; ?>

    <?php if ($modulo['aprofundamento']): ?>
      <details class="cartao conteudo aprofundamento" id="aprofundamento">
        <summary><h2>🔎 Aprofundamento do módulo e referências</h2></summary>
        <?= Texto::html($modulo['aprofundamento']) ?>
        <?php if ($modulo['referencias']): ?>
          <div class="referencias"><h3>Referências</h3><?= Texto::html($modulo['referencias']) ?></div>
        <?php endif; ?>
      </details>
    <?php endif; ?>

    <section class="cartao" id="questoes" aria-labelledby="titulo-questoes">
      <h2 id="titulo-questoes">✏️ Questões</h2>
      <?php if (!$licao['na_trilha']): ?>
        <p class="aviso-bloqueio">Para responder e ganhar pontos, adicione <strong><?= e($trilha['titulo']) ?></strong> ao seu perfil.</p>
        <a class="botao" href="trilha.php?t=<?= e($licao['trilha']) ?>">Ir para a apresentação da trilha</a>
      <?php elseif ($licao['estado'] === 'bloqueada'): ?>
        <p class="aviso-bloqueio">🔒 As questões abrem quando você concluir a lição anterior<?= $licao['anterior'] ? ': <a href="licao.php?id=' . $licao['anterior']['id'] . '">' . e($licao['anterior']['titulo']) . '</a>' : '' ?>.</p>
      <?php elseif ($totalQuestoes === 0): ?>
        <p class="vazio">Esta lição ainda não tem questões.</p>
      <?php else: ?>
        <?php if ($licao['tentativas'] > 0): ?>
          <p class="campo__ajuda">Tentativas: <?= (int) $licao['tentativas'] ?> · melhor resultado <?= $licao['melhor_acertos'] ?>/<?= $totalQuestoes ?>. Refazer só pode melhorar seu aproveitamento.</p>
        <?php endif; ?>
        <form method="post" action="licao.php?id=<?= $idLicao ?>">
          <?= campoCsrf() ?>
          <ol class="questoes">
            <?php foreach ($licao['questoes'] as $questao): ?>
              <li class="questao">
                <fieldset>
                  <legend class="questao__enunciado"><?= Texto::html($questao['enunciado_questao']) ?></legend>
                  <div class="alternativas">
                    <?php foreach ($questao['alternativas'] as $i => $alternativa): ?>
                      <label class="alternativa">
                        <input type="radio" name="resposta[<?= (int) $questao['id_questao'] ?>]" value="<?= $i ?>" required>
                        <b><?= $letras[$i] ?></b> <span><?= e($alternativa) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </fieldset>
              </li>
            <?php endforeach; ?>
          </ol>
          <div class="barra-acoes">
            <button class="botao" type="submit">Enviar respostas</button>
          </div>
        </form>
      <?php endif; ?>
    </section>
  </article>
<?php endif; ?>

<nav class="navegacao-par" aria-label="Outras lições">
  <?php if ($licao['anterior']): ?>
    <a class="botao botao--fantasma" href="licao.php?id=<?= $licao['anterior']['id'] ?>">← <?= e($licao['anterior']['titulo']) ?></a>
  <?php else: ?><span></span><?php endif; ?>
  <?php if ($licao['seguinte']): ?>
    <a class="botao botao--fantasma" href="licao.php?id=<?= $licao['seguinte']['id'] ?>"><?= e($licao['seguinte']['titulo']) ?> →</a>
  <?php endif; ?>
</nav>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
