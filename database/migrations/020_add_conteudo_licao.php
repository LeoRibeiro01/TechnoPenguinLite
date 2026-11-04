<?php
declare(strict_types=1);

return [
    'up' => 'ALTER TABLE licao
        ADD COLUMN teoria_licao MEDIUMTEXT NULL,
        ADD COLUMN pratica_licao MEDIUMTEXT NULL',
    'down' => 'ALTER TABLE licao
        DROP COLUMN pratica_licao,
        DROP COLUMN teoria_licao',
];
