<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\ChatController;
use TechnoPenguin\Controllers\IgluController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\IgluConvite;
use TechnoPenguin\Support\Gelo;

/** Iglu: criação (?novo=1), visão geral, chat, membros e configurações, além da tela de entrada. */

$usuario = exigirCadastroCompleto();
$idUsuario = (int) $usuario['id_usuario'];
$controller = new IgluController(conexao());
$iglus = new Iglu(conexao());
$convites = new IgluConvite(conexao());
$idIglu = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

if (metodoPost()) {
    validarCsrf();
    $acao = (string) ($_POST['acao'] ?? '');
    $destino = 'iglu.php?id=' . $idIglu . (isset($_POST['aba']) ? '&aba=' . rawurlencode((string) $_POST['aba']) : '');
    $voltar = (string) ($_POST['voltar'] ?? '');
    if (preg_match('/^(painel|iglu)\.php(\?[\w=&%.-]*)?$/', $voltar) === 1) {
        $destino = $voltar;
    }
    $idConvite = (int) ($_POST['id_convite'] ?? 0);
    $idMembro = (int) ($_POST['id_usuario'] ?? 0);
    try {
        $mensagem = match ($acao) {
            'criar' => (function () use ($controller, $idUsuario, &$destino): string {
                $iglu = $controller->criar($idUsuario, $_POST);
                $destino = 'iglu.php?id=' . $iglu['id_iglu'] . '&aba=membros';
                return 'Iglu criado! Agora convide seus amigos.';
            })(),
            'editar' => (function () use ($controller, $idUsuario, $idIglu): string {
                $controller->editar($idUsuario, $idIglu, $_POST);
                return 'Iglu atualizado.';
            })(),
            'chat' => (function () use ($controller, $idUsuario, $idIglu): string {
                $controller->alternarChat($idUsuario, $idIglu, !empty($_POST['ativo']));
                return !empty($_POST['ativo']) ? 'Chat ligado.' : 'Chat desligado.';
            })(),
            'convidar' => $controller->convidar($usuario, $idIglu, (string) ($_POST['arroba'] ?? '')),
            'pedir' => $controller->pedirEntrada($usuario, $idIglu),
            'aceitar_convite' => $controller->aceitarConvite($usuario, $idConvite),
            'recusar_convite' => (function () use ($controller, $idUsuario, $idConvite, &$destino): string {
                $controller->recusarConvite($idUsuario, $idConvite);
                $destino = 'painel.php?aba=iglus';
                return 'Convite recusado.';
            })(),
            'aprovar_pedido' => $controller->responderPedido($usuario, $idConvite, true),
            'recusar_pedido' => $controller->responderPedido($usuario, $idConvite, false),
            'papel' => (function () use ($controller, $idUsuario, $idIglu, $idMembro): string {
                $controller->definirPapel($idUsuario, $idIglu, $idMembro, (string) ($_POST['papel'] ?? ''));
                return 'Papel atualizado.';
            })(),
            'remover' => (function () use ($controller, $idUsuario, $idIglu, $idMembro): string {
                $controller->removerMembro($idUsuario, $idIglu, $idMembro);
                return 'Membro removido.';
            })(),
            'sair' => (function () use ($controller, $idUsuario, $idIglu, &$destino): string {
                $controller->sair($idUsuario, $idIglu);
                $destino = 'painel.php?aba=iglus';
                return 'Você saiu do iglu.';
            })(),
            'excluir' => (function () use ($controller, $idUsuario, $idIglu, &$destino): string {
                $controller->excluir($idUsuario, $idIglu);
                $destino = 'painel.php?aba=iglus';
                return 'Iglu apagado.';
            })(),
            default => throw new ValidationException(['acao' => 'Ação desconhecida.']),
        };
        definirAviso($mensagem);
    } catch (ValidationException $e) {
        definirAviso('⚠ ' . $e->getMessage());
        if ($acao === 'criar') {
            $_SESSION['iglu_rascunho'] = $_POST;
            $destino = 'iglu.php?novo=1';
        }
    }
    redirecionar($destino);
}

