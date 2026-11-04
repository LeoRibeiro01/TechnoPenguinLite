<?php
declare(strict_types=1);

/** Itens de vestuário comprados; o catálogo fica em TechnoPenguin\Support\Vestuario. */
return [
    'up' => "CREATE TABLE usuario_item (
        id_usuario INT UNSIGNED NOT NULL,
        item VARCHAR(40) NOT NULL,
        comprado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_usuario, item),
        CONSTRAINT fk_usuario_item_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS usuario_item',
];
