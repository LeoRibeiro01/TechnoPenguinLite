<?php
declare(strict_types=1);

/** Código estável para a aplicação reconhecer cada conquista; o catálogo fica em Support\Conquistas. */
return [
    'up' => 'ALTER TABLE conquista
        ADD COLUMN codigo_conquista VARCHAR(40) NULL DEFAULT NULL,
        ADD COLUMN icone_conquista VARCHAR(10) NULL DEFAULT NULL,
        ADD UNIQUE KEY uq_conquista_codigo (codigo_conquista)',
    'down' => 'ALTER TABLE conquista
        DROP INDEX uq_conquista_codigo,
        DROP COLUMN icone_conquista,
        DROP COLUMN codigo_conquista',
];
