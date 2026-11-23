<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * ÁREA DO PROFESSOR - CRUD DE MÓDULOS
 *
 *   store   POST   /professor/cursos/{curso}/modulos   cria um módulo (formulário fica na tela do curso)
 *   edit    GET    /professor/modulos/{modulo}/editar  dados do módulo + lista de lições
 *   update  PUT    /professor/modulos/{modulo}         salva o módulo
 *   destroy DELETE /professor/modulos/{modulo}         exclui o módulo (e as lições dele)
 */
class ModuloController extends Controller
{
    // As regras de validação são as mesmas no store e no update, então ficam num lugar só.
    const REGRAS = [
        'titulo'      => 'required|max:100',
        'descricao'   => 'required|max:255',
        'dificuldade' => 'required|in:iniciante,intermediario,avancado',
    ];

    public function store(Request $request, Curso $curso)
    {
        $this->verificarPermissao($curso);

        $dados = $request->validate(self::REGRAS);
        // O módulo novo vai para o fim: maior "ordem" que já existe + 1.
        $dados['ordem'] = Modulo::where('curso_id', $curso->id)->max('ordem') + 1;

        // $curso->modulos()->create() já preenche o curso_id.
        $modulo = $curso->modulos()->create($dados);

        return redirect()->route('professor.modulos.edit', $modulo)
            ->with('aviso', 'Módulo criado! Agora adicione as lições.');
    }

    public function edit(Modulo $modulo)
    {
        $this->verificarPermissao($modulo->curso);

        return view('professor.modulos.edit', [
            'modulo' => $modulo,
            'licoes' => $modulo->licoes()->withCount('exercicios')->get(),
        ]);
    }

    public function update(Request $request, Modulo $modulo)
    {
        $this->verificarPermissao($modulo->curso);

        $modulo->update($request->validate(self::REGRAS));

        return redirect()->route('professor.modulos.edit', $modulo)->with('aviso', 'Módulo salvo!');
    }

    public function destroy(Modulo $modulo)
    {
        $this->verificarPermissao($modulo->curso);

        $curso = $modulo->curso;
        $modulo->delete();

        return redirect()->route('professor.cursos.edit', $curso)->with('aviso', 'Módulo excluído.');
    }

    private function verificarPermissao(Curso $curso)
    {
        if (!Auth::user()->podeEditar($curso)) {
            abort(403);
        }
    }
}
