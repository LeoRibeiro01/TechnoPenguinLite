<?php
declare(strict_types=1);

namespace TechnoPenguin\Support;

use PDO;

/**
 * Junta as trilhas oficiais (Catalogo) e as da comunidade (cursos criados por professores,
 * identificados pelo slug "comunidade-<id>") num único formato.
 */
final class Trilhas
{
    private static array $cache = [];

    public static function info(PDO $pdo, string $slug): ?array
    {
        if (($oficial = Catalogo::trilha($slug)) !== null) {
            return $oficial + ['comunidade' => false, 'publicado' => true, 'id_professor' => null];
        }
        if (!str_starts_with($slug, 'comunidade-')) {
            return null;
        }
        if (!array_key_exists($slug, self::$cache)) {
            $consulta = $pdo->prepare(
                'SELECT c.*, u.arroba AS arroba_professor FROM curso c
                 LEFT JOIN usuario u ON u.id_usuario = c.id_professor
                 WHERE c.linguagem_curso = ? LIMIT 1'
            );
            $consulta->execute([$slug]);
            $curso = $consulta->fetch();
            self::$cache[$slug] = $curso === false ? null : self::deCurso($curso);
        }
        return self::$cache[$slug];
    }

    /** Pode ser adicionada a um perfil: oficial ou da comunidade publicada. */
    public static function disponivel(PDO $pdo, string $slug): bool
    {
        $trilha = self::info($pdo, $slug);
        return $trilha !== null && $trilha['publicado'];
    }

    /** Cursos publicados da comunidade, com o número de lições e de alunos. */
    public static function comunidade(PDO $pdo): array
    {
        $linhas = $pdo->query(
            "SELECT c.*, u.arroba AS arroba_professor,
                    (SELECT COUNT(*) FROM modulo m JOIN licao l ON l.id_modulo = m.id_modulo WHERE m.id_curso = c.id_curso) AS total_licoes,
                    (SELECT COUNT(*) FROM usuario_linguagem ul WHERE ul.linguagem = c.linguagem_curso) AS total_alunos
             FROM curso c LEFT JOIN usuario u ON u.id_usuario = c.id_professor
             WHERE c.id_professor IS NOT NULL AND c.publicado_curso = 1
             ORDER BY total_alunos DESC, c.titulo_curso"
        )->fetchAll();
        return array_map(static fn (array $c): array => self::deCurso($c) + [
            'total_licoes' => (int) $c['total_licoes'],
            'total_alunos' => (int) $c['total_alunos'],
        ], $linhas);
    }

    private static function deCurso(array $curso): array
    {
        return [
            'slug' => $curso['linguagem_curso'],
            'titulo' => $curso['titulo_curso'],
            'tipo' => $curso['tipo_curso'],
            'sigla' => $curso['sigla_curso'] ?: mb_strtoupper(mb_substr($curso['titulo_curso'], 0, 2)),
            'cor' => $curso['cor_curso'] ?: '#5c7aa5',
            'descricao' => $curso['descricao_curso'],
            'comunidade' => true,
            'publicado' => (bool) $curso['publicado_curso'],
            'id_professor' => $curso['id_professor'] === null ? null : (int) $curso['id_professor'],
            'arroba_professor' => $curso['arroba_professor'] ?? null,
            'id_curso' => (int) $curso['id_curso'],
        ];
    }
}
