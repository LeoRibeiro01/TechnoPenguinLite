<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Support\Catalogo;

if (($usuario = usuarioAtual()) !== null) {
    redirecionar(destinoInicial($usuario));
}

$valores = ['nome' => '', 'arroba' => '', 'email' => '', 'nascimento' => '', 'papel' => 'aluno', 'email_responsavel' => ''];
$erros = [];
if (metodoPost()) {
    validarCsrf();
    $dados = [
        'nome' => trim((string) ($_POST['nome'] ?? '')),
        'arroba' => UsuarioController::normalizarArroba((string) ($_POST['arroba'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'senha' => (string) ($_POST['senha'] ?? ''),
        'nascimento' => (string) ($_POST['nascimento'] ?? ''),
        'papel' => ($_POST['papel'] ?? '') === 'professor' ? 'professor' : 'aluno',
        'aceite' => !empty($_POST['aceite']),
        'email_responsavel' => trim((string) ($_POST['email_responsavel'] ?? '')),
        'consentimento_responsavel' => !empty($_POST['consentimento_responsavel']),
    ];
    $valores = array_intersect_key($dados, $valores);

    $usuarios = new UsuarioController(conexao());
    $erros = $usuarios->validarCadastro($dados);
    if (!isset($erros['senha']) && $dados['senha'] !== ($_POST['confirmar_senha'] ?? null)) {
        $erros['confirmar_senha'] = 'As senhas não coincidem.';
    }
    if ($erros === []) {
        try {
            $usuario = $usuarios->store($dados);
            entrar($usuario);
            redirecionar('boas-vindas.php');
        } catch (ValidationException $e) {
            $erros = $e->erros;
        }
    }
}

/** Atributos de acessibilidade e a mensagem de erro de um campo. */
function erroCampo(array $erros, string $campo): array
{
    if (!isset($erros[$campo])) {
        return ['', ''];
    }
    return [
        'aria-invalid="true" aria-describedby="erro-' . $campo . '"',
        '<p class="auth-field-error" id="erro-' . $campo . '">' . e($erros[$campo]) . '</p>',
    ];
}

[$attrNome, $msgNome] = erroCampo($erros, 'nome');
[$attrArroba, $msgArroba] = erroCampo($erros, 'arroba');
[$attrEmail, $msgEmail] = erroCampo($erros, 'email');
[$attrNascimento, $msgNascimento] = erroCampo($erros, 'nascimento');
[$attrSenha, $msgSenha] = erroCampo($erros, 'senha');
[$attrConfirmar, $msgConfirmar] = erroCampo($erros, 'confirmar_senha');

$tituloPagina = 'Criar conta';
$descricaoPagina = 'Crie sua conta TechnoPenguin e comece a aprender programação.';
require __DIR__ . '/app/Views/auth_inicio.php';
?>
        <h1 id="auth-title">Crie seu perfil</h1>
        <p class="auth-intro">Leva menos de 1 minuto.</p>

        <?php if ($erros !== []): ?>
          <p class="auth-alert" role="alert">Corrija os campos destacados para continuar.</p>
        <?php endif; ?>

        <form class="auth-form" action="cadastro.php" method="post" id="form-cadastro">
          <?= campoCsrf() ?>
          <fieldset class="auth-papel">
            <legend>Eu sou</legend>
            <label><input type="radio" name="papel" value="aluno" <?= $valores['papel'] === 'aluno' ? 'checked' : '' ?>> Aluno</label>
            <label><input type="radio" name="papel" value="professor" <?= $valores['papel'] === 'professor' ? 'checked' : '' ?>> Professor</label>
          </fieldset>
          <?= isset($erros['papel']) ? '<p class="auth-field-error">' . e($erros['papel']) . '</p>' : '' ?>

          <label for="nome">Nome</label>
          <input id="nome" name="nome" type="text" placeholder="Como você quer ser chamado" autocomplete="name"
            value="<?= e($valores['nome']) ?>" required minlength="2" maxlength="50" <?= $attrNome ?>>
          <?= $msgNome ?>

          <label for="arroba">Seu @</label>
          <div class="auth-arroba">
            <span aria-hidden="true">@</span>
            <input id="arroba" name="arroba" type="text" placeholder="seu_usuario" autocomplete="username"
              value="<?= e($valores['arroba']) ?>" required minlength="3" maxlength="30" pattern="@?[a-z0-9_.]{3,30}"
              title="De 3 a 30 caracteres: letras minúsculas sem acento, números, _ ou ." autocapitalize="none"
              spellcheck="false" <?= $attrArroba ?>>
          </div>
          <?= $msgArroba ?: '<p class="auth-hint">Único, como no Instagram. É por ele que seus amigos vão te encontrar.</p>' ?>

          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="Digite seu email" autocomplete="email"
            value="<?= e($valores['email']) ?>" required maxlength="50" <?= $attrEmail ?>>
          <?= $msgEmail ?>

          <label for="nascimento">Data de nascimento</label>
          <input id="nascimento" name="nascimento" type="date" autocomplete="bday" max="<?= date('Y-m-d') ?>"
            value="<?= e($valores['nascimento']) ?>" required <?= $attrNascimento ?>>
          <?= $msgNascimento ?: '<p class="auth-hint">O chat dos iglus é liberado só para maiores de 18 anos.</p>' ?>

          <div class="auth-responsavel" id="responsavel" <?= isset($erros['email_responsavel']) || isset($erros['consentimento_responsavel']) ? '' : 'hidden' ?>>
            <p>Como você tem menos de <?= Catalogo::IDADE_CRIANCA ?> anos, a LGPD exige a autorização de um responsável.</p>
            <label for="email_responsavel">Email do responsável</label>
            <input id="email_responsavel" name="email_responsavel" type="email" maxlength="50" value="<?= e($valores['email_responsavel']) ?>">
            <?= isset($erros['email_responsavel']) ? '<p class="auth-field-error">' . e($erros['email_responsavel']) . '</p>' : '' ?>
            <label class="auth-check">
              <input type="checkbox" name="consentimento_responsavel" value="1">
              Meu responsável leu a Política de Privacidade e autorizou este cadastro.
            </label>
            <?= isset($erros['consentimento_responsavel']) ? '<p class="auth-field-error">' . e($erros['consentimento_responsavel']) . '</p>' : '' ?>
          </div>

          <label for="senha">Senha</label>
          <input id="senha" name="senha" type="password" placeholder="Digite sua senha" autocomplete="new-password"
            required minlength="8" maxlength="72" <?= $attrSenha ?>>
          <?= $msgSenha ?: '<p class="auth-hint">Mínimo de 8 caracteres.</p>' ?>

          <label for="confirmar_senha">Confirmar senha</label>
          <input id="confirmar_senha" name="confirmar_senha" type="password" placeholder="Digite sua senha novamente"
            autocomplete="new-password" required minlength="8" maxlength="72" <?= $attrConfirmar ?>>
          <?= $msgConfirmar ?>

          <label class="auth-check">
            <input type="checkbox" name="aceite" value="1" required>
            Li e aceito os <a href="termos.php" target="_blank">Termos de Uso</a> e a
            <a href="privacidade.php" target="_blank">Política de Privacidade</a>.
          </label>
          <?= isset($erros['aceite']) ? '<p class="auth-field-error">' . e($erros['aceite']) . '</p>' : '' ?>

          <button class="auth-submit" type="submit">Avançar</button>
        </form>

        <p class="auth-switch">Já tem uma conta? <a href="login.php">Entrar</a></p>

        <script>
          const senha = document.querySelector('#senha');
          const confirmar = document.querySelector('#confirmar_senha');
          const arroba = document.querySelector('#arroba');
          const nascimento = document.querySelector('#nascimento');
          const responsavel = document.querySelector('#responsavel');
          const conferir = () => confirmar.setCustomValidity(
            confirmar.value === '' || confirmar.value === senha.value ? '' : 'As senhas não coincidem.'
          );
          senha.addEventListener('input', conferir);
          confirmar.addEventListener('input', conferir);
          arroba.addEventListener('input', () => {
            arroba.value = arroba.value.toLowerCase().replace(/^@/, '').replace(/\s/g, '');
          });
          // Mostra os campos do responsável para menores de <?= Catalogo::IDADE_CRIANCA ?> anos.
          const conferirIdade = () => {
            if (!nascimento.value) return;
            const data = new Date(nascimento.value + 'T00:00');
            const hoje = new Date();
            let idade = hoje.getFullYear() - data.getFullYear();
            if (hoje < new Date(hoje.getFullYear(), data.getMonth(), data.getDate())) idade--;
            responsavel.hidden = idade >= <?= Catalogo::IDADE_CRIANCA ?>;
          };
          nascimento.addEventListener('change', conferirIdade);
          conferirIdade();
        </script>
<?php require __DIR__ . '/app/Views/auth_fim.php'; ?>