/* ---------- Criação ---------- */
if (isset($_GET['novo'])) {
    $rascunho = $_SESSION['iglu_rascunho'] ?? null;
    unset($_SESSION['iglu_rascunho']);
    $igluForm = $rascunho === null ? [] : [
        'nome' => (string) ($rascunho['nome'] ?? ''),
        'descricao' => (string) ($rascunho['descricao'] ?? ''),
        'cor' => (string) ($rascunho['cor'] ?? '#5c7aa5'),
        'icone' => (string) ($rascunho['icone'] ?? '🐧'),
        'aberto' => !empty($rascunho['aberto']),
        'chat' => !empty($rascunho['chat']),
        'trilhas' => array_map(
            static fn (string $t): bool => in_array($t, (array) ($rascunho['obrigatorias'] ?? []), true),
            array_flip(array_filter((array) ($rascunho['trilhas'] ?? []), 'is_string'))
        ),
    ];
    $tituloPagina = 'Criar iglu';
    $paginaAtual = 'painel.php?aba=iglus';
    require __DIR__ . '/app/Views/app_inicio.php';
    ?>
    <p class="migalhas"><a href="painel.php?aba=iglus">← Iglus</a></p>
    <div class="cabecalho-teco">
      <?= teco('avatar--rosto avatar--balao') ?>
      <div class="balao balao--compacto">
        <h1>Vamos construir um iglu!</h1>
        <p>Um iglu é um grupo de estudos: escolham o que vão aprender juntos e mantenham o gelo do grupo firme. Se ninguém estudar por mais de <?= Gelo::DIAS_TOLERANCIA ?> dias, ele derrete.</p>
      </div>
    </div>
    <form method="post" action="iglu.php" class="cartao form-iglu">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="criar">
      <?php require __DIR__ . '/app/Views/iglu_campos.php'; ?>
      <div class="barra-acoes">
        <a class="botao botao--fantasma" href="painel.php?aba=iglus">Cancelar</a>
        <button class="botao" type="submit">Criar iglu</button>
      </div>
    </form>
    <?php
    require __DIR__ . '/app/Views/app_fim.php';
    exit;
}

$iglu = $iglus->find($idIglu);
if ($iglu === null) {
    definirAviso('⚠ Esse iglu não existe mais.');
    redirecionar('painel.php?aba=iglus');
}
$papel = $iglus->papel($idIglu, $idUsuario);
$gestor = in_array($papel, ['dono', 'admin'], true);
$trilhas = $iglus->trilhas($idIglu);
$obrigatorias = array_keys(array_filter($trilhas));
$membros = $iglus->membros($idIglu);
$pendente = $convites->pendente($idIglu, $idUsuario);
$estadoGelo = Gelo::estado((int) $iglu['dias_consecutivos_iglu'], $iglu['ultima_atividade_iglu']);
$entrar = $papel === null && isset($_GET['entrar']);
$papeis = ['dono' => 'Dono', 'admin' => 'Admin', 'membro' => 'Membro'];
$abas = ['geral' => 'Visão geral', 'chat' => 'Chat', 'membros' => 'Membros'] + ($gestor ? ['config' => 'Configurações'] : []);
$aba = $papel !== null && isset($abas[$_GET['aba'] ?? '']) ? $_GET['aba'] : 'geral';
$bloqueioChat = (new ChatController(conexao()))->bloqueio($usuario, $iglu);

$tituloPagina = $iglu['nome_iglu'];
$paginaAtual = 'painel.php?aba=iglus';
require __DIR__ . '/app/Views/app_inicio.php';
?>
<p class="migalhas"><a href="painel.php?aba=iglus">← Iglus</a></p>

