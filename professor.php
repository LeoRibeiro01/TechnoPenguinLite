<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\PerguntaController;
use TechnoPenguin\Controllers\ProfessorController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\Pergunta;

/** Painel do professor: cursos criados, perguntas pendentes e criação de um novo curso. */

$usuario = exigirCadastroCompleto();
if (!ProfessorController::ehProfessor($usuario)) {
    definirAviso('⚠ Ative o perfil de professor em Minha conta para criar cursos.');
    redirecionar('conta.php#professor');
}
$professores = new ProfessorController(conexao());
$erro = null;
$valores = ['titulo' => '', 'descricao' => '', 'tipo' => 'linguagem', 'sigla' => '', 'cor' => '#2897e6'];

if (metodoPost()) {
    validarCsrf();
    $valores = array_intersect_key($_POST, $valores) + $valores;
    try {
        $curso = $professores->criarCurso($usuario, $_POST);
        definirAviso('Curso criado como rascunho. Agora monte os módulos e as lições.');
        redirecionar('professor-curso.php?id=' . $curso['id_curso']);
    } catch (ValidationException $e) {
        $erro = $e->getMessage();
    }
}

$cursos = (new Curso(conexao()))->doProfessor((int) $usuario['id_usuario']);
$pendentes = (new Pergunta(conexao()))->pendentesDoProfessor((int) $usuario['id_usuario']);

$tituloPagina = 'Painel do professor';
$paginaAtual = 'professor.php';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<h1 class="app-titulo">Painel do professor</h1>
<p class="app-subtitulo">Crie linguagens e assuntos para a aba Comunidade, monte módulos por dificuldade e responda às perguntas dos alunos.</p>

<?php if ($pendentes !== []): ?>
  <section class="cartao" aria-labelledby="titulo-pendentes">
    <h2 class="cartao__titulo" id="titulo-pendentes">💬 Perguntas esperando resposta (<?= count($pendentes) ?>)</h2>
    <ul class="lista-perguntas lista-perguntas--compacta">
      <?php foreach ($pendentes as $pergunta): ?>
        <li>
          <a href="<?= e(PerguntaController::link($pergunta['linguagem_curso'], (int) $pergunta['id_pergunta'])) ?>">
            <strong>@<?= e($pergunta['arroba']) ?></strong> em <?= e($pergunta['titulo_curso']) ?>:
            “<?= e(mb_strimwidth($pergunta['texto_pergunta'], 0, 90, '…')) ?>”
          </a>
          <?= (bool) $pergunta['publica_pergunta'] ? '' : '<span class="selo">🔒 privada</span>' ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>

<section aria-labelledby="titulo-meus-cursos">
  <h2 class="secao-titulo" id="titulo-meus-cursos">Meus cursos</h2>
  <?php if ($cursos === []): ?>
    <p class="vazio">Você ainda não criou nenhum curso.</p>
  <?php endif; ?>
  <div class="grade-explorar">
    <?php foreach ($cursos as $curso): ?>
      <article class="cartao-comunidade" style="--cor-trilha: <?= e($curso['cor_curso']) ?>">
        <header>
          <?= iconeTrilha($curso['linguagem_curso']) ?>
          <div>
            <h3><?= e($curso['titulo_curso']) ?></h3>
            <small><?= $curso['publicado_curso'] ? '<span class="selo selo--novo">Publicado</span>' : '<span class="selo">Rascunho</span>' ?></small>
          </div>
        </header>
        <p class="cartao-comunidade__meta">
          <?= e(plural((int) $curso['total_licoes'], 'lição', 'lições')) ?> · <?= e(plural((int) $curso['total_alunos'], 'aluno', 'alunos')) ?>
          <?= (int) $curso['perguntas_pendentes'] > 0 ? ' · <strong>' . (int) $curso['perguntas_pendentes'] . ' perguntas novas</strong>' : '' ?>
        </p>
        <div class="barra-acoes barra-acoes--esquerda">
          <a class="botao botao--pequeno" href="professor-curso.php?id=<?= (int) $curso['id_curso'] ?>">Editar</a>
          <a class="botao botao--pequeno botao--fantasma" href="trilha.php?t=<?= e($curso['linguagem_curso']) ?>">Ver como aluno</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="cartao" aria-labelledby="titulo-novo-curso">
  <h2 class="cartao__titulo" id="titulo-novo-curso">+ Criar um curso</h2>
  <?php if ($erro !== null): ?><p class="app-erro-msg" role="alert"><?= e($erro) ?></p><?php endif; ?>
  <form method="post" action="professor.php" class="form-conta form-duas-colunas">
    <?= campoCsrf() ?>
    <label class="campo"><span>Nome da linguagem ou assunto</span><input name="titulo" value="<?= e($valores['titulo']) ?>" required minlength="3" maxlength="100" placeholder="Ex.: Lua para jogos"></label>
    <label class="campo"><span>Tipo</span>
      <select name="tipo">
        <?php foreach (ProfessorController::TIPOS as $valor => $rotulo): ?>
          <option value="<?= e($valor) ?>" <?= $valores['tipo'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label class="campo campo--largo"><span>Descrição curta</span><textarea name="descricao" rows="2" required minlength="10" maxlength="400" placeholder="Em uma ou duas frases: o que o aluno vai aprender?"><?= e($valores['descricao']) ?></textarea></label>
    <label class="campo"><span>Sigla do ícone (até 6 letras)</span><input name="sigla" value="<?= e($valores['sigla']) ?>" required maxlength="6" placeholder="Lua"></label>
    <label class="campo"><span>Cor do ícone</span><input type="color" name="cor" value="<?= e($valores['cor']) ?>"></label>
    <div class="barra-acoes campo--largo"><button class="botao" type="submit">Criar curso</button></div>
  </form>
</section>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
