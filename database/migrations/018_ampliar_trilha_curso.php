<?php
declare(strict_types=1);

/** Cada trilha tem exatamente um curso. */
return [
    'up' => 'ALTER TABLE curso
        MODIFY linguagem_curso VARCHAR(40) NULL DEFAULT NULL,
        ADD UNIQUE KEY uq_curso_linguagem (linguagem_curso),
        DROP INDEX idx_curso_linguagem',
    'down' => 'ALTER TABLE curso
        ADD KEY idx_curso_linguagem (linguagem_curso),
        DROP INDEX uq_curso_linguagem,
        MODIFY linguagem_curso VARCHAR(20) NULL DEFAULT NULL',
];
