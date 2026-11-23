{{-- ÁREA DO PROFESSOR: editar lição + exercícios.
     Controllers: Professor\LicaoController@edit/@update/@destroy e Professor\ExercicioController@store/@destroy --}}
@extends('layouts.app')

@section('titulo', 'Editar lição')

@section('conteudo')
<p><a href="{{ route('professor.modulos.edit', $licao->modulo) }}">← {{ $licao->modulo->titulo }}</a>
  · <a href="{{ route('licoes.show', $licao) }}">Ver como aluno</a></p>

@include('partials.erros')

{{-- ---------- Dados da lição ---------- --}}
<section class="cartao">
  <h1>{{ $licao->titulo }}</h1>
  <form method="POST" action="{{ route('professor.licoes.update', $licao) }}" class="formulario">
    @csrf
    @method('PUT')
    <label>Título da lição
      <input type="text" name="titulo" value="{{ old('titulo', $licao->titulo) }}" required maxlength="100">
    </label>
    <label>Teoria
      <textarea name="teoria" rows="8" required>{{ old('teoria', $licao->teoria) }}</textarea>
    </label>
    <label>Prática: exemplo de código (opcional)
      <textarea name="pratica" rows="6" class="codigo">{{ old('pratica', $licao->pratica) }}</textarea>
    </label>
    <button class="botao" type="submit">Salvar lição</button>
  </form>
</section>

{{-- ---------- Exercícios ---------- --}}
<section class="cartao">
  <h2>Exercícios</h2>

  @forelse ($licao->exercicios as $exercicio)
    <div class="exercicio">
      <p><strong>{{ $loop->iteration }}.</strong> {{ $exercicio->enunciado }}</p>
      <ul>
        @foreach ($exercicio->alternativas() as $letra => $texto)
          <li>{{ $letra }}) {{ $texto }} @if ($letra == $exercicio->resposta_correta) <strong class="certo">✔ correta</strong> @endif</li>
        @endforeach
      </ul>
      <small class="suave">Explicação: {{ $exercicio->explicacao }}</small>
      <form method="POST" action="{{ route('professor.exercicios.destroy', $exercicio) }}"
            onsubmit="return confirm('Excluir este exercício?')">
        @csrf
        @method('DELETE')
        <button class="botao botao--claro" type="submit">Excluir exercício</button>
      </form>
    </div>
  @empty
    <p class="suave">Nenhum exercício ainda. Para publicar o curso, toda lição precisa de pelo menos um.</p>
  @endforelse

  {{-- Novo exercício --}}
  <h3>Novo exercício</h3>
  <form method="POST" action="{{ route('professor.exercicios.store', $licao) }}" class="formulario">
    @csrf
    <label>Pergunta
      <textarea name="enunciado" rows="3" required>{{ old('enunciado') }}</textarea>
    </label>
    <label>Alternativa A
      <input type="text" name="alternativa_a" value="{{ old('alternativa_a') }}" required maxlength="255">
    </label>
    <label>Alternativa B
      <input type="text" name="alternativa_b" value="{{ old('alternativa_b') }}" required maxlength="255">
    </label>
    <label>Alternativa C (opcional)
      <input type="text" name="alternativa_c" value="{{ old('alternativa_c') }}" maxlength="255">
    </label>
    <label>Alternativa D (opcional)
      <input type="text" name="alternativa_d" value="{{ old('alternativa_d') }}" maxlength="255">
    </label>
    <label>Resposta correta
      <select name="resposta_correta">
        @foreach (['a', 'b', 'c', 'd'] as $letra)
          <option value="{{ $letra }}" @selected(old('resposta_correta') == $letra)>{{ strtoupper($letra) }}</option>
        @endforeach
      </select>
    </label>
    <label>Explicação (aparece quando o aluno erra)
      <textarea name="explicacao" rows="2" required>{{ old('explicacao') }}</textarea>
    </label>
    <button class="botao" type="submit">Adicionar exercício</button>
  </form>
</section>

<section class="cartao zona-perigo">
  <h2>Excluir lição</h2>
  <form method="POST" action="{{ route('professor.licoes.destroy', $licao) }}"
        onsubmit="return confirm('Excluir esta lição e os exercícios dela?')">
    @csrf
    @method('DELETE')
    <button class="botao botao--perigo" type="submit">Excluir lição</button>
  </form>
</section>
@endsection
