<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Curso;
use TechnoPenguin\Models\Notificacao;
use TechnoPenguin\Models\Pergunta;

/** Perguntas da comunidade nos cursos criados por professores. Só o professor do curso responde. */
final class PerguntaController
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public static function link(string $trilha, int $idPergunta): string
    {
        return 'trilha.php?t=' . rawurlencode($trilha) . '&pergunta=' . $idPergunta . '#pergunta-' . $idPergunta;
    }

    public function perguntar(array $aluno, string $trilha, string $texto, bool $publica): array
    {
        $curso = $this->cursoDaComunidade($trilha);
        $texto = trim($texto);
        if (mb_strlen($texto) < 10 || mb_strlen($texto) > 1000) {
            throw new ValidationException(['pergunta' => 'Escreva uma pergunta de 10 a 1000 caracteres.']);
        }
        $pergunta = (new Pergunta($this->pdo))->create([
            'id_curso' => (int) $curso['id_curso'],
            'id_aluno' => (int) $aluno['id_usuario'],
            'texto_pergunta' => $texto,
            'publica_pergunta' => (int) $publica,
        ]);
        if ($curso['id_professor'] !== null && (int) $curso['id_professor'] !== (int) $aluno['id_usuario']) {
            (new Notificacao($this->pdo))->enviar(
                (int) $curso['id_professor'],
                'pergunta',
                "❓ @{$aluno['arroba']} fez uma pergunta em {$curso['titulo_curso']}.",
                self::link($trilha, (int) $pergunta['id_pergunta'])
            );
        }
        return $pergunta;
    }

    public function responder(array $professor, int $idPergunta, string $resposta): void
    {
        $perguntas = new Pergunta($this->pdo);
        $pergunta = $perguntas->find($idPergunta) ?? throw new ValidationException(['pergunta' => 'Pergunta não encontrada.']);
        $curso = (new Curso($this->pdo))->find((int) $pergunta['id_curso']);
        if ($curso === null || (int) $curso['id_professor'] !== (int) $professor['id_usuario']) {
            throw new ValidationException(['pergunta' => 'Só o professor deste curso pode responder.']);
        }
        $resposta = trim($resposta);
        if (mb_strlen($resposta) < 2 || mb_strlen($resposta) > 3000) {
            throw new ValidationException(['pergunta' => 'Escreva uma resposta de até 3000 caracteres.']);
        }
        $primeiraResposta = $pergunta['respondida_em'] === null;
        $perguntas->update($idPergunta, ['resposta_pergunta' => $resposta, 'respondida_em' => date('Y-m-d H:i:s')]);
        (new Notificacao($this->pdo))->enviar(
            (int) $pergunta['id_aluno'],
            'pergunta',
            ($primeiraResposta ? '💬 Sua pergunta foi respondida' : '💬 A resposta da sua pergunta foi atualizada')
                . " em {$curso['titulo_curso']}.",
            self::link($curso['linguagem_curso'], $idPergunta)
        );
    }

    private function cursoDaComunidade(string $trilha): array
    {
        $curso = (new Curso($this->pdo))->porTrilha($trilha);
        if ($curso === null || $curso['id_professor'] === null || !(bool) $curso['publicado_curso']) {
            throw new ValidationException(['pergunta' => 'Perguntas só existem nos cursos da comunidade.']);
        }
        return $curso;
    }
}
