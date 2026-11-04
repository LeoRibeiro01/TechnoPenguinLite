<?php
declare(strict_types=1);

/** Tentativas de login com falha, para bloquear temporariamente ataques de força bruta. */
return [
    'up' => "CREATE TABLE tentativa_login (
        id_tentativa INT UNSIGNED NOT NULL AUTO_INCREMENT,
        chave_tentativa CHAR(64) NOT NULL,
        criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_tentativa),
        KEY idx_tentativa_chave (chave_tentativa, criada_em)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS tentativa_login',
];
