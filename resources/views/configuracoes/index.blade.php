{{-- CONFIGURAÇÕES: conta, acessibilidade, senha e excluir conta. Controller: ConfiguracaoController --}}
@extends('layouts.app')

@section('titulo', 'Configurações')

@section('conteudo')
<h1>Configurações</h1>

@include('partials.erros')

{{-- ---------- Conta (RF06/RF07) ---------- --}}
<section class="cartao">
  <h2>Minha conta</h2>
  <form method="POST" action="{{ route('configuracoes.conta') }}" class="formulario">
    @csrf
    @method('PUT')
    <label>Nome
      <input type="text" name="nome" value="{{ old('nome', $user->nome) }}" required>
    </label>
    <label>Nome de usuário
      <input type="text" name="nome_usuario" value="{{ old('nome_usuario', $user->nome_usuario) }}" required>
    </label>
    <label>E-mail
      <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </label>
    <button class="botao" type="submit">Salvar conta</button>
  </form>
</section>

{{-- ---------- Acessibilidade ---------- --}}
<section class="cartao">
  <h2>Acessibilidade</h2>
  <form method="POST" action="{{ route('configuracoes.acessibilidade') }}" class="formulario">
    @csrf
    @method('PUT')

    <label>Tamanho do texto
      <select name="tamanho_fonte">
        @foreach ($tamanhos as $valor => $texto)
          <option value="{{ $valor }}" @selected($user->tamanho_fonte == $valor)>{{ $texto }}</option>
        @endforeach
      </select>
    </label>

    {{-- Checkbox: só é enviado quando está marcado (o controller usa $request->boolean()) --}}
    <label class="opcao">
      <input type="checkbox" name="alto_contraste" value="1" @checked($user->alto_contraste)>
      Alto contraste (fundo preto, texto branco e destaques em amarelo)
    </label>

    <label class="opcao">
      <input type="checkbox" name="destacar_links" value="1" @checked($user->destacar_links)>
      Destacar links (sublinhados e em negrito)
    </label>

    <button class="botao" type="submit">Salvar acessibilidade</button>
  </form>
</section>

{{-- ---------- Senha (RF04/RF05) ---------- --}}
<section class="cartao">
  <h2>Senha</h2>
  <p><a class="botao botao--claro" href="{{ route('senha.alterar') }}">Alterar senha</a></p>
</section>

{{-- ---------- Excluir conta ---------- --}}
<section class="cartao zona-perigo">
  <h2>Excluir conta</h2>
  <p>Isso apaga sua conta, seu progresso e suas conquistas para sempre.
    @if ($user->ehProfessor()) Os cursos que você criou também serão apagados. @endif
  </p>

  {{-- onsubmit + confirm(): o navegador pergunta "tem certeza?" antes de enviar --}}
  <form method="POST" action="{{ route('configuracoes.excluir') }}" class="formulario"
        onsubmit="return confirm('Tem certeza? Esta ação não pode ser desfeita.')">
    @csrf
    @method('DELETE')
    <label>Digite sua senha para confirmar
      <input type="password" name="senha_atual" required>
    </label>
    <button class="botao botao--perigo" type="submit">Excluir minha conta</button>
  </form>
</section>
@endsection
