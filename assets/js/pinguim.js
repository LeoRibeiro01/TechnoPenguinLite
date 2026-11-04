/*
 * Recolore a foto original do pinguim. A máscara (pinguim_mascara.png) marca cada região:
 * vermelho = corpo, verde = barriga e rosto, azul = bico e patas. Cada pixel da região recebe
 * a cor escolhida, mantendo o sombreado da foto (luminância relativa ao tom dominante).
 * Olhos, bochechas e sobrancelhas ficam fora da máscara e não mudam.
 */
(() => {
  const BASE = 'assets/imgs/pinguim/pinguim_original.png';
  const MASCARA = 'assets/imgs/pinguim/pinguim_mascara.png';
  // Luminância do tom dominante de cada região na foto: corpo, barriga, detalhe.
  const REFERENCIA = [63, 239, 161];
  const PADRAO = '#384048,#ecf0f0,#ec9038';

  let fontes = null;
  const geradas = new Map();

  const carregar = (src) => new Promise((ok, falha) => {
    const img = new Image();
    img.onload = () => ok(img);
    img.onerror = falha;
    img.src = src;
  });

  const pixels = (img) => {
    const canvas = document.createElement('canvas');
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;
    const ctx = canvas.getContext('2d', { willReadFrequently: true });
    ctx.drawImage(img, 0, 0);
    return ctx.getImageData(0, 0, canvas.width, canvas.height);
  };

  const preparar = () => fontes ??= Promise.all([carregar(BASE), carregar(MASCARA)])
    .then(([base, mascara]) => ({ base: pixels(base), mascara: pixels(mascara).data }));

  const rgb = (hex) => [1, 3, 5].map((i) => parseInt(hex.slice(i, i + 2), 16));

  // Mais escuro que o tom dominante: escurece a cor; mais claro: clareia em direção ao branco.
  const tingir = (alvo, referencia, luminancia) => {
    const fator = luminancia / referencia;
    if (fator <= 1) return alvo * fator;
    return alvo + (255 - alvo) * Math.min(1, (fator - 1) / (255 / referencia - 1));
  };

  const gerar = (cores) => {
    const chave = cores.join(',');
    if (!geradas.has(chave)) {
      geradas.set(chave, preparar().then(({ base, mascara }) => {
        const saida = new ImageData(new Uint8ClampedArray(base.data), base.width, base.height);
        const d = saida.data;
        const alvos = cores.map(rgb);
        for (let i = 0; i < d.length; i += 4) {
          if (!mascara[i + 3]) continue;
          const regiao = mascara[i] ? 0 : mascara[i + 1] ? 1 : 2;
          const luminancia = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
          const [r, g, b] = alvos[regiao];
          d[i] = tingir(r, REFERENCIA[regiao], luminancia);
          d[i + 1] = tingir(g, REFERENCIA[regiao], luminancia);
          d[i + 2] = tingir(b, REFERENCIA[regiao], luminancia);
        }
        const canvas = document.createElement('canvas');
        canvas.width = saida.width;
        canvas.height = saida.height;
        canvas.getContext('2d').putImageData(saida, 0, 0);
        return new Promise((ok) => canvas.toBlob((blob) => ok(URL.createObjectURL(blob))));
      }));
    }
    return geradas.get(chave);
  };

  /** Aplica as cores de data-pinguim="corpo,barriga,detalhe" na imagem. */
  const pintar = async (img) => {
    const chave = (img.dataset.pinguim || '').toLowerCase();
    const cores = chave.split(',');
    img.dataset.pedido = chave;
    if (cores.length !== 3 || chave === PADRAO) {
      img.src = BASE;
      return;
    }
    try {
      const url = await gerar(cores);
      // Ignora respostas antigas quando as cores mudam rápido (pré-visualização ao vivo).
      if (img.dataset.pedido === chave) img.src = url;
    } catch {
      img.src = BASE;
    }
  };

  window.Pinguim = { pintar };
  document.querySelectorAll('img[data-pinguim]').forEach(pintar);
})();
