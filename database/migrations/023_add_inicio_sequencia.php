<?php
declare(strict_types=1);

return [
    'up' => 'ALTER TABLE sequencia ADD COLUMN inicio_sequencia DATE NULL DEFAULT NULL',
    'down' => 'ALTER TABLE sequencia DROP COLUMN inicio_sequencia',
];
