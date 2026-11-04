<?php
declare(strict_types=1);

namespace TechnoPenguin\Support;

use DateTimeImmutable;

/**
 * Regras do "Gelo", a ofensiva de dias com lições. Vale para usuários e para iglus.
 * Cada dia com pelo menos uma lição soma um dia ao gelo. Depois de mais de
 * DIAS_TOLERANCIA dias seguidos sem lição, o gelo derrete e a contagem volta a zero.
 */
final class Gelo
{
    public const DIAS_TOLERANCIA = 3;

    public const APAGADO = 'apagado';
    public const FEITO_HOJE = 'feito_hoje';
    public const PENDENTE = 'pendente';
    public const DERRETIDO = 'derretido';

    public static function hoje(): DateTimeImmutable
    {
        return new DateTimeImmutable('today');
    }

    /** Dias completos sem lição desde a última atividade (0 quando a última foi hoje ou ontem). */
    public static function diasSemLicao(?string $ultimaAtividade, ?DateTimeImmutable $hoje = null): ?int
    {
        if ($ultimaAtividade === null) {
            return null;
        }
        $hoje ??= self::hoje();
        $ultima = new DateTimeImmutable(substr($ultimaAtividade, 0, 10));
        return max(0, (int) $ultima->diff($hoje)->format('%r%a') - 1);
    }

    public static function estado(int $dias, ?string $ultimaAtividade, ?DateTimeImmutable $hoje = null): string
    {
        $semLicao = self::diasSemLicao($ultimaAtividade, $hoje);
        if ($semLicao === null) {
            return self::APAGADO;
        }
        if ($semLicao > self::DIAS_TOLERANCIA) {
            return $dias > 0 ? self::DERRETIDO : self::APAGADO;
        }
        if ($dias === 0) {
            return self::APAGADO;
        }
        $hoje ??= self::hoje();
        return substr($ultimaAtividade, 0, 10) === $hoje->format('Y-m-d') ? self::FEITO_HOJE : self::PENDENTE;
    }

    /** Quantos dias ainda podem passar sem lição antes de o gelo derreter. */
    public static function diasAteDerreter(?string $ultimaAtividade, ?DateTimeImmutable $hoje = null): int
    {
        return max(0, self::DIAS_TOLERANCIA - (int) self::diasSemLicao($ultimaAtividade, $hoje));
    }

    /**
     * Novo [dias, início] depois de uma lição hoje. Não muda nada se já houve lição hoje;
     * recomeça do 1 se o gelo estava apagado ou derretido.
     */
    public static function registrar(int $dias, ?string $inicio, ?string $ultimaAtividade, ?DateTimeImmutable $hoje = null): array
    {
        $hoje ??= self::hoje();
        $estado = self::estado($dias, $ultimaAtividade, $hoje);
        return match ($estado) {
            self::FEITO_HOJE => [$dias, $inicio, false],
            self::PENDENTE => [$dias + 1, $inicio, false],
            default => [1, $hoje->format('Y-m-d'), true],
        };
    }
}
