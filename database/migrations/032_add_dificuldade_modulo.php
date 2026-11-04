<?php
declare(strict_types=1);

/** Módulos agrupados por dificuldade, com texto de aprofundamento e referências. */
return [
    'up' => "ALTER TABLE modulo
        ADD COLUMN dificuldade_modulo VARCHAR(15) NOT NULL DEFAULT 'iniciante',
        ADD COLUMN aprofundamento_modulo TEXT NULL,
        ADD COLUMN referencias_modulo TEXT NULL",
    'down' => 'ALTER TABLE modulo
        DROP COLUMN referencias_modulo,
        DROP COLUMN aprofundamento_modulo,
        DROP COLUMN dificuldade_modulo',
];
