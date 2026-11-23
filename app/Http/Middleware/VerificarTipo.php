<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/*
 * MIDDLEWARE "tipo" - deixa passar só os tipos de usuário permitidos.
 *
 * Middleware = um "porteiro" que roda ANTES do controller.
 * Uso nas rotas (routes/web.php):
 *   Route::middleware('tipo:admin')->group(...)            -> só admin
 *   Route::middleware('tipo:professor,admin')->group(...)  -> professor ou admin
 *
 * O apelido "tipo" é registrado em bootstrap/app.php.
 */
class VerificarTipo
{
    // ...$tipos recebe a lista que vem depois dos dois-pontos: 'tipo:professor,admin' -> ['professor', 'admin']
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        if (!in_array($request->user()->tipo, $tipos)) {
            abort(403, 'Você não tem permissão para acessar esta página.');   // 403 = proibido
        }

        return $next($request);   // pode passar: segue para o controller
    }
}
