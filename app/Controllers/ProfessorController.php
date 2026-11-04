<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use PDOException;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\Licao;
use TechnoPenguin\Models\Modulo;
use TechnoPenguin\Models\Questao;
use TechnoPenguin\Support\Catalogo;

/**
 * Cursos da comunidade: o professor cria uma linguagem ou assunto e monta módulos (por
 * dificuldade), lições e questões. Tudo é conferido contra o dono do curso.
 */
final class ProfessorController
{
    public const TIPOS = ['linguagem' => 'Linguagem', 'assunto' => 'Assunto'];
    private const PONTOS_POR_DIFICULDADE = ['iniciante' => 10, 'intermediario' => 15, 'avancado' => 20];

    public function __construct(private readonly PDO $pdo)
    {
    }

    public static function ehProfessor(array $usuario): bool
    {
        return ($usuario['papel_usuario'] ?? 'aluno') === 'professor';
    }

    public function criarCurso(array $professor, array $dados): array
    {
        $this->exigirProfessor($professor);
        $campos = $this->validarBasico($dados);
        $cursos = new Curso($this->pdo);
        $this->pdo->beginTransaction();
        try {
            $curso = $cursos->create($campos + [
                'nivel_dificuldade_curso' => 'Iniciante ao avançado',
                'id_professor' => (int) $professor['id_usuario'],
                'publicado_curso' => 0,
            ]);
            $curso = $cursos->update((int) $curso['id_curso'], ['linguagem_curso' => 'comunidade-' . $curso['id_curso']]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        return $curso;
    }

    public function salvarCurso(array $professor, int $idCurso, array $dados): void
    {
        $curso = $this->cursoDoProfessor($professor, $idCurso);
        $campos = $this->validarBasico($dados);
        $objetivos = [];
        foreach (array_keys(Catalogo::DIFICULDADES) as $nivel) {
            $linhas = preg_split('/\R/', (string) ($dados["objetivos_{$nivel}"] ?? ''));
            $objetivos[$nivel] = array_values(array_filter(array_map('trim', $linhas), static fn (string $l): bool => $l !== ''));
        }
        $publicar = !empty($dados['publicado']);
        if ($publicar && !$this->temConteudo($idCurso)) {
            throw new ValidationException(['curso' => 'Para publicar, crie pelo menos um módulo com uma lição e uma questão.']);
        }
        (new Curso($this->pdo))->update((int) $curso['id_curso'], $campos + [
            'historia_curso' => $this->texto($dados['historia'] ?? '', 6000),
            'uso_atual_curso' => $this->texto($dados['uso_atual'] ?? '', 4000),
            'objetivos_curso' => json_encode($objetivos, JSON_UNESCAPED_UNICODE),
            'referencias_curso' => $this->texto($dados['referencias'] ?? '', 4000),
            'publicado_curso' => (int) $publicar,
        ]);
    }

    public function apagarCurso(array $professor, int $idCurso): void
    {
        $this->cursoDoProfessor($professor, $idCurso);
        try {
            (new Curso($this->pdo))->delete($idCurso);
        } catch (PDOException) {
            throw new ValidationException(['curso' => 'Alunos já estudaram este curso. Despublique em vez de apagar.']);
        }
        $this->pdo->prepare("DELETE FROM usuario_linguagem WHERE linguagem = ?")->execute(['comunidade-' . $idCurso]);
    }

    public function salvarModulo(array $professor, int $idCurso, ?int $idModulo, array $dados): array
    {
        $this->cursoDoProfessor($professor, $idCurso);
        $titulo = trim((string) ($dados['titulo'] ?? ''));
        $dificuldade = (string) ($dados['dificuldade'] ?? '');
        if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 100) {
            throw new ValidationException(['modulo' => 'O título do módulo precisa ter de 3 a 100 caracteres.']);
        }
        if (!isset(Catalogo::DIFICULDADES[$dificuldade])) {
            throw new ValidationException(['modulo' => 'Escolha a dificuldade do módulo.']);
        }
        $campos = [
            'titulo_modulo' => $titulo,
            'dificuldade_modulo' => $dificuldade,
            'descricao_modulo' => $this->texto($dados['descricao'] ?? '', 400) ?: null,
            'projeto_modulo' => $this->texto($dados['projeto'] ?? '', 4000) ?: null,
            'aprofundamento_modulo' => $this->texto($dados['aprofundamento'] ?? '', 8000) ?: null,
            'referencias_modulo' => $this->texto($dados['referencias'] ?? '', 3000) ?: null,
        ];
        $modulos = new Modulo($this->pdo);
        if ($idModulo !== null) {
            $this->moduloDoProfessor($professor, $idModulo);
            return $modulos->update($idModulo, $campos);
        }
        $consulta = $this->pdo->prepare('SELECT COALESCE(MAX(sequencia_modulo), 0) + 1 FROM modulo WHERE id_curso = ?');
        $consulta->execute([$idCurso]);
        return $modulos->create($campos + ['id_curso' => $idCurso, 'sequencia_modulo' => (int) $consulta->fetchColumn()]);
    }

    public function apagarModulo(array $professor, int $idModulo): void
    {
        $this->moduloDoProfessor($professor, $idModulo);
        try {
            (new Modulo($this->pdo))->delete($idModulo);
        } catch (PDOException) {
            throw new ValidationException(['modulo' => 'Alunos já fizeram lições deste módulo; edite em vez de apagar.']);
        }
    }

    public function salvarLicao(array $professor, int $idModulo, ?int $idLicao, array $dados): array
    {
        $modulo = $this->moduloDoProfessor($professor, $idModulo);
        $titulo = trim((string) ($dados['titulo'] ?? ''));
        $teoria = $this->texto($dados['teoria'] ?? '', 20000);
        if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 100) {
            throw new ValidationException(['licao' => 'O título da lição precisa ter de 3 a 100 caracteres.']);
        }
        if (mb_strlen($teoria) < 30) {
            throw new ValidationException(['licao' => 'Escreva a parte teórica (pelo menos 30 caracteres).']);
        }
        $campos = [
            'titulo_licao' => $titulo,
            'teoria_licao' => $teoria,
            'pratica_licao' => $this->texto($dados['pratica'] ?? '', 20000),
            'pontos_licao' => self::PONTOS_POR_DIFICULDADE[$modulo['dificuldade_modulo']] ?? 10,
        ];
        $licoes = new Licao($this->pdo);
        if ($idLicao !== null) {
            $this->licaoDoProfessor($professor, $idLicao);
            return $licoes->update($idLicao, $campos);
        }
        $consulta = $this->pdo->prepare('SELECT COALESCE(MAX(sequencia_licao), 0) + 1 FROM licao WHERE id_modulo = ?');
        $consulta->execute([$idModulo]);
        return $licoes->create($campos + ['id_modulo' => $idModulo, 'sequencia_licao' => (int) $consulta->fetchColumn()]);
    }

