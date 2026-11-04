<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Controllers\ProfessorController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\Modulo;
use TechnoPenguin\Support\Catalogo;

/** Edição de um curso da comunidade: apresentação, publicação e módulos (por dificuldade). */

$usuario = exigirCadastroCompleto();
$professores = new ProfessorController(conexao());
$idCurso = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
try {
    $curso = $professores->cursoDoProfessor($usuario, $idCurso);
} catch (ValidationException $e) {
    definirAviso('⚠ ' . $e->getMessage());
    redirecionar('professor.php');
}
$voltar = 'professor-curso.php?id=' . $idCurso;

if (metodoPost()) {
    validarCsrf();
    $acao = (string) ($_POST['acao'] ?? '');
    $idModulo = isset($_POST['id_modulo']) && $_POST['id_modulo'] !== '' ? (int) $_POST['id_modulo'] : null;
    try {
        switch ($acao) {
            case 'curso':
                $professores->salvarCurso($usuario, $idCurso, $_POST);
                definirAviso(!empty($_POST['publicado']) ? 'Curso salvo e publicado na Comunidade!' : 'Curso salvo como rascunho.');
                break;
            case 'modulo':
                $modulo = $professores->salvarModulo($usuario, $idCurso, $idModulo, $_POST);
                definirAviso($idModulo === null ? 'Módulo criado. Agora adicione lições.' : 'Módulo salvo.');
                $voltar .= '#modulo-' . $modulo['id_modulo'];
                break;
            case 'apagar_modulo':
                $professores->apagarModulo($usuario, (int) $idModulo);
                definirAviso('Módulo apagado.');
                break;
            case 'apagar_curso':
                $professores->apagarCurso($usuario, $idCurso);
                definirAviso('Curso apagado.');
                redirecionar('professor.php');
                // no break
            default:
                throw new ValidationException(['acao' => 'Ação desconhecida.']);
        }
    } catch (ValidationException $e) {
        definirAviso('⚠ ' . $e->getMessage());
    }
    redirecionar($voltar);
}

$objetivos = json_decode((string) ($curso['objetivos_curso'] ?? ''), true) ?: [];
$modulos = (new Curso(conexao()))->modulos($idCurso);
$licoesPorModulo = [];
foreach ($modulos as $modulo) {
    $licoesPorModulo[(int) $modulo['id_modulo']] = (new Modulo(conexao()))->licoes((int) $modulo['id_modulo']);
}

$tituloPagina = 'Editar ' . $curso['titulo_curso'];
$paginaAtual = 'professor.php';
require __DIR__ . '/app/Views/app_inicio.php';