<header class="iglu-banner" style="--cor-iglu: <?= e($iglu['cor_iglu']) ?>">
  <span class="iglu-banner__emblema" aria-hidden="true"><?= e($iglu['icone_iglu']) ?></span>
  <div class="iglu-banner__texto">
    <h1><?= e($iglu['nome_iglu']) ?></h1>
    <p><?= e($iglu['descricao_iglu']) ?></p>
    <p class="iglu-banner__chips">
      <span><?= $iglu['aberto_iglu'] ? '🔓 Aberto para pedidos' : '🔒 Só por convite' ?></span>
      <span><?= $iglu['chat_ativo'] ? '💬 Chat ligado' : '🔕 Chat desligado' ?></span>
      <span>👥 <?= e(plural(count($membros), 'membro', 'membros')) ?></span>
      <?php if ($papel !== null): ?><span>Você é <?= e(mb_strtolower($papeis[$papel])) ?></span><?php endif; ?>
    </p>
  </div>
  <div class="iglu-banner__gelo">
    <div class="gelo gelo--<?= e($estadoGelo) ?>" aria-hidden="true"><span><?= (int) $iglu['dias_consecutivos_iglu'] ?></span></div>
    <small>
      <?= match ($estadoGelo) {
          Gelo::FEITO_HOJE => 'Gelo firme hoje!',
          Gelo::PENDENTE => 'Ninguém estudou hoje',
          default => 'Gelo apagado',
      } ?>
    </small>
  </div>
</header>

<?php if ($entrar):
    $jaTenho = (new UsuarioController(conexao()))->trilhas($idUsuario);
    $ehConvite = $pendente !== null && $pendente['tipo_convite'] === 'convite';
?>
  <section class="cartao entrar-iglu" aria-labelledby="titulo-entrar">
    <?= teco('avatar--rosto avatar--balao') ?>
    <div>
      <h2 class="app-titulo" id="titulo-entrar"><?= $ehConvite ? 'Você foi convidado para este iglu!' : 'Entrar no iglu' ?></h2>
      <?php if ($obrigatorias === []): ?>
        <p>Este iglu não tem trilhas obrigatórias. Seu perfil continua como está.</p>
      <?php else: ?>
        <p><strong>Ao entrar, estas linguagens e assuntos serão adicionados ao seu perfil:</strong></p>
        <ul class="lista-trilhas">
          <?php foreach ($obrigatorias as $slug): ?>
            <li>
              <?= iconeTrilha($slug) ?> <?= e(trilhaInfo($slug)['titulo'] ?? $slug) ?>
              <?= in_array($slug, $jaTenho, true) ? '<span class="selo">você já tem</span>' : '<span class="selo selo--novo">nova</span>' ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php if ($pendente !== null && !$ehConvite): ?>
        <p class="selo">Seu pedido está à espera de aprovação de um admin.</p>
      <?php elseif (!$ehConvite && !$iglu['aberto_iglu']): ?>
        <p class="selo">🔒 Este iglu só aceita membros convidados.</p>
      <?php else: ?>
        <div class="barra-acoes barra-acoes--esquerda">
          <form method="post" action="iglu.php">
            <?= campoCsrf() ?>
            <input type="hidden" name="id" value="<?= $idIglu ?>">
            <?php if ($ehConvite): ?>
              <input type="hidden" name="acao" value="aceitar_convite">
              <input type="hidden" name="id_convite" value="<?= (int) $pendente['id_convite'] ?>">
              <button class="botao" type="submit">Aceitar e entrar</button>
            <?php else: ?>
              <input type="hidden" name="acao" value="pedir">
              <button class="botao" type="submit">Aceitar trilhas e pedir para entrar</button>
            <?php endif; ?>
          </form>
          <?php if ($ehConvite): ?>
            <form method="post" action="iglu.php">
              <?= campoCsrf() ?>
              <input type="hidden" name="id" value="<?= $idIglu ?>">
              <input type="hidden" name="acao" value="recusar_convite">
              <input type="hidden" name="id_convite" value="<?= (int) $pendente['id_convite'] ?>">
              <button class="botao botao--fantasma" type="submit">Recusar</button>
            </form>
          <?php else: ?>
            <a class="botao botao--fantasma" href="iglu.php?id=<?= $idIglu ?>">Cancelar</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php require __DIR__ . '/app/Views/app_fim.php'; exit; endif; ?>

<?php if ($papel === null): ?>
  <div class="cartao faixa-acao">
    <?php if ($pendente !== null && $pendente['tipo_convite'] === 'pedido'): ?>
      <p>Seu pedido para entrar está à espera de aprovação.</p>
    <?php elseif ($pendente !== null || $iglu['aberto_iglu']): ?>
      <p>Gostou? Entre para estudar com o grupo.</p>
      <a class="botao" href="iglu.php?id=<?= $idIglu ?>&entrar=1"><?= $pendente !== null ? 'Ver convite' : 'Quero entrar neste iglu' ?></a>
    <?php else: ?>
      <p>🔒 Este iglu só aceita membros convidados.</p>
    <?php endif; ?>
  </div>