    public function apagarLicao(array $professor, int $idLicao): array
    {
        $licao = $this->licaoDoProfessor($professor, $idLicao);
        try {
            (new Licao($this->pdo))->delete($idLicao);
        } catch (PDOException) {
            throw new ValidationException(['licao' => 'Alunos já fizeram esta lição; edite em vez de apagar.']);
        }
        return $licao;
    }

    /** Questão de múltipla escolha com 2 a 4 alternativas e uma correta. */
    public function salvarQuestao(array $professor, int $idLicao, ?int $idQuestao, array $dados): void
    {
        $this->licaoDoProfessor($professor, $idLicao);
        $enunciado = $this->texto($dados['enunciado'] ?? '', 3000);
        $alternativas = array_values(array_filter(
            array_map(static fn ($a): string => trim((string) $a), (array) ($dados['alternativas'] ?? [])),
            static fn (string $a): bool => $a !== ''
        ));
        $correta = filter_var($dados['correta'] ?? null, FILTER_VALIDATE_INT);
        if (mb_strlen($enunciado) < 5) {
            throw new ValidationException(['questao' => 'Escreva o enunciado da questão.']);
        }
        if (count($alternativas) < 2 || count($alternativas) > 4) {
            throw new ValidationException(['questao' => 'Informe de 2 a 4 alternativas.']);
        }
        if ($correta === false || $correta < 0 || $correta >= count($alternativas)) {
            throw new ValidationException(['questao' => 'Marque qual alternativa é a correta.']);
        }
        $campos = [
            'enunciado_questao' => $enunciado,
            'tipo_questao' => 'multipla_escolha',
            'gabarito_questao' => (string) $correta,
            'alternativas_questao' => json_encode(array_map(static fn (string $a): string => mb_substr($a, 0, 300), $alternativas), JSON_UNESCAPED_UNICODE),
            'explicacao_questao' => $this->texto($dados['explicacao'] ?? '', 1000) ?: null,
        ];
        $questoes = new Questao($this->pdo);
        if ($idQuestao !== null) {
            $questao = $questoes->find($idQuestao);
            if ($questao === null || (int) $questao['id_licao'] !== $idLicao) {
                throw new ValidationException(['questao' => 'Questão não encontrada.']);
            }
            $questoes->update($idQuestao, $campos);
            return;
        }
        $consulta = $this->pdo->prepare('SELECT COALESCE(MAX(sequencia_questao), 0) + 1 FROM questao WHERE id_licao = ?');
        $consulta->execute([$idLicao]);
        $questoes->create($campos + ['id_licao' => $idLicao, 'sequencia_questao' => (int) $consulta->fetchColumn()]);
    }

