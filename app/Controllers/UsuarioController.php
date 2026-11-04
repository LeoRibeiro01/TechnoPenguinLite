<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use DateTimeImmutable;
use PDO;
use PDOException;
use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Models\UsuarioLinguagem;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Trilhas;

final class UsuarioController extends ResourceController
{
    protected const MODEL_CLASS = Usuario::class;

    /** Hash usado quando o email não existe, para o login levar o mesmo tempo nos dois casos. */
    private const HASH_FICTICIO = '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';

    /** Ordem do questionário inicial; "dados" vale para contas criadas antes do nascimento/consentimento. */
    public const ETAPAS = ['dados', 'nivel', 'trilhas', 'objetivo', 'meta', 'avatar'];

    /** Falhas de login toleradas por email/@ ou IP dentro da janela, antes do bloqueio temporário. */
    public const LIMITE_TENTATIVAS = 5;
    public const JANELA_TENTATIVAS_MINUTOS = 15;

    public const VISIBILIDADES = [
        'todos' => 'Todos os pinguins',
        'amigos' => 'Só meus amigos',
        'privado' => 'Ninguém (perfil privado)',
    ];

    public function __construct(private readonly PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /** Idade em anos completos, ou null quando a data de nascimento não foi informada. */
    public static function idade(array $usuario, ?DateTimeImmutable $hoje = null): ?int
    {
        if (empty($usuario['data_nascimento'])) {
            return null;
        }
        return (new DateTimeImmutable($usuario['data_nascimento']))->diff($hoje ?? new DateTimeImmutable('today'))->y;
    }

    /** Devolve os erros de cadastro por campo; vazio quando os dados são válidos. */
    public function validarCadastro(array $dados): array
    {
        $erros = $this->errosDados($dados);
        $arroba = $dados['arroba'] ?? null;
        $email = $dados['email'] ?? null;
        if (($erro = $this->erroNome($dados['nome'] ?? null)) !== null) {
            $erros['nome'] = $erro;
        }
        if (($erro = $this->erroArroba($arroba)) !== null) {
            $erros['arroba'] = $erro;
        } elseif ($this->usuarios()->buscarPorArroba($arroba) !== null) {
            $erros['arroba'] = 'Este @ já está em uso. Tente outro.';
        }
        if (($erro = $this->erroEmail($email)) !== null) {
            $erros['email'] = $erro;
        } elseif ($this->usuarios()->buscarPorEmail($email) !== null) {
            $erros['email'] = 'Este email já está cadastrado.';
        }
        if (($erro = $this->erroSenha($dados['senha'] ?? null)) !== null) {
            $erros['senha'] = $erro;
        }
        if (($dados['papel'] ?? 'aluno') === 'professor' && !isset($erros['nascimento'])
            && self::idade(['data_nascimento' => $dados['nascimento']]) < Catalogo::IDADE_ADULTA) {
            $erros['papel'] = 'Contas de professor são para maiores de 18 anos.';
        }
        return $erros;
    }

    /** Normaliza o @: sem o "@" inicial, sem espaços e em minúsculas. */
    public static function normalizarArroba(string $arroba): string
    {
        return mb_strtolower(ltrim(trim($arroba), '@'));
    }

    public function store(array $dados): array
    {
        $dados['nome'] = trim((string) ($dados['nome'] ?? ''));
        $dados['arroba'] = self::normalizarArroba((string) ($dados['arroba'] ?? ''));
        $dados['email'] = mb_strtolower(trim((string) ($dados['email'] ?? '')));
        $erros = $this->validarCadastro($dados);
        if ($erros !== []) {
            throw new ValidationException($erros);
        }

        try {
            $usuario = parent::store([
                'nome' => $dados['nome'],
                'arroba' => $dados['arroba'],
                'email' => $dados['email'],
                'senha' => password_hash($dados['senha'], PASSWORD_BCRYPT),
                'papel_usuario' => ($dados['papel'] ?? 'aluno') === 'professor' ? 'professor' : 'aluno',
            ] + $this->camposDados($dados));
        } catch (PDOException $e) {
            // Corrida entre a validação e o INSERT: as chaves únicas do banco têm a última palavra.
            if ($e->getCode() === '23000') {
                throw new ValidationException(str_contains($e->getMessage(), 'uq_usuario_arroba')
                    ? ['arroba' => 'Este @ já está em uso. Tente outro.']
                    : ['email' => 'Este email já está cadastrado.']);
            }
            throw $e;
        }
        $this->usuarios()->iniciarSequencia((int) $usuario['id_usuario']);
        return $usuario;
    }

    public function update(int|array $id, array $dados): array
    {
        $erros = [];
        if (array_key_exists('nome', $dados)) {
            $dados['nome'] = trim((string) $dados['nome']);
            $erros['nome'] = $this->erroNome($dados['nome']);
        }
        if (array_key_exists('arroba', $dados)) {
            $dados['arroba'] = self::normalizarArroba((string) $dados['arroba']);
            $erros['arroba'] = $this->erroArroba($dados['arroba']);
            $existente = $erros['arroba'] === null ? $this->usuarios()->buscarPorArroba($dados['arroba']) : null;
            if ($existente !== null && (int) $existente['id_usuario'] !== $id) {
                $erros['arroba'] = 'Este @ já está em uso. Tente outro.';
            }
        }
        if (array_key_exists('senha', $dados)) {
            if (!is_string($dados['senha']) || $dados['senha'] === '') {
                unset($dados['senha']);
            } elseif (($erros['senha'] = $this->erroSenha($dados['senha'])) === null) {
                $dados['senha'] = password_hash($dados['senha'], PASSWORD_BCRYPT);
            }
        }
        if (array_key_exists('email', $dados)) {
            $dados['email'] = mb_strtolower(trim((string) $dados['email']));
            $erros['email'] = $this->erroEmail($dados['email']);
            $existente = $erros['email'] === null ? $this->usuarios()->buscarPorEmail($dados['email']) : null;
            if ($existente !== null && (int) $existente['id_usuario'] !== $id) {
                $erros['email'] = 'Este email já está cadastrado.';
            }
        }
        $erros = array_filter($erros);
        if ($erros !== []) {
            throw new ValidationException($erros);
        }
        return parent::update($id, $dados);
    }

    /**
     * Confere email (ou @) e senha; devolve o usuário sem o hash da senha, ou null. Depois de
     * LIMITE_TENTATIVAS falhas no mesmo login ou IP, bloqueia por JANELA_TENTATIVAS_MINUTOS.
     */
    public function autenticar(string $login, string $senha, string $ip = ''): ?array
    {
        $login = trim($login);
        $chaves = [hash('sha256', 'login:' . mb_strtolower($login)), hash('sha256', 'ip:' . $ip)];
        if ($this->tentativasRecentes($chaves) >= self::LIMITE_TENTATIVAS) {
            throw new ValidationException(['login' => 'Muitas tentativas sem sucesso. Espere '
                . self::JANELA_TENTATIVAS_MINUTOS . ' minutos e tente de novo.']);
        }
        $usuario = str_contains($login, '@') && !str_starts_with($login, '@')
            ? $this->usuarios()->buscarPorEmail(mb_strtolower($login))
            : $this->usuarios()->buscarPorArroba(self::normalizarArroba($login));
        $senhaConfere = password_verify($senha, $usuario['senha'] ?? self::HASH_FICTICIO);
        if ($usuario === null || !$senhaConfere) {
            $registrar = $this->pdo->prepare('INSERT INTO tentativa_login (chave_tentativa) VALUES (?)');
            foreach ($chaves as $chave) {
                $registrar->execute([$chave]);
            }
            return null;
        }
        $this->pdo->prepare('DELETE FROM tentativa_login WHERE chave_tentativa = ? OR criada_em < NOW() - INTERVAL 1 DAY')
            ->execute([$chaves[0]]);
        if (password_needs_rehash($usuario['senha'], PASSWORD_BCRYPT)) {
            parent::update((int) $usuario['id_usuario'], ['senha' => password_hash($senha, PASSWORD_BCRYPT)]);
        }
        unset($usuario['senha']);
        return $usuario;
    }

    /** Primeira etapa do questionário inicial ainda sem resposta, ou null quando tudo está feito. */
    public function etapaPendente(array $usuario): ?string
    {
        return match (true) {
            empty($usuario['data_nascimento']) || empty($usuario['aceite_termos_em']) => 'dados',
            $usuario['nivel_conhecimento_usuario'] === null
                || !isset(Catalogo::NIVEIS[$usuario['nivel_conhecimento_usuario']]) => 'nivel',
            $this->trilhas((int) $usuario['id_usuario']) === [] => 'trilhas',
            $usuario['objetivo_usuario'] === null => 'objetivo',
            $usuario['meta_diaria_usuario'] === null => 'meta',
            $usuario['cor_corpo_avatar'] === null => 'avatar',
            default => null,
        };
    }

    /** Etapa "dados" de contas antigas: nascimento, consentimento e, para crianças, o responsável. */
    public function salvarDados(int $id, array $dados): void
    {
        $erros = $this->errosDados($dados);
        if ($erros !== []) {
            throw new ValidationException($erros);
        }
        parent::update($id, $this->camposDados($dados));
    }

    /** Dados pessoais editáveis em Minha conta. */
    public function atualizarPerfil(int $id, array $dados): void
    {
        $erroNascimento = $this->erroNascimento($dados['nascimento'] ?? null);
        if ($erroNascimento !== null) {
            throw new ValidationException(['nascimento' => $erroNascimento]);
        }
        $usuario = $this->usuarios()->find($id);
        if (($usuario['papel_usuario'] ?? '') === 'professor'
            && self::idade(['data_nascimento' => $dados['nascimento']]) < Catalogo::IDADE_ADULTA) {
            throw new ValidationException(['nascimento' => 'Professores precisam ter 18 anos ou mais.']);
        }
        $this->update($id, [
            'nome' => $dados['nome'] ?? '',
            'arroba' => $dados['arroba'] ?? '',
            'email' => $dados['email'] ?? '',
            'data_nascimento' => $dados['nascimento'],
        ]);
    }

    public function trocarSenha(int $id, string $atual, string $nova, string $confirmacao): void
    {
        $usuario = $this->usuarios()->find($id);
        if ($usuario === null || !password_verify($atual, $usuario['senha'])) {
            throw new ValidationException(['senha_atual' => 'A senha atual não confere.']);
        }
        if ($nova !== $confirmacao) {
            throw new ValidationException(['senha' => 'As senhas novas não coincidem.']);
        }
        $this->update($id, ['senha' => $nova]);
    }

    public function salvarPrivacidade(int $id, array $dados): void
    {
        $visibilidade = (string) ($dados['visibilidade'] ?? '');
        if (!isset(self::VISIBILIDADES[$visibilidade])) {
            throw new ValidationException(['visibilidade' => 'Escolha quem pode ver seu perfil.']);
        }
        parent::update($id, [
            'perfil_visibilidade' => $visibilidade,
            'aparecer_busca' => (int) !empty($dados['aparecer_busca']),
            'mostrar_online' => (int) !empty($dados['mostrar_online']),
        ]);
    }

    /** Ativa ou desativa o perfil de professor (só para maiores de 18). */
    public function definirProfessor(array $usuario, bool $professor): void
    {
        if ($professor && (self::idade($usuario) ?? 0) < Catalogo::IDADE_ADULTA) {
            throw new ValidationException(['papel' => 'O perfil de professor é para maiores de 18 anos.']);
        }
        parent::update((int) $usuario['id_usuario'], ['papel_usuario' => $professor ? 'professor' : 'aluno']);
    }

    /** Todos os dados pessoais do usuário (direito de acesso e portabilidade da LGPD). */
    public function exportar(int $id): array
    {
        $consultar = function (string $sql) use ($id): array {
            $consulta = $this->pdo->prepare($sql);
            $consulta->execute(array_fill(0, substr_count($sql, '?'), $id));
            return $consulta->fetchAll();
        };
        $usuario = $this->usuarios()->find($id);
        unset($usuario['senha']);
        return [
            'gerado_em' => date(DATE_ATOM),
            'conta' => $usuario,
            'trilhas' => $consultar('SELECT linguagem AS trilha, data_escolha FROM usuario_linguagem WHERE id_usuario = ?'),
            'progresso' => $consultar('SELECT p.*, l.titulo_licao FROM progresso p JOIN licao l ON l.id_licao = p.id_licao WHERE p.id_usuario = ?'),
            'gelo' => $consultar('SELECT dias_consecutivos_sequencia, inicio_sequencia, ultima_atividade_sequencia FROM sequencia WHERE id_usuario = ?'),
            'certificados' => $consultar('SELECT * FROM certificado WHERE id_usuario = ?'),
            'conquistas' => $consultar('SELECT c.nome_conquista, uc.data_desbloqueio FROM usuario_conquista uc JOIN conquista c ON c.id_conquista = uc.id_conquista WHERE uc.id_usuario = ?'),
            'itens_vestuario' => $consultar('SELECT item, comprado_em FROM usuario_item WHERE id_usuario = ?'),
            'amizades' => $consultar(
                'SELECT u.arroba, a.status_amizade, a.criada_em FROM amizade a
                 JOIN usuario u ON u.id_usuario = IF(a.id_solicitante = ?, a.id_destinatario, a.id_solicitante)
                 WHERE a.id_solicitante = ? OR a.id_destinatario = ?'
            ),
            'iglus' => $consultar('SELECT i.nome_iglu, m.papel_membro, m.entrou_em FROM iglu_membro m JOIN iglu i ON i.id_iglu = m.id_iglu WHERE m.id_usuario = ?'),
            'mensagens_chat' => $consultar('SELECT i.nome_iglu, m.texto_mensagem, m.criada_em FROM iglu_mensagem m JOIN iglu i ON i.id_iglu = m.id_iglu WHERE m.id_usuario = ?'),
            'perguntas' => $consultar('SELECT c.titulo_curso, p.texto_pergunta, p.publica_pergunta, p.resposta_pergunta, p.criada_em FROM pergunta p JOIN curso c ON c.id_curso = p.id_curso WHERE p.id_aluno = ?'),
            'cursos_criados' => $consultar('SELECT titulo_curso, descricao_curso, publicado_curso, criado_em FROM curso WHERE id_professor = ?'),
            'notificacoes' => $consultar('SELECT tipo_notificacao, mensagem_notificacao, criada_em FROM notificacao WHERE id_usuario = ?'),
        ];
    }

    /**
     * Exclui a conta e os dados pessoais (direito de eliminação). Antes, passa a posse dos
     * iglus para outro membro. Cursos criados como professor continuam, sem autor, e são
     * despublicados.
     */
    public function excluir(int $id, string $senha): void
    {
        $usuario = $this->usuarios()->find($id);
        if ($usuario === null || !password_verify($senha, $usuario['senha'])) {
            throw new ValidationException(['senha_exclusao' => 'A senha não confere.']);
        }
        $iglus = new IgluController($this->pdo);
        foreach ((new Iglu($this->pdo))->doUsuario($id) as $iglu) {
            $iglus->sair($id, (int) $iglu['id_iglu']);
        }
        $this->pdo->prepare('UPDATE curso SET publicado_curso = 0 WHERE id_professor = ?')->execute([$id]);
        $this->usuarios()->delete($id);
    }

    /** Grava as cores do avatar; aceita qualquer cor hexadecimal #rrggbb. */
    public function salvarAvatar(int $id, array $cores): array
    {
        $dados = [];
        $erros = [];
        foreach (['corpo', 'barriga', 'detalhe', 'fundo'] as $parte) {
            $cor = strtolower(trim((string) ($cores[$parte] ?? '')));
            if (preg_match('/^#[0-9a-f]{6}$/', $cor) !== 1) {
                $erros[$parte] = 'Escolha uma cor válida.';
            }
            $dados["cor_{$parte}_avatar"] = $cor;
        }
        if ($erros !== []) {
            throw new ValidationException($erros);
        }
        return parent::update($id, $dados);
    }

    public function trilhas(int $id): array
    {
        return array_column((new UsuarioLinguagem($this->pdo))->deUsuario($id), 'linguagem');
    }

    /** Quem não sabe nada de programação começa obrigatoriamente pela trilha de lógica. */
    public function salvarNivel(int $id, mixed $nivel): void
    {
        if (!is_string($nivel) || !isset(Catalogo::NIVEIS[$nivel])) {
            throw new ValidationException(['nivel' => 'Escolha uma das opções.']);
        }
        parent::update($id, ['nivel_conhecimento_usuario' => $nivel]);
        if ($nivel === 'zero') {
            (new UsuarioLinguagem($this->pdo))->substituir($id, [Catalogo::TRILHA_INICIAL]);
        }
    }

    public function salvarTrilhas(int $id, mixed $trilhas): void
    {
        (new UsuarioLinguagem($this->pdo))->substituir($id, $this->validarTrilhas($trilhas));
    }

    public function adicionarTrilhas(int $id, mixed $trilhas): void
    {
        (new UsuarioLinguagem($this->pdo))->adicionar($id, $this->validarTrilhas($trilhas));
    }

    public function salvarObjetivo(int $id, mixed $objetivo): void
    {
        if (!is_string($objetivo) || !isset(Catalogo::OBJETIVOS[$objetivo])) {
            throw new ValidationException(['objetivo' => 'Escolha uma das opções.']);
        }
        parent::update($id, ['objetivo_usuario' => $objetivo]);
    }

    public function salvarMeta(int $id, mixed $minutos): void
    {
        $minutos = filter_var($minutos, FILTER_VALIDATE_INT);
        if ($minutos === false || !isset(Catalogo::METAS[$minutos])) {
            throw new ValidationException(['meta' => 'Escolha uma das metas.']);
        }
        parent::update($id, ['meta_diaria_usuario' => $minutos]);
    }

    /** Aceita trilhas oficiais e cursos publicados da comunidade. */
    private function validarTrilhas(mixed $trilhas): array
    {
        $trilhas = is_array($trilhas) ? array_values(array_unique(array_filter($trilhas, 'is_string'))) : [];
        $invalidas = array_filter($trilhas, fn (string $t): bool => !Trilhas::disponivel($this->pdo, $t));
        if ($trilhas === [] || $invalidas !== []) {
            throw new ValidationException(['trilhas' => 'Escolha pelo menos uma linguagem ou assunto da lista.']);
        }
        return $trilhas;
    }

    /** Erros de nascimento, consentimento e responsável (cadastro e etapa "dados"). */
    private function errosDados(array $dados): array
    {
        $erros = [];
        if (($erro = $this->erroNascimento($dados['nascimento'] ?? null)) !== null) {
            $erros['nascimento'] = $erro;
        } elseif (self::idade(['data_nascimento' => $dados['nascimento']]) < Catalogo::IDADE_CRIANCA) {
            $email = trim((string) ($dados['email_responsavel'] ?? ''));
            if ($this->erroEmail($email) !== null) {
                $erros['email_responsavel'] = 'Informe o email de um responsável legal.';
            }
            if (empty($dados['consentimento_responsavel'])) {
                $erros['consentimento_responsavel'] = 'Um responsável precisa autorizar o cadastro.';
            }
        }
        if (empty($dados['aceite'])) {
            $erros['aceite'] = 'Para continuar, aceite os Termos de Uso e a Política de Privacidade.';
        }
        return $erros;
    }

    private function camposDados(array $dados): array
    {
        $crianca = self::idade(['data_nascimento' => $dados['nascimento']]) < Catalogo::IDADE_CRIANCA;
        return [
            'data_nascimento' => $dados['nascimento'],
            'aceite_termos_em' => date('Y-m-d H:i:s'),
            'email_responsavel' => $crianca ? mb_strtolower(trim((string) $dados['email_responsavel'])) : null,
        ];
    }

    private function erroNascimento(mixed $data): ?string
    {
        $valida = is_string($data) ? DateTimeImmutable::createFromFormat('!Y-m-d', $data) : false;
        if ($valida === false || $valida->format('Y-m-d') !== $data) {
            return 'Informe sua data de nascimento.';
        }
        $idade = self::idade(['data_nascimento' => $data]);
        if ($valida > new DateTimeImmutable('today') || $idade < 5 || $idade > 120) {
            return 'Confira a data de nascimento.';
        }
        return null;
    }

    private function tentativasRecentes(array $chaves): int
    {
        $consulta = $this->pdo->prepare(
            'SELECT COUNT(*) FROM tentativa_login WHERE chave_tentativa = ? AND criada_em >= NOW() - INTERVAL '
            . self::JANELA_TENTATIVAS_MINUTOS . ' MINUTE GROUP BY chave_tentativa'
        );
        $maximo = 0;
        foreach ($chaves as $chave) {
            $consulta->execute([$chave]);
            $maximo = max($maximo, (int) $consulta->fetchColumn());
        }
        return $maximo;
    }

    private function usuarios(): Usuario
    {
        return $this->model;
    }

    private function erroNome(mixed $nome): ?string
    {
        if (!is_string($nome) || mb_strlen(trim($nome)) < 2 || mb_strlen($nome) > 50) {
            return 'Informe seu nome, com 2 a 50 caracteres.';
        }
        return null;
    }

    private function erroArroba(mixed $arroba): ?string
    {
        if (!is_string($arroba) || preg_match('/^[a-z0-9_.]{3,30}$/', $arroba) !== 1) {
            return 'Use de 3 a 30 caracteres: letras minúsculas sem acento, números, "_" ou ".".';
        }
        return null;
    }

    private function erroEmail(mixed $email): ?string
    {
        if (!is_string($email) || mb_strlen($email) > 50 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return 'Informe um email válido de até 50 caracteres.';
        }
        return null;
    }

    private function erroSenha(mixed $senha): ?string
    {
        if (!is_string($senha) || strlen($senha) < 8 || strlen($senha) > 72) {
            return 'A senha deve ter entre 8 e 72 caracteres.';
        }
        return null;
    }
}
