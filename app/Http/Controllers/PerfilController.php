<?php

namespace App\Http\Controllers;

use App\Models\Linguagem;
use App\Models\User;
use App\Support\Pinguim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * CONTROLLER DO PERFIL (dados de estudo, avatar e foto)
 *   RF08 e RF09 -> criar/editar o avatar
 *   RF10 e RF11 -> escolher/editar a linguagem
 *   RF12 e RF13 -> adicionar/trocar a foto de perfil
 *   RF14 e RF15 -> informar/alterar o nível de conhecimento
 *   RF20 e RF21 -> definir/alterar a meta diária
 * (Nome, nome de usuário, e-mail, acessibilidade e excluir conta ficam no ConfiguracaoController.)
 *
 * É o exemplo de "Update" do CRUD: o formulário vem preenchido e o PUT salva as mudanças.
 */
class PerfilController extends Controller
{
    // GET /perfil -> formulário com os dados atuais
    public function editar()
    {
        return view('perfil.editar', [
            'user'       => Auth::user(),
            'linguagens' => Linguagem::orderBy('nome')->get(),   // para o <select>
            'niveis'     => User::NIVEIS,
        ]);
    }

    // PUT /perfil -> salva os dados
    public function atualizar(Request $request)
    {
        $user = Auth::user();

        $dados = $request->validate([
            'linguagem_id' => 'required|exists:linguagens,id',   // precisa existir na tabela linguagens
            'nivel'        => 'required|in:iniciante,intermediario,avancado',
            'meta_diaria'  => 'required|integer|between:1,20',
        ]);

        // update() preenche as colunas com o array validado e salva (UPDATE users SET ... WHERE id = ...)
        $user->update($dados);

        // Padrão PRG (Post/Redirect/Get): depois de salvar, redireciona.
        // Assim, apertar F5 não envia o formulário de novo.
        return redirect()->route('perfil.editar')->with('aviso', 'Perfil salvo!');
    }

    // GET /perfil/avatar -> tela "Meu pinguim" (cores)
    public function formAvatar()
    {
        return view('perfil.avatar', [
            'user'    => Auth::user(),
            'partes'  => Pinguim::PARTES,
            'paletas' => Pinguim::PALETAS,
            'padrao'  => Pinguim::CORES_PADRAO,
        ]);
    }

    // PUT /perfil/avatar -> salva as 4 cores (corpo, barriga, bico/patas e fundo)
    public function salvarAvatar(Request $request)
    {
        // Cada cor precisa estar no formato #rrggbb (ex.: #ff8800).
        $request->validate([
            'corpo'   => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'barriga' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'detalhe' => 'required|regex:/^#[0-9a-fA-F]{6}$/',
            'fundo'   => 'required|regex:/^#[0-9a-fA-F]{6}$/',
        ]);

        Auth::user()->update([
            'cor_corpo'   => strtolower($request->corpo),
            'cor_barriga' => strtolower($request->barriga),
            'cor_detalhe' => strtolower($request->detalhe),
            'cor_fundo'   => strtolower($request->fundo),
        ]);

        return redirect()->route('perfil.editar')->with('aviso', 'Seu pinguim foi atualizado!');
    }

    // GET /perfil/foto -> formulário de upload
    public function formFoto()
    {
        return view('perfil.foto', ['user' => Auth::user()]);
    }

    // POST /perfil/foto -> recebe a imagem e salva em public/uploads
    public function salvarFoto(Request $request)
    {
        $request->validate([
            // image + mimes: só imagens (ninguém consegue enviar um .php); max em KB (2048 = 2 MB)
            'foto' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $user = Auth::user();
        $arquivo = $request->file('foto');

        // Nome único: usuario_5_1732370000.png (id + horário atual)
        $nome = 'usuario_' . $user->id . '_' . time() . '.' . $arquivo->extension();
        $arquivo->move(public_path('uploads'), $nome);

        // RF13: apaga a foto antiga, se existir, para não acumular arquivos.
        if ($user->foto && file_exists(public_path('uploads/' . $user->foto))) {
            unlink(public_path('uploads/' . $user->foto));
        }

        $user->update(['foto' => $nome]);   // no banco vai só o NOME do arquivo

        return redirect()->route('perfil.editar')->with('aviso', 'Foto de perfil salva!');
    }
}
