<?php
declare(strict_types=1);

/** papel_membro: dono, admin ou membro. */
return [
    'up' => "CREATE TABLE iglu_membro (
        id_iglu INT UNSIGNED NOT NULL,
        id_usuario INT UNSIGNED NOT NULL,
        papel_membro VARCHAR(10) NOT NULL DEFAULT 'membro',
        entrou_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_iglu, id_usuario),
        KEY idx_iglu_membro_usuario (id_usuario),
        CONSTRAINT fk_iglu_membro_iglu FOREIGN KEY (id_iglu) REFERENCES iglu (id_iglu) ON DELETE CASCADE,
        CONSTRAINT fk_iglu_membro_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS iglu_membro',
];
