<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Progresso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * PAINEL DE ADMINISTRAÇÃO (só admin: middleware 'tipo:admin' nas rotas)
 *   index       GET    /admin                        números gerais, usuários (com busca) e cursos da comunidade
 *   alterarTipo PUT    /admin/usuarios/{usuario}/tipo  muda o tipo: aluno, professor ou admin
 *   excluir     DELETE /admin/usuarios/{usuario}       remove um usuário
 * Para editar ou excluir cursos da comunidade, o admin usa as telas da área do professor
 * (User::podeEditar() deixa o admin mexer em qualquer curso da comunidade).
 */
class AdminController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->query('busca');   // o que foi digitado no campo de busca (?busca=...)

        $usuarios = User::orderBy('nome');
        if ($busca) {
            // where(function...) agrupa as condições entre parênteses: (nome LIKE ? OR email LIKE ? OR nome_usuario LIKE ?)
            $usuarios->where(function ($consulta) use ($busca) {
                $consulta->where('nome', 'like', "%$busca%")
                    ->orWhere('email', 'like', "%$busca%")
                    ->orWhere('nome_usuario', 'like', "%$busca%");
            });
        }

        return view('admin.index', [
            'busca'    => $busca,
            'usuarios' => $usuarios->get(),
            'tipos'    => User::TIPOS,
            'numeros'  => [
                'Alunos'               => User::where('tipo', 'aluno')->count(),
                'Professores'          => User::where('tipo', 'professor')->count(),
                'Administradores'      => User::where('tipo', 'admin')->count(),
                'Cursos oficiais'      => Curso::whereNull('professor_id')->count(),
                'Cursos da comunidade' => Curso::whereNotNull('professor_id')->count(),
                'Lições concluídas'    => Progresso::where('aprovado', true)->count(),
            ],
            'cursosComunidade' => Curso::whereNotNull('professor_id')->with('professor')->orderBy('titulo')->get(),
        ]);
    }

    public function alterarTipo(Request $request, User $usuario)
    {
        $request->validate(['tipo' => 'required|in:aluno,professor,admin']);

        // Evita o admin tirar a própria permissão sem querer.
        if ($usuario->id === Auth::id()) {
            return back()->with('aviso', 'Você não pode mudar o seu próprio tipo.');
        }

        $usuario->update(['tipo' => $request->tipo]);

        return back()->with('aviso', "{$usuario->nome} agora é " . User::TIPOS[$request->tipo] . '.');
    }

    public function excluir(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return back()->with('aviso', 'Para excluir a sua própria conta, use a tela Configurações.');
        }

        if ($usuario->foto && file_exists(public_path('uploads/' . $usuario->foto))) {
            unlink(public_path('uploads/' . $usuario->foto));
        }

        // cascadeOnDelete: o banco apaga junto o progresso, as conquistas e os cursos criados por ele.
        $usuario->delete();

        return back()->with('aviso', "Usuário {$usuario->nome} excluído.");
    }
}
