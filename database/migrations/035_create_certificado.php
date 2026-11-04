<?php
declare(strict_types=1);

/** Nome, trilha e horas são copiados na emissão para o certificado não mudar depois. */
return [
    'up' => "CREATE TABLE certificado (
        id_certificado INT UNSIGNED NOT NULL AUTO_INCREMENT,
        codigo_certificado CHAR(12) NOT NULL,
        id_usuario INT UNSIGNED NOT NULL,
        trilha VARCHAR(40) NOT NULL,
        titulo_trilha VARCHAR(100) NOT NULL,
        nivel_certificado VARCHAR(15) NOT NULL,
        nome_aluno VARCHAR(50) NOT NULL,
        horas_certificado SMALLINT UNSIGNED NOT NULL,
        aproveitamento_certificado DECIMAL(5, 2) NOT NULL,
        emitido_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_certificado),
        UNIQUE KEY uq_certificado_codigo (codigo_certificado),
        UNIQUE KEY uq_certificado_usuario_nivel (id_usuario, trilha, nivel_certificado),
        CONSTRAINT fk_certificado_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS certificado',
];
