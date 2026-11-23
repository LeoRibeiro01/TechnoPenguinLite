{{-- PAINEL DE ADMINISTRAÇÃO. Controller: AdminController --}}
@extends('layouts.app')

@section('titulo', 'Administração')

@section('conteudo')
<h1>Administração</h1>

@include('partials.erros')

{{-- ---------- Números gerais ---------- --}}
<section class="numeros">
  @foreach ($numeros as $rotulo => $valor)
    <div class="numero">
      <strong>{{ $valor }}</strong>
      <span>{{ $rotulo }}</span>
    </div>
  @endforeach
</section>

{{-- ---------- Usuários ---------- --}}
<section class="cartao">
  <h2>Usuários</h2>

  {{-- Busca: formulário GET, o texto vai no endereço (?busca=...) --}}
  <form method="GET" action="{{ route('admin.index') }}" class="busca">
    <label for="busca">Buscar por nome, e-mail ou nome de usuário</label>
    <input type="search" id="busca" name="busca" value="{{ $busca }}">
    <button class="botao" type="submit">Buscar</button>
    @if ($busca)
      <a href="{{ route('admin.index') }}">Limpar</a>
    @endif
  </form>

  {{-- div com overflow: no celular a tabela rola para o lado em vez de estourar a tela --}}
  <div class="tabela-rolagem">
    <table class="tabela">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Pontos</th>
          <th>Tipo</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($usuarios as $usuario)
          <tr>
            <td>{{ $usuario->nome }}<br><small class="suave">{{ '@' . $usuario->nome_usuario }}</small></td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->pontos }}</td>
            <td>
              @if ($usuario->id === auth()->id())
                {{ $tipos[$usuario->tipo] }} (você)
              @else
                {{-- Trocar o tipo do usuário --}}
                <form method="POST" action="{{ route('admin.usuarios.tipo', $usuario) }}" class="em-linha">
                  @csrf
                  @method('PUT')
                  <select name="tipo" aria-label="Tipo de {{ $usuario->nome }}">
                    @foreach ($tipos as $valor => $texto)
                      <option value="{{ $valor }}" @selected($usuario->tipo == $valor)>{{ $texto }}</option>
                    @endforeach
                  </select>
                  <button class="botao botao--claro" type="submit">Salvar</button>
                </form>
              @endif
            </td>
            <td>
              @if ($usuario->id !== auth()->id())
                <form method="POST" action="{{ route('admin.usuarios.excluir', $usuario) }}"
                      onsubmit="return confirm('Excluir este usuário? Esta ação não pode ser desfeita.')">
                  @csrf
                  @method('DELETE')
                  <button class="botao botao--perigo" type="submit">Excluir</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5">Nenhum usuário encontrado.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

{{-- ---------- Cursos da comunidade (moderação) ---------- --}}
<section class="cartao">
  <h2>Cursos da comunidade</h2>
  @forelse ($cursosComunidade as $curso)
    <div class="linha">
      <div class="linha__texto">
        <strong>{{ $curso->titulo }}</strong>
        <small class="suave">por {{ '@' . $curso->professor->nome_usuario }} · {{ $curso->publicado ? 'Publicado' : 'Rascunho' }}</small>
      </div>
      <div class="botoes">
        <a class="botao botao--claro" href="{{ route('cursos.show', $curso) }}">Ver</a>
        {{-- O admin usa as telas do professor para editar (User::podeEditar libera o admin) --}}
        <a class="botao botao--claro" href="{{ route('professor.cursos.edit', $curso) }}">Editar</a>
        <form method="POST" action="{{ route('professor.cursos.destroy', $curso) }}"
              onsubmit="return confirm('Excluir este curso e tudo dentro dele?')">
          @csrf
          @method('DELETE')
          <button class="botao botao--perigo" type="submit">Excluir</button>
        </form>
      </div>
    </div>
  @empty
    <p class="suave">Nenhum curso da comunidade.</p>
  @endforelse
</section>
@endsection