<?php else: ?>
  <nav class="abas" aria-label="Seções do iglu">
    <?php foreach ($abas as $chave => $rotulo): ?>
      <a href="iglu.php?id=<?= $idIglu ?>&aba=<?= $chave ?>" <?= $chave === $aba ? 'aria-current="page"' : '' ?>><?= e($rotulo) ?></a>
    <?php endforeach; ?>
  </nav>
<?php endif; ?>

<?php if ($aba === 'geral'):
    $progresso = $papel !== null ? $iglus->progressoMembros($idIglu) : [];
?>
  <dl class="iglu-numeros">
    <div><dt>Dias de gelo</dt><dd>🧊 <?= (int) $iglu['dias_consecutivos_iglu'] ?></dd></div>
    <div><dt>Lições na semana</dt><dd>📚 <?= $iglus->licoesDaSemana($idIglu) ?></dd></div>
    <div><dt>Membros</dt><dd>👥 <?= count($membros) ?></dd></div>
    <div><dt>Trilhas</dt><dd>🗺️ <?= count($trilhas) ?></dd></div>
  </dl>

  <div class="iglu-grade">
    <section class="cartao" aria-labelledby="titulo-trilhas-iglu">
      <h2 class="cartao__titulo" id="titulo-trilhas-iglu">Linguagens e assuntos</h2>
      <ul class="lista-trilhas">
        <?php foreach ($trilhas as $slug => $obrigatoria): ?>
          <li>
            <?= iconeTrilha($slug) ?>
            <a href="trilha.php?t=<?= e($slug) ?>"><?= e(trilhaInfo($slug)['titulo'] ?? $slug) ?></a>
            <?= $obrigatoria ? '<span class="selo selo--obrigatoria">obrigatória</span>' : '' ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <?php if ($papel !== null): ?>
      <section class="cartao" aria-labelledby="titulo-ranking">
        <h2 class="cartao__titulo" id="titulo-ranking">Ranking do iglu</h2>
        <p class="campo__ajuda">XP ganho nas trilhas do iglu desde que cada um entrou.</p>
        <ol class="ranking">
          <?php foreach ($iglus->ranking($idIglu) as $posicao => $linha): ?>
            <li>
              <span class="ranking__pos"><?= ['🥇', '🥈', '🥉'][$posicao] ?? $posicao + 1 ?></span>
              <a href="perfil.php?u=<?= e(rawurlencode($linha['arroba'])) ?>">@<?= e($linha['arroba']) ?></a>
              <span class="ranking__xp"><?= (int) $linha['xp_iglu'] ?> XP · <?= e(plural((int) $linha['licoes_iglu'], 'lição', 'lições')) ?></span>
            </li>
          <?php endforeach; ?>
        </ol>
      </section>

      <section class="cartao iglu-grade__largo" aria-labelledby="titulo-progresso-grupo">
        <h2 class="cartao__titulo" id="titulo-progresso-grupo">Progresso do grupo</h2>
        <div class="tabela-rolagem">
          <table class="tabela-progresso">
            <thead>
              <tr>
                <th scope="col">Membro</th>
                <?php foreach (array_keys($trilhas) as $slug): ?>
                  <th scope="col"><?= iconeTrilha($slug) ?> <span class="sr-only"><?= e(trilhaInfo($slug)['titulo'] ?? $slug) ?></span></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($membros as $membro): ?>
                <tr>
                  <th scope="row"><a href="<?= e(linkPerfil($membro)) ?>">@<?= e($membro['arroba']) ?></a></th>
                  <?php foreach (array_keys($trilhas) as $slug):
                      [$feitas, $total] = $progresso[(int) $membro['id_usuario']][$slug] ?? [0, 0];
                      $pct = $total > 0 ? (int) floor($feitas / $total * 100) : 0;
                  ?>
                    <td><div class="barra barra--fina"><span style="width: <?= $pct ?>%"></span></div><small><?= $pct ?>%</small></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section class="cartao iglu-grade__largo" aria-labelledby="titulo-atividade">
        <h2 class="cartao__titulo" id="titulo-atividade">Atividade recente</h2>
        <?php $atividade = $iglus->atividade($idIglu); ?>
        <?php if ($atividade === []): ?>
          <p class="vazio">Nenhuma lição ainda. Seja o primeiro!</p>
        <?php endif; ?>
        <ul class="atividade">
          <?php foreach ($atividade as $item): ?>
            <li>
              <?= iconeTrilha($item['trilha']) ?>
              <span><strong>@<?= e($item['arroba']) ?></strong> concluiu “<?= e($item['titulo_licao']) ?>”</span>
              <time datetime="<?= e($item['data_conclusao_progresso']) ?>"><?= e(date('d/m H:i', strtotime($item['data_conclusao_progresso']))) ?></time>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
    <?php endif; ?>
  </div>

