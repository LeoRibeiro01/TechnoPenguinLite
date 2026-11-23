<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DE CONFIGURAÇÕES
 *   - Conta: editar nome, nome de usuário (RF06/RF07) e e-mail
 *   - Acessibilidade: tamanho da fonte, alto contraste e destacar links
 *   - Excluir conta
 * (A troca de senha fica no SenhaController, com link nesta tela.)
 */
class ConfiguracaoController extends Controller
{
    // GET /configuracoes
    public function index()
    {
        return view('configuracoes.index', [
            'user'    => Auth::user(),
            'tamanhos' => User::TAMANHOS_FONTE,
        ]);
    }

    // PUT /configuracoes/conta -> salva nome, nome de usuário e e-mail
    public function atualizarConta(Request $request)
    {
        $user = Auth::user();

        $dados = $request->validate([
            'nome'         => 'required|max:80',
            // unique:users,nome_usuario,ID -> não pode repetir, IGNORANDO o próprio usuário (RF07)
            'nome_usuario' => 'required|regex:/^[a-z0-9_.]{3,30}$/|unique:users,nome_usuario,' . $user->id,
            'email'        => 'required|email|max:120|unique:users,email,' . $user->id,
        ]);

        $user->update($dados);

        return redirect()->route('configuracoes')->with('aviso', 'Dados da conta salvos!');
    }

    // PUT /configuracoes/acessibilidade
    public function atualizarAcessibilidade(Request $request)
    {
        $request->validate([
            'tamanho_fonte' => 'required|in:normal,grande,muito_grande',
        ]);

        Auth::user()->update([
            'tamanho_fonte'  => $request->tamanho_fonte,
            // Checkbox desmarcado não é enviado pelo navegador. boolean() devolve true se veio marcado, false se não veio.
            'alto_contraste' => $request->boolean('alto_contraste'),
            'destacar_links' => $request->boolean('destacar_links'),
        ]);

        return redirect()->route('configuracoes')->with('aviso', 'Preferências de acessibilidade salvas!');
    }

    // DELETE /configuracoes/conta -> exclui a própria conta (pede a senha para confirmar)
    public function excluirConta(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required|current_password',
        ]);

        $user = Auth::user();

        // Apaga a foto do disco, se tiver.
        if ($user->foto && file_exists(public_path('uploads/' . $user->foto))) {
            unlink(public_path('uploads/' . $user->foto));
        }

        Auth::logout();
        // Graças ao cascadeOnDelete nas migrations, o banco apaga junto o progresso, as conquistas
        // e (se for professor) os cursos criados por ele.
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
