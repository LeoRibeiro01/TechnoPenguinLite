<?php
/** Aba Comunidade: linguagens e assuntos criados por professores. Espera $usuario. */

use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\ProfessorController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Support\Trilhas;

$cursos = Trilhas::comunidade(conexao());
$minhas = (new UsuarioController(conexao()))->trilhas((int) $usuario['id_usuario']);
$liberado = (new AprendizadoController(conexao()))->podeExplorar($usuario);
?>
<div class="secao-cabecalho secao-cabecalho--topo">
  <div>
    <h1 class="app-titulo">Comunidade</h1>
    <p class="app-subtitulo">Linguagens e assuntos criados por professores da plataforma, com perguntas e respostas da comunidade.</p>
  </div>
  <?php if (ProfessorController::ehProfessor($usuario)): ?>
    <a class="botao" href="professor.php">+ Criar curso</a>
  <?php else: ?>
    <a class="botao botao--fantasma" href="conta.php#professor">Quero ensinar</a>
  <?php endif; ?>
</div>

<?php if (!$liberado): ?>
  <div class="cartao bloqueio">
    <?= teco('avatar--rosto avatar--balao') ?>
    <p><strong>Primeiro, a base!</strong> Conclua o primeiro módulo de Lógica de Programação para começar cursos da comunidade.</p>
  </div>
<?php endif; ?>

<?php if ($cursos === []): ?>
  <p class="vazio">Ainda não há cursos publicados pela comunidade. Que tal ser o primeiro professor? 🐧</p>
<?php endif; ?>

<div class="grade-explorar">
  <?php foreach ($cursos as $curso): ?>
    <article class="cartao-comunidade" style="--cor-trilha: <?= e($curso['cor']) ?>">
      <header>
        <?= iconeTrilha($curso['slug']) ?>
        <div>
          <h2><a href="trilha.php?t=<?= e($curso['slug']) ?>"><?= e($curso['titulo']) ?></a></h2>
          <small><?= e(ProfessorController::TIPOS[$curso['tipo']] ?? 'Curso') ?> · por
            <?php if ($curso['arroba_professor']): ?>
              <a href="perfil.php?u=<?= e(rawurlencode($curso['arroba_professor'])) ?>">@<?= e($curso['arroba_professor']) ?></a>
            <?php else: ?>
              professor removido
            <?php endif; ?>
          </small>
        </div>
      </header>
      <p><?= e($curso['descricao']) ?></p>
      <p class="cartao-comunidade__meta">
        <?= e(plural($curso['total_licoes'], 'lição', 'lições')) ?> · <?= e(plural($curso['total_alunos'], 'aluno', 'alunos')) ?>
        <?= in_array($curso['slug'], $minhas, true) ? ' · <strong>no seu perfil</strong>' : '' ?>
      </p>
      <a class="botao botao--pequeno" href="trilha.php?t=<?= e($curso['slug']) ?>">Ver curso</a>
    </article>
  <?php endforeach; ?>
</div>
