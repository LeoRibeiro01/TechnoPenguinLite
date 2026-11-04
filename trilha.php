<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\AprendizadoController;
use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Controllers\PerguntaController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Pergunta;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Texto;

/**
 * Apresentação de uma linguagem ou assunto: história, uso atual, o que se aprende em cada
 * dificuldade, módulos com progresso, certificados, referências e (nos cursos da comunidade)
 * as perguntas da comunidade.
 */

$usuario = exigirCadastroCompleto();
$idUsuario = (int) $usuario['id_usuario'];
$slug = (string) ($_GET['t'] ?? '');
$trilha = trilhaInfo($slug);
if ($trilha === null || (!$trilha['publicado'] && $trilha['id_professor'] !== $idUsuario)) {
    definirAviso('⚠ Esta trilha não está disponível.');
    redirecionar('painel.php');
}
$aprendizado = new AprendizadoController(conexao());
$usuarios = new UsuarioController(conexao());
$certificados = new CertificadoController(conexao());
$ehProfessorDoCurso = $trilha['comunidade'] && $trilha['id_professor'] === $idUsuario;
$voltar = 'trilha.php?t=' . rawurlencode($slug);

if (metodoPost()) {
    validarCsrf();
    try {
        switch ((string) ($_POST['acao'] ?? '')) {
            case 'adicionar':
                if (!$aprendizado->podeExplorar($usuario) && $slug !== Catalogo::TRILHA_INICIAL) {
                    throw new ValidationException(['trilha' => 'Conclua o primeiro módulo de Lógica de Programação para liberar outras trilhas.']);
                }
                $usuarios->adicionarTrilhas($idUsuario, [$slug]);
                $proxima = $aprendizado->proximaLicao($slug, $usuario);
                definirAviso("{$trilha['titulo']} foi adicionada ao seu perfil. Bons estudos!");
                redirecionar($proxima !== null ? 'licao.php?id=' . $proxima['id'] : $voltar);
                // no break
            case 'emitir':
                $certificado = $certificados->emitir($usuario, $slug, (string) ($_POST['nivel'] ?? ''));
                redirecionar('certificado.php?codigo=' . $certificado['codigo_certificado']);
                // no break
            case 'perguntar':
                $pergunta = (new PerguntaController(conexao()))->perguntar(
                    $usuario,
                    $slug,
                    (string) ($_POST['texto'] ?? ''),
                    ($_POST['visibilidade'] ?? '') !== 'professor'
                );
                definirAviso('Pergunta enviada! Você será avisado quando o professor responder.');
                redirecionar(PerguntaController::link($slug, (int) $pergunta['id_pergunta']));
                // no break
            case 'responder':
                $idPergunta = (int) ($_POST['id_pergunta'] ?? 0);
                (new PerguntaController(conexao()))->responder($usuario, $idPergunta, (string) ($_POST['resposta'] ?? ''));
                definirAviso('Resposta publicada. O aluno foi notificado.');
                redirecionar(PerguntaController::link($slug, $idPergunta));
                // no break
            default:
                throw new ValidationException(['acao' => 'Ação desconhecida.']);
        }
    } catch (ValidationException $e) {
        definirAviso('⚠ ' . $e->getMessage());
        redirecionar($voltar);
    }
}

$mapa = $aprendizado->mapa($slug, $usuario);
$curso = $mapa['curso'] ?? null;
$minha = in_array($slug, $usuarios->trilhas($idUsuario), true);
$situacao = $mapa !== null ? $certificados->situacao($slug, $usuario, $mapa) : [];
$objetivos = json_decode((string) ($curso['objetivos_curso'] ?? ''), true) ?: [];
$perguntas = $trilha['comunidade'] && $curso !== null
    ? (new Pergunta(conexao()))->visiveis((int) $curso['id_curso'], $idUsuario, $ehProfessorDoCurso)
    : [];
$destaque = (int) ($_GET['pergunta'] ?? 0);
$horasTotais = $situacao[CertificadoController::COMPLETO]['horas'] ?? 0;
$porcentagem = static fn (float $v): string => (string) (int) floor($v * 100) . '%';

