{{-- ÁREA DO PROFESSOR: editar módulo + lista de lições. Controller: Professor\ModuloController@edit / @update / @destroy --}}
@extends('layouts.app')

@section('titulo', 'Editar módulo')

@section('conteudo')
<p><a href="{{ route('professor.cursos.edit', $modulo->curso) }}">← {{ $modulo->curso->titulo }}</a></p>

@include('partials.erros')

<section class="cartao">
  <h1>Módulo {{ $modulo->ordem }}: {{ $modulo->titulo }}</h1>
  <form method="POST" action="{{ route('professor.modulos.update', $modulo) }}" class="formulario">
    @csrf
    @method('PUT')
    <label>Título do módulo
      <input type="text" name="titulo" value="{{ old('titulo', $modulo->titulo) }}" required maxlength="100">
    </label>
    <label>Descrição
      <input type="text" name="descricao" value="{{ old('descricao', $modulo->descricao) }}" required maxlength="255">
    </label>
    <label>Dificuldade
      <select name="dificuldade">
        <option value="iniciante" @selected($modulo->dificuldade == 'iniciante')>Iniciante</option>
        <option value="intermediario" @selected($modulo->dificuldade == 'intermediario')>Intermediário</option>
        <option value="avancado" @selected($modulo->dificuldade == 'avancado')>Avançado</option>
      </select>
    </label>
    <button class="botao" type="submit">Salvar módulo</button>
  </form>
</section>

<section class="cartao">
  <h2>Lições</h2>
  @forelse ($licoes as $licao)
    <div class="linha">
      <div class="linha__texto">
        <strong>{{ $licao->ordem }}. {{ $licao->titulo }}</strong>
        <small class="suave">{{ $licao->exercicios_count }} exercícios</small>
      </div>
      <a class="botao botao--claro" href="{{ route('professor.licoes.edit', $licao) }}">Editar lição e exercícios</a>
    </div>
  @empty
    <p class="suave">Nenhuma lição ainda.</p>
  @endforelse

  <p><a class="botao" href="{{ route('professor.licoes.create', $modulo) }}">+ Nova lição</a></p>
</section>

<section class="cartao zona-perigo">
  <h2>Excluir módulo</h2>
  <form method="POST" action="{{ route('professor.modulos.destroy', $modulo) }}"
        onsubmit="return confirm('Excluir este módulo e todas as lições dele?')">
    @csrf
    @method('DELETE')
    <button class="botao botao--perigo" type="submit">Excluir módulo</button>
  </form>
</section>
@endsection
