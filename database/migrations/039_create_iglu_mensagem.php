<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE iglu_mensagem (
        id_mensagem INT UNSIGNED NOT NULL AUTO_INCREMENT,
        id_iglu INT UNSIGNED NOT NULL,
        id_usuario INT UNSIGNED NOT NULL,
        texto_mensagem VARCHAR(500) NOT NULL,
        criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_mensagem),
        KEY idx_iglu_mensagem_iglu (id_iglu, id_mensagem),
        CONSTRAINT fk_iglu_mensagem_iglu FOREIGN KEY (id_iglu) REFERENCES iglu (id_iglu) ON DELETE CASCADE,
        CONSTRAINT fk_iglu_mensagem_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS iglu_mensagem',
];