$camposModulo = static function (?array $modulo): void {
    ?>
    <label class="campo"><span>Título do módulo</span><input name="titulo" value="<?= e($modulo['titulo_modulo'] ?? '') ?>" required minlength="3" maxlength="100"></label>
    <label class="campo"><span>Dificuldade</span>
      <select name="dificuldade">
        <?php foreach (Catalogo::DIFICULDADES as $valor => [$rotulo]): ?>
          <option value="<?= e($valor) ?>" <?= ($modulo['dificuldade_modulo'] ?? 'iniciante') === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label class="campo campo--largo"><span>Descrição curta</span><input name="descricao" maxlength="400" value="<?= e($modulo['descricao_modulo'] ?? '') ?>"></label>
    <label class="campo campo--largo"><span>Projeto para fixar o módulo</span><textarea name="projeto" rows="3" maxlength="4000"><?= e($modulo['projeto_modulo'] ?? '') ?></textarea></label>
    <label class="campo campo--largo"><span>Aprofundamento (texto extra mostrado no fim da teoria)</span><textarea name="aprofundamento" rows="4" maxlength="8000"><?= e($modulo['aprofundamento_modulo'] ?? '') ?></textarea></label>
    <label class="campo campo--largo"><span>Referências (uma por linha, começando com "- ")</span><textarea name="referencias" rows="3" maxlength="3000" placeholder="- Livro — Autor (ano)&#10;- [Documentação oficial](https://...)"><?= e($modulo['referencias_modulo'] ?? '') ?></textarea></label>
    <?php
};
?>
<p class="migalhas"><a href="professor.php">← Painel do professor</a> · <a href="trilha.php?t=<?= e($curso['linguagem_curso']) ?>">Ver como aluno</a></p>
<h1 class="app-titulo"><?= iconeTrilha($curso['linguagem_curso']) ?> <?= e($curso['titulo_curso']) ?></h1>
<p class="app-subtitulo">
  <?= $curso['publicado_curso'] ? '<span class="selo selo--novo">Publicado na Comunidade</span>' : '<span class="selo">Rascunho: só você vê</span>' ?>
  · Formatação: parágrafos separados por linha em branco, <code>**negrito**</code>, <code>`código`</code>, listas com "- ", blocos com <code>```</code> e links <code>[texto](https://...)</code>.
</p>

<details class="cartao" open>
  <summary class="cartao__titulo">Apresentação do curso</summary>
  <form method="post" action="professor-curso.php" class="form-conta form-duas-colunas">
    <?= campoCsrf() ?>
    <input type="hidden" name="id" value="<?= $idCurso ?>">
    <input type="hidden" name="acao" value="curso">
    <label class="campo"><span>Nome</span><input name="titulo" value="<?= e($curso['titulo_curso']) ?>" required maxlength="100"></label>
    <label class="campo"><span>Tipo</span>
      <select name="tipo">
        <?php foreach (ProfessorController::TIPOS as $valor => $rotulo): ?>
          <option value="<?= e($valor) ?>" <?= $curso['tipo_curso'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label class="campo"><span>Sigla do ícone</span><input name="sigla" value="<?= e($curso['sigla_curso']) ?>" required maxlength="6"></label>
    <label class="campo"><span>Cor</span><input type="color" name="cor" value="<?= e($curso['cor_curso'] ?: '#2897e6') ?>"></label>
    <label class="campo campo--largo"><span>Descrição curta</span><textarea name="descricao" rows="2" required maxlength="400"><?= e($curso['descricao_curso']) ?></textarea></label>
    <label class="campo campo--largo"><span>Como surgiu (história)</span><textarea name="historia" rows="5" maxlength="6000"><?= e($curso['historia_curso'] ?? '') ?></textarea></label>
    <label class="campo campo--largo"><span>Como é usado hoje</span><textarea name="uso_atual" rows="4" maxlength="4000"><?= e($curso['uso_atual_curso'] ?? '') ?></textarea></label>
    <?php foreach (Catalogo::DIFICULDADES as $nivel => [$rotulo]): ?>
      <label class="campo"><span>O que se aprende no nível <?= e($rotulo) ?> (um item por linha)</span>
        <textarea name="objetivos_<?= e($nivel) ?>" rows="4"><?= e(implode("\n", $objetivos[$nivel] ?? [])) ?></textarea></label>
    <?php endforeach; ?>
    <label class="campo campo--largo"><span>Para estudar mais (livros e links)</span><textarea name="referencias" rows="4" maxlength="4000"><?= e($curso['referencias_curso'] ?? '') ?></textarea></label>
    <label class="caixa campo--largo"><input type="checkbox" name="publicado" value="1" <?= $curso['publicado_curso'] ? 'checked' : '' ?>> Publicar na aba Comunidade</label>
    <div class="barra-acoes campo--largo"><button class="botao" type="submit">Salvar apresentação</button></div>
  </form>
</details>

<h2 class="secao-titulo">Módulos</h2>
<?php if ($modulos === []): ?>
  <p class="vazio">Nenhum módulo ainda. Crie o primeiro abaixo: comece pelo nível Iniciante.</p>
<?php endif; ?>
<?php foreach ($modulos as $modulo):
    $idModulo = (int) $modulo['id_modulo'];
?>
  <section class="cartao modulo-editor" id="modulo-<?= $idModulo ?>">
    <header class="modulo-editor__topo">
      <div>
        <span class="selo nivel-selo--<?= e($modulo['dificuldade_modulo']) ?>"><?= e(CertificadoController::rotulo($modulo['dificuldade_modulo'])) ?></span>
        <h3><?= e($modulo['titulo_modulo']) ?></h3>
      </div>
      <a class="botao botao--pequeno" href="professor-licao.php?modulo=<?= $idModulo ?>">+ Lição</a>
    </header>
    <ol class="licoes-editor">
      <?php foreach ($licoesPorModulo[$idModulo] as $licao): ?>
        <li><a href="professor-licao.php?id=<?= (int) $licao['id_licao'] ?>"><?= e($licao['titulo_licao']) ?></a></li>
      <?php endforeach; ?>
      <?php if ($licoesPorModulo[$idModulo] === []): ?><li class="vazio">Sem lições ainda.</li><?php endif; ?>
    </ol>
    <details>
      <summary>Editar módulo</summary>
      <form method="post" action="professor-curso.php" class="form-conta form-duas-colunas">
        <?= campoCsrf() ?>
        <input type="hidden" name="id" value="<?= $idCurso ?>">
        <input type="hidden" name="acao" value="modulo">
        <input type="hidden" name="id_modulo" value="<?= $idModulo ?>">
        <?php $camposModulo($modulo); ?>
        <div class="barra-acoes campo--largo">
          <button class="botao" type="submit">Salvar módulo</button>
        </div>
      </form>
      <form method="post" action="professor-curso.php" onsubmit="return confirm('Apagar o módulo e suas lições?')">
        <?= campoCsrf() ?>
        <input type="hidden" name="id" value="<?= $idCurso ?>">
        <input type="hidden" name="acao" value="apagar_modulo">
        <input type="hidden" name="id_modulo" value="<?= $idModulo ?>">
        <button class="botao botao--pequeno botao--perigo" type="submit">Apagar módulo</button>
      </form>
    </details>
  </section>
<?php endforeach; ?>

<section class="cartao" aria-labelledby="titulo-novo-modulo">
  <h2 class="cartao__titulo" id="titulo-novo-modulo">+ Novo módulo</h2>
  <form method="post" action="professor-curso.php" class="form-conta form-duas-colunas">
    <?= campoCsrf() ?>
    <input type="hidden" name="id" value="<?= $idCurso ?>">
    <input type="hidden" name="acao" value="modulo">
    <?php $camposModulo(null); ?>
    <div class="barra-acoes campo--largo"><button class="botao" type="submit">Criar módulo</button></div>
  </form>
</section>

<div class="barra-acoes barra-acoes--perigo">
  <form method="post" action="professor-curso.php" onsubmit="return confirm('Apagar o curso inteiro?')">
    <?= campoCsrf() ?>
    <input type="hidden" name="id" value="<?= $idCurso ?>">
    <input type="hidden" name="acao" value="apagar_curso">
    <button class="botao botao--perigo" type="submit">Apagar curso</button>
  </form>
</div>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
