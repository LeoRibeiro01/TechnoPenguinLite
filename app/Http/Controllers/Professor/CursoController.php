<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * ÁREA DO PROFESSOR - CRUD DE CURSOS DA COMUNIDADE
 * Rotas com o prefixo /professor, protegidas pelo middleware 'tipo:professor,admin'.
 *
 *   index   GET    /professor                       lista os cursos do professor
 *   create  GET    /professor/cursos/criar          formulário de curso novo
 *   store   POST   /professor/cursos                salva o curso novo (como rascunho)
 *   edit    GET    /professor/cursos/{curso}/editar dados do curso + lista de módulos
 *   update  PUT    /professor/cursos/{curso}        salva os dados e publica/despublica
 *   destroy DELETE /professor/cursos/{curso}        exclui o curso (e tudo dentro dele)
 *
 * Esta pasta (Professor/) tem 4 controllers, um para cada nível: Curso > Modulo > Licao > Exercicio.
 * "namespace App\Http\Controllers\Professor" = o endereço da classe, que segue o nome da pasta.
 */
class CursoController extends Controller
{
    public function index()
    {
        return view('professor.cursos.index', [
            'cursos' => Auth::user()->cursosCriados()->withCount('licoes')->orderBy('titulo')->get(),
        ]);
    }

    public function create()
    {
        return view('professor.cursos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo'    => 'required|max:100',
            'descricao' => 'required|max:255',
            'sigla'     => 'required|max:5',
            'cor'       => 'required|regex:/^#[0-9a-fA-F]{6}$/',
        ]);

        $dados['professor_id'] = Auth::id();
        $dados['tipo'] = 'comunidade';
        $dados['publicado'] = false;   // começa como rascunho: só aparece para os alunos quando publicar

        $curso = Curso::create($dados);

        return redirect()->route('professor.cursos.edit', $curso)
            ->with('aviso', 'Curso criado! Agora adicione os módulos.');
    }

    public function edit(Curso $curso)
    {
        $this->verificarPermissao($curso);

        return view('professor.cursos.edit', [
            'curso'   => $curso,
            'modulos' => $curso->modulos()->withCount('licoes')->get(),
        ]);
    }

    public function update(Request $request, Curso $curso)
    {
        $this->verificarPermissao($curso);

        $dados = $request->validate([
            'titulo'    => 'required|max:100',
            'descricao' => 'required|max:255',
            'sigla'     => 'required|max:5',
            'cor'       => 'required|regex:/^#[0-9a-fA-F]{6}$/',
        ]);
        $dados['publicado'] = $request->boolean('publicado');   // checkbox "Publicar"

        // Para publicar, o curso precisa de pelo menos uma lição, e toda lição precisa de exercício.
        if ($dados['publicado']) {
            // doesntHave('exercicios') = lições que NÃO têm nenhum exercício
            $semExercicio = $curso->licoes()->doesntHave('exercicios')->count();
            if ($curso->licoes()->count() === 0 || $semExercicio > 0) {
                return back()->withErrors(['publicado' => 'Para publicar, o curso precisa ter lições e toda lição precisa ter pelo menos um exercício.']);
            }
        }

        $curso->update($dados);

        return redirect()->route('professor.cursos.edit', $curso)->with('aviso', 'Curso salvo!');
    }

    public function destroy(Curso $curso)
    {
        $this->verificarPermissao($curso);

        // cascadeOnDelete nas migrations: apaga junto módulos, lições, exercícios e o progresso dos alunos.
        $curso->delete();

        // Admin volta para o painel de administração; professor volta para a lista dele.
        if (Auth::user()->ehAdmin()) {
            return redirect()->route('admin.index')->with('aviso', 'Curso excluído.');
        }
        return redirect()->route('professor.cursos.index')->with('aviso', 'Curso excluído.');
    }

    // Só o professor dono do curso (ou um admin) pode mexer nele.
    private function verificarPermissao(Curso $curso)
    {
        if (!Auth::user()->podeEditar($curso)) {
            abort(403);
        }
    }
}
