{{-- ALTERAR SENHA (RF04, RF05) - controller: SenhaController@formAlterar / @alterar --}}
@extends('layouts.app')

@section('titulo', 'Alterar senha')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>Alterar senha</h1>
  <p class="centro suave">Depois de salvar, você vai precisar entrar de novo com a senha nova.</p>

  @include('partials.erros')

  <form method="POST" action="{{ route('senha.alterar') }}" class="formulario">
    @csrf
    {{-- Formulário HTML só sabe GET e POST. @method('PUT') avisa o Laravel que é uma atualização. --}}
    @method('PUT')

    <label>Senha atual
      <input type="password" name="senha_atual" required>
    </label>
    <label>Senha nova
      <input type="password" name="password" minlength="6" required>
    </label>
    <label>Confirmar senha nova
      <input type="password" name="password_confirmation" minlength="6" required>
    </label>
    <button class="botao" type="submit">Salvar senha</button>
  </form>

  <p class="centro"><a href="{{ route('perfil.editar') }}">Voltar para o perfil</a></p>
</section>
@endsection
