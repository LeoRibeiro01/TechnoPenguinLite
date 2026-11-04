<?php
declare(strict_types=1);

/** Um pedido por par de usuários; fica "pendente" até o destinatário aceitar. */
return [
    'up' => "CREATE TABLE amizade (
        id_amizade INT UNSIGNED NOT NULL AUTO_INCREMENT,
        id_solicitante INT UNSIGNED NOT NULL,
        id_destinatario INT UNSIGNED NOT NULL,
        status_amizade VARCHAR(20) NOT NULL DEFAULT 'pendente',
        criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_amizade),
        UNIQUE KEY uq_amizade_par (id_solicitante, id_destinatario),
        KEY idx_amizade_destinatario (id_destinatario),
        CONSTRAINT fk_amizade_solicitante FOREIGN KEY (id_solicitante) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
        CONSTRAINT fk_amizade_destinatario FOREIGN KEY (id_destinatario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS amizade',
];