<?php elseif ($aba === 'chat'): ?>
  <section class="cartao chat" aria-labelledby="titulo-chat">
    <div class="chat__topo">
      <h2 class="cartao__titulo" id="titulo-chat">💬 Chat do iglu</h2>
      <?php if ($gestor): ?>
        <form method="post" action="iglu.php">
          <?= campoCsrf() ?>
          <input type="hidden" name="id" value="<?= $idIglu ?>">
          <input type="hidden" name="aba" value="chat">
          <input type="hidden" name="acao" value="chat">
          <?php if (!$iglu['chat_ativo']): ?><input type="hidden" name="ativo" value="1"><?php endif; ?>
          <button class="botao botao--pequeno botao--fantasma" type="submit"><?= $iglu['chat_ativo'] ? 'Desligar chat' : 'Ligar chat' ?></button>
        </form>
      <?php endif; ?>
    </div>
    <?php if ($bloqueioChat !== null): ?>
      <div class="chat__bloqueio">
        <p>🔒 <?= e($bloqueioChat) ?></p>
        <?php if (str_contains($bloqueioChat, 'nascimento')): ?><a class="botao botao--pequeno" href="conta.php#perfil">Informar data de nascimento</a><?php endif; ?>
      </div>
    <?php else: ?>
      <ol class="chat__mensagens" id="chat-mensagens" aria-live="polite"></ol>
      <form class="chat__form" id="chat-form">
        <label class="sr-only" for="chat-texto">Mensagem</label>
        <textarea id="chat-texto" name="texto" rows="2" maxlength="<?= ChatController::TAMANHO_MAXIMO ?>" required placeholder="Escreva para o iglu (Enter envia, Shift+Enter quebra linha)"></textarea>
        <button class="botao" type="submit">Enviar</button>
      </form>
      <p class="campo__ajuda">Seja gentil. Admins podem apagar mensagens. Não compartilhe dados pessoais.</p>
      <script>
        (() => {
          const lista = document.querySelector('#chat-mensagens');
          const form = document.querySelector('#chat-form');
          const texto = document.querySelector('#chat-texto');
          const endereco = 'iglu-chat.php?id=<?= $idIglu ?>';
          const csrf = <?= json_encode(csrfToken()) ?>;
          let ultimo = 0;

          const desenhar = (mensagem) => {
            const item = document.createElement('li');
            item.className = 'chat__mensagem' + (mensagem.minha ? ' chat__mensagem--minha' : '');
            item.dataset.id = mensagem.id;
            const autor = document.createElement('a');
            autor.href = 'perfil.php?u=' + encodeURIComponent(mensagem.arroba);
            autor.textContent = '@' + mensagem.arroba;
            const hora = document.createElement('time');
            hora.textContent = mensagem.hora;
            const corpo = document.createElement('p');
            corpo.textContent = mensagem.texto;
            item.append(autor, hora, corpo);
            if (mensagem.pode_apagar) {
              const apagar = document.createElement('button');
              apagar.type = 'button';
              apagar.className = 'chat__apagar';
              apagar.textContent = 'apagar';
              apagar.addEventListener('click', () => enviar({ acao: 'apagar', id_mensagem: mensagem.id }).then(() => item.remove()));
              item.append(apagar);
            }
            lista.append(item);
          };

          const buscar = async () => {
            const resposta = await fetch(`${endereco}&depois=${ultimo}`, { headers: { Accept: 'application/json' } });
            const dados = await resposta.json();
            if (dados.erro) return;
            const noFim = lista.scrollHeight - lista.scrollTop - lista.clientHeight < 60;
            dados.mensagens.forEach((m) => { desenhar(m); ultimo = Math.max(ultimo, m.id); });
            if (dados.mensagens.length && noFim) lista.scrollTop = lista.scrollHeight;
          };

          const enviar = async (campos) => {
            const corpo = new FormData();
            corpo.append('csrf', csrf);
            Object.entries(campos).forEach(([k, v]) => corpo.append(k, v));
            const resposta = await fetch(endereco, { method: 'POST', body: corpo });
            const dados = await resposta.json();
            if (dados.erro) alert(dados.erro);
            return dados;
          };

          form.addEventListener('submit', async (evento) => {
            evento.preventDefault();
            if (!texto.value.trim()) return;
            const dados = await enviar({ acao: 'enviar', texto: texto.value });
            if (!dados.erro) {
              texto.value = '';
              await buscar();
              lista.scrollTop = lista.scrollHeight;
            }
          });
          texto.addEventListener('keydown', (evento) => {
            if (evento.key === 'Enter' && !evento.shiftKey) {
              evento.preventDefault();
              form.requestSubmit();
            }
          });

          buscar().then(() => { lista.scrollTop = lista.scrollHeight; });
          setInterval(() => { if (!document.hidden) buscar(); }, 4000);
        })();
      </script>
    <?php endif; ?>
  </section>

