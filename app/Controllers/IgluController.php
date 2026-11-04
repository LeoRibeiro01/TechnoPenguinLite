<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\IgluConvite;
use TechnoPenguin\Models\Notificacao;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Models\UsuarioLinguagem;
use TechnoPenguin\Support\Trilhas;

/**
 * Iglus: grupos de estudo. O dono e os admins gerem trilhas, membros e pedidos;
 * qualquer membro pode convidar. Ao entrar, as trilhas obrigatórias vão para o perfil.
 */
final class IgluController extends ResourceController
{
    protected const MODEL_CLASS = Iglu::class;
    private const GESTORES = ['dono', 'admin'];

    public function __construct(private readonly PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /** $trilhas: slugs estudados no iglu; $obrigatorias: subconjunto exigido de quem entra. */
    public function criar(int $idUsuario, array $dados): array
    {
        [$campos, $trilhas] = $this->validar($dados);
        $this->pdo->beginTransaction();
        try {
            $iglu = $this->iglus()->create($campos + ['id_criador' => $idUsuario]);
            $id = (int) $iglu['id_iglu'];
            $this->iglus()->adicionarMembro($id, $idUsuario, 'dono');
            $this->iglus()->substituirTrilhas($id, $trilhas);
            (new UsuarioLinguagem($this->pdo))->adicionar($idUsuario, array_keys(array_filter($trilhas)));
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        (new ConquistaController($this->pdo))->avaliar($idUsuario);
        return $iglu;
    }

    public function editar(int $idUsuario, int $idIglu, array $dados): void
    {
        $this->exigirGestor($idIglu, $idUsuario);
        [$campos, $trilhas] = $this->validar($dados);
        $this->iglus()->update($idIglu, $campos);
        $this->iglus()->substituirTrilhas($idIglu, $trilhas);
    }

    /** Convida pelo @. Se a pessoa já tinha pedido para entrar e quem convida é gestor, ela entra. */
    public function convidar(array $remetente, int $idIglu, string $arroba): string
    {
        $idRemetente = (int) $remetente['id_usuario'];
        $iglu = $this->exigirMembro($idIglu, $idRemetente);
        $convidado = (new Usuario($this->pdo))->buscarPorArroba(UsuarioController::normalizarArroba($arroba));
        if ($convidado === null) {
            throw new ValidationException(['convite' => 'Nenhum usuário com esse @.']);
        }
        $idConvidado = (int) $convidado['id_usuario'];
        if ($this->iglus()->papel($idIglu, $idConvidado) !== null) {
            throw new ValidationException(['convite' => "@{$convidado['arroba']} já está no iglu."]);
        }
        $pendente = $this->convites()->pendente($idIglu, $idConvidado);
        if ($pendente !== null) {
            if ($pendente['tipo_convite'] === 'pedido' && $this->ehGestor($idIglu, $idRemetente)) {
                return $this->responderPedido($remetente, (int) $pendente['id_convite'], true);
            }
            throw new ValidationException(['convite' => "@{$convidado['arroba']} já tem um convite pendente."]);
        }
        $this->convites()->create([
            'id_iglu' => $idIglu, 'id_usuario' => $idConvidado, 'id_remetente' => $idRemetente, 'tipo_convite' => 'convite',
        ]);
        (new Notificacao($this->pdo))->enviar(
            $idConvidado,
            'iglu',
            "@{$remetente['arroba']} convidou você para o iglu {$iglu['nome_iglu']}.",
            'iglu.php?id=' . $idIglu . '&entrar=1'
        );
        return "Convite enviado para @{$convidado['arroba']}.";
    }

    /** Pedido para entrar; a pessoa já aceitou as trilhas obrigatórias ao pedir. */
    public function pedirEntrada(array $usuario, int $idIglu): string
    {
        $idUsuario = (int) $usuario['id_usuario'];
        $iglu = $this->iglus()->find($idIglu) ?? throw new ValidationException(['iglu' => 'Iglu não encontrado.']);
        if ($this->iglus()->papel($idIglu, $idUsuario) !== null) {
            throw new ValidationException(['iglu' => 'Você já está neste iglu.']);
        }
        $pendente = $this->convites()->pendente($idIglu, $idUsuario);
        if ($pendente !== null && $pendente['tipo_convite'] === 'convite') {
            return $this->aceitarConvite($usuario, (int) $pendente['id_convite']);
        }
        if (!(bool) $iglu['aberto_iglu']) {
            throw new ValidationException(['iglu' => 'Este iglu é fechado: só entra quem recebe convite.']);
        }
        if ($pendente !== null) {
            throw new ValidationException(['iglu' => 'Seu pedido já está à espera de aprovação.']);
        }
        $this->convites()->create(['id_iglu' => $idIglu, 'id_usuario' => $idUsuario, 'tipo_convite' => 'pedido']);
        $gestores = array_column(array_filter(
            $this->iglus()->membros($idIglu),
            static fn (array $m): bool => in_array($m['papel_membro'], self::GESTORES, true)
        ), 'id_usuario');
        (new Notificacao($this->pdo))->enviarParaVarios(
            $gestores,
            'iglu',
            "@{$usuario['arroba']} pediu para entrar no iglu {$iglu['nome_iglu']}.",
            'iglu.php?id=' . $idIglu
        );
        return 'Pedido enviado! Um admin do iglu vai analisar.';
    }

    public function aceitarConvite(array $usuario, int $idConvite): string
    {
        $convite = $this->convites()->find($idConvite);
        if ($convite === null || (int) $convite['id_usuario'] !== (int) $usuario['id_usuario']
            || $convite['tipo_convite'] !== 'convite' || $convite['status_convite'] !== 'pendente') {
            throw new ValidationException(['iglu' => 'Este convite não existe mais.']);
        }
        $iglu = $this->entrar((int) $convite['id_iglu'], (int) $usuario['id_usuario'], $idConvite);
        if ($convite['id_remetente'] !== null) {
            (new Notificacao($this->pdo))->enviar(
                (int) $convite['id_remetente'],
                'iglu',
                "@{$usuario['arroba']} aceitou o convite e entrou no iglu {$iglu['nome_iglu']}!",
                'iglu.php?id=' . $iglu['id_iglu']
            );
        }
        return "Bem-vindo ao iglu {$iglu['nome_iglu']}!";
    }

    public function recusarConvite(int $idUsuario, int $idConvite): void
    {
        $convite = $this->convites()->find($idConvite);
        if ($convite !== null && (int) $convite['id_usuario'] === $idUsuario && $convite['status_convite'] === 'pendente') {
            $this->convites()->update($idConvite, ['status_convite' => 'recusado']);
        }
    }

    public function responderPedido(array $gestor, int $idConvite, bool $aprovar): string
    {
        $convite = $this->convites()->find($idConvite);
        if ($convite === null || $convite['tipo_convite'] !== 'pedido' || $convite['status_convite'] !== 'pendente') {
            throw new ValidationException(['iglu' => 'Este pedido não existe mais.']);
        }
        $idIglu = (int) $convite['id_iglu'];
        $this->exigirGestor($idIglu, (int) $gestor['id_usuario']);
        $notificacoes = new Notificacao($this->pdo);
        if (!$aprovar) {
            $this->convites()->update($idConvite, ['status_convite' => 'recusado']);
            $iglu = $this->iglus()->find($idIglu);
            $notificacoes->enviar((int) $convite['id_usuario'], 'iglu', "Seu pedido para entrar no iglu {$iglu['nome_iglu']} foi recusado.");
            return 'Pedido recusado.';
        }
        $iglu = $this->entrar($idIglu, (int) $convite['id_usuario'], $idConvite);
        $notificacoes->enviar((int) $convite['id_usuario'], 'iglu', "Você entrou no iglu {$iglu['nome_iglu']}! As trilhas obrigatórias já estão no seu perfil.", 'iglu.php?id=' . $idIglu);
        return 'Pedido aprovado.';
    }

    /** Sai do iglu. Se o dono sai, o membro mais antigo (de preferência admin) vira dono; sem membros, o iglu é apagado. */
    public function sair(int $idUsuario, int $idIglu): void
    {
        $papel = $this->iglus()->papel($idIglu, $idUsuario);
        if ($papel === null) {
            return;
        }
        $this->iglus()->removerMembro($idIglu, $idUsuario);
        $restantes = $this->iglus()->membros($idIglu);
        if ($restantes === []) {
            $this->iglus()->delete($idIglu);
            return;
        }
        if ($papel === 'dono') {
            usort($restantes, static fn (array $a, array $b): int =>
                [$a['papel_membro'] !== 'admin', $a['entrou_em']] <=> [$b['papel_membro'] !== 'admin', $b['entrou_em']]);
            $this->iglus()->alterarPapel($idIglu, (int) $restantes[0]['id_usuario'], 'dono');
        }
    }

    /** Só o dono nomeia ou tira admins. */
    public function definirPapel(int $idDono, int $idIglu, int $idMembro, string $papel): void
    {
        if ($this->iglus()->papel($idIglu, $idDono) !== 'dono') {
            throw new ValidationException(['iglu' => 'Só o dono do iglu pode mudar os papéis.']);
        }
        if (!in_array($papel, ['admin', 'membro'], true) || $idMembro === $idDono || $this->iglus()->papel($idIglu, $idMembro) === null) {
            throw new ValidationException(['iglu' => 'Não foi possível mudar o papel deste membro.']);
        }
        $this->iglus()->alterarPapel($idIglu, $idMembro, $papel);
    }

    /** Gestores removem membros; admins não removem outros admins nem o dono. */
    public function removerMembro(int $idGestor, int $idIglu, int $idMembro): void
    {
        $papelGestor = $this->exigirGestor($idIglu, $idGestor);
        $papelMembro = $this->iglus()->papel($idIglu, $idMembro);
        if ($papelMembro === null || $papelMembro === 'dono' || ($papelMembro === 'admin' && $papelGestor !== 'dono')) {
            throw new ValidationException(['iglu' => 'Você não pode remover este membro.']);
        }
        $this->iglus()->removerMembro($idIglu, $idMembro);
    }

    public function excluir(int $idUsuario, int $idIglu): void
    {
        if ($this->iglus()->papel($idIglu, $idUsuario) !== 'dono') {
            throw new ValidationException(['iglu' => 'Só o dono pode apagar o iglu.']);
        }
        $iglu = $this->iglus()->find($idIglu);
        $outros = array_diff($this->iglus()->idsMembros($idIglu), [$idUsuario]);
        $this->iglus()->delete($idIglu);
        (new Notificacao($this->pdo))->enviarParaVarios($outros, 'iglu', "O iglu {$iglu['nome_iglu']} foi apagado pelo dono.");
    }

    public function ehGestor(int $idIglu, int $idUsuario): bool
    {
        return in_array($this->iglus()->papel($idIglu, $idUsuario), self::GESTORES, true);
    }

    /** Adiciona o membro e copia as trilhas obrigatórias para o perfil dele. */
    private function entrar(int $idIglu, int $idUsuario, int $idConvite): array
    {
        $iglu = $this->iglus()->find($idIglu) ?? throw new ValidationException(['iglu' => 'Iglu não encontrado.']);
        $this->pdo->beginTransaction();
        try {
            $this->iglus()->adicionarMembro($idIglu, $idUsuario);
            (new UsuarioLinguagem($this->pdo))->adicionar($idUsuario, array_keys(array_filter($this->iglus()->trilhas($idIglu))));
            $this->convites()->update($idConvite, ['status_convite' => 'aceito']);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        (new ConquistaController($this->pdo))->avaliar($idUsuario);
        return $iglu;
    }

    /** Ícones que o criador pode escolher para o iglu. */
    public const ICONES = ['🐧', '🧊', '🏔️', '❄️', '🐟', '🚀', '💻', '🤖', '📚', '🎮', '🌐', '🔐', '🎨', '🧪', '⚡', '🌈'];

    private function validar(array $dados): array
    {
        $nome = trim((string) ($dados['nome'] ?? ''));
        $descricao = trim((string) ($dados['descricao'] ?? ''));
        $trilhas = array_values(array_filter(
            array_unique(array_filter((array) ($dados['trilhas'] ?? []), 'is_string')),
            fn (string $t): bool => Trilhas::disponivel($this->pdo, $t)
        ));
        $obrigatorias = array_intersect((array) ($dados['obrigatorias'] ?? []), $trilhas);
        $cor = strtolower(trim((string) ($dados['cor'] ?? '#5c7aa5')));
        $icone = (string) ($dados['icone'] ?? self::ICONES[0]);
        $erros = [];
        if (mb_strlen($nome) < 3 || mb_strlen($nome) > 60) {
            $erros['nome'] = 'O nome do iglu precisa ter de 3 a 60 caracteres.';
        }
        if (mb_strlen($descricao) < 5 || mb_strlen($descricao) > 400) {
            $erros['descricao'] = 'Escreva uma descrição de 5 a 400 caracteres.';
        }
        if ($trilhas === []) {
            $erros['trilhas'] = 'Escolha pelo menos uma linguagem ou assunto para o iglu.';
        }
        if (preg_match('/^#[0-9a-f]{6}$/', $cor) !== 1) {
            $erros['cor'] = 'Escolha uma cor válida.';
        }
        if (!in_array($icone, self::ICONES, true)) {
            $erros['icone'] = 'Escolha um dos ícones.';
        }
        if ($erros !== []) {
            throw new ValidationException($erros);
        }
        $mapa = [];
        foreach ($trilhas as $trilha) {
            $mapa[$trilha] = in_array($trilha, $obrigatorias, true);
        }
        return [[
            'nome_iglu' => $nome,
            'descricao_iglu' => $descricao,
            'cor_iglu' => $cor,
            'icone_iglu' => $icone,
            'aberto_iglu' => (int) !empty($dados['aberto']),
            'chat_ativo' => (int) !empty($dados['chat']),
        ], $mapa];
    }

    /** Dono e admins ligam ou desligam o chat do iglu. */
    public function alternarChat(int $idUsuario, int $idIglu, bool $ativo): void
    {
        $this->exigirGestor($idIglu, $idUsuario);
        $this->iglus()->update($idIglu, ['chat_ativo' => (int) $ativo]);
    }

    private function exigirMembro(int $idIglu, int $idUsuario): array
    {
        $iglu = $this->iglus()->find($idIglu);
        if ($iglu === null || $this->iglus()->papel($idIglu, $idUsuario) === null) {
            throw new ValidationException(['iglu' => 'Você não faz parte deste iglu.']);
        }
        return $iglu;
    }

    private function exigirGestor(int $idIglu, int $idUsuario): string
    {
        $papel = $this->iglus()->papel($idIglu, $idUsuario);
        if (!in_array($papel, self::GESTORES, true)) {
            throw new ValidationException(['iglu' => 'Só o dono ou um admin do iglu pode fazer isso.']);
        }
        return $papel;
    }

    private function iglus(): Iglu
    {
        return $this->model;
    }

    private function convites(): IgluConvite
    {
        return new IgluConvite($this->pdo);
    }
}
