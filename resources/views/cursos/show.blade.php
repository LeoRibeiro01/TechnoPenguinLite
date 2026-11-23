{{-- CURSO (RF22, RF23, RF24, RF16) - controller: CursoController@show --}}
@extends('layouts.app')

@section('titulo', $curso->titulo)

@section('conteudo')
<p>
  @if ($curso->ehDaComunidade())
    <a href="{{ route('comunidade') }}">← Voltar à comunidade</a>
  @else
    <a href="{{ route('painel') }}">← Voltar ao painel</a>
  @endif
</p>

{{-- Aviso para o professor/admin que está vendo um rascunho --}}
@if (!$curso->publicado)
  <p class="aviso">Rascunho: só você (e os administradores) estão vendo este curso.</p>
@endif

<section class="cartao">
  <span class="icone" style="background: {{ $curso->cor }}">{{ $curso->sigla }}</span>
  <h1>{{ $curso->titulo }}</h1>
  <p class="suave">{{ $curso->descricao }}</p>
  @if ($curso->ehDaComunidade())
    <p class="suave">Criado por {{ '@' . $curso->professor->nome_usuario }}</p>
  @endif

  {{-- RF16: progresso no curso --}}
  <div class="barra"><div style="width: {{ $total > 0 ? round($feitas / $total * 100) : 0 }}%"></div></div>
  <small>{{ $feitas }} de {{ $total }} lições concluídas</small>

  {{-- <details> cria uma parte que abre e fecha ao clicar (sem JavaScript) --}}
  @if ($curso->historia)
    <details>
      <summary>Sobre o curso</summary>
      {{-- {!! !!} mostra o HTML sem escapar. Só usamos com o conteúdo das lições, que foi escrito pela equipe. --}}
      <h3>Como surgiu</h3>
      {!! $curso->historia !!}
      <h3>Como é usado hoje</h3>
      {!! $curso->hoje !!}
      <h3>Referências</h3>
      {!! $curso->referencias !!}
    </details>
  @endif
</section>

{{-- RF23: módulos --}}
@foreach ($modulos as $modulo)
  <section class="cartao">
    <h2>Módulo {{ $modulo->ordem }}: {{ $modulo->titulo }}</h2>
    <span class="etiqueta">{{ ucfirst($modulo->dificuldade) }}</span>
    <p class="suave">{{ $modulo->descricao }}</p>

    {{-- RF24: lições do módulo, com o estado de cada uma --}}
    <ol class="licoes">
      {{-- "?? []": módulo ainda sem lições (curso da comunidade em construção) --}}
      @foreach ($licoesPorModulo[$modulo->id] ?? [] as $licao)
        <li>
          @if ($licao->aprovada)
            ✅ <a href="{{ route('licoes.show', $licao) }}">{{ $licao->titulo }}</a> <small class="suave">(concluída, pode refazer)</small>
          @elseif ($licao->liberada)
            ▶️ <a href="{{ route('licoes.show', $licao) }}"><strong>{{ $licao->titulo }}</strong></a> <small class="suave">(próxima)</small>
          @else
            🔒 <span class="suave">{{ $licao->titulo }}</span>
          @endif
        </li>
      @endforeach
    </ol>

    {{-- Só os cursos oficiais têm projeto/aprofundamento/referências por módulo --}}
    @if ($modulo->projeto)
    <details>
      <summary>Projeto, aprofundamento e referências do módulo</summary>
      <h3>Projeto sugerido</h3>
      {!! $modulo->projeto !!}
      <h3>Para aprofundar</h3>
      {!! $modulo->aprofundamento !!}
      <h3>Referências</h3>
      {!! $modulo->referencias !!}
    </details>
    @endif
  </section>
@endforeach
@endsection
