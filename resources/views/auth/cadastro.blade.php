{{-- TELA DE CADASTRO (RF01, RF06) - controller: AuthController@formCadastro / @cadastrar --}}
@extends('layouts.app')

@section('titulo', 'Criar conta')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>Criar conta</h1>

  @include('partials.erros')

  {{-- method POST + action route('cadastro') -> cai na rota Route::post('/cadastro', ...) do routes/web.php --}}
  <form method="POST" action="{{ route('cadastro') }}" class="formulario">
    {{-- @csrf: campo escondido com um código que prova que o formulário veio do nosso site.
         O Laravel recusa (erro 419) qualquer POST/PUT/DELETE sem ele. --}}
    @csrf

    {{-- Tipo de conta. (Administradores são criados por outro admin, no painel Admin.) --}}
    <fieldset class="opcoes">
      <legend>Quero me cadastrar como</legend>
      <label><input type="radio" name="tipo" value="aluno" @checked(old('tipo', 'aluno') == 'aluno')> Aluno</label>
      <label><input type="radio" name="tipo" value="professor" @checked(old('tipo') == 'professor')> Professor (também cria cursos)</label>
    </fieldset>

    <label>Nome
      {{-- old('nome'): devolve o que foi digitado, se a validação falhar e voltar para cá --}}
      <input type="text" name="nome" value="{{ old('nome') }}" required>
    </label>

    <label>Nome de usuário
      <input type="text" name="nome_usuario" value="{{ old('nome_usuario') }}" placeholder="ex.: pinguim_dev" required>
    </label>

    <label>E-mail
      <input type="email" name="email" value="{{ old('email') }}" required>
    </label>

    <label>Senha
      <input type="password" name="password" minlength="6" required>
    </label>

    {{-- O nome "password_confirmation" é o que a regra "confirmed" procura --}}
    <label>Confirmar senha
      <input type="password" name="password_confirmation" minlength="6" required>
    </label>

    <button class="botao" type="submit">Criar conta</button>
  </form>

  <p class="centro">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
</section>
@endsection