$tituloPagina = $trilha['titulo'];
$paginaAtual = $trilha['comunidade'] ? 'painel.php?aba=comunidade' : 'painel.php';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<p class="migalhas"><a href="<?= $trilha['comunidade'] ? 'painel.php?aba=comunidade' : 'painel.php' ?>">← <?= $trilha['comunidade'] ? 'Comunidade' : 'Minhas trilhas' ?></a></p>

<header class="apresentacao" style="--cor-trilha: <?= e($trilha['cor']) ?>">
  <div class="apresentacao__titulo">
    <?= iconeTrilha($slug, 'icone-trilha--grande') ?>
    <div>
      <p class="apresentacao__tipo">
        <?php if ($trilha['comunidade']): ?>
          Comunidade · criado por
          <?= $trilha['arroba_professor'] ? '<a href="perfil.php?u=' . e(rawurlencode($trilha['arroba_professor'])) . '">@' . e($trilha['arroba_professor']) . '</a>' : 'professor removido' ?>
          <?= !$trilha['publicado'] ? ' · <strong>rascunho (só você vê)</strong>' : '' ?>
        <?php else: ?>
          <?= ['base' => 'Fundamentos', 'linguagem' => 'Linguagem', 'assunto' => 'Assunto'][$trilha['tipo']] ?? 'Trilha' ?>
        <?php endif; ?>
      </p>
      <h1 class="app-titulo"><?= e($trilha['titulo']) ?></h1>
      <p class="app-subtitulo"><?= e($trilha['descricao']) ?></p>
    </div>
  </div>

  <?php if ($mapa !== null && $mapa['total_licoes'] > 0): ?>
    <div class="apresentacao__progresso">
      <div class="anel" style="--pct: <?= $mapa['percentual'] ?>" role="img" aria-label="<?= $mapa['percentual'] ?>% concluído">
        <span><?= $mapa['percentual'] ?>%</span>
      </div>
      <dl class="mini-stats">
        <div><dt>Lições</dt><dd><?= $mapa['feitas'] ?>/<?= $mapa['total_licoes'] ?></dd></div>
        <div><dt>Acertos</dt><dd><?= $mapa['aproveitamento_feitas'] === null ? '—' : $porcentagem($mapa['aproveitamento_feitas']) ?></dd></div>
        <div><dt>Carga</dt><dd>~<?= $horasTotais ?>h</dd></div>
      </dl>
      <?php if (!$minha): ?>
        <form method="post" action="<?= e($voltar) ?>">
          <?= campoCsrf() ?>
          <input type="hidden" name="acao" value="adicionar">
          <button class="botao" type="submit">Adicionar ao perfil e começar</button>
        </form>
      <?php elseif ($mapa['proxima'] !== null): ?>
        <a class="botao" href="licao.php?id=<?= $mapa['proxima']['id'] ?>">
          <?= $mapa['feitas'] === 0 ? 'Começar' : 'Continuar de onde parei' ?> →
        </a>
        <small class="apresentacao__proxima">Próxima: <?= e($mapa['proxima']['titulo']) ?></small>
      <?php else: ?>
        <span class="selo selo--novo">Trilha concluída 🎉</span>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</header>

<?php if ($ehProfessorDoCurso): ?>
  <p class="app-aviso">Você é o professor deste curso. <a href="professor-curso.php?id=<?= (int) $curso['id_curso'] ?>">Editar conteúdo</a></p>
<?php endif; ?>

