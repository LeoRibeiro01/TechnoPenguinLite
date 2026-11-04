<?php
declare(strict_types=1);

return [
    'up' => 'ALTER TABLE progresso
        ADD COLUMN acertos_progresso SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        ADD COLUMN xp_progresso INT UNSIGNED NOT NULL DEFAULT 0,
        ADD COLUMN pontos_progresso INT UNSIGNED NOT NULL DEFAULT 0,
        ADD KEY idx_progresso_data (data_conclusao_progresso)',
    'down' => 'ALTER TABLE progresso
        DROP INDEX idx_progresso_data,
        DROP COLUMN pontos_progresso,
        DROP COLUMN xp_progresso,
        DROP COLUMN acertos_progresso',
];
