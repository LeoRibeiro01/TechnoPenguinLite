<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\ProfessorController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Licao;

/** Edição de uma lição da comunidade (teoria e prática) e das suas questões. ?modulo= cria uma nova. */

$usuario = exigirCadastroCompleto();
$professores = new ProfessorController(conexao());
$idLicao = isset($_GET['id']) || isset($_POST['id']) ? (int) ($_GET['id'] ?? $_POST['id']) : null;
try {
    $licao = $idLicao === null ? null : $professores->licaoDoProfessor($usuario, $idLicao);
    $modulo = $professores->moduloDoProfessor($usuario, (int) ($licao['id_modulo'] ?? $_GET['modulo'] ?? $_POST['modulo'] ?? 0));
} catch (ValidationException $e) {
    definirAviso('⚠ ' . $e->getMessage());
    redirecionar('professor.php');
}
$idModulo = (int) $modulo['id_modulo'];
$voltarCurso = 'professor-curso.php?id=' . $modulo['id_curso'] . '#modulo-' . $idModulo;

if (metodoPost()) {
    validarCsrf();
    $acao = (string) ($_POST['acao'] ?? '');
    $idQuestao = isset($_POST['id_questao']) && $_POST['id_questao'] !== '' ? (int) $_POST['id_questao'] : null;
    try {
        switch ($acao) {
            case 'licao':
                $salva = $professores->salvarLicao($usuario, $idModulo, $idLicao, $_POST);
                definirAviso($idLicao === null ? 'Lição criada. Agora adicione as questões.' : 'Lição salva.');
                redirecionar('professor-licao.php?id=' . $salva['id_licao'] . ($idLicao === null ? '#questoes' : ''));
                // no break
            case 'questao':
                $professores->salvarQuestao($usuario, (int) $idLicao, $idQuestao, $_POST);
                definirAviso($idQuestao === null ? 'Questão adicionada.' : 'Questão salva.');
                break;
            case 'apagar_questao':
                $professores->apagarQuestao($usuario, (int) $idLicao, (int) $idQuestao);
                definirAviso('Questão apagada.');
                break;
            case 'apagar_licao':
                $professores->apagarLicao($usuario, (int) $idLicao);
                definirAviso('Lição apagada.');
                redirecionar($voltarCurso);
                // no break
            default:
                throw new ValidationException(['acao' => 'Ação desconhecida.']);
        }
    } catch (ValidationException $e) {
        definirAviso('⚠ ' . $e->getMessage());
    }
    redirecionar($idLicao === null ? 'professor-licao.php?modulo=' . $idModulo : 'professor-licao.php?id=' . $idLicao . '#questoes');
}

$questoes = $licao === null ? [] : array_map(static function (array $q): array {
    $q['alternativas'] = json_decode((string) $q['alternativas_questao'], true) ?: [];
    return $q;
}, (new Licao(conexao()))->questoes((int) $idLicao));
$letras = ['A', 'B', 'C', 'D'];

$tituloPagina = $licao === null ? 'Nova lição' : 'Editar ' . $licao['titulo_licao'];
$paginaAtual = 'professor.php';
require __DIR__ . '/app/Views/app_inicio.php';