<?php if (!empty($curso['historia_curso']) || !empty($curso['uso_atual_curso'])): ?>
  <div class="apresentacao__textos">
    <?php if (!empty($curso['historia_curso'])): ?>
      <section class="cartao conteudo" aria-labelledby="titulo-historia">
        <h2 id="titulo-historia">📜 Como surgiu</h2>
        <?= Texto::html($curso['historia_curso']) ?>
      </section>
    <?php endif; ?>
    <?php if (!empty($curso['uso_atual_curso'])): ?>
      <section class="cartao conteudo" aria-labelledby="titulo-hoje">
        <h2 id="titulo-hoje">🌍 Como é usado hoje</h2>
        <?= Texto::html($curso['uso_atual_curso']) ?>
      </section>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($mapa !== null && $mapa['niveis'] !== []): ?>
  <section aria-labelledby="titulo-aprender">
    <h2 class="secao-titulo" id="titulo-aprender">O que você vai aprender</h2>
    <div class="niveis-aprender">
      <?php foreach ($mapa['niveis'] as $nivel => $modulos):
          $estado = $situacao[$nivel];
      ?>
        <article class="cartao nivel-aprender nivel-aprender--<?= e($nivel) ?>">
          <header>
            <h3><?= e(CertificadoController::rotulo($nivel)) ?></h3>
            <span class="selo">~<?= $estado['horas'] ?>h</span>
          </header>
          <?php if (!empty($objetivos[$nivel])): ?>
            <ul class="lista-check">
              <?php foreach ($objetivos[$nivel] as $objetivo): ?>
                <li><?= e($objetivo) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <ul class="lista-check">
              <?php foreach ($modulos as $modulo): ?>
                <li><?= e($modulo['titulo']) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="barra"><span style="width: <?= $estado['percentual'] ?>%"></span></div>
          <small><?= $estado['percentual'] ?>% concluído</small>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section aria-labelledby="titulo-modulos">
    <h2 class="secao-titulo" id="titulo-modulos">Módulos</h2>
    <?php foreach ($mapa['niveis'] as $nivel => $modulos): ?>
      <h3 class="nivel-titulo nivel-titulo--<?= e($nivel) ?>"><?= e(CertificadoController::rotulo($nivel)) ?></h3>
      <div class="grade-modulos">
        <?php foreach ($modulos as $modulo): ?>
          <a class="cartao-modulo <?= $modulo['percentual'] === 100 ? 'cartao-modulo--completo' : '' ?>" href="modulo.php?id=<?= $modulo['id'] ?>">
            <div class="cartao-modulo__topo">
              <strong><?= e($modulo['titulo']) ?></strong>
              <span class="cartao-modulo__pct"><?= $modulo['percentual'] ?>%</span>
            </div>
            <?php if ($modulo['descricao']): ?>
              <p><?= e($modulo['descricao']) ?></p>
            <?php endif; ?>
            <div class="barra"><span style="width: <?= $modulo['percentual'] ?>%"></span></div>
            <small>
              <?= $modulo['feitas'] ?>/<?= $modulo['total_licoes'] ?> lições
              <?= $modulo['aproveitamento_feitas'] !== null ? ' · ' . $porcentagem($modulo['aproveitamento_feitas']) . ' de acertos' : '' ?>
              <?= $modulo['percentual'] === 100 ? '' : ' · faltam ' . (100 - $modulo['percentual']) . '%' ?>
            </small>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </section>

  <section class="cartao" aria-labelledby="titulo-certificados" id="certificados">
    <h2 class="cartao__titulo" id="titulo-certificados">📜 Certificados</h2>
    <p class="campo__ajuda">
      Cada nível tem seu certificado, e a trilha completa tem um próprio. Para emitir: <strong>100% das lições</strong> do nível
      concluídas e <strong>pelo menos <?= Catalogo::APROVEITAMENTO_CERTIFICADO * 100 ?>% de acertos</strong> em todas as questões dele.
      Refaça lições para melhorar seus acertos: vale sempre a sua melhor tentativa.
    </p>
    <ul class="certificados-lista">
      <?php foreach ($situacao as $nivel => $estado): ?>
        <li class="certificado-item <?= $estado['certificado'] ? 'certificado-item--emitido' : '' ?>">
          <div>
            <strong><?= e($trilha['titulo']) ?> <?= e($estado['rotulo']) ?></strong>
            <small><?= $estado['horas'] ?> horas · <?= $estado['percentual'] ?>% concluído · <?= $porcentagem($estado['aproveitamento']) ?> de acertos</small>
          </div>
          <?php if ($estado['certificado']): ?>
            <a class="botao botao--pequeno" href="certificado.php?codigo=<?= e($estado['certificado']['codigo_certificado']) ?>">Ver certificado</a>
          <?php elseif ($estado['pode_emitir'] && $minha): ?>
            <form method="post" action="<?= e($voltar) ?>">
              <?= campoCsrf() ?>
              <input type="hidden" name="acao" value="emitir">
              <input type="hidden" name="nivel" value="<?= e($nivel) ?>">
              <button class="botao botao--pequeno" type="submit">Emitir certificado</button>
            </form>
          <?php else: ?>
            <span class="selo">🔒 <?= $estado['percentual'] < 100 ? 'Faltam ' . (100 - $estado['percentual']) . '% das lições' : 'Precisa de ' . (Catalogo::APROVEITAMENTO_CERTIFICADO * 100) . '% de acertos' ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php else: ?>
  <p class="vazio">Os módulos desta trilha estão a caminho.</p>
