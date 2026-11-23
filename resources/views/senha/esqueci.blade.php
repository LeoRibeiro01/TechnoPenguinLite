{{-- ESQUECI MINHA SENHA OU MEU E-MAIL (RF03, passo 1) - controller: SenhaController@formEsqueci / @enviarLink --}}
@extends('layouts.app')

@section('titulo', 'Recuperar acesso')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>Recuperar acesso</h1>
  <p class="centro">Digite seu e-mail <strong>ou</strong> seu nome de usuário. Enviaremos um link para o e-mail da conta para você criar uma senha nova.</p>

  @include('partials.erros')

  <form method="POST" action="{{ route('senha.esqueci') }}" class="formulario">
    @csrf
    <label>E-mail ou nome de usuário
      <input type="text" name="login" value="{{ old('login') }}" required>
    </label>
    <button class="botao" type="submit">Enviar link</button>
  </form>

  <p class="centro"><a href="{{ route('login') }}">Voltar para o login</a></p>
</section>
@endsection
