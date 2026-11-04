<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\IgluMensagem;

/**
 * Chat de texto dos iglus. Só para membros maiores de 18 anos e só quando os gestores do
 * iglu deixam o chat ligado.
 */
final class ChatController
{
    public const TAMANHO_MAXIMO = 500;
    /** Intervalo mínimo entre mensagens do mesmo usuário, contra spam. */
    public const SEGUNDOS_ENTRE_MENSAGENS = 2;

    public function __construct(private readonly PDO $pdo)
    {
    }

    /** Motivo pelo qual o usuário não pode usar o chat, ou null quando pode. */
    public function bloqueio(array $usuario, array $iglu): ?string
    {
        $idade = UsuarioController::idade($usuario);
        if ($idade === null) {
            return 'Informe sua data de nascimento em Minha conta para usar o chat.';
        }
        if ($idade < \TechnoPenguin\Support\Catalogo::IDADE_ADULTA) {
            return 'O chat dos iglus é liberado apenas para maiores de 18 anos.';
        }
        if ((new Iglu($this->pdo))->papel((int) $iglu['id_iglu'], (int) $usuario['id_usuario']) === null) {
            return 'Entre no iglu para participar do chat.';
        }
        if (!(bool) $iglu['chat_ativo']) {
            return 'Os admins do iglu desativaram o chat.';
        }
        return null;
    }

    public function mensagens(array $usuario, array $iglu, int $depois): array
    {
        $this->exigirAcesso($usuario, $iglu);
        $gestor = (new IgluController($this->pdo))->ehGestor((int) $iglu['id_iglu'], (int) $usuario['id_usuario']);
        return array_map(static fn (array $m): array => [
            'id' => (int) $m['id_mensagem'],
            'arroba' => $m['arroba'],
            'nome' => $m['nome'],
            'texto' => $m['texto_mensagem'],
            'hora' => date('d/m H:i', strtotime($m['criada_em'])),
            'minha' => (int) $m['id_usuario'] === (int) $usuario['id_usuario'],
            'pode_apagar' => $gestor || (int) $m['id_usuario'] === (int) $usuario['id_usuario'],
        ], (new IgluMensagem($this->pdo))->depoisDe((int) $iglu['id_iglu'], $depois));
    }

    public function enviar(array $usuario, array $iglu, string $texto): void
    {
        $this->exigirAcesso($usuario, $iglu);
        $texto = trim(preg_replace('/[^\P{C}\n]+/u', '', $texto));
        if ($texto === '' || mb_strlen($texto) > self::TAMANHO_MAXIMO) {
            throw new ValidationException(['mensagem' => 'Escreva uma mensagem de até ' . self::TAMANHO_MAXIMO . ' caracteres.']);
        }
        $mensagens = new IgluMensagem($this->pdo);
        $ultima = $mensagens->ultimaDoUsuario((int) $usuario['id_usuario']);
        if ($ultima !== null && strtotime($ultima) > time() - self::SEGUNDOS_ENTRE_MENSAGENS) {
            throw new ValidationException(['mensagem' => 'Calma, pinguim! Espere um instante entre as mensagens.']);
        }
        $mensagens->create(['id_iglu' => (int) $iglu['id_iglu'], 'id_usuario' => (int) $usuario['id_usuario'], 'texto_mensagem' => $texto]);
    }

    /** O autor apaga a própria mensagem; dono e admins apagam qualquer uma do iglu. */
    public function apagar(array $usuario, array $iglu, int $idMensagem): void
    {
        $this->exigirAcesso($usuario, $iglu);
        $mensagens = new IgluMensagem($this->pdo);
        $mensagem = $mensagens->find($idMensagem);
        if ($mensagem === null || (int) $mensagem['id_iglu'] !== (int) $iglu['id_iglu']) {
            return;
        }
        $gestor = (new IgluController($this->pdo))->ehGestor((int) $iglu['id_iglu'], (int) $usuario['id_usuario']);
        if (!$gestor && (int) $mensagem['id_usuario'] !== (int) $usuario['id_usuario']) {
            throw new ValidationException(['mensagem' => 'Você só pode apagar suas mensagens.']);
        }
        $mensagens->delete($idMensagem);
    }

    private function exigirAcesso(array $usuario, array $iglu): void
    {
        $motivo = $this->bloqueio($usuario, $iglu);
        if ($motivo !== null) {
            throw new ValidationException(['chat' => $motivo]);
        }
    }
}
