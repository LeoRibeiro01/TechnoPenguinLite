{{-- CONQUISTAS E MEDALHAS (RF17) - controller: ConquistaController@index --}}
@extends('layouts.app')

@section('titulo', 'Conquistas')

@section('conteudo')
<h1>Conquistas e medalhas</h1>
<p class="suave">Você tem {{ $minhas->count() }} de {{ $conquistas->count() }}.</p>

<section class="grade">
  @foreach ($conquistas as $conquista)
    {{-- $minhas foi indexada pelo id no controller: isset() diz se o usuário tem esta conquista --}}
    @if (isset($minhas[$conquista->id]))
      <div class="cartao conquista">
        <span class="conquista__icone">{{ $conquista->icone }}</span>
        <h3>{{ $conquista->nome }}</h3>
        <p class="suave">{{ $conquista->descricao }}</p>
        {{-- pivot = dados da tabela conquista_user; created_at = quando ganhou --}}
        <small>Ganha em {{ $minhas[$conquista->id]->pivot->created_at->format('d/m/Y') }}</small>
      </div>
    @else
      <div class="cartao conquista conquista--bloqueada">
        <span class="conquista__icone">🔒</span>
        <h3>{{ $conquista->nome }}</h3>
        <p class="suave">{{ $conquista->descricao }}</p>
      </div>
    @endif
  @endforeach
</section>
@endsection
