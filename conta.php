<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Support\Catalogo;

/** Minha conta: dados pessoais, senha, privacidade, perfil de professor, exportação e exclusão (LGPD). */

$usuario = exigirLogin();
$idUsuario = (int) $usuario['id_usuario'];
$usuarios = new UsuarioController(conexao());
$erros = [];

if (metodoPost()) {
    validarCsrf();
    $acao = (string) ($_POST['acao'] ?? '');
    try {
        switch ($acao) {
            case 'perfil':
                $usuarios->atualizarPerfil($idUsuario, [
                    'nome' => (string) ($_POST['nome'] ?? ''),
                    'arroba' => (string) ($_POST['arroba'] ?? ''),
                    'email' => (string) ($_POST['email'] ?? ''),
                    'nascimento' => (string) ($_POST['nascimento'] ?? ''),
                ]);
                definirAviso('Seus dados foram atualizados.');
                break;
            case 'senha':
                $usuarios->trocarSenha($idUsuario, (string) ($_POST['senha_atual'] ?? ''), (string) ($_POST['senha'] ?? ''), (string) ($_POST['confirmar_senha'] ?? ''));
                session_regenerate_id(true);
                definirAviso('Senha alterada.');
                break;
            case 'privacidade':
                $usuarios->salvarPrivacidade($idUsuario, $_POST);
                definirAviso('Preferências de privacidade salvas.');
                break;
            case 'professor':
                $usuarios->definirProfessor($usuario, !empty($_POST['professor']));
                definirAviso(!empty($_POST['professor']) ? 'Perfil de professor ativado! Crie seu primeiro curso.' : 'Perfil de professor desativado.');
                redirecionar(!empty($_POST['professor']) ? 'professor.php' : 'conta.php');
                // no break
            case 'exportar':
                $dados = $usuarios->exportar($idUsuario);
                header('Content-Type: application/json; charset=utf-8');
                header('Content-Disposition: attachment; filename="technopenguin-meus-dados-' . $usuario['arroba'] . '.json"');
                echo json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            case 'excluir':
                if (trim((string) ($_POST['confirmacao'] ?? '')) !== 'EXCLUIR') {
                    throw new ValidationException(['confirmacao' => 'Digite EXCLUIR para confirmar.']);
                }
                $usuarios->excluir($idUsuario, (string) ($_POST['senha_exclusao'] ?? ''));
                sair();
                definirAviso('Sua conta e seus dados foram excluídos. Sentiremos sua falta! 🐧');
                redirecionar('login.php');
                // no break
            default:
                throw new ValidationException(['acao' => 'Ação desconhecida.']);
        }
        redirecionar('conta.php#' . $acao);
    } catch (ValidationException $e) {
        $erros = $e->erros + ['_acao' => $acao];
    }
}

$usuario = usuarioAtual(true);
$idade = UsuarioController::idade($usuario);
$erroDe = static fn (string $campo): string => isset($erros[$campo]) ? '<p class="app-erro-msg">' . e($erros[$campo]) . '</p>' : '';

$tituloPagina = 'Minha conta';
$paginaAtual = 'conta.php';
$semMenu = (new UsuarioController(conexao()))->etapaPendente($usuario) !== null;
require __DIR__ . '/app/Views/app_inicio.php';
?>
<h1 class="app-titulo">Minha conta</h1>
<p class="app-subtitulo">Seus dados, sua privacidade. Aqui você exerce os direitos da LGPD: acessar, corrigir, levar e excluir seus dados.</p>

<?php if ($erros !== []): ?>
  <p class="app-erro-msg" role="alert"><?= e(array_values(array_diff_key($erros, ['_acao' => 1]))[0] ?? 'Confira os campos.') ?></p>
<?php endif; ?>

