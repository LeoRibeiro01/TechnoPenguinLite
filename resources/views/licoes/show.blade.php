{{--
  LIÇÃO (RF24 a RF29) - controller: LicaoController@show (abrir) e @responder (corrigir)

  A mesma tela serve para as duas situações:
    $resultado == null -> o usuário ainda não respondeu: mostra o formulário
    $resultado != null -> já respondeu: mostra a correção de cada exercício
--}}
@extends('layouts.app')

@section('titulo', $licao->titulo)

@section('conteudo')
<p><a href="{{ route('cursos.show', $licao->modulo->curso_id) }}">← {{ $licao->modulo->curso->titulo }}</a></p>

<section class="cartao">
  <p class="suave">Módulo {{ $licao->modulo->ordem }}: {{ $licao->modulo->titulo }}</p>
  <h1>{{ $licao->titulo }}</h1>

  {{-- RF24: conteúdo da lição. teoriaHtml()/praticaHtml() (Model Licao) devolvem HTML seguro:
       nos cursos oficiais é o HTML da equipe; nos da comunidade o texto do professor já vem escapado. --}}
  <h2>Teoria</h2>
  <div class="texto">{!! $licao->teoriaHtml() !!}</div>

  @if ($licao->pratica)
    <h2>Prática</h2>
    <div class="texto">{!! $licao->praticaHtml() !!}</div>
  @endif
</section>

{{-- ---------- Resultado (só aparece depois de responder) ---------- --}}
@if ($resultado)
  <section class="cartao resultado {{ $resultado['aprovado'] ? 'resultado--ok' : 'resultado--erro' }}">
    <h2>Você acertou {{ $resultado['acertos'] }} de {{ $resultado['total'] }} ({{ $resultado['porcentagem'] }}%)</h2>

    @if ($resultado['aprovado'])
      <p>Muito bem! Lição concluída. 🎉</p>
      @if ($resultado['pontos'] > 0)
        <p>Você ganhou <strong>{{ $resultado['pontos'] }} pontos</strong> ❄</p>
      @endif
      @foreach ($resultado['conquistas'] as $conquista)
        <p>Nova conquista: {{ $conquista->icone }} <strong>{{ $conquista->nome }}</strong></p>
      @endforeach

      {{-- RF29: só aprovado vê o botão da próxima lição --}}
      @if ($proxima)
        <a class="botao" href="{{ route('licoes.show', $proxima) }}">Próxima lição →</a>
      @else
        <p>Você terminou o curso! 🏆</p>
      @endif
    @else
      <p>Você precisa acertar pelo menos {{ \App\Models\User::NOTA_MINIMA }}% para liberar a próxima lição. Veja as correções e tente de novo.</p>
    @endif
  </section>
@endif

{{-- ---------- Exercícios (RF25) ---------- --}}
<section class="cartao">
  <h2>Exercícios</h2>

  <form method="POST" action="{{ route('licoes.responder', $licao) }}">
    @csrf

    @foreach ($licao->exercicios as $numero => $exercicio)
      @php
        // Correção deste exercício (ou null se ainda não respondeu).
        $correcao = $resultado ? $resultado['correcao'][$exercicio->id] : null;
      @endphp

      <fieldset class="exercicio">
        <legend>Exercício {{ $numero + 1 }}</legend>
        <div class="texto">{!! $exercicio->enunciadoHtml() !!}</div>

        @foreach ($exercicio->alternativas() as $letra => $texto)
          <label class="alternativa">
            {{-- name="respostas[ID]" faz o PHP receber uma lista: respostas[15] = 'b', respostas[16] = 'a'... --}}
            <input type="radio" name="respostas[{{ $exercicio->id }}]" value="{{ $letra }}"
                   @checked($correcao && $correcao['resposta'] === $letra) required>
            {{ $letra }}) {{ $texto }}
          </label>
        @endforeach

        {{-- RF27: diz se acertou. RF28: se errou, mostra a resposta certa e a explicação. --}}
        @if ($correcao)
          @if ($correcao['acertou'])
            <p class="certo">✔ Correto!</p>
          @else
            <p class="errado">
              ✘ Resposta errada. A certa é a letra <strong>{{ $exercicio->resposta_correta }}</strong>.<br>
              {{ $exercicio->explicacao }}
            </p>
          @endif
        @endif
      </fieldset>
    @endforeach

    <button class="botao" type="submit">{{ $resultado ? 'Responder de novo' : 'Enviar respostas' }}</button>
  </form>

  <p class="navegacao">
    @if ($anterior)
      <a href="{{ route('licoes.show', $anterior) }}">← Lição anterior</a>
    @endif
  </p>
</section>
@endsection
