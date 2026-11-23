{{-- PERFIL (RF10/11, RF14/15, RF20/21 + links para avatar e foto) - controller: PerfilController@editar / @atualizar
     Nome, nome de usuário, e-mail e senha ficam em Configurações. --}}
@extends('layouts.app')

@section('titulo', 'Meu perfil')

@section('conteudo')
<section class="cartao perfil">
  <div class="perfil__imagens">
    @include('partials.avatar', ['u' => $user, 'classe' => 'avatar--corpo avatar--perfil'])
    @if ($user->foto)
      <img class="foto" src="{{ asset('uploads/' . $user->foto) }}" alt="Foto de perfil">
    @endif
  </div>

  <div>
    <h1>{{ $user->nome }}</h1>
    <p class="suave">{{ '@' . $user->nome_usuario }} · {{ \App\Models\User::TIPOS[$user->tipo] }}</p>
    <p class="botoes">
      <a class="botao botao--claro" href="{{ route('perfil.avatar') }}">Editar avatar</a>
      <a class="botao botao--claro" href="{{ route('perfil.foto') }}">{{ $user->foto ? 'Trocar foto' : 'Adicionar foto' }}</a>
      <a class="botao botao--claro" href="{{ route('configuracoes') }}">Configurações da conta</a>
    </p>
  </div>
</section>

<section class="cartao">
  <h2>Meus estudos</h2>

  @include('partials.erros')

  <form method="POST" action="{{ route('perfil.atualizar') }}" class="formulario">
    @csrf
    @method('PUT')

    {{-- old('campo', valor_atual): se a validação falhou, mostra o que foi digitado; senão, o que está no banco --}}
    <label>Linguagem que quero estudar
      <select name="linguagem_id" required>
        <option value="">Escolha...</option>
        @foreach ($linguagens as $linguagem)
          {{-- @selected(condição) escreve "selected" quando a condição é verdadeira --}}
          <option value="{{ $linguagem->id }}" @selected(old('linguagem_id', $user->linguagem_id) == $linguagem->id)>
            {{ $linguagem->nome }}
          </option>
        @endforeach
      </select>
    </label>

    <label>Nível de conhecimento em programação
      <select name="nivel" required>
        <option value="">Escolha...</option>
        @foreach ($niveis as $valor => $texto)
          <option value="{{ $valor }}" @selected(old('nivel', $user->nivel) == $valor)>{{ $texto }}</option>
        @endforeach
      </select>
    </label>

    <label>Meta diária (quantas lições por dia)
      <input type="number" name="meta_diaria" min="1" max="20" value="{{ old('meta_diaria', $user->meta_diaria) }}" required>
    </label>

    <button class="botao" type="submit">Salvar</button>
  </form>
</section>
@endsection
