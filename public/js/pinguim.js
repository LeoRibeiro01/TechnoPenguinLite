/*
 * PINTA O PINGUIM COM AS CORES DO USUÁRIO (RF08/RF09)
 * ===================================================
 * Este é o arquivo mais "avançado" do projeto. Resumo para explicar na apresentação:
 *
 *   1. O avatar é a FOTO do pinguim (imgs/pinguim/pinguim_original.png).
 *   2. Uma segunda imagem, a MÁSCARA (pinguim_mascara.png), tem o mesmo tamanho e marca as regiões:
 *        vermelho = corpo | verde = barriga e rosto | azul = bico e patas
 *   3. O script desenha a foto num <canvas> (uma "tela" do HTML onde dá para mexer pixel por pixel),
 *      e troca a cor de cada pixel que está dentro de uma região da máscara pela cor escolhida,
 *      mantendo o claro/escuro da foto (o sombreado). Olhos e bochechas ficam fora da máscara.
 *   4. O resultado vira uma imagem nova, que substitui a foto no <img>.
 *
 * Como usar no HTML (o partial resources/views/partials/avatar.blade.php já faz isso):
 *   <img src="imgs/pinguim/pinguim_original.png" data-pinguim="#384048,#ecf0f0,#ec9038">
 *   (as 3 cores são: corpo, barriga, bico/patas)
 *
 * Para atualizar ao vivo (tela do avatar): mude o data-pinguim e chame window.Pinguim.pintar(img).
 */
(function () {
  // Endereço das imagens, montado a partir do endereço deste script (public/js/pinguim.js -> public/imgs/...).
  // Assim funciona tanto no "php artisan serve" quanto dentro de uma subpasta do XAMPP.
  const pasta = new URL('../imgs/pinguim/', document.currentScript.src);
  const BASE = new URL('pinguim_original.png', pasta).href;
  const MASCARA = new URL('pinguim_mascara.png', pasta).href;

  // Brilho do tom principal de cada região na foto original: corpo, barriga, detalhe.
  // Pixels mais escuros que isso ficam mais escuros na cor nova; mais claros, mais claros.
  const REFERENCIA = [63, 239, 161];

  // Cores originais da foto: com elas não é preciso pintar nada.
  const PADRAO = '#384048,#ecf0f0,#ec9038';

  let fontes = null;            // foto e máscara já carregadas (carrega só uma vez)
  const geradas = new Map();    // imagens já pintadas, por combinação de cores (cache)

  // Carrega uma imagem e avisa quando terminou (Promise).
  function carregar(src) {
    return new Promise(function (ok, falha) {
      const img = new Image();
      img.onload = function () { ok(img); };
      img.onerror = falha;
      img.src = src;
    });
  }

  // Desenha a imagem num canvas e devolve os pixels (cada pixel = 4 números: R, G, B, transparência).
  function pixels(img) {
    const canvas = document.createElement('canvas');
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;
    const ctx = canvas.getContext('2d', { willReadFrequently: true });
    ctx.drawImage(img, 0, 0);
    return ctx.getImageData(0, 0, canvas.width, canvas.height);
  }

  function preparar() {
    if (!fontes) {
      fontes = Promise.all([carregar(BASE), carregar(MASCARA)]).then(function (imagens) {
        return { base: pixels(imagens[0]), mascara: pixels(imagens[1]).data };
      });
    }
    return fontes;
  }

  // "#ff8800" -> [255, 136, 0]
  function rgb(hex) {
    return [1, 3, 5].map(function (i) { return parseInt(hex.slice(i, i + 2), 16); });
  }

  // Aplica o sombreado da foto na cor nova: mais escuro que a referência escurece; mais claro, clareia.
  function tingir(alvo, referencia, luminancia) {
    const fator = luminancia / referencia;
    if (fator <= 1) {
      return alvo * fator;
    }
    return alvo + (255 - alvo) * Math.min(1, (fator - 1) / (255 / referencia - 1));
  }

  // Gera (ou pega do cache) a imagem pintada com as 3 cores. Devolve o endereço da imagem nova.
  function gerar(cores) {
    const chave = cores.join(',');
    if (!geradas.has(chave)) {
      geradas.set(chave, preparar().then(function (f) {
        const saida = new ImageData(new Uint8ClampedArray(f.base.data), f.base.width, f.base.height);
        const d = saida.data;
        const alvos = cores.map(rgb);

        // Passa por todos os pixels (de 4 em 4 números).
        for (let i = 0; i < d.length; i += 4) {
          if (!f.mascara[i + 3]) continue;   // fora da máscara: não mexe
          // Qual região? vermelho na máscara = 0 (corpo), verde = 1 (barriga), senão 2 (detalhe)
          const regiao = f.mascara[i] ? 0 : (f.mascara[i + 1] ? 1 : 2);
          const luminancia = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
          const cor = alvos[regiao];
          d[i] = tingir(cor[0], REFERENCIA[regiao], luminancia);
          d[i + 1] = tingir(cor[1], REFERENCIA[regiao], luminancia);
          d[i + 2] = tingir(cor[2], REFERENCIA[regiao], luminancia);
        }

        const canvas = document.createElement('canvas');
        canvas.width = saida.width;
        canvas.height = saida.height;
        canvas.getContext('2d').putImageData(saida, 0, 0);
        return new Promise(function (ok) {
          canvas.toBlob(function (blob) { ok(URL.createObjectURL(blob)); });
        });
      }));
    }
    return geradas.get(chave);
  }

  // Pinta um <img data-pinguim="corpo,barriga,detalhe">.
  function pintar(img) {
    const chave = (img.dataset.pinguim || '').toLowerCase();
    const cores = chave.split(',');
    img.dataset.pedido = chave;

    if (cores.length !== 3 || chave === PADRAO) {
      img.src = BASE;
      return;
    }
    gerar(cores).then(function (url) {
      // Se as cores mudaram enquanto pintava (prévia ao vivo), ignora o resultado antigo.
      if (img.dataset.pedido === chave) img.src = url;
    }).catch(function () {
      img.src = BASE;
    });
  }

  window.Pinguim = { pintar: pintar };

  // Ao abrir a página, pinta todos os pinguins.
  document.querySelectorAll('img[data-pinguim]').forEach(pintar);
})();
