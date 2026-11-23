<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Licao;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * ÁREA DO PROFESSOR - CRUD DE LIÇÕES
 * (Tem o mesmo nome do LicaoController do aluno, mas está em outra pasta/namespace,
 *  por isso não há conflito: App\Http\Controllers\Professor\LicaoController.)
 *
 *   create  GET    /professor/modulos/{modulo}/licoes/criar  formulário de lição nova
 *   store   POST   /professor/modulos/{modulo}/licoes        salva a lição
 *   edit    GET    /professor/licoes/{licao}/editar          dados da lição + exercícios
 *   update  PUT    /professor/licoes/{licao}                 salva a lição
 *   destroy DELETE /professor/licoes/{licao}                 exclui a lição (e os exercícios)
 */
class LicaoController extends Controller
{
    const REGRAS = [
        'titulo'  => 'required|max:100',
        'teoria'  => 'required',
        'pratica' => 'nullable',   // exemplo de código é opcional
    ];

    public function create(Modulo $modulo)
    {
        $this->verificarPermissao($modulo->curso);

        return view('professor.licoes.create', ['modulo' => $modulo]);
    }

    public function store(Request $request, Modulo $modulo)
    {
        $this->verificarPermissao($modulo->curso);

        $dados = $request->validate(self::REGRAS);
        // A nova lição vai para o fim do módulo: maior "ordem" que já existe + 1.
        $dados['ordem'] = Licao::where('modulo_id', $modulo->id)->max('ordem') + 1;

        $licao = $modulo->licoes()->create($dados);

        return redirect()->route('professor.licoes.edit', $licao)
            ->with('aviso', 'Lição criada! Agora adicione os exercícios.');
    }

    public function edit(Licao $licao)
    {
        $this->verificarPermissao($licao->modulo->curso);

        return view('professor.licoes.edit', ['licao' => $licao]);
    }

    public function update(Request $request, Licao $licao)
    {
        $this->verificarPermissao($licao->modulo->curso);

        $licao->update($request->validate(self::REGRAS));

        return redirect()->route('professor.licoes.edit', $licao)->with('aviso', 'Lição salva!');
    }

    public function destroy(Licao $licao)
    {
        $this->verificarPermissao($licao->modulo->curso);

        $modulo = $licao->modulo;
        $licao->delete();

        return redirect()->route('professor.modulos.edit', $modulo)->with('aviso', 'Lição excluída.');
    }

    private function verificarPermissao(Curso $curso)
    {
        if (!Auth::user()->podeEditar($curso)) {
            abort(403);
        }
    }
}