<div class="conta-grade">
  <section class="cartao" id="perfil" aria-labelledby="titulo-dados">
    <h2 class="cartao__titulo" id="titulo-dados">👤 Dados pessoais</h2>
    <form method="post" action="conta.php" class="form-conta">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="perfil">
      <label class="campo"><span>Nome (aparece no perfil e nos certificados)</span>
        <input name="nome" value="<?= e($usuario['nome']) ?>" required minlength="2" maxlength="50"></label>
      <?= $erroDe('nome') ?>
      <label class="campo"><span>@</span>
        <input name="arroba" value="<?= e($usuario['arroba']) ?>" required pattern="@?[a-z0-9_.]{3,30}" autocapitalize="none"></label>
      <?= $erroDe('arroba') ?>
      <label class="campo"><span>Email</span>
        <input type="email" name="email" value="<?= e($usuario['email']) ?>" required maxlength="50"></label>
      <?= $erroDe('email') ?>
      <label class="campo"><span>Data de nascimento</span>
        <input type="date" name="nascimento" value="<?= e($usuario['data_nascimento'] ?? '') ?>" required max="<?= date('Y-m-d') ?>"></label>
      <?= $erroDe('nascimento') ?>
      <p class="campo__ajuda">
        <?= $idade === null ? 'Sem data de nascimento, o chat dos iglus fica bloqueado.'
            : ($idade >= Catalogo::IDADE_ADULTA ? 'Você tem acesso ao chat dos iglus.' : 'O chat dos iglus fica liberado aos 18 anos.') ?>
      </p>
      <div class="barra-acoes"><button class="botao" type="submit">Salvar dados</button></div>
    </form>
  </section>

  <section class="cartao" id="privacidade" aria-labelledby="titulo-privacidade">
    <h2 class="cartao__titulo" id="titulo-privacidade">🔒 Privacidade</h2>
    <form method="post" action="conta.php" class="form-conta">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="privacidade">
      <fieldset class="opcoes-coluna">
        <legend>Quem pode ver meu perfil completo</legend>
        <?php foreach (UsuarioController::VISIBILIDADES as $valor => $rotulo): ?>
          <label><input type="radio" name="visibilidade" value="<?= e($valor) ?>" <?= $usuario['perfil_visibilidade'] === $valor ? 'checked' : '' ?>> <?= e($rotulo) ?></label>
        <?php endforeach; ?>
      </fieldset>
      <label class="caixa"><input type="checkbox" name="aparecer_busca" value="1" <?= $usuario['aparecer_busca'] ? 'checked' : '' ?>> Aparecer na pesquisa por @</label>
      <label class="caixa"><input type="checkbox" name="mostrar_online" value="1" <?= $usuario['mostrar_online'] ? 'checked' : '' ?>> Mostrar quando estou online</label>
      <div class="barra-acoes"><button class="botao" type="submit">Salvar privacidade</button></div>
    </form>
  </section>

  <section class="cartao" id="senha" aria-labelledby="titulo-senha">
    <h2 class="cartao__titulo" id="titulo-senha">🔑 Senha</h2>
    <form method="post" action="conta.php" class="form-conta">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="senha">
      <label class="campo"><span>Senha atual</span><input type="password" name="senha_atual" required autocomplete="current-password"></label>
      <?= $erroDe('senha_atual') ?>
      <label class="campo"><span>Nova senha</span><input type="password" name="senha" required minlength="8" maxlength="72" autocomplete="new-password"></label>
      <label class="campo"><span>Confirmar nova senha</span><input type="password" name="confirmar_senha" required minlength="8" maxlength="72" autocomplete="new-password"></label>
      <?= $erroDe('senha') ?>
      <div class="barra-acoes"><button class="botao" type="submit">Trocar senha</button></div>
    </form>
  </section>

  <section class="cartao" id="professor" aria-labelledby="titulo-professor">
    <h2 class="cartao__titulo" id="titulo-professor">🎓 Perfil de professor</h2>
    <p class="campo__ajuda">Professores criam linguagens e assuntos na aba Comunidade, montam módulos, lições e questões, e respondem às perguntas dos alunos. Disponível para maiores de 18 anos.</p>
    <form method="post" action="conta.php">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="professor">
      <?php if (($usuario['papel_usuario'] ?? '') === 'professor'): ?>
        <p><span class="selo selo--novo">Perfil de professor ativo</span> <a href="professor.php">Ir para o painel do professor</a></p>
        <button class="botao botao--fantasma" type="submit">Desativar perfil de professor</button>
      <?php else: ?>
        <input type="hidden" name="professor" value="1">
        <button class="botao" type="submit" <?= ($idade ?? 0) < Catalogo::IDADE_ADULTA ? 'disabled title="Disponível a partir dos 18 anos"' : '' ?>>Quero ser professor</button>
      <?php endif; ?>
    </form>
    <?= $erroDe('papel') ?>
  </section>

  <section class="cartao" id="exportar" aria-labelledby="titulo-exportar">
    <h2 class="cartao__titulo" id="titulo-exportar">📦 Baixar meus dados</h2>
    <p class="campo__ajuda">Um arquivo JSON com tudo o que guardamos sobre você: conta, trilhas, progresso, certificados, amizades, iglus, mensagens e perguntas.</p>
    <form method="post" action="conta.php">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="exportar">
      <button class="botao botao--fantasma" type="submit">Baixar meus dados (.json)</button>
    </form>
    <p class="campo__ajuda">Leia a <a href="privacidade.php">Política de Privacidade</a> e os <a href="termos.php">Termos de Uso</a>.
      Consentimento registrado em <?= e($usuario['aceite_termos_em'] ? date('d/m/Y H:i', strtotime($usuario['aceite_termos_em'])) : '—') ?>.</p>
  </section>

  <section class="cartao cartao--perigo" id="excluir" aria-labelledby="titulo-excluir">
    <h2 class="cartao__titulo" id="titulo-excluir">🗑️ Excluir conta</h2>
    <p class="campo__ajuda">Apaga sua conta e seus dados pessoais: progresso, certificados, amizades, mensagens e perguntas. Seus iglus passam para outro membro. <strong>Não dá para desfazer.</strong> Se quiser, baixe seus dados antes.</p>
    <form method="post" action="conta.php" class="form-conta" onsubmit="return confirm('Excluir sua conta para sempre?')">
      <?= campoCsrf() ?>
      <input type="hidden" name="acao" value="excluir">
      <label class="campo"><span>Sua senha</span><input type="password" name="senha_exclusao" required autocomplete="current-password"></label>
      <?= $erroDe('senha_exclusao') ?>
      <label class="campo"><span>Digite EXCLUIR para confirmar</span><input name="confirmacao" required pattern="EXCLUIR" autocomplete="off"></label>
      <?= $erroDe('confirmacao') ?>
      <div class="barra-acoes"><button class="botao botao--perigo" type="submit">Excluir minha conta</button></div>
    </form>
  </section>
</div>
<?php require __DIR__ . '/app/Views/app_fim.php'; ?>
