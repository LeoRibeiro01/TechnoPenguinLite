{{-- ÁREA DO PROFESSOR: editar curso + módulos. Controllers: Professor\CursoController e Professor\ModuloController@store --}}
@extends('layouts.app')

@section('titulo', 'Editar curso')

@section('conteudo')
<p><a href="{{ route('professor.cursos.index') }}">← Meus cursos</a> · <a href="{{ route('cursos.show', $curso) }}">Ver como aluno</a></p>

@include('partials.erros')

{{-- ---------- Dados do curso (UPDATE) ---------- --}}
<section class="cartao">
  <h1>{{ $curso->titulo }}</h1>
  <form method="POST" action="{{ route('professor.cursos.update', $curso) }}" class="formulario">
    @csrf
    @method('PUT')
    <label>Título
      <input type="text" name="titulo" value="{{ old('titulo', $curso->titulo) }}" required maxlength="100">
    </label>
    <label>Descrição curta
      <input type="text" name="descricao" value="{{ old('descricao', $curso->descricao) }}" required maxlength="255">
    </label>
    <label>Sigla do ícone
      <input type="text" name="sigla" value="{{ old('sigla', $curso->sigla) }}" required maxlength="5">
    </label>
    <label>Cor do ícone
      <input type="color" name="cor" value="{{ old('cor', $curso->cor) }}">
    </label>
    <label class="opcao">
      <input type="checkbox" name="publicado" value="1" @checked($curso->publicado)>
      Publicado (aparece na aba Comunidade para os alunos)
    </label>
    <button class="botao" type="submit">Salvar curso</button>
  </form>
</section>

{{-- ---------- Módulos do curso ---------- --}}
<section class="cartao">
  <h2>Módulos</h2>
  @forelse ($modulos as $modulo)
    <div class="linha">
      <div class="linha__texto">
        <strong>{{ $modulo->ordem }}. {{ $modulo->titulo }}</strong>
        <small class="suave">{{ ucfirst($modulo->dificuldade) }} · {{ $modulo->licoes_count }} lições</small>
      </div>
      <a class="botao botao--claro" href="{{ route('professor.modulos.edit', $modulo) }}">Editar módulo e lições</a>
    </div>
  @empty
    <p class="suave">Nenhum módulo ainda.</p>
  @endforelse

  {{-- Novo módulo (CREATE) --}}
  <h3>Novo módulo</h3>
  <form method="POST" action="{{ route('professor.modulos.store', $curso) }}" class="formulario">
    @csrf
    <label>Título do módulo
      <input type="text" name="titulo" required maxlength="100">
    </label>
    <label>Descrição
      <input type="text" name="descricao" required maxlength="255">
    </label>
    <label>Dificuldade
      <select name="dificuldade">
        <option value="iniciante">Iniciante</option>
        <option value="intermediario">Intermediário</option>
        <option value="avancado">Avançado</option>
      </select>
    </label>
    <button class="botao" type="submit">Adicionar módulo</button>
  </form>
</section>

{{-- ---------- Excluir curso (DELETE) ---------- --}}
<section class="cartao zona-perigo">
  <h2>Excluir curso</h2>
  <p>Apaga o curso, os módulos, as lições, os exercícios e o progresso dos alunos neste curso.</p>
  <form method="POST" action="{{ route('professor.cursos.destroy', $curso) }}"
        onsubmit="return confirm('Excluir este curso e tudo dentro dele?')">
    @csrf
    @method('DELETE')
    <button class="botao botao--perigo" type="submit">Excluir curso</button>
  </form>
</section>
@endsection