<?php endif; ?>

<?php if (!empty($curso['referencias_curso'])): ?>
  <section class="cartao conteudo referencias" aria-labelledby="titulo-referencias">
    <h2 id="titulo-referencias">📚 Para estudar mais</h2>
    <?= Texto::html($curso['referencias_curso']) ?>
  </section>
<?php endif; ?>

<?php if ($trilha['comunidade'] && $curso !== null): ?>
  <section class="perguntas" id="perguntas" aria-labelledby="titulo-perguntas">
    <h2 class="secao-titulo" id="titulo-perguntas">💬 Perguntas da comunidade</h2>
    <?php if (!$ehProfessorDoCurso): ?>
      <form method="post" action="<?= e($voltar) ?>#perguntas" class="cartao form-pergunta">
        <?= campoCsrf() ?>
        <input type="hidden" name="acao" value="perguntar">
        <label class="campo">
          <span>Sua pergunta</span>
          <textarea name="texto" rows="3" minlength="10" maxlength="1000" required placeholder="Ficou com dúvida em alguma lição? Pergunte ao professor."></textarea>
        </label>
        <fieldset class="opcoes-inline">
          <legend>Quem pode ver</legend>
          <label><input type="radio" name="visibilidade" value="todos" checked> Todos (a resposta ajuda a comunidade)</label>
          <label><input type="radio" name="visibilidade" value="professor"> Só o professor</label>
        </fieldset>
        <div class="barra-acoes"><button class="botao" type="submit">Enviar pergunta</button></div>
      </form>
    <?php endif; ?>

    <?php if ($perguntas === []): ?>
      <p class="vazio">Nenhuma pergunta ainda.</p>
    <?php endif; ?>
    <ul class="lista-perguntas">
      <?php foreach ($perguntas as $pergunta): ?>
        <li class="pergunta <?= (int) $pergunta['id_pergunta'] === $destaque ? 'pergunta--destaque' : '' ?>" id="pergunta-<?= (int) $pergunta['id_pergunta'] ?>">
          <p class="pergunta__autor">
            <a href="perfil.php?u=<?= e(rawurlencode($pergunta['arroba'])) ?>">@<?= e($pergunta['arroba']) ?></a>
            · <?= e(date('d/m/Y', strtotime($pergunta['criada_em']))) ?>
            <?= (bool) $pergunta['publica_pergunta'] ? '' : '<span class="selo">🔒 só para o professor</span>' ?>
          </p>
          <p class="pergunta__texto"><?= nl2br(e($pergunta['texto_pergunta'])) ?></p>
          <?php if ($pergunta['resposta_pergunta'] !== null): ?>
            <div class="pergunta__resposta">
              <strong>Resposta do professor</strong>
              <?= Texto::html($pergunta['resposta_pergunta']) ?>
            </div>
          <?php elseif (!$ehProfessorDoCurso): ?>
            <p class="selo">Aguardando resposta</p>
          <?php endif; ?>
          <?php if ($ehProfessorDoCurso): ?>
            <form method="post" action="<?= e($voltar) ?>" class="form-resposta">
              <?= campoCsrf() ?>
              <input type="hidden" name="acao" value="responder">
              <input type="hidden" name="id_pergunta" value="<?= (int) $pergunta['id_pergunta'] ?>">
              <textarea name="resposta" rows="3" required maxlength="3000" placeholder="Escreva sua resposta"><?= e($pergunta['resposta_pergunta'] ?? '') ?></textarea>
              <button class="botao botao--pequeno" type="submit"><?= $pergunta['resposta_pergunta'] === null ? 'Responder' : 'Atualizar resposta' ?></button>
            </form>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
