<?php
declare(strict_types=1);

/** Trilhas agora incluem assuntos, com slugs mais longos que os das linguagens. */
return [
    'up' => 'ALTER TABLE usuario_linguagem MODIFY linguagem VARCHAR(40) NOT NULL',
    'down' => 'ALTER TABLE usuario_linguagem MODIFY linguagem VARCHAR(20) NOT NULL',
];
