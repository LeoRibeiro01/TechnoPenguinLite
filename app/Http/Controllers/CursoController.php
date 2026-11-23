<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DE CURSO
 *   RF22 -> o usuário escolhe um curso (clicando nele no painel)
 *   RF23 -> mostra os módulos do curso
 *   RF24 -> mostra as lições de cada módulo (concluída, liberada ou bloqueada)
 *   RF16 -> mostra o progresso no curso
 */
class CursoController extends Controller
{
    // GET /cursos/{curso}
    // "Curso $curso" = Route Model Binding: o Laravel pega o {curso} da URL,
    // faz Curso::findOrFail(id) sozinho e entrega o objeto pronto (ou mostra 404 se não existir).
    public function show(Curso $curso)
    {
        // Curso da comunidade ainda em rascunho: só o professor dono e os admins podem ver.
        if (!$curso->visivelPara(Auth::user())) {
            abort(404);
        }

        $licoes = $curso->licoesDoUsuario(Auth::user());

        return view('cursos.show', [
            'curso'   => $curso,
            'modulos' => $curso->modulos,
            // groupBy separa as lições por módulo: $licoesPorModulo[id_do_modulo] = lista de lições
            'licoesPorModulo' => $licoes->groupBy('modulo_id'),
            'feitas'  => $licoes->where('aprovada', true)->count(),
            'total'   => $licoes->count(),
        ]);
    }
}
