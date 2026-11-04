<?php
declare(strict_types=1);

/** Trilhas estudadas no iglu; as obrigatórias entram no perfil de quem entra no iglu. */
return [
    'up' => "CREATE TABLE iglu_trilha (
        id_iglu INT UNSIGNED NOT NULL,
        trilha VARCHAR(40) NOT NULL,
        obrigatoria TINYINT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (id_iglu, trilha),
        CONSTRAINT fk_iglu_trilha_iglu FOREIGN KEY (id_iglu) REFERENCES iglu (id_iglu) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS iglu_trilha',
];
