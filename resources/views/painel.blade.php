{{-- PAINEL (RF16, RF18, RF19, RF20, RF22) - controller: PainelController@index --}}
@extends('layouts.app')

@section('titulo', 'Painel')

@section('conteudo')
<section class="boas-vindas">
  @include('partials.avatar', ['u' => $user, 'classe' => 'avatar--corpo avatar--perfil'])
  <div>
    <h1>Olá, {{ $user->nome }}!</h1>
    @if (!$user->linguagem_id || !$user->nivel)
      <p>Complete seu perfil: <a href="{{ route('perfil.editar') }}">escolha sua linguagem e seu nível</a>.</p>
    @else
      {{-- $user->linguagem usa o relacionamento belongsTo do Model User --}}
      <p class="suave">Estudando {{ $user->linguagem->nome }} · {{ \App\Models\User::NIVEIS[$user->nivel] }}</p>
    @endif
  </div>
</section>

{{-- Números do usuário --}}
<section class="numeros">
  <div class="numero">
    <strong>{{ $user->pontos }}</strong>
    <span>❄ pontos</span>
  </div>
  <div class="numero">
    <strong>{{ $user->sequenciaAtual() }}</strong>
    <span>🔥 dias seguidos</span>
  </div>
  <div class="numero">
    <strong>{{ $feitasHoje }}/{{ $user->meta_diaria }}</strong>
    <span>🎯 meta de hoje {{ $feitasHoje >= $user->meta_diaria ? '✔' : '' }}</span>
  </div>
  <a class="numero" href="{{ route('conquistas') }}">
    <strong>{{ $totalConquistas }}</strong>
    <span>🏅 conquistas</span>
  </a>
</section>

@if ($meuCurso)
  <h2>Sua linguagem</h2>
  <section class="grade">
    @include('partials.curso-card', ['curso' => $meuCurso])
  </section>
@endif

<h2>Linguagens</h2>
<section class="grade">
  {{-- where('tipo', '!=', 'assunto') filtra a lista que já veio do controller (não faz outra consulta) --}}
  @foreach ($cursos->where('tipo', '!=', 'assunto') as $curso)
    @include('partials.curso-card', ['curso' => $curso])
  @endforeach
</section>

<h2>Outros assuntos</h2>
<section class="grade">
  @foreach ($cursos->where('tipo', 'assunto') as $curso)
    @include('partials.curso-card', ['curso' => $curso])
  @endforeach
</section>
@endsection
