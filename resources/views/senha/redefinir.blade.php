{{-- REDEFINIR SENHA (RF03, passo 2) - aberta pelo link do e-mail. Controller: SenhaController@formRedefinir / @redefinir --}}
@extends('layouts.app')

@section('titulo', 'Nova senha')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>Criar senha nova</h1>

  @include('partials.erros')

  <form method="POST" action="{{ route('senha.redefinir') }}" class="formulario">
    @csrf
    {{-- O token vem do link do e-mail; vai escondido para o controller conferir. --}}
    <input type="hidden" name="token" value="{{ $token }}">

    <label>E-mail
      <input type="email" name="email" value="{{ old('email', $email) }}" required>
    </label>
    <label>Senha nova
      <input type="password" name="password" minlength="6" required>
    </label>
    <label>Confirmar senha nova
      <input type="password" name="password_confirmation" minlength="6" required>
    </label>
    <button class="botao" type="submit">Salvar senha</button>
  </form>
</section>
@endsection
