<?php
declare(strict_types=1);

/** Múltipla escolha: alternativas em JSON; o gabarito guarda o índice da correta. */
return [
    'up' => 'ALTER TABLE questao
        ADD COLUMN alternativas_questao TEXT NULL,
        ADD COLUMN explicacao_questao TEXT NULL,
        ADD COLUMN sequencia_questao INT UNSIGNED NOT NULL DEFAULT 0',
    'down' => 'ALTER TABLE questao
        DROP COLUMN sequencia_questao,
        DROP COLUMN explicacao_questao,
        DROP COLUMN alternativas_questao',
];
