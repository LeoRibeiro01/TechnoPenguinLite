<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

/** Cada trilha do catálogo tem um curso, ligado pelo slug em linguagem_curso. */
final class Curso extends Model
{
    protected const TABLE = 'curso';
    protected const PRIMARY_KEY = 'id_curso';
    protected const FILLABLE = [
        'titulo_curso', 'descricao_curso', 'nivel_dificuldade_curso', 'linguagem_curso',
        'historia_curso', 'uso_atual_curso', 'objetivos_curso', 'referencias_curso',
        'tipo_curso', 'sigla_curso', 'cor_curso', 'publicado_curso', 'id_professor',
    ];

    public function modulos(int $idCurso): array
    {
        return $this->many('SELECT * FROM modulo WHERE id_curso = ? ORDER BY sequencia_modulo', [$idCurso]);
    }

    public function porTrilha(string $trilha): ?array
    {
        return $this->one('SELECT * FROM curso WHERE linguagem_curso = ? LIMIT 1', [$trilha]);
    }

    /** Por trilha: total de lições e quantas o usuário já concluiu. */
    public function resumoTrilhas(int $idUsuario): array
    {
        $linhas = $this->many(
            "SELECT c.linguagem_curso AS trilha, COUNT(DISTINCT m.id_modulo) AS total_modulos, COUNT(l.id_licao) AS total_licoes,
                    COUNT(p.id_progresso) AS licoes_concluidas
             FROM curso c
             LEFT JOIN modulo m ON m.id_curso = c.id_curso
             LEFT JOIN licao l ON l.id_modulo = m.id_modulo
             LEFT JOIN progresso p ON p.id_licao = l.id_licao AND p.id_usuario = ? AND p.status_progresso = 'Concluída'
             WHERE c.linguagem_curso IS NOT NULL
             GROUP BY c.id_curso",
            [$idUsuario]
        );
        return array_column($linhas, null, 'trilha');
    }

    /** Lições da trilha em ordem de estudo, com o progresso do usuário em cada uma. */
    public function licoesComProgresso(int $idCurso, int $idUsuario): array
    {
        return $this->many(
            "SELECT m.id_modulo, m.titulo_modulo, m.sequencia_modulo, m.descricao_modulo, m.projeto_modulo,
                    m.dificuldade_modulo, m.aprofundamento_modulo, m.referencias_modulo,
                    l.id_licao, l.titulo_licao, l.sequencia_licao, l.pontos_licao,
                    (SELECT COUNT(*) FROM questao q WHERE q.id_licao = l.id_licao) AS total_questoes,
                    p.status_progresso, p.acertos_progresso, p.tentativas_progresso
             FROM modulo m
             LEFT JOIN licao l ON l.id_modulo = m.id_modulo
             LEFT JOIN progresso p ON p.id_licao = l.id_licao AND p.id_usuario = ?
             WHERE m.id_curso = ?
             ORDER BY " . self::ORDEM_MODULOS . ', l.sequencia_licao',
            [$idUsuario, $idCurso]
        );
    }

    /** Ordem de estudo: por dificuldade e, dentro dela, pela sequência do módulo. */
    public const ORDEM_MODULOS = "FIELD(m.dificuldade_modulo, 'iniciante', 'intermediario', 'avancado'), m.sequencia_modulo";

    /** Cursos criados por um professor, com contagens para o painel dele. */
    public function doProfessor(int $idProfessor): array
    {
        return $this->many(
            'SELECT c.*,
                    (SELECT COUNT(*) FROM modulo m JOIN licao l ON l.id_modulo = m.id_modulo WHERE m.id_curso = c.id_curso) AS total_licoes,
                    (SELECT COUNT(*) FROM usuario_linguagem ul WHERE ul.linguagem = c.linguagem_curso) AS total_alunos,
                    (SELECT COUNT(*) FROM pergunta pg WHERE pg.id_curso = c.id_curso AND pg.respondida_em IS NULL) AS perguntas_pendentes
             FROM curso c WHERE c.id_professor = ? ORDER BY c.criado_em DESC',
            [$idProfessor]
        );
    }

    public function comModulos(int $idCurso): ?array
    {
        $curso = $this->find($idCurso);
        if ($curso !== null) {
            $curso['modulos'] = $this->modulos($idCurso);
        }
        return $curso;
    }
}