    public function apagarQuestao(array $professor, int $idLicao, int $idQuestao): void
    {
        $this->licaoDoProfessor($professor, $idLicao);
        $questoes = new Questao($this->pdo);
        $questao = $questoes->find($idQuestao);
        if ($questao !== null && (int) $questao['id_licao'] === $idLicao) {
            $questoes->delete($idQuestao);
        }
    }

    public function cursoDoProfessor(array $professor, int $idCurso): array
    {
        $this->exigirProfessor($professor);
        $curso = (new Curso($this->pdo))->find($idCurso);
        if ($curso === null || (int) $curso['id_professor'] !== (int) $professor['id_usuario']) {
            throw new ValidationException(['curso' => 'Curso não encontrado entre os seus.']);
        }
        return $curso;
    }

    public function moduloDoProfessor(array $professor, int $idModulo): array
    {
        $modulo = (new Modulo($this->pdo))->find($idModulo) ?? throw new ValidationException(['modulo' => 'Módulo não encontrado.']);
        $this->cursoDoProfessor($professor, (int) $modulo['id_curso']);
        return $modulo;
    }

    public function licaoDoProfessor(array $professor, int $idLicao): array
    {
        $licao = (new Licao($this->pdo))->find($idLicao) ?? throw new ValidationException(['licao' => 'Lição não encontrada.']);
        $this->moduloDoProfessor($professor, (int) $licao['id_modulo']);
        return $licao;
    }

    private function exigirProfessor(array $usuario): void
    {
        if (!self::ehProfessor($usuario)) {
            throw new ValidationException(['professor' => 'Ative o perfil de professor em Minha conta para criar cursos.']);
        }
    }

    private function validarBasico(array $dados): array
    {
        $titulo = trim((string) ($dados['titulo'] ?? ''));
        $descricao = trim((string) ($dados['descricao'] ?? ''));
        $tipo = (string) ($dados['tipo'] ?? '');
        $sigla = trim((string) ($dados['sigla'] ?? ''));
        $cor = strtolower(trim((string) ($dados['cor'] ?? '')));
        $erros = [];
        if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 100) {
            $erros['titulo'] = 'O nome precisa ter de 3 a 100 caracteres.';
        }
        if (mb_strlen($descricao) < 10 || mb_strlen($descricao) > 400) {
            $erros['descricao'] = 'A descrição precisa ter de 10 a 400 caracteres.';
        }
        if (!isset(self::TIPOS[$tipo])) {
            $erros['tipo'] = 'Escolha se é uma linguagem ou um assunto.';
        }
        if (mb_strlen($sigla) < 1 || mb_strlen($sigla) > 6) {
            $erros['sigla'] = 'A sigla do ícone precisa ter de 1 a 6 caracteres.';
        }
        if (preg_match('/^#[0-9a-f]{6}$/', $cor) !== 1) {
            $erros['cor'] = 'Escolha uma cor válida.';
        }
        if ($erros !== []) {
            throw new ValidationException($erros);
        }
        return ['titulo_curso' => $titulo, 'descricao_curso' => $descricao, 'tipo_curso' => $tipo, 'sigla_curso' => $sigla, 'cor_curso' => $cor];
    }

    private function temConteudo(int $idCurso): bool
    {
        $consulta = $this->pdo->prepare(
            'SELECT COUNT(*) FROM questao q JOIN licao l ON l.id_licao = q.id_licao JOIN modulo m ON m.id_modulo = l.id_modulo WHERE m.id_curso = ?'
        );
        $consulta->execute([$idCurso]);
        return (int) $consulta->fetchColumn() > 0;
    }

    private function texto(mixed $valor, int $maximo): string
    {
        return mb_substr(trim(str_replace("\r\n", "\n", (string) $valor)), 0, $maximo);
    }
}
