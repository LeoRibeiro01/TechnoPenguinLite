<?php
declare(strict_types=1);

return [
    'up' => "ALTER TABLE usuario
        ADD COLUMN cor_corpo_avatar CHAR(7) NULL DEFAULT NULL,
        ADD COLUMN cor_barriga_avatar CHAR(7) NULL DEFAULT NULL,
        ADD COLUMN cor_detalhe_avatar CHAR(7) NULL DEFAULT NULL,
        ADD COLUMN nivel_conhecimento_usuario VARCHAR(20) NULL DEFAULT NULL,
        ADD UNIQUE KEY uq_usuario_nome (nome)",
    'down' => 'ALTER TABLE usuario
        DROP INDEX uq_usuario_nome,
        DROP COLUMN nivel_conhecimento_usuario,
        DROP COLUMN cor_detalhe_avatar,
        DROP COLUMN cor_barriga_avatar,
        DROP COLUMN cor_corpo_avatar',
];
