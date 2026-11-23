{{-- ABA COMUNIDADE - cursos criados por professores e publicados. Controller: PainelController@comunidade --}}
@extends('layouts.app')

@section('titulo', 'Comunidade')

@section('conteudo')
<h1>Comunidade</h1>
<p class="suave">Cursos criados pelos professores da plataforma.</p>

@if ($user->ehProfessor())
  <p><a class="botao" href="{{ route('professor.cursos.create') }}">+ Criar curso</a></p>
@endif

<section class="grade">
  {{-- @forelse = @foreach com um @empty para quando a lista estiver vazia --}}
  @forelse ($cursos as $curso)
    @include('partials.curso-card', ['curso' => $curso])
  @empty
    <p class="suave">Ainda não há cursos publicados pela comunidade.</p>
  @endforelse
</section>
@endsection