<?php elseif ($aba === 'membros'): ?>
  <div class="iglu-grade">
    <section class="cartao" aria-labelledby="titulo-membros">
      <h2 class="cartao__titulo" id="titulo-membros">Membros (<?= count($membros) ?>)</h2>
      <ul class="pessoas">
        <?php foreach ($membros as $membro): ?>
          <li class="pessoa">
            <?= avatarDe($membro, 'avatar--rosto avatar--lista') ?>
            <div class="pessoa__info">
              <a class="pessoa__nome" href="<?= e(linkPerfil($membro)) ?>"><strong><?= e($membro['nome']) ?></strong></a>
              <span>@<?= e($membro['arroba']) ?> · <?= $papeis[$membro['papel_membro']] ?> · Nível <?= nivelXp((int) $membro['xp_usuario']) ?></span>
            </div>
            <span class="online <?= estaOnline($membro) ? 'online--sim' : '' ?>" title="<?= estaOnline($membro) ? 'Online agora' : 'Offline' ?>"></span>
            <?php if ((int) $membro['id_usuario'] !== $idUsuario && $gestor && $membro['papel_membro'] !== 'dono'): ?>
              <div class="pessoa__acoes">
                <?php if ($papel === 'dono'): ?>
                  <form method="post" action="iglu.php">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="id" value="<?= $idIglu ?>">
                    <input type="hidden" name="aba" value="membros">
                    <input type="hidden" name="acao" value="papel">
                    <input type="hidden" name="id_usuario" value="<?= (int) $membro['id_usuario'] ?>">
                    <input type="hidden" name="papel" value="<?= $membro['papel_membro'] === 'admin' ? 'membro' : 'admin' ?>">
                    <button class="botao botao--pequeno botao--fantasma" type="submit"><?= $membro['papel_membro'] === 'admin' ? 'Tirar admin' : 'Tornar admin' ?></button>
                  </form>
                <?php endif; ?>
                <?php if ($papel === 'dono' || $membro['papel_membro'] === 'membro'): ?>
                  <form method="post" action="iglu.php" onsubmit="return confirm('Remover @<?= e($membro['arroba']) ?> do iglu?')">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="id" value="<?= $idIglu ?>">
                    <input type="hidden" name="aba" value="membros">
                    <input type="hidden" name="acao" value="remover">
                    <input type="hidden" name="id_usuario" value="<?= (int) $membro['id_usuario'] ?>">
                    <button class="botao botao--pequeno botao--fantasma" type="submit">Remover</button>
                  </form>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <div>
      <section class="cartao" aria-labelledby="titulo-convidar">
        <h2 class="cartao__titulo" id="titulo-convidar">Convidar</h2>
        <p class="campo__ajuda">Convide amigos ou qualquer pinguim pelo @. Quem aceitar verá as trilhas obrigatórias antes de entrar.</p>
        <form method="post" action="iglu.php" class="busca busca--compacta">
          <?= campoCsrf() ?>
          <input type="hidden" name="id" value="<?= $idIglu ?>">
          <input type="hidden" name="aba" value="membros">
          <input type="hidden" name="acao" value="convidar">
          <label class="sr-only" for="convidar-arroba">@ de quem você quer convidar</label>
          <input id="convidar-arroba" type="text" name="arroba" placeholder="@usuario" required autocapitalize="none">
          <button class="botao botao--pequeno" type="submit">Convidar</button>
        </form>
      </section>

      <?php if ($gestor && ($pedidos = $convites->pedidos($idIglu)) !== []): ?>
        <section class="cartao" aria-labelledby="titulo-pedidos">
          <h2 class="cartao__titulo" id="titulo-pedidos">Pedidos para entrar</h2>
          <ul class="pessoas">
            <?php foreach ($pedidos as $pedido): ?>
              <li class="pessoa">
                <?= avatarDe($pedido, 'avatar--rosto avatar--lista') ?>
                <div class="pessoa__info"><a class="pessoa__nome" href="<?= e(linkPerfil($pedido)) ?>"><strong><?= e($pedido['nome']) ?></strong></a><span>@<?= e($pedido['arroba']) ?></span></div>
                <div class="pessoa__acoes">
                  <?php foreach (['aprovar_pedido' => 'Aprovar', 'recusar_pedido' => 'Recusar'] as $acao => $rotulo): ?>
                    <form method="post" action="iglu.php">
                      <?= campoCsrf() ?>
                      <input type="hidden" name="id" value="<?= $idIglu ?>">
                      <input type="hidden" name="aba" value="membros">
                      <input type="hidden" name="acao" value="<?= $acao ?>">
                      <input type="hidden" name="id_convite" value="<?= (int) $pedido['id_convite'] ?>">
                      <button class="botao botao--pequeno <?= $acao === 'recusar_pedido' ? 'botao--fantasma' : '' ?>" type="submit"><?= $rotulo ?></button>
                    </form>
                  <?php endforeach; ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>
    </div>
  </div>

