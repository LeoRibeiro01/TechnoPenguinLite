<?php

/*
 * COMANDOS DE TERMINAL (arquivo padrão do Laravel, não foi alterado)
 * Aqui se criam comandos próprios do "php artisan". O "inspire" é só um exemplo do Laravel
 * (php artisan inspire mostra uma frase). O projeto não usa comandos próprios.
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
