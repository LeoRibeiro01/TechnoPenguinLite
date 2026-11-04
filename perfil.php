<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\AmizadeController;
use TechnoPenguin\Controllers\CertificadoController;
use TechnoPenguin\Controllers\GeloController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Amizade;
use TechnoPenguin\Models\Certificado;
use TechnoPenguin\Models\Conquista;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Support\Conquistas;

/** Perfil de um pinguim: avatar inteiro, conquistas, trilhas completas e em andamento, iglus. */

$leitor = exigirCadastroCompleto();
$arroba = UsuarioController::normalizarArroba((string) ($_GET['u'] ?? $leitor['arroba']));
$dono = (new Usuario(conexao()))->buscarPorArroba($arroba);
if ($dono === null) {
    definirAviso('⚠ Nenhum pinguim com esse @.');
    redirecionar('painel.php?aba=amigos');
}
unset($dono['senha']);
$idDono = (int) $dono['id_usuario'];
$proprio = $idDono === (int) $leitor['id_usuario'];
$voltar = linkPerfil($dono);

if (metodoPost()) {
    validarCsrf();
    try {
        definirAviso((new AmizadeController(conexao()))->solicitar($leitor, $idDono));
    } catch (ValidationException $e) {
        definirAviso('⚠ ' . $e->getMessage());
    }
    redirecionar($voltar);
}

$visivel = podeVerPerfil($dono, $leitor);
$relacao = $proprio ? null : ((new Amizade(conexao()))->relacoes((int) $leitor['id_usuario'])[$idDono] ?? null);

if ($visivel) {
    $resumo = (new Curso(conexao()))->resumoTrilhas($idDono);
    $completas = [];
    $estudando = [];
    foreach ((new UsuarioController(conexao()))->trilhas($idDono) as $slug) {
        $trilha = trilhaInfo($slug);
        $total = (int) ($resumo[$slug]['total_licoes'] ?? 0);
        if ($trilha === null || $total === 0) {
            continue;
        }
        $percentual = (int) floor((int) $resumo[$slug]['licoes_concluidas'] / $total * 100);
        if ($percentual === 100) {
            $completas[$slug] = $trilha;
        } else {
            $estudando[$slug] = $trilha + ['percentual' => $percentual];
        }
    }
    uasort($estudando, static fn (array $a, array $b): int => $b['percentual'] <=> $a['percentual']);
    $conquistas = array_column((new Conquista(conexao()))->deUsuario($idDono), null, 'codigo_conquista');
    $certificados = (new Certificado(conexao()))->deUsuario($idDono);
    $iglus = (new Iglu(conexao()))->doUsuario($idDono);
    $gelo = (new GeloController(conexao()))->resumo($idDono);
}

