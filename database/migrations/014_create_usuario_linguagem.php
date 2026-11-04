<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE usuario_linguagem (
        id_usuario INT UNSIGNED NOT NULL,
        linguagem VARCHAR(20) NOT NULL,
        data_escolha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_usuario, linguagem),
        CONSTRAINT fk_usuario_linguagem_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS usuario_linguagem',
];
