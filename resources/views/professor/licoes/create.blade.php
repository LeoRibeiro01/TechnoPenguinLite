{{-- ÁREA DO PROFESSOR: nova lição. Controller: Professor\LicaoController@create / @store --}}
@extends('layouts.app')

@section('titulo', 'Nova lição')

@section('conteudo')
<p><a href="{{ route('professor.modulos.edit', $modulo) }}">← {{ $modulo->titulo }}</a></p>

<section class="cartao">
  <h1>Nova lição</h1>

  @include('partials.erros')

  <form method="POST" action="{{ route('professor.licoes.store', $modulo) }}" class="formulario">
    @csrf
    <label>Título da lição
      <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="100">
    </label>
    <label>Teoria (texto simples; as quebras de linha são mantidas)
      <textarea name="teoria" rows="8" required>{{ old('teoria') }}</textarea>
    </label>
    <label>Prática: exemplo de código (opcional)
      <textarea name="pratica" rows="6" class="codigo">{{ old('pratica') }}</textarea>
    </label>
    <button class="botao" type="submit">Criar lição</button>
  </form>
</section>
@endsection
