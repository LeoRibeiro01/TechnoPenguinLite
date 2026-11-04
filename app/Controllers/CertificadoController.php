<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Core\ValidationException;
use TechnoPenguin\Models\Certificado;
use TechnoPenguin\Models\Notificacao;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Trilhas;

/**
 * Certificados por nível (iniciante, intermediário, avançado) e da trilha completa. Exigem
 * 100% das lições do nível concluídas e aproveitamento mínimo em todas as questões dele.
 */
final class CertificadoController
{
    public const COMPLETO = 'completo';

    public function __construct(private readonly PDO $pdo)
    {
    }

    public static function rotulo(string $nivel): string
    {
        return $nivel === self::COMPLETO ? 'Completo' : (Catalogo::DIFICULDADES[$nivel][0] ?? $nivel);
    }

    /** Carga horária estimada de um conjunto de módulos (lições + projeto de cada módulo). */
    public static function horas(array $modulos): int
    {
        $horas = 0;
        foreach ($modulos as $modulo) {
            [, $porLicao, $porProjeto] = Catalogo::DIFICULDADES[$modulo['dificuldade']] ?? Catalogo::DIFICULDADES['iniciante'];
            $horas += count($modulo['licoes']) * $porLicao + ($modulo['projeto'] ? $porProjeto : 0);
        }
        return $horas;
    }

    /**
     * Situação de cada certificado da trilha: nível => estatísticas, horas, se pode emitir e o
     * certificado já emitido. Inclui "completo" quando a trilha tem lições.
     */
    public function situacao(string $trilha, array $usuario, ?array $mapa = null): array
    {
        $mapa ??= (new AprendizadoController($this->pdo))->mapa($trilha, $usuario);
        if ($mapa === null || $mapa['total_licoes'] === 0) {
            return [];
        }
        $emitidos = (new Certificado($this->pdo))->daTrilha((int) $usuario['id_usuario'], $trilha);
        $grupos = $mapa['niveis'] + [self::COMPLETO => $mapa['modulos']];
        $situacao = [];
        foreach ($grupos as $nivel => $modulos) {
            $licoes = array_merge([], ...array_map(static fn (array $m): array => $m['licoes'], $modulos));
            $estatisticas = AprendizadoController::estatisticas($licoes);
            $situacao[$nivel] = $estatisticas + [
                'nivel' => $nivel,
                'rotulo' => self::rotulo($nivel),
                'horas' => self::horas($modulos),
                'pode_emitir' => $estatisticas['total_licoes'] > 0
                    && $estatisticas['feitas'] === $estatisticas['total_licoes']
                    && $estatisticas['aproveitamento'] >= Catalogo::APROVEITAMENTO_CERTIFICADO,
                'certificado' => $emitidos[$nivel] ?? null,
            ];
        }
        return $situacao;
    }

    public function emitir(array $usuario, string $trilha, string $nivel): array
    {
        $info = Trilhas::info($this->pdo, $trilha) ?? throw new ValidationException(['certificado' => 'Trilha não encontrada.']);
        $situacao = $this->situacao($trilha, $usuario)[$nivel] ?? null;
        if ($situacao === null) {
            throw new ValidationException(['certificado' => 'Este nível não existe nesta trilha.']);
        }
        if ($situacao['certificado'] !== null) {
            return $situacao['certificado'];
        }
        if (!$situacao['pode_emitir']) {
            throw new ValidationException(['certificado' => sprintf(
                'Para emitir, conclua 100%% das lições e tenha pelo menos %d%% de acertos. Agora: %d%% concluído e %d%% de acertos.',
                Catalogo::APROVEITAMENTO_CERTIFICADO * 100,
                $situacao['percentual'],
                (int) floor($situacao['aproveitamento'] * 100)
            )]);
        }
        $certificado = (new Certificado($this->pdo))->create([
            'codigo_certificado' => self::novoCodigo(),
            'id_usuario' => (int) $usuario['id_usuario'],
            'trilha' => $trilha,
            'titulo_trilha' => $info['titulo'],
            'nivel_certificado' => $nivel,
            'nome_aluno' => $usuario['nome'],
            'horas_certificado' => $situacao['horas'],
            'aproveitamento_certificado' => round($situacao['aproveitamento'] * 100, 2),
        ]);
        (new Notificacao($this->pdo))->enviar(
            (int) $usuario['id_usuario'],
            'certificado',
            "📜 Certificado emitido: {$info['titulo']} {$situacao['rotulo']} ({$situacao['horas']} horas).",
            'certificado.php?codigo=' . $certificado['codigo_certificado']
        );
        (new ConquistaController($this->pdo))->avaliar((int) $usuario['id_usuario']);
        return $certificado;
    }

    /** Código de verificação sem caracteres que se confundem (0/O, 1/I). */
    private static function novoCodigo(): string
    {
        $alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $codigo = '';
        for ($i = 0; $i < 12; $i++) {
            $codigo .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
        }
        return $codigo;
    }
}
