{{-- MEU PINGUIM: cores do avatar (RF08, RF09) - controller: PerfilController@formAvatar / @salvarAvatar
     As roupas ficam na tela Vestuário (VestuarioController). --}}
@extends('layouts.app')

@section('titulo', 'Meu pinguim')

@section('conteudo')
<div class="personalizar">
  <section class="personalizar__previa">
    @include('partials.avatar', ['u' => $user, 'classe' => 'avatar--corpo avatar--palco'])
    <p class="centro"><a class="botao botao--claro" href="{{ route('vestuario') }}">Trocar roupas</a></p>
  </section>

  <section class="cartao personalizar__painel">
    <h1>Personalize seu pinguim</h1>
    <p class="suave">Escolha as cores que mais combinam com você.</p>

    @include('partials.erros')

    <form method="POST" action="{{ route('perfil.avatar') }}" id="form-pinguim">
      @csrf
      @method('PUT')

      {{-- Uma linha de cores para cada parte: corpo, barriga, detalhe e fundo --}}
      @foreach ($partes as $parte => $texto)
        @php
          // Cor atual desta parte (as colunas se chamam cor_corpo, cor_barriga, cor_detalhe, cor_fundo).
          $atual = strtolower($user->{'cor_' . $parte});
          // Se a cor atual não está na paleta, é uma cor "livre" escolhida no seletor.
          $livre = !isset($paletas[$parte][$atual]);
        @endphp
        <fieldset class="cores" data-parte="{{ $parte }}">
          <legend>{{ $texto[0] }} <small class="suave">{{ $texto[1] }}</small></legend>
          <div class="cores__lista">
            @foreach ($paletas[$parte] as $hex => $nomeCor)
              {{-- Cada cor é um rádio escondido; a bolinha colorida (span) é o que aparece --}}
              <label class="cor" title="{{ $nomeCor }}">
                <input type="radio" name="{{ $parte }}" value="{{ $hex }}" @checked($hex === $atual)>
                <span class="cor__amostra" style="--cor: {{ $hex }}"></span>
                <span class="so-leitor">{{ $nomeCor }}</span>
              </label>
            @endforeach
            {{-- Última opção: qualquer cor, pelo seletor de cores do navegador --}}
            <label class="cor cor--livre" title="Outra cor">
              <input type="radio" name="{{ $parte }}" value="{{ $atual }}" data-livre @checked($livre)>
              <span class="cor__amostra" style="--cor: {{ $atual }}">
                <input type="color" value="{{ $atual }}" aria-label="Escolher outra cor para {{ strtolower($texto[0]) }}">
              </span>
            </label>
          </div>
        </fieldset>
      @endforeach

      <div class="botoes">
        <button class="botao botao--claro" type="button" id="aleatorio">Surpreenda-me</button>
        <button class="botao botao--claro" type="button" id="original">Cores originais</button>
        <button class="botao" type="submit">Salvar</button>
      </div>
    </form>
  </section>
</div>

<script>
  /*
   * Prévia ao vivo: sempre que uma cor muda, atualiza o pinguim grande sem precisar salvar.
   *   - corpo, barriga e detalhe: muda o data-pinguim da foto e pede para o pinguim.js pintar de novo;
   *   - fundo: muda a variável CSS --fundo.
   */
  const avatar = document.querySelector('.avatar--palco');
  const foto = avatar.querySelector('img[data-pinguim]');
  const form = document.querySelector('#form-pinguim');
  // A diretiva json do Blade transforma o array do PHP em objeto JavaScript: { corpo: '#384048', ... }
  const padrao = @json($padrao);

  // Cor marcada numa parte (ex.: valor('corpo') -> '#384048')
  function valor(parte) {
    return form.querySelector('input[name="' + parte + '"]:checked').value;
  }

  function atualizar() {
    avatar.style.setProperty('--fundo', valor('fundo'));
    foto.dataset.pinguim = [valor('corpo'), valor('barriga'), valor('detalhe')].join(',');
    window.Pinguim.pintar(foto);
  }

  // Marca uma cor numa parte: se ela está na paleta, marca a bolinha; senão, usa a opção "livre".
  function marcar(grupo, cor) {
    const opcoes = grupo.querySelectorAll('input[type="radio"]:not([data-livre])');
    for (const opcao of opcoes) {
      if (opcao.value === cor) {
        opcao.checked = true;
        return;
      }
    }
    const livre = grupo.querySelector('[data-livre]');
    livre.value = cor;
    livre.checked = true;
    livre.nextElementSibling.style.setProperty('--cor', cor);
  }

  form.querySelectorAll('.cores').forEach(function (grupo) {
    const livre = grupo.querySelector('[data-livre]');
    const seletor = grupo.querySelector('input[type="color"]');

    // Clicou numa bolinha da paleta
    grupo.addEventListener('change', function (evento) {
      if (evento.target.type === 'radio') atualizar();
    });
    // Mexeu no seletor de "outra cor"
    seletor.addEventListener('input', function () {
      livre.value = seletor.value;
      livre.checked = true;
      livre.nextElementSibling.style.setProperty('--cor', seletor.value);
      atualizar();
    });
  });

  // Botão "Surpreenda-me": sorteia uma cor da paleta para cada parte
  document.querySelector('#aleatorio').addEventListener('click', function () {
    form.querySelectorAll('.cores').forEach(function (grupo) {
      const opcoes = grupo.querySelectorAll('input[type="radio"]:not([data-livre])');
      opcoes[Math.floor(Math.random() * opcoes.length)].checked = true;
    });
    atualizar();
  });

  // Botão "Cores originais": volta para as cores da foto
  document.querySelector('#original').addEventListener('click', function () {
    form.querySelectorAll('.cores').forEach(function (grupo) {
      marcar(grupo, padrao[grupo.dataset.parte]);
    });
    atualizar();
  });
</script>
@endsection
