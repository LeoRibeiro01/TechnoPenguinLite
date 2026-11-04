<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use PDO;
use TechnoPenguin\Models\Iglu;
use TechnoPenguin\Models\Notificacao;
use TechnoPenguin\Models\Sequencia;
use TechnoPenguin\Models\Usuario;
use TechnoPenguin\Support\Gelo;

/**
 * Mantém o gelo (ofensiva) dos usuários e dos iglus. O derretimento é verificado de forma
 * preguiçosa: quando o usuário ou um membro do iglu abre a plataforma.
 */
final class GeloController
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** Estado do gelo do usuário para a interface. */
    public function resumo(int $idUsuario): array
    {
        $sequencia = $this->sequencia($idUsuario);
        $dias = (int) $sequencia['dias_consecutivos_sequencia'];
        $ultima = $sequencia['ultima_atividade_sequencia'];
        return [
            'dias' => $dias,
            'estado' => Gelo::estado($dias, $ultima),
            'dias_ate_derreter' => Gelo::diasAteDerreter($ultima),
            'inicio' => $sequencia['inicio_sequencia'],
        ];
    }

    /** Conta uma lição de hoje no gelo do usuário e no de todos os iglus dele. */
    public function registrarLicao(int $idUsuario, string $arroba): array
    {
        $sequencia = $this->sequencia($idUsuario);
        [$dias, $inicio, $ligou] = Gelo::registrar(
            (int) $sequencia['dias_consecutivos_sequencia'],
            $sequencia['inicio_sequencia'],
            $sequencia['ultima_atividade_sequencia']
        );
        (new Sequencia($this->pdo))->update((int) $sequencia['id_sequencia'], [
            'dias_consecutivos_sequencia' => $dias,
            'inicio_sequencia' => $inicio,
            'ultima_atividade_sequencia' => date('Y-m-d H:i:s'),
        ]);
        if ($ligou) {
            $this->notificacoes()->enviar($idUsuario, 'gelo', '🧊 Seu Gelo foi ligado! Faça pelo menos uma lição a cada dia para ele crescer.', 'painel.php');
        }

        $iglus = new Iglu($this->pdo);
        foreach ($iglus->doUsuario($idUsuario) as $iglu) {
            [$diasIglu, $inicioIglu, $ligouIglu] = Gelo::registrar(
                (int) $iglu['dias_consecutivos_iglu'],
                $iglu['inicio_sequencia_iglu'],
                $iglu['ultima_atividade_iglu']
            );
            $iglus->update((int) $iglu['id_iglu'], [
                'dias_consecutivos_iglu' => $diasIglu,
                'inicio_sequencia_iglu' => $inicioIglu,
                'ultima_atividade_iglu' => date('Y-m-d'),
            ]);
            if ($ligouIglu) {
                $this->notificacoes()->enviarParaVarios(
                    $iglus->idsMembros((int) $iglu['id_iglu']),
                    'iglu',
                    "🧊 @{$arroba} ligou o gelo do iglu {$iglu['nome_iglu']}!",
                    'iglu.php?id=' . $iglu['id_iglu']
                );
            }
        }
        return ['dias' => $dias, 'ligou' => $ligou];
    }

    /** Derrete o gelo do usuário e dos iglus dele que passaram do prazo, avisando os envolvidos. */
    public function verificar(int $idUsuario): void
    {
        $sequencia = $this->sequencia($idUsuario);
        if (Gelo::estado((int) $sequencia['dias_consecutivos_sequencia'], $sequencia['ultima_atividade_sequencia']) === Gelo::DERRETIDO) {
            $diasPerdidos = (int) $sequencia['dias_consecutivos_sequencia'];
            (new Sequencia($this->pdo))->update((int) $sequencia['id_sequencia'], [
                'dias_consecutivos_sequencia' => 0,
                'inicio_sequencia' => null,
            ]);
            $this->notificacoes()->enviar(
                $idUsuario,
                'gelo',
                "💧 Seu Gelo de {$diasPerdidos} " . ($diasPerdidos === 1 ? 'dia' : 'dias') . ' derreteu depois de mais de ' . Gelo::DIAS_TOLERANCIA . ' dias sem lições. Faça uma lição para ligar outro!',
                'painel.php'
            );
        }

        $iglus = new Iglu($this->pdo);
        foreach ($iglus->doUsuario($idUsuario) as $iglu) {
            if (Gelo::estado((int) $iglu['dias_consecutivos_iglu'], $iglu['ultima_atividade_iglu']) !== Gelo::DERRETIDO) {
                continue;
            }
            $iglus->update((int) $iglu['id_iglu'], ['dias_consecutivos_iglu' => 0, 'inicio_sequencia_iglu' => null]);
            $this->notificacoes()->enviarParaVarios(
                $iglus->idsMembros((int) $iglu['id_iglu']),
                'iglu',
                "💧 O iglu {$iglu['nome_iglu']} derreteu! Ninguém fez lições por mais de " . Gelo::DIAS_TOLERANCIA . ' dias. Façam uma lição para reconstruí-lo.',
                'iglu.php?id=' . $iglu['id_iglu']
            );
        }
    }

    /**
     * Alerta diário enquanto o usuário ainda não fez a lição do dia. Devolve null quando já
     * houve lição hoje ou quando o alerta de hoje já foi mostrado.
     */
    public function alertaDoDia(array $usuario): ?array
    {
        $resumo = $this->resumo((int) $usuario['id_usuario']);
        $hoje = Gelo::hoje()->format('Y-m-d');
        if ($resumo['estado'] === Gelo::FEITO_HOJE || $usuario['ultimo_alerta_gelo'] === $hoje) {
            return null;
        }
        (new Usuario($this->pdo))->update((int) $usuario['id_usuario'], ['ultimo_alerta_gelo' => $hoje]);
        return $resumo;
    }

    private function sequencia(int $idUsuario): array
    {
        (new Usuario($this->pdo))->iniciarSequencia($idUsuario);
        return (new Sequencia($this->pdo))->deUsuario($idUsuario);
    }

    private function notificacoes(): Notificacao
    {
        return new Notificacao($this->pdo);
    }
}
