<?php
declare(strict_types=1);

/** Aparência do iglu, se aceita pedidos de entrada e se o chat está ligado. */
return [
    'up' => "ALTER TABLE iglu
        ADD COLUMN cor_iglu CHAR(7) NOT NULL DEFAULT '#5c7aa5',
        ADD COLUMN icone_iglu VARCHAR(10) NOT NULL DEFAULT '🐧',
        ADD COLUMN aberto_iglu TINYINT(1) NOT NULL DEFAULT 1,
        ADD COLUMN chat_ativo TINYINT(1) NOT NULL DEFAULT 1",
    'down' => 'ALTER TABLE iglu
        DROP COLUMN chat_ativo,
        DROP COLUMN aberto_iglu,
        DROP COLUMN icone_iglu,
        DROP COLUMN cor_iglu',
];
