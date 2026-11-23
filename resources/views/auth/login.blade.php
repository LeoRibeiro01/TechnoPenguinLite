{{-- TELA DE LOGIN (RF02) - controller: AuthController@formLogin / @login --}}
@extends('layouts.app')

@section('titulo', 'Entrar')

@section('conteudo')
<section class="cartao cartao--estreito">
  <img class="mascote" src="{{ asset('imgs/teco-waving.png') }}" alt="Teco, o pinguim, acenando">
  <h1>Entrar na sua conta</h1>

  @include('partials.erros')

  <form method="POST" action="{{ route('login') }}" class="formulario">
    @csrf

    <label>E-mail
      <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    </label>

    <label>Senha
      <input type="password" name="password" required>
    </label>

    <button class="botao" type="submit">Entrar</button>
  </form>

  <p class="centro"><a href="{{ route('senha.esqueci') }}">Esqueci minha senha ou meu e-mail</a></p>
  <p class="centro">Ainda não tem conta? <a href="{{ route('cadastro') }}">Criar conta</a></p>
</section>
@endsection
