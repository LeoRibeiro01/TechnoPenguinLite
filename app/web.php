<?php
declare(strict_types=1);

/** Ponto de entrada comum das páginas PHP: sessão, CSRF, usuário logado e helpers de view. */

require_once __DIR__ . '/bootstrap.php';

use TechnoPenguin\Controllers\GeloController;
use TechnoPenguin\Controllers\UsuarioController;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Models\Amizade;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Trilhas;
use TechnoPenguin\Support\Vestuario;

carregarAmbiente(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');

set_exception_handler(static function (Throwable $e): void {
    error_log((string) $e);
    if (!headers_sent()) {
        http_response_code(500);
    }
    $titulo = 'Algo correu mal';
    $mensagem = strtolower(getenv('APP_ENV') ?: 'development') === 'production'
        ? 'Não conseguimos completar o pedido. Tente novamente daqui a pouco.'
        : $e->getMessage();
    require __DIR__ . '/Views/erro.php';
});

session_name('technopenguin');
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => ($_SERVER['HTTPS'] ?? 'off') !== 'off',
]);
session_start();

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: same-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}

function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function conexao(): PDO
{
    static $pdo = null;
    return $pdo ??= criarConexao();
}

function redirecionar(string $destino): never
{
    header('Location: ' . $destino, true, 303);
    exit;
}

