<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Pergunta extends Model
{
    protected const TABLE = 'pergunta';
    protected const PRIMARY_KEY = 'id_pergunta';
    protected const FILLABLE = ['id_curso', 'id_aluno', 'texto_pergunta', 'publica_pergunta', 'resposta_pergunta', 'respondida_em'];

    /**
     * Perguntas que o leitor pode ver: as públicas, as dele e, para o professor do curso, todas.
     * Respondidas e não respondidas misturadas, as mais recentes primeiro.
     */
    public function visiveis(int $idCurso, int $idLeitor, bool $ehProfessor): array
    {
        return $this->many(
            'SELECT p.*, u.arroba, u.nome FROM pergunta p
             INNER JOIN usuario u ON u.id_usuario = p.id_aluno
             WHERE p.id_curso = ? AND (? = 1 OR p.publica_pergunta = 1 OR p.id_aluno = ?)
             ORDER BY p.respondida_em IS NOT NULL, p.criada_em DESC',
            [$idCurso, (int) $ehProfessor, $idLeitor]
        );
    }

    /** Perguntas sem resposta nos cursos de um professor. */
    public function pendentesDoProfessor(int $idProfessor): array
    {
        return $this->many(
            'SELECT p.*, u.arroba, c.titulo_curso, c.linguagem_curso FROM pergunta p
             INNER JOIN curso c ON c.id_curso = p.id_curso
             INNER JOIN usuario u ON u.id_usuario = p.id_aluno
             WHERE c.id_professor = ? AND p.respondida_em IS NULL
             ORDER BY p.criada_em',
            [$idProfessor]
        );
    }
}
