<?php
declare(strict_types=1);

namespace TechnoPenguin\Support;

/** Catálogo das conquistas; o seeder grava cada uma na tabela conquista pelo código. */
final class Conquistas
{
    /** código => [nome, descrição, ícone]. */
    public const CATALOGO = [
        'primeira-licao' => ['Primeiros passos', 'Conclua sua primeira lição.', '🐣'],
        'dez-licoes' => ['Pinguim estudioso', 'Conclua 10 lições.', '📚'],
        'cinquenta-licoes' => ['Pinguim sábio', 'Conclua 50 lições.', '🦉'],
        'nota-maxima' => ['Gabaritou!', 'Acerte todas as questões de uma lição.', '💯'],
        'gelo-3' => ['Gelo firme', 'Mantenha o gelo por 3 dias.', '🧊'],
        'gelo-7' => ['Geleira', 'Mantenha o gelo por 7 dias.', '🏔️'],
        'gelo-30' => ['Era do gelo', 'Mantenha o gelo por 30 dias.', '❄️'],
        'poliglota' => ['Poliglota', 'Estude 3 linguagens ou assuntos diferentes.', '🌐'],
        'primeiro-certificado' => ['Certificado!', 'Emita seu primeiro certificado.', '📜'],
        'trilha-completa' => ['Domínio total', 'Conclua uma trilha inteira e emita o certificado completo.', '🏆'],
        'sociavel' => ['Pinguim sociável', 'Tenha 3 amigos.', '🤝'],
        'fundador' => ['Construtor de iglus', 'Crie um iglu.', '🏠'],
        'em-grupo' => ['Bando unido', 'Participe de um iglu.', '🐧'],
        'estiloso' => ['Pinguim estiloso', 'Compre 3 peças exclusivas no vestuário.', '🎩'],
    ];

    /** Nomes usados pelo seeder antigo, para reaproveitar as linhas que já existem. */
    public const NOMES_ANTIGOS = [
        'Primeiros passos' => 'primeira-licao',
        'Sequência de 3 dias' => 'gelo-3',
        'Primeira lição concluída' => 'primeira-licao',
    ];
}
