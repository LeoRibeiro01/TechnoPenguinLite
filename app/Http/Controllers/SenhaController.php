<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

/*
 * CONTROLLER DE SENHA
 *   RF03 -> recuperar a senha por e-mail (esqueci a senha + redefinir).
 *           Quem esqueceu o e-mail pode usar o nome de usuário: o link vai para o e-mail da conta.
 *   RF04 -> alterar a senha estando logado
 *   RF05 -> depois de alterar, pedir login com a senha nova
 *
 * A recuperação usa a classe Password, que já vem no Laravel:
 *   1. Password::sendResetLink() cria um código (token), salva na tabela password_reset_tokens
 *      e chama User::sendPasswordResetNotification(), que manda o e-mail com o link;
 *   2. o link abre /redefinir-senha/{token};
 *   3. Password::reset() confere o token e, se estiver certo, troca a senha.
 */
class SenhaController extends Controller
{
    // GET /esqueci-senha
    public function formEsqueci()
    {
        return view('senha.esqueci');
    }

    /*
     * POST /esqueci-senha -> envia o e-mail com o link
     *
     * Aceita o E-MAIL ou o NOME DE USUÁRIO. Assim, quem esqueceu também o e-mail digita o
     * nome de usuário, e a tela mostra para qual e-mail o link foi (escondido: a*****a@gmail.com).
     */
    public function enviarLink(Request $request)
    {
        $request->validate(['login' => 'required']);

        // Procura pelo e-mail OU pelo nome de usuário.
        $user = User::where('email', $request->login)
            ->orWhere('nome_usuario', strtolower($request->login))
            ->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Não encontramos nenhuma conta com esse e-mail ou nome de usuário.'])->onlyInput('login');
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        // __() traduz o status usando lang/pt_BR/passwords.php
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('aviso', 'Enviamos um link para criar uma senha nova para ' . $user->emailMascarado() . '.');
        }
        return back()->withErrors(['login' => __($status)])->onlyInput('login');
    }

    // GET /redefinir-senha/{token} -> formulário da senha nova (aberto pelo link do e-mail)
    public function formRedefinir(Request $request, $token)
    {
        return view('senha.redefinir', [
            'token' => $token,
            'email' => $request->query('email'),   // vem no link: ...?email=fulano@...
        ]);
    }

    // POST /redefinir-senha -> confere o token e salva a senha nova
    public function redefinir(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // A função (closure) só roda se o token for válido para esse e-mail.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $senha) {
                $user->password = Hash::make($senha);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('aviso', __($status));
        }
        return back()->withErrors(['email' => __($status)]);
    }

    // GET /senha -> formulário de alterar senha (logado)
    public function formAlterar()
    {
        return view('senha.alterar');
    }

    // PUT /senha -> salva a senha nova e manda fazer login de novo
    public function alterar(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required|current_password',   // confere com a senha do usuário logado
            'password'    => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        // RF05: sai da conta para entrar de novo com a senha nova.
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('aviso', 'Senha alterada! Entre de novo usando a senha nova.');
    }
}
