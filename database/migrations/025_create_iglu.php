<?php
declare(strict_types=1);

/** Grupo de estudos com ofensiva própria (o gelo do iglu). */
return [
    'up' => "CREATE TABLE iglu (
        id_iglu INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nome_iglu VARCHAR(60) NOT NULL,
        descricao_iglu VARCHAR(400) NOT NULL,
        id_criador INT UNSIGNED NULL,
        dias_consecutivos_iglu INT UNSIGNED NOT NULL DEFAULT 0,
        ultima_atividade_iglu DATE NULL DEFAULT NULL,
        inicio_sequencia_iglu DATE NULL DEFAULT NULL,
        criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_iglu),
        CONSTRAINT fk_iglu_criador FOREIGN KEY (id_criador) REFERENCES usuario (id_usuario) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS iglu',
];
