<?php
declare(strict_types=1);

return [
    'up' => "ALTER TABLE curso
        ADD COLUMN linguagem_curso VARCHAR(20) NULL DEFAULT NULL,
        ADD KEY idx_curso_linguagem (linguagem_curso)",
    'down' => 'ALTER TABLE curso
        DROP INDEX idx_curso_linguagem,
        DROP COLUMN linguagem_curso',
];
