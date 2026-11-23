{{-- VESTUÁRIO: roupas do pinguim (RF08, RF09) - controller: VestuarioController --}}
@extends('layouts.app')

@section('titulo', 'Vestuário')

@section('conteudo')
<div class="vestuario">
  <section class="vestuario__perfil">
    <h1>Vestuário</h1>
    @include('partials.avatar', ['u' => $user, 'classe' => 'avatar--corpo avatar--palco'])
    <p class="vestuario__saldo">❄ <strong>{{ $saldo }}</strong> pontos para gastar</p>
    <p class="suave">Ganhe pontos acertando exercícios. Comprar peças não diminui sua pontuação acumulada nem suas medalhas.</p>
    <a class="botao botao--claro" href="{{ route('perfil.avatar') }}">Mudar cores e fundo</a>
  </section>

  <div>
    {{-- Uma seção por camada, de cima para baixo: cabeça, óculos, pescoço, corpo --}}
    @foreach (array_reverse($camadas) as $camada => $rotulo)
      <section class="cartao" id="{{ $camada }}">
        <h2>{{ $rotulo }}</h2>
        <div class="grade-itens">

          {{-- Opção "Nenhum": tira a peça desta camada --}}
          <form method="POST" action="{{ route('vestuario.vestir') }}">
            @csrf
            <input type="hidden" name="camada" value="{{ $camada }}">
            <input type="hidden" name="item" value="">
            <button class="item {{ $user->$camada === null ? 'item--vestido' : '' }}" type="submit">
              <span class="item__imagem item__imagem--vazio" aria-hidden="true">∅</span>
              <span class="item__nome">Nenhum</span>
            </button>
          </form>

          @foreach (\App\Support\Pinguim::itensDaCamada($camada) as $item)
            @php
              $tem = $user->temItem($item['codigo']);          // grátis ou já comprada
              $vestido = $user->$camada === $item['codigo'];
              $semPontos = !$tem && $saldo < $item['preco'];
            @endphp
            {{-- Se já tem, o formulário VESTE; se não tem, COMPRA (e já veste) --}}
            <form method="POST" action="{{ $tem ? route('vestuario.vestir') : route('vestuario.comprar') }}">
              @csrf
              <input type="hidden" name="camada" value="{{ $camada }}">
              <input type="hidden" name="item" value="{{ $item['codigo'] }}">
              <button class="item {{ $vestido ? 'item--vestido' : '' }} {{ $tem ? '' : 'item--loja' }}" type="submit"
                      @disabled($semPontos)
                      data-camada="{{ $camada }}" data-imagem="{{ asset($item['imagem']) }}">
                <span class="item__imagem item__imagem--{{ $camada }}">
                  <img src="{{ asset($item['imagem']) }}" alt="" loading="lazy">
                </span>
                <span class="item__nome">{{ $item['nome'] }}</span>
                <span class="item__preco">
                  @if ($vestido)
                    Vestindo
                  @elseif ($tem)
                    {{ $item['preco'] === 0 ? 'Grátis' : 'Seu' }}
                  @else
                    ❄ {{ $item['preco'] }}
                  @endif
                </span>
              </button>
            </form>
          @endforeach
        </div>
      </section>
    @endforeach
  </div>
</div>

<script>
  /*
   * Prévia: passar o mouse (ou o foco do teclado) numa peça mostra como ela fica no pinguim,
   * antes de vestir ou comprar. Ao sair, volta a imagem que estava.
   */
  const camadas = {};
  document.querySelectorAll('.avatar--palco [data-camada]').forEach(function (img) {
    // Guarda como cada camada estava, para poder restaurar.
    camadas[img.dataset.camada] = { img: img, src: img.getAttribute('src'), escondida: img.hidden };
  });

  function restaurar(camada) {
    const c = camadas[camada];
    if (c.src) c.img.src = c.src;
    c.img.hidden = c.escondida;
  }

  document.querySelectorAll('.item[data-imagem]').forEach(function (botao) {
    function mostrar() {
      const c = camadas[botao.dataset.camada];
      c.img.src = botao.dataset.imagem;
      c.img.hidden = false;
    }
    botao.addEventListener('mouseenter', mostrar);
    botao.addEventListener('focus', mostrar);
    botao.addEventListener('mouseleave', function () { restaurar(botao.dataset.camada); });
    botao.addEventListener('blur', function () { restaurar(botao.dataset.camada); });
  });
</script>
@endsection
