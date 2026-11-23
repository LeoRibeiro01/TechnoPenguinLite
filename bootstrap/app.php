<?php

/*
 * Configuração inicial do Laravel (arquivo padrão do framework).
 * Mudanças do projeto:
 *   - quem já está logado e tenta abrir login/cadastro vai para o painel;
 *   - registra o apelido 'tipo' para o nosso middleware VerificarTipo (aluno/professor/admin).
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',        // nossas rotas
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo('/painel');   // middleware 'guest' manda logados para o painel

        // Agora dá para escrever ->middleware('tipo:admin') nas rotas.
        $middleware->alias([
            'tipo' => \App\Http\Middleware\VerificarTipo::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
