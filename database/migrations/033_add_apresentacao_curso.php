<?php
declare(strict_types=1);

/**
 * Tela de apresentação da trilha e cursos da comunidade: um curso com id_professor
 * foi criado por um professor; os oficiais têm id_professor nulo.
 */
return [
    'up' => [
        "ALTER TABLE curso
            ADD COLUMN historia_curso TEXT NULL,
            ADD COLUMN uso_atual_curso TEXT NULL,
            ADD COLUMN objetivos_curso TEXT NULL,
            ADD COLUMN referencias_curso TEXT NULL,
            ADD COLUMN tipo_curso VARCHAR(15) NOT NULL DEFAULT 'linguagem',
            ADD COLUMN sigla_curso VARCHAR(6) NULL DEFAULT NULL,
            ADD COLUMN cor_curso CHAR(7) NULL DEFAULT NULL,
            ADD COLUMN publicado_curso TINYINT(1) NOT NULL DEFAULT 1,
            ADD COLUMN id_professor INT UNSIGNED NULL DEFAULT NULL,
            ADD COLUMN criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
        'ALTER TABLE curso
            ADD CONSTRAINT fk_curso_professor FOREIGN KEY (id_professor) REFERENCES usuario (id_usuario) ON DELETE SET NULL',
    ],
    'down' => [
        'ALTER TABLE curso DROP FOREIGN KEY fk_curso_professor',
        'ALTER TABLE curso
            DROP INDEX fk_curso_professor,
            DROP COLUMN criado_em,
            DROP COLUMN id_professor,
            DROP COLUMN publicado_curso,
            DROP COLUMN cor_curso,
            DROP COLUMN sigla_curso,
            DROP COLUMN tipo_curso,
            DROP COLUMN referencias_curso,
            DROP COLUMN objetivos_curso,
            DROP COLUMN uso_atual_curso,
            DROP COLUMN historia_curso',
    ],
];
