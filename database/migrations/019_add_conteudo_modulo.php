<?php
declare(strict_types=1);

return [
    'up' => 'ALTER TABLE modulo
        ADD COLUMN descricao_modulo VARCHAR(400) NULL DEFAULT NULL,
        ADD COLUMN projeto_modulo TEXT NULL',
    'down' => 'ALTER TABLE modulo
        DROP COLUMN projeto_modulo,
        DROP COLUMN descricao_modulo',
];
