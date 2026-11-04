<?php
declare(strict_types=1);

/** tipo_convite "convite" parte de um membro; "pedido" parte de quem quer entrar. */
return [
    'up' => "CREATE TABLE iglu_convite (
        id_convite INT UNSIGNED NOT NULL AUTO_INCREMENT,
        id_iglu INT UNSIGNED NOT NULL,
        id_usuario INT UNSIGNED NOT NULL,
        id_remetente INT UNSIGNED NULL,
        tipo_convite VARCHAR(10) NOT NULL,
        status_convite VARCHAR(10) NOT NULL DEFAULT 'pendente',
        criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_convite),
        KEY idx_iglu_convite_usuario (id_usuario, status_convite),
        KEY idx_iglu_convite_iglu (id_iglu, status_convite),
        CONSTRAINT fk_iglu_convite_iglu FOREIGN KEY (id_iglu) REFERENCES iglu (id_iglu) ON DELETE CASCADE,
        CONSTRAINT fk_iglu_convite_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
        CONSTRAINT fk_iglu_convite_remetente FOREIGN KEY (id_remetente) REFERENCES usuario (id_usuario) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS iglu_convite',
];
