<?php
declare(strict_types=1);

/** Perguntas da comunidade: públicas ou só para o professor do curso; só ele responde. */
return [
    'up' => "CREATE TABLE pergunta (
        id_pergunta INT UNSIGNED NOT NULL AUTO_INCREMENT,
        id_curso INT UNSIGNED NOT NULL,
        id_aluno INT UNSIGNED NOT NULL,
        texto_pergunta VARCHAR(1000) NOT NULL,
        publica_pergunta TINYINT(1) NOT NULL DEFAULT 1,
        resposta_pergunta TEXT NULL,
        respondida_em DATETIME NULL DEFAULT NULL,
        criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_pergunta),
        KEY idx_pergunta_curso (id_curso, criada_em),
        CONSTRAINT fk_pergunta_curso FOREIGN KEY (id_curso) REFERENCES curso (id_curso) ON DELETE CASCADE,
        CONSTRAINT fk_pergunta_aluno FOREIGN KEY (id_aluno) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS pergunta',
];
