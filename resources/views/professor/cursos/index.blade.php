{{-- ÁREA DO PROFESSOR: lista dos meus cursos. Controller: Professor\CursoController@index --}}
@extends('layouts.app')

@section('titulo', 'Área do professor')

@section('conteudo')
<h1>Área do professor</h1>
<p class="suave">Crie cursos para a aba Comunidade: curso → módulos → lições → exercícios.</p>
<p><a class="botao" href="{{ route('professor.cursos.create') }}">+ Novo curso</a></p>

@forelse ($cursos as $curso)
  <section class="cartao linha">
    <span class="icone" style="background: {{ $curso->cor }}">{{ $curso->sigla }}</span>
    <div class="linha__texto">
      <h2>{{ $curso->titulo }}</h2>
      <small class="suave">{{ $curso->licoes_count }} lições ·
        {{ $curso->publicado ? 'Publicado' : 'Rascunho' }}</small>
    </div>
    <div class="botoes">
      <a class="botao botao--claro" href="{{ route('cursos.show', $curso) }}">Ver como aluno</a>
      <a class="botao" href="{{ route('professor.cursos.edit', $curso) }}">Editar</a>
    </div>
  </section>
@empty
  <p class="suave">Você ainda não criou nenhum curso.</p>
@endforelse
@endsection