$camposQuestao = static function (?array $questao) use ($letras): void {
    ?>
    <label class="campo campo--largo"><span>Enunciado</span><textarea name="enunciado" rows="2" required maxlength="3000"><?= e($questao['enunciado_questao'] ?? '') ?></textarea></label>
    <fieldset class="alternativas-editor campo--largo">
      <legend>Alternativas (marque a correta; deixe em branco as que não usar)</legend>
      <?php foreach ($letras as $i => $letra): ?>
        <label class="alternativa-editor">
          <input type="radio" name="correta" value="<?= $i ?>" <?= (int) ($questao['gabarito_questao'] ?? 0) === $i ? 'checked' : '' ?> aria-label="Alternativa <?= $letra ?> é a correta">
          <b><?= $letra ?></b>
          <input type="text" name="alternativas[]" maxlength="300" value="<?= e($questao['alternativas'][$i] ?? '') ?>" <?= $i < 2 ? 'required' : '' ?>>
        </label>
      <?php endforeach; ?>
    </fieldset>
    <label class="campo campo--largo"><span>Explicação mostrada na correção</span><input name="explicacao" maxlength="1000" value="<?= e($questao['explicacao_questao'] ?? '') ?>"></label>
    <?php
};
?>
<p class="migalhas"><a href="<?= e($voltarCurso) ?>">← <?= e($modulo['titulo_modulo']) ?></a></p>
<h1 class="app-titulo"><?= $licao === null ? 'Nova lição' : e($licao['titulo_licao']) ?></h1>
<p class="app-subtitulo">Formatação: <code>**negrito**</code>, <code>`código`</code>, listas com "- ", blocos de código entre <code>```</code> e links <code>[texto](https://...)</code>.</p>

<form method="post" action="professor-licao.php" class="cartao form-conta">
  <?= campoCsrf() ?>
  <input type="hidden" name="acao" value="licao">
  <?php if ($licao !== null): ?><input type="hidden" name="id" value="<?= (int) $idLicao ?>"><?php else: ?><input type="hidden" name="modulo" value="<?= $idModulo ?>"><?php endif; ?>
  <label class="campo"><span>Título</span><input name="titulo" value="<?= e($licao['titulo_licao'] ?? '') ?>" required minlength="3" maxlength="100"></label>
  <label class="campo"><span>Teoria</span><textarea name="teoria" rows="12" required minlength="30" class="editor-codigo"><?= e($licao['teoria_licao'] ?? '') ?></textarea></label>
  <label class="campo"><span>Na prática (exemplos e código)</span><textarea name="pratica" rows="10" class="editor-codigo"><?= e($licao['pratica_licao'] ?? '') ?></textarea></label>
  <div class="barra-acoes">
    <?php if ($licao !== null): ?><a class="botao botao--fantasma" href="licao.php?id=<?= (int) $idLicao ?>" target="_blank">Pré-visualizar</a><?php endif; ?>
    <button class="botao" type="submit"><?= $licao === null ? 'Criar lição' : 'Salvar lição' ?></button>
  </div>
</form>

<?php if ($licao !== null): ?>
  <section id="questoes" aria-labelledby="titulo-questoes">
    <h2 class="secao-titulo" id="titulo-questoes">Questões (<?= count($questoes) ?>)</h2>
    <p class="campo__ajuda">O aluno conclui a lição com 60% de acertos. Para o certificado, precisa de 70% de acertos em todas as questões do nível.</p>
    <?php foreach ($questoes as $n => $questao): ?>
      <details class="cartao">
        <summary><strong><?= $n + 1 ?>.</strong> <?= e(mb_strimwidth($questao['enunciado_questao'], 0, 100, '…')) ?></summary>
        <form method="post" action="professor-licao.php" class="form-conta form-duas-colunas">
          <?= campoCsrf() ?>
          <input type="hidden" name="id" value="<?= (int) $idLicao ?>">
          <input type="hidden" name="acao" value="questao">
          <input type="hidden" name="id_questao" value="<?= (int) $questao['id_questao'] ?>">
          <?php $camposQuestao($questao); ?>
          <div class="barra-acoes campo--largo"><button class="botao" type="submit">Salvar questão</button></div>
        </form>
        <form method="post" action="professor-licao.php" onsubmit="return confirm('Apagar a questão?')">
          <?= campoCsrf() ?>
          <input type="hidden" name="id" value="<?= (int) $idLicao ?>">
          <input type="hidden" name="acao" value="apagar_questao">
          <input type="hidden" name="id_questao" value="<?= (int) $questao['id_questao'] ?>">
          <button class="botao botao--pequeno botao--perigo" type="submit">Apagar questão</button>
        </form>
      </details>
    <?php endforeach; ?>

    <form method="post" action="professor-licao.php" class="cartao form-conta form-duas-colunas">
      <?= campoCsrf() ?>
      <input type="hidden" name="id" value="<?= (int) $idLicao ?>">
      <input type="hidden" name="acao" value="questao">
      <h3 class="cartao__titulo campo--largo">+ Nova questão</h3>
      <?php $camposQuestao(null); ?>
      <div class="barra-acoes campo--largo"><button class="botao" type="submit">Adicionar questão</button></div>
    </form>
  </section>

  <div class="barra-acoes barra-acoes--perigo">
    <form method="post" action="professor-licao.php" onsubmit="return confirm('Apagar a lição?')">
      <?= campoCsrf() ?>
      <input type="hidden" name="id" value="<?= (int) $idLicao ?>">
      <input type="hidden" name="acao" value="apagar_licao">
      <button class="botao botao--perigo" type="submit">Apagar lição</button>
    </form>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
