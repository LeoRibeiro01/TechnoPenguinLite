{{--
  LAYOUT PRINCIPAL - a "moldura" de todas as telas (topo, menu, avisos e rodapé).

  Cada tela começa com @extends('layouts.app') e preenche dois espaços:
    @section('titulo')   -> o texto da aba do navegador   (aparece onde está @yield('titulo'))
    @section('conteudo') -> o miolo da página             (aparece onde está @yield('conteudo'))

  Blade em 1 minuto:
    {{ $x }}     mostra $x já escapado (protege contra XSS)
    {!! $x !!}   mostra $x SEM escapar (só para HTML confiável, como o conteúdo das lições)
    @if / @foreach / @auth ... @endif / @endforeach / @endauth
--}}
<!doctype html>
{{-- Acessibilidade: as escolhas do usuário viram classes aqui (ex.: "fonte-grande contraste").
     O CSS usa essas classes para aumentar o texto, trocar as cores etc. --}}
<html lang="pt-BR" class="{{ auth()->check() ? auth()->user()->classesAcessibilidade() : '' }}">
<head>
  <meta charset="utf-8">
  {{-- viewport: a página se adapta ao celular (RNF04) --}}
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('titulo') | TechnoPenguin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
  {{-- asset() gera o endereço de um arquivo da pasta public/ --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

{{-- Acessibilidade: quem navega pelo teclado (Tab) pode pular o menu e ir direto ao conteúdo. --}}
<a class="pular" href="#conteudo">Pular para o conteúdo</a>

<header class="topo">
  <a href="{{ auth()->check() ? route('painel') : route('inicio') }}">
    <img class="topo__logo" src="{{ asset('imgs/logo.svg') }}" alt="TechnoPenguin">
  </a>

  {{-- @auth: este pedaço só aparece para quem está logado --}}
  @auth
    <nav class="menu" aria-label="Menu principal">
      <a href="{{ route('painel') }}">Painel</a>
      <a href="{{ route('comunidade') }}">Comunidade</a>
      <a href="{{ route('conquistas') }}">Conquistas</a>
      <a href="{{ route('vestuario') }}">Vestuário</a>

      {{-- Links que dependem do tipo de usuário --}}
      @if (auth()->user()->ehProfessor())
        <a href="{{ route('professor.cursos.index') }}">Professor</a>
      @endif
      @if (auth()->user()->ehAdmin())
        <a href="{{ route('admin.index') }}">Admin</a>
      @endif

      <a href="{{ route('perfil.editar') }}" class="menu__perfil">
        @if (auth()->user()->foto)
          <img class="foto foto--mini" src="{{ asset('uploads/' . auth()->user()->foto) }}" alt="">
        @else
          @include('partials.avatar', ['u' => auth()->user(), 'classe' => 'avatar--rosto avatar--mini'])
        @endif
        Perfil
      </a>
      <a href="{{ route('configuracoes') }}">Configurações</a>
      {{-- Logout é POST (e não um link) porque muda o estado: sai da conta. --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="menu__sair" type="submit">Sair</button>
      </form>
    </nav>
  @endauth
</header>

<main class="conteudo" id="conteudo">
  {{-- Mensagem enviada pelo controller com ->with('aviso', '...'). Aparece uma vez só. --}}
  @if (session('aviso'))
    <p class="aviso">{{ session('aviso') }}</p>
  @endif

  @yield('conteudo')
</main>

<footer class="rodape">
  <small>© 2026 TechnoPenguin · Aprenda, pratique e cresça, um código de cada vez.</small>
</footer>

{{-- Pinta os pinguins com as cores de cada usuário (fica no fim para a página já estar montada). --}}
<script src="{{ asset('js/pinguim.js') }}"></script>
</body>
</html>