<?php elseif ($aba === 'config'): ?>
  <form method="post" action="iglu.php" class="cartao form-iglu">
    <?= campoCsrf() ?>
    <input type="hidden" name="id" value="<?= $idIglu ?>">
    <input type="hidden" name="aba" value="config">
    <input type="hidden" name="acao" value="editar">
    <h2 class="cartao__titulo">Editar iglu</h2>
    <?php
    $igluForm = [
        'nome' => $iglu['nome_iglu'], 'descricao' => $iglu['descricao_iglu'], 'cor' => $iglu['cor_iglu'],
        'icone' => $iglu['icone_iglu'], 'aberto' => (bool) $iglu['aberto_iglu'], 'chat' => (bool) $iglu['chat_ativo'],
        'trilhas' => $trilhas,
    ];
    require __DIR__ . '/app/Views/iglu_campos.php';
    ?>
    <div class="barra-acoes"><button class="botao" type="submit">Salvar alterações</button></div>
  </form>
<?php endif; ?>

<?php if ($papel !== null): ?>
  <div class="barra-acoes barra-acoes--perigo">
    <form method="post" action="iglu.php" onsubmit="return confirm('Sair do iglu?')">
      <?= campoCsrf() ?>
      <input type="hidden" name="id" value="<?= $idIglu ?>">
      <input type="hidden" name="acao" value="sair">
      <button class="botao botao--fantasma" type="submit">Sair do iglu</button>
    </form>
    <?php if ($papel === 'dono'): ?>
      <form method="post" action="iglu.php" onsubmit="return confirm('Apagar o iglu para todos os membros?')">
        <?= campoCsrf() ?>
        <input type="hidden" name="id" value="<?= $idIglu ?>">
        <input type="hidden" name="acao" value="excluir">
        <button class="botao botao--perigo" type="submit">Apagar iglu</button>
      </form>
    <?php endif; ?>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
