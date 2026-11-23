{{-- FOTO DE PERFIL (RF12, RF13) - controller: PerfilController@formFoto / @salvarFoto --}}
@extends('layouts.app')

@section('titulo', 'Foto de perfil')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>{{ $user->foto ? 'Trocar foto de perfil' : 'Adicionar foto de perfil' }}</h1>

  @if ($user->foto)
    <img class="foto foto--grande" src="{{ asset('uploads/' . $user->foto) }}" alt="Foto atual">
  @endif

  @include('partials.erros')

  {{-- enctype="multipart/form-data" é OBRIGATÓRIO para o formulário enviar arquivos --}}
  <form method="POST" action="{{ route('perfil.foto') }}" enctype="multipart/form-data" class="formulario">
    @csrf
    <label>Imagem (JPG, PNG, GIF ou WEBP, até 2 MB)
      <input type="file" name="foto" accept="image/*" required>
    </label>
    <button class="botao" type="submit">Salvar foto</button>
  </form>

  <p class="centro"><a href="{{ route('perfil.editar') }}">Voltar para o perfil</a></p>
</section>
@endsection
