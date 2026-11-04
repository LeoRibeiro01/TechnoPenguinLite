<?php
declare(strict_types=1);

/** acertos_progresso passa a guardar a melhor tentativa; refazer a lição pode melhorar o aproveitamento. */
return [
    'up' => 'ALTER TABLE progresso
        ADD COLUMN total_questoes_progresso SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        ADD COLUMN tentativas_progresso SMALLINT UNSIGNED NOT NULL DEFAULT 0',
    'down' => 'ALTER TABLE progresso
        DROP COLUMN tentativas_progresso,
        DROP COLUMN total_questoes_progresso',
];