function metodoPost(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function csrfToken(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function campoCsrf(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}

/** Interrompe o pedido quando o formulário não traz o token desta sessão. */
function validarCsrf(): void
{
    $token = $_POST['csrf'] ?? null;
    if (!is_string($token) || !hash_equals(csrfToken(), $token)) {
        http_response_code(419);
        $titulo = 'Sessão expirada';
        $mensagem = 'O formulário expirou. Volte à página anterior, recarregue e tente de novo.';
        require __DIR__ . '/Views/erro.php';
        exit;
    }
}

function definirAviso(string $mensagem): void
{
    $_SESSION['aviso'] = $mensagem;
}

function consumirAviso(): ?string
{
    $aviso = $_SESSION['aviso'] ?? null;
    unset($_SESSION['aviso']);
    return $aviso;
}

function entrar(array $usuario): void
{
    session_regenerate_id(true);
    $_SESSION['id_usuario'] = (int) $usuario['id_usuario'];
    unset($_SESSION['csrf']);
}

/** Limpa os dados e troca o id da sessão; a sessão nova ainda pode levar um aviso. */
function sair(): void
{
    $_SESSION = [];
    session_regenerate_id(true);
}

/** Usuário da sessão, sem o hash da senha; null quando ninguém está logado. */
function usuarioAtual(bool $recarregar = false): ?array
{
    static $usuario = null;
    $id = $_SESSION['id_usuario'] ?? null;
    if (!is_int($id)) {
        return null;
    }
    if ($usuario === null || $recarregar) {
        $usuario = (new Usuario(conexao()))->find($id);
        if ($usuario === null) {
            sair();
            return null;
        }
        unset($usuario['senha']);
    }
    return $usuario;
}

/** Exige sessão; também marca o acesso (para o "online") e verifica o gelo uma vez por dia. */
function exigirLogin(): array
{
    $usuario = usuarioAtual();
    if ($usuario === null) {
        definirAviso('Entre na sua conta para continuar.');
        redirecionar('login.php');
    }
    $idUsuario = (int) $usuario['id_usuario'];
    (new Usuario(conexao()))->registrarAcesso($idUsuario);
    if (($_SESSION['gelo_verificado'] ?? null) !== date('Y-m-d')) {
        (new GeloController(conexao()))->verificar($idUsuario);
        $_SESSION['gelo_verificado'] = date('Y-m-d');
    }
    return $usuario;
}

/** Exige sessão e o questionário inicial concluído; senão leva para a etapa pendente. */
function exigirCadastroCompleto(): array
{
    $usuario = exigirLogin();
    $etapa = (new UsuarioController(conexao()))->etapaPendente($usuario);
    if ($etapa !== null) {
        redirecionar(paginaDaEtapa($etapa));
    }
    return $usuario;
}

function paginaDaEtapa(string $etapa): string
{
    return $etapa === 'avatar' ? 'personalizar.php' : 'boas-vindas.php';
}

function destinoInicial(array $usuario): string
{
    $etapa = (new UsuarioController(conexao()))->etapaPendente($usuario);
    return $etapa === null ? 'painel.php' : paginaDaEtapa($etapa);
}

function coresAvatar(array $usuario): array
{
    return [
        'corpo' => $usuario['cor_corpo_avatar'] ?? Catalogo::AVATAR_PADRAO['corpo'],
        'barriga' => $usuario['cor_barriga_avatar'] ?? Catalogo::AVATAR_PADRAO['barriga'],
        'detalhe' => $usuario['cor_detalhe_avatar'] ?? Catalogo::AVATAR_PADRAO['detalhe'],
        'fundo' => $usuario['cor_fundo_avatar'] ?? Catalogo::AVATAR_PADRAO['fundo'],
    ];
}

/** camada => slug do item vestido (ou null). */
function vestimentaAvatar(array $usuario): array
{
    $itens = [];
    foreach (array_keys(Vestuario::CAMADAS) as $camada) {
        $slug = $usuario["{$camada}_avatar"] ?? null;
        $itens[$camada] = $slug !== null && Vestuario::item($slug) !== null ? $slug : null;
    }
    return $itens;
}

/**
 * Pinguim em camadas: a foto original (recolorida no navegador por assets/js/pinguim.js)
 * e as peças de vestuário por cima. Classes: avatar--corpo (corpo inteiro) ou avatar--rosto.
 */
function avatar(array $cores, array $itens, string $classe = 'avatar--corpo', string $alt = ''): string
{
    $html = '<div class="avatar ' . e($classe) . '" style="--fundo:' . e($cores['fundo']) . '">'
        . '<img class="avatar__camada" src="assets/imgs/pinguim/pinguim_original.png" alt="' . e($alt) . '"'
        . ' data-pinguim="' . e($cores['corpo'] . ',' . $cores['barriga'] . ',' . $cores['detalhe']) . '">';
    foreach (array_keys(Vestuario::CAMADAS) as $camada) {
        $item = isset($itens[$camada]) ? Vestuario::item($itens[$camada]) : null;
        $html .= '<img class="avatar__camada" alt="" data-camada="' . e($camada) . '"'
            . ($item === null
                ? ' hidden src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="'
                : ' src="' . e($item['imagem']) . '" style="filter:' . e($item['filtro'] ?: 'none') . '"')
            . '>';
    }
    return $html . '</div>';
}

function avatarDe(array $usuario, string $classe = 'avatar--corpo'): string
{
    return avatar(coresAvatar($usuario), vestimentaAvatar($usuario), $classe, 'Pinguim de @' . $usuario['arroba']);
}

/** O Teco: pinguim original de terno e cartola, que guia o questionário inicial. */
function teco(string $classe = 'avatar--corpo avatar--teco'): string
{
    $cores = Catalogo::AVATAR_PADRAO;
    $cores['fundo'] = 'transparent';
    return avatar($cores, Vestuario::TECO, $classe, 'Teco, o pinguim de terno e cartola');
}

function iconeTrilha(string $slug, string $classe = ''): string
{
    $trilha = trilhaInfo($slug);
    if ($trilha === null) {
        return '';
    }
    return '<span class="icone-trilha ' . e($classe) . '" style="--cor-trilha:' . e($trilha['cor']) . '" aria-hidden="true">'
        . e($trilha['sigla']) . '</span>';
}

/** Online quando abriu alguma página nos últimos 5 minutos. */
function estaOnline(array $usuario): bool
{
    if (isset($usuario['mostrar_online']) && !(bool) $usuario['mostrar_online']) {
        return false;
    }
    $ultimo = $usuario['ultimo_acesso_usuario'] ?? null;
    return $ultimo !== null && strtotime($ultimo) >= time() - 300;
}

function nivelXp(int $xp): int
{
    return intdiv($xp, 100) + 1;
}

function plural(int $n, string $um, string $varios): string
{
    return $n . ' ' . ($n === 1 ? $um : $varios);
}

/** Dados de uma trilha oficial ou da comunidade (slug, titulo, tipo, sigla, cor, descricao...). */
function trilhaInfo(string $slug): ?array
{
    return Trilhas::info(conexao(), $slug);
}

function linkPerfil(array $usuario): string
{
    return 'perfil.php?u=' . rawurlencode($usuario['arroba']);
}

/** Respeita a escolha de privacidade do dono do perfil. */
function podeVerPerfil(array $dono, array $leitor): bool
{
    if ((int) $dono['id_usuario'] === (int) $leitor['id_usuario']) {
        return true;
    }
    return match ($dono['perfil_visibilidade'] ?? 'todos') {
        'privado' => false,
        'amigos' => ((new Amizade(conexao()))->relacoes((int) $leitor['id_usuario'])[(int) $dono['id_usuario']] ?? null) === 'amigo',
        default => true,
    };
}

function idadeDe(array $usuario): ?int
{
    return UsuarioController::idade($usuario);
}
