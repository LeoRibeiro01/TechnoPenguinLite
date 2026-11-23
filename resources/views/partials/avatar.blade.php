{{--
  AVATAR DO PINGUIM (RF08 e RF09) - pedaço de tela reutilizável.
  Uso:  @include('partials.avatar', ['u' => $user, 'classe' => 'avatar--corpo avatar--palco'])

  Classes de tamanho/formato (CSS em public/css/app.css, seção AVATAR):
    avatar--corpo  -> pinguim inteiro (retângulo)      | avatar--rosto -> só a cabeça (círculo)
    avatar--palco  -> grande | avatar--perfil -> médio | avatar--mini   -> pequeno (menu)

  Como funciona: várias imagens do mesmo tamanho (520x1024) empilhadas uma em cima da outra:
    1. a foto do pinguim, que o public/js/pinguim.js pinta com as cores do data-pinguim;
    2. por cima, uma imagem para cada camada de roupa (roupa, pescoço, óculos, chapéu).
  O fundo colorido vem da variável CSS --fundo.
--}}
<div class="avatar {{ $classe ?? 'avatar--corpo avatar--perfil' }}" style="--fundo: {{ $u->cor_fundo }}">
  <img class="avatar__camada" src="{{ asset('imgs/pinguim/pinguim_original.png') }}"
       alt="Pinguim de {{ '@' . $u->nome_usuario }}"
       data-pinguim="{{ $u->cor_corpo }},{{ $u->cor_barriga }},{{ $u->cor_detalhe }}">

  @foreach (\App\Support\Pinguim::CAMADAS as $camada => $rotulo)
    {{-- $u->$camada = código da peça vestida nesta camada (ou null) --}}
    @php $item = \App\Support\Pinguim::item($u->$camada); @endphp
    @if ($item)
      <img class="avatar__camada" data-camada="{{ $camada }}" src="{{ asset($item['imagem']) }}" alt="">
    @else
      {{-- Camada vazia: a imagem fica escondida (o vestuário usa para mostrar a prévia ao passar o mouse) --}}
      <img class="avatar__camada" data-camada="{{ $camada }}" alt="" hidden>
    @endif
  @endforeach
</div>
