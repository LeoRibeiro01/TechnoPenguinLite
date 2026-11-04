<?php
declare(strict_types=1);

/** Nascimento (idade mínima do chat), consentimento, privacidade do perfil e papel de professor. */
return [
    'up' => "ALTER TABLE usuario
        ADD COLUMN data_nascimento DATE NULL DEFAULT NULL,
        ADD COLUMN aceite_termos_em DATETIME NULL DEFAULT NULL,
        ADD COLUMN email_responsavel VARCHAR(100) NULL DEFAULT NULL,
        ADD COLUMN perfil_visibilidade VARCHAR(10) NOT NULL DEFAULT 'todos',
        ADD COLUMN aparecer_busca TINYINT(1) NOT NULL DEFAULT 1,
        ADD COLUMN mostrar_online TINYINT(1) NOT NULL DEFAULT 1,
        ADD COLUMN papel_usuario VARCHAR(10) NOT NULL DEFAULT 'aluno'",
    'down' => 'ALTER TABLE usuario
        DROP COLUMN papel_usuario,
        DROP COLUMN mostrar_online,
        DROP COLUMN aparecer_busca,
        DROP COLUMN perfil_visibilidade,
        DROP COLUMN email_responsavel,
        DROP COLUMN aceite_termos_em,
        DROP COLUMN data_nascimento',
];
