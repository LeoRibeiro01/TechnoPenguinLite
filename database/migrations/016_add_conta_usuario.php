<?php
declare(strict_types=1);

/** "nome" passa a ser o nome de exibição; o identificador único do perfil é o @arroba. */
return [
    'up' => [
        "ALTER TABLE usuario
            ADD COLUMN arroba VARCHAR(30) NULL DEFAULT NULL AFTER nome,
            ADD COLUMN objetivo_usuario VARCHAR(20) NULL DEFAULT NULL,
            ADD COLUMN meta_diaria_usuario SMALLINT UNSIGNED NULL DEFAULT NULL,
            ADD COLUMN xp_usuario INT UNSIGNED NOT NULL DEFAULT 0,
            ADD COLUMN pontos_usuario INT UNSIGNED NOT NULL DEFAULT 0,
            ADD COLUMN cor_fundo_avatar CHAR(7) NULL DEFAULT NULL,
            ADD COLUMN chapeu_avatar VARCHAR(40) NULL DEFAULT NULL,
            ADD COLUMN oculos_avatar VARCHAR(40) NULL DEFAULT NULL,
            ADD COLUMN pescoco_avatar VARCHAR(40) NULL DEFAULT NULL,
            ADD COLUMN roupa_avatar VARCHAR(40) NULL DEFAULT NULL,
            ADD COLUMN ultimo_acesso_usuario DATETIME NULL DEFAULT NULL,
            ADD COLUMN ultimo_alerta_gelo DATE NULL DEFAULT NULL",
        'UPDATE usuario SET arroba = LOWER(nome) WHERE arroba IS NULL',
        'ALTER TABLE usuario
            MODIFY arroba VARCHAR(30) NOT NULL,
            ADD UNIQUE KEY uq_usuario_arroba (arroba),
            DROP INDEX uq_usuario_nome',
    ],
    'down' => 'ALTER TABLE usuario
        ADD UNIQUE KEY uq_usuario_nome (nome),
        DROP INDEX uq_usuario_arroba,
        DROP COLUMN ultimo_alerta_gelo,
        DROP COLUMN ultimo_acesso_usuario,
        DROP COLUMN roupa_avatar,
        DROP COLUMN pescoco_avatar,
        DROP COLUMN oculos_avatar,
        DROP COLUMN chapeu_avatar,
        DROP COLUMN cor_fundo_avatar,
        DROP COLUMN pontos_usuario,
        DROP COLUMN xp_usuario,
        DROP COLUMN meta_diaria_usuario,
        DROP COLUMN objetivo_usuario,
        DROP COLUMN arroba',
];
