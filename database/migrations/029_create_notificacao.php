<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE notificacao (
        id_notificacao INT UNSIGNED NOT NULL AUTO_INCREMENT,
        id_usuario INT UNSIGNED NOT NULL,
        tipo_notificacao VARCHAR(20) NOT NULL,
        mensagem_notificacao VARCHAR(300) NOT NULL,
        link_notificacao VARCHAR(200) NULL DEFAULT NULL,
        lida_notificacao TINYINT(1) NOT NULL DEFAULT 0,
        criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_notificacao),
        KEY idx_notificacao_usuario (id_usuario, lida_notificacao),
        CONSTRAINT fk_notificacao_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS notificacao',
];
