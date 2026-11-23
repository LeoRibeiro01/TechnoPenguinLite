{{-- ÁREA DO PROFESSOR: novo curso. Controller: Professor\CursoController@create / @store --}}
@extends('layouts.app')

@section('titulo', 'Novo curso')

@section('conteudo')
<p><a href="{{ route('professor.cursos.index') }}">← Meus cursos</a></p>

<section class="cartao cartao--estreito">
  <h1>Novo curso</h1>

  @include('partials.erros')

  <form method="POST" action="{{ route('professor.cursos.store') }}" class="formulario">
    @csrf
    <label>Título
      <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="100">
    </label>
    <label>Descrição curta
      <input type="text" name="descricao" value="{{ old('descricao') }}" required maxlength="255">
    </label>
    <label>Sigla do ícone (até 5 letras, ex.: Lua)
      <input type="text" name="sigla" value="{{ old('sigla') }}" required maxlength="5">
    </label>
    <label>Cor do ícone
      <input type="color" name="cor" value="{{ old('cor', '#2897e6') }}">
    </label>
    <button class="botao" type="submit">Criar curso</button>
  </form>
  <p class="suave">O curso começa como rascunho. Você publica depois de criar as lições e os exercícios.</p>
</section>
@endsection
