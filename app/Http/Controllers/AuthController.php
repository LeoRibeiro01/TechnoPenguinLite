<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/*
 * CONTROLLER DE AUTENTICAÇÃO: cadastro, login e logout.
 * Requisitos: RF01 (criar conta), RF02 (login), RF06 (nome de usuário único), RNF02 (senha com hash)
 *
 * Usa o sistema de login que já vem no Laravel (classe Auth), sem pacote extra.
 * Auth guarda o id do usuário na sessão; depois, em qualquer lugar, Auth::user() devolve o usuário logado.
 */
class AuthController extends Controller
{
    // GET /cadastro -> mostra o formulário
    public function formCadastro()
    {
        return view('auth.cadastro');
    }

    // POST /cadastro -> valida, cria a conta e já entra nela
    public function cadastrar(Request $request)
    {
        // validate(): se alguma regra falhar, o Laravel VOLTA para o formulário sozinho,
        // com os erros em $errors e o que foi digitado em old('campo').
        $dados = $request->validate([
            // Quem se cadastra escolhe aluno ou professor. Admin só é criado por outro admin (ou pelo seeder).
            'tipo'         => 'required|in:aluno,professor',
            'nome'         => 'required|max:80',
            // regex: de 3 a 30 caracteres, só letras minúsculas, números, _ e ponto.
            // unique:users: não pode existir outro igual na tabela users (RF06)
            'nome_usuario' => 'required|regex:/^[a-z0-9_.]{3,30}$/|unique:users',
            'email'        => 'required|email|max:120|unique:users',
            // confirmed: precisa ter um campo "password_confirmation" igual
            'password'     => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'tipo'         => $dados['tipo'],
            'nome'         => $dados['nome'],
            'nome_usuario' => $dados['nome_usuario'],
            'email'        => $dados['email'],
            'password'     => Hash::make($dados['password']),   // RNF02: salva o hash, nunca a senha
        ]);

        Auth::login($user);   // já deixa logado

        // with('aviso', ...) guarda uma mensagem que aparece uma vez na próxima página.
        return redirect()->route('perfil.editar')
            ->with('aviso', 'Conta criada! Agora escolha sua linguagem, seu nível e sua meta diária.');
    }

    // GET /login -> mostra o formulário
    public function formLogin()
    {
        return view('auth.login');
    }

    // POST /login -> confere e-mail e senha
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Auth::attempt busca o usuário pelo e-mail e compara a senha com o hash do banco.
        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();   // troca o id da sessão (segurança)
            return redirect()->route('painel');
        }

        // Falhou: volta com uma mensagem de erro e mantém o e-mail digitado.
        return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->onlyInput('email');
    }

    // POST /logout -> sai da conta
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();       // apaga os dados da sessão
        $request->session()->regenerateToken();  // gera um novo token CSRF

        return redirect()->route('login');
    }
}