$tituloPagina = '@' . $dono['arroba'];
$paginaAtual = $proprio ? 'perfil.php' : 'painel.php?aba=amigos';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<section class="perfil-publico">
  <aside class="perfil-publico__avatar">
    <?= avatarDe($dono, 'avatar--corpo avatar--palco') ?>
  </aside>
  <div class="perfil-publico__info">
    <p class="apresentacao__tipo">
      <?= ($dono['papel_usuario'] ?? '') === 'professor' ? '🎓 Professor' : 'Pinguim estudante' ?>
      <?php if (estaOnline($dono)): ?> · <span class="online online--sim"></span> online agora<?php endif; ?>
    </p>
    <h1 class="app-titulo"><?= e($dono['nome']) ?></h1>
    <p class="perfil__arroba">@<?= e($dono['arroba']) ?></p>

    <?php if ($visivel): ?>
      <dl class="perfil-numeros">
        <div><dt>Nível</dt><dd><?= nivelXp((int) $dono['xp_usuario']) ?></dd></div>
        <div><dt>XP</dt><dd><?= (int) $dono['xp_usuario'] ?></dd></div>
        <div><dt>Gelo</dt><dd>🧊 <?= $gelo['dias'] ?></dd></div>
        <div><dt>Conquistas</dt><dd><?= count($conquistas) ?></dd></div>
        <div><dt>Certificados</dt><dd><?= count($certificados) ?></dd></div>
      </dl>
    <?php endif; ?>

    <div class="barra-acoes barra-acoes--esquerda">
      <?php if ($proprio): ?>
        <a class="botao" href="personalizar.php">Editar pinguim</a>
        <a class="botao botao--fantasma" href="vestuario.php">Vestuário</a>
        <a class="botao botao--fantasma" href="conta.php#privacidade">Privacidade do perfil</a>
      <?php elseif ($relacao === 'amigo'): ?>
        <span class="selo selo--novo">Vocês são amigos ✓</span>
      <?php elseif ($relacao === 'enviado'): ?>
        <span class="selo">Pedido de amizade enviado</span>
      <?php else: ?>
        <form method="post" action="<?= e($voltar) ?>">
          <?= campoCsrf() ?>
          <button class="botao" type="submit"><?= $relacao === 'recebido' ? 'Aceitar pedido de amizade' : '+ Adicionar amigo' ?></button>
        </form>
      <?php endif; ?>
    </div>

    <?php if ($visivel): ?>
      <div class="perfil-grade perfil-grade--lado">
        <section class="cartao" aria-labelledby="titulo-estudando">
          <h2 class="cartao__titulo" id="titulo-estudando">📖 Estudando agora</h2>
          <?php if ($estudando === []): ?>
            <p class="vazio">Nenhuma trilha em andamento.</p>
          <?php endif; ?>
          <ul class="lista-progresso">
            <?php foreach ($estudando as $slug => $trilha): ?>
              <li>
                <?= iconeTrilha($slug) ?>
                <a href="trilha.php?t=<?= e($slug) ?>"><?= e($trilha['titulo']) ?></a>
                <div class="barra"><span style="width: <?= $trilha['percentual'] ?>%"></span></div>
                <small><?= $trilha['percentual'] ?>%</small>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>

        <section class="cartao" aria-labelledby="titulo-completas">
          <h2 class="cartao__titulo" id="titulo-completas">🏆 Linguagens e assuntos completos</h2>
          <?php if ($completas === []): ?>
            <p class="vazio">Nenhuma trilha concluída ainda.</p>
          <?php endif; ?>
          <ul class="lista-trilhas">
            <?php foreach ($completas as $slug => $trilha): ?>
              <li><?= iconeTrilha($slug) ?> <a href="trilha.php?t=<?= e($slug) ?>"><?= e($trilha['titulo']) ?></a> <span class="selo selo--novo">100%</span></li>
            <?php endforeach; ?>
          </ul>
          <?php if ($certificados !== []): ?>
            <h3 class="subtitulo-cartao">Certificados</h3>
            <ul class="lista-certificados">
              <?php foreach ($certificados as $certificado): ?>
                <li><a href="certificado.php?codigo=<?= e($certificado['codigo_certificado']) ?>">📜 <?= e($certificado['titulo_trilha']) ?> <?= e(CertificadoController::rotulo($certificado['nivel_certificado'])) ?></a> <small><?= (int) $certificado['horas_certificado'] ?>h</small></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </section>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php if (!$visivel): ?>
  <div class="cartao bloqueio">
    <p>🔒 <?= ($dono['perfil_visibilidade'] ?? '') === 'amigos' ? 'Este pinguim mostra o perfil só para amigos.' : 'Este perfil é privado.' ?></p>
  </div>
<?php else: ?>
  <div class="perfil-grade">
    <section class="cartao perfil-grade__largo" aria-labelledby="titulo-conquistas">
      <h2 class="cartao__titulo" id="titulo-conquistas">🏅 Conquistas</h2>
      <?php if ($conquistas === []): ?>
        <p class="vazio">Nenhuma conquista ainda.</p>
      <?php endif; ?>
      <ul class="grade-conquistas">
        <?php foreach (Conquistas::CATALOGO as $codigo => [$nome, $descricao, $icone]):
            if (!isset($conquistas[$codigo])) {
                continue;
            }
        ?>
          <li class="conquista" title="<?= e($descricao) ?>">
            <span class="conquista__icone" aria-hidden="true"><?= $icone ?></span>
            <strong><?= e($nome) ?></strong>
            <small>Desde <?= e(date('d/m/Y', strtotime($conquistas[$codigo]['data_desbloqueio']))) ?></small>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section class="cartao perfil-grade__largo" aria-labelledby="titulo-iglus-perfil">
      <h2 class="cartao__titulo" id="titulo-iglus-perfil">🏠 Iglus</h2>
      <?php if ($iglus === []): ?>
        <p class="vazio">Não participa de nenhum iglu.</p>
      <?php endif; ?>
      <div class="grade-iglus">
        <?php foreach ($iglus as $iglu): ?>
          <a class="cartao-iglu" href="iglu.php?id=<?= (int) $iglu['id_iglu'] ?>" style="--cor-iglu: <?= e($iglu['cor_iglu']) ?>">
            <span class="iglu-emblema" aria-hidden="true"><?= e($iglu['icone_iglu']) ?></span>
            <div>
              <strong><?= e($iglu['nome_iglu']) ?></strong>
              <small><?= e(['dono' => 'Dono', 'admin' => 'Admin', 'membro' => 'Membro'][$iglu['papel_membro']]) ?> · <?= e(plural((int) $iglu['total_membros'], 'membro', 'membros')) ?></small>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
