@historia
Em 1989, o físico **Tim Berners-Lee**, no CERN, propôs um sistema de documentos ligados por hiperlinks. Em 1991 publicou a primeira página web, escrita em **HTML** (HyperText Markup Language), uma linguagem de marcação inspirada no SGML. As primeiras versões tinham só títulos, parágrafos, listas e links.

Os navegadores começaram a inventar tags de estilo (como `<font>`), misturando conteúdo e aparência. Para separar as duas coisas, **Håkon Wium Lie** propôs o **CSS** (Cascading Style Sheets) em 1994, junto com Bert Bos; o CSS1 virou padrão do W3C em 1996. Depois vieram o CSS2, a "guerra dos navegadores" e, em 2014, o **HTML5**, com tags semânticas, áudio, vídeo e canvas. Hoje HTML e CSS são "padrões vivos", atualizados continuamente pelo WHATWG e pelo W3C.

@hoje
Toda página da web é feita de HTML e CSS, inclusive as criadas com React, WordPress ou construtores visuais. O CSS moderno tem **Flexbox**, **Grid**, variáveis, container queries, `:has()` e aninhamento, que eliminaram a maioria dos truques antigos. Além de sites, HTML e CSS montam e-mails, apps híbridos, interfaces de apps desktop (Electron) e até documentos para impressão. Frameworks como **Tailwind CSS** e **Bootstrap** são muito usados, mas dependem de entender o CSS por trás.

@iniciante
- Montar a estrutura de uma página com HTML
- Usar links, imagens, listas e tags semânticas
- Criar formulários acessíveis com validação nativa
- Publicar uma página simples

@intermediario
- Estilizar com seletores, cascata e especificidade
- Dominar o box model, unidades e display
- Usar cores, tipografia e variáveis CSS com bom contraste
- Criar estados de hover e foco

@avancado
- Montar layouts com Flexbox e Grid
- Fazer sites responsivos com abordagem mobile-first
- Criar transições e animações respeitando acessibilidade
- Publicar uma landing page completa e responsiva

@referencias
**Livros**
- *HTML e CSS: Projete e Construa Websites* — Jon Duckett: visual e didático, ótimo para iniciantes.
- *CSS: The Definitive Guide* — Eric Meyer e Estelle Weyl: a referência completa do CSS.
- *Every Layout* — Heydon Pickering e Andy Bell: layouts robustos com CSS moderno.
- *Inclusive Components* — Heydon Pickering: componentes acessíveis.

**Na internet**
- [MDN: Aprenda desenvolvimento web](https://developer.mozilla.org/pt-BR/docs/Learn)
- [web.dev Learn CSS](https://web.dev/learn/css) — curso gratuito do Google.
- [Flexbox Froggy](https://flexboxfroggy.com/#pt-br) e [Grid Garden](https://cssgridgarden.com/#pt-br) — jogos para aprender layout.

@modulo 1
**HTML descreve significado, não aparência.** O navegador transforma o HTML na **árvore DOM** e, junto com o CSS, decide o que desenhar. Leitores de tela, buscadores e assistentes de voz leem essa árvore. Por isso a semântica importa: um `<button>` já é focável pelo teclado e anunciado como botão; uma `<div>` com clique não.

A primeira regra das diretrizes **WCAG** e do ARIA é: use o elemento HTML nativo certo antes de recorrer a atributos ARIA. Toda imagem com significado precisa de `alt`; imagens decorativas usam `alt=""`. Os títulos `h1` a `h6` formam um sumário da página e devem seguir a ordem.

HTML é tolerante a erros (o navegador "conserta" tags mal fechadas), mas cada navegador pode consertar de um jeito. Valide seu código no validador do W3C.

@fontes 1
- Duckett, *HTML e CSS: Projete e Construa Websites*, capítulos 1 a 7
- [WCAG 2.2 — Visão geral (W3C)](https://www.w3.org/WAI/standards-guidelines/wcag/pt-BR)
- [MDN: HTML — boas práticas de acessibilidade](https://developer.mozilla.org/pt-BR/docs/Learn/Accessibility/HTML)

@modulo 2
**A cascata decide qual regra vence em três etapas:** origem e importância (`!important`, estilos do navegador, do autor), depois **especificidade** e, por último, **ordem**. O CSS moderno adicionou as **camadas** (`@layer`), que deixam você declarar a prioridade de blocos inteiros de estilos, resolvendo guerras de especificidade.

Valores passam por várias etapas: declarado, **computado** (unidades relativas convertidas), usado e real. Algumas propriedades são **herdadas** (cor, fonte), outras não (margens, bordas). As palavras-chave `inherit`, `initial` e `unset` controlam isso.

Eric Meyer, em *CSS: The Definitive Guide*, recomenda pensar em CSS como um sistema: defina tokens (cores, espaçamentos, raios) em variáveis, crie componentes com classes e evite estilizar por id. Metodologias como **BEM** (`.cartao__titulo--destaque`) ajudam a manter nomes previsíveis.

@fontes 2
- Meyer e Weyl, *CSS: The Definitive Guide*, capítulos sobre especificidade, herança e cascata
- [MDN: Cascata, especificidade e herança](https://developer.mozilla.org/pt-BR/docs/Learn/CSS/Building_blocks/Cascade_and_inheritance)
- [web.dev: Cascade layers](https://web.dev/learn/css/cascade)

@modulo 3
**Layouts intrínsecos se adaptam sozinhos.** Em vez de definir uma media query para cada tamanho de tela, o CSS moderno permite layouts que respondem ao espaço disponível: `repeat(auto-fit, minmax(240px, 1fr))`, `clamp()` para tamanhos fluidos (`font-size: clamp(1rem, 2.5vw, 2rem)`) e **container queries**, que adaptam um componente ao tamanho do seu contêiner, não da janela. O livro *Every Layout* mostra como montar layouts inteiros com poucas regras robustas.

Desempenho também é layout: anime só `transform` e `opacity` (que não forçam o navegador a recalcular o layout), reserve espaço para imagens com `width`/`height` ou `aspect-ratio` (evitando que a página "pule") e carregue imagens fora da tela com `loading="lazy"`.

Teste sempre com teclado, zoom de 200% e em telas pequenas de verdade.

@fontes 3
- Pickering e Bell, *Every Layout* (axiomas e layouts intrínsecos)
- [web.dev: Learn Responsive Design](https://web.dev/learn/design)
- [MDN: Container queries](https://developer.mozilla.org/pt-BR/docs/Web/CSS/CSS_containment/Container_queries)
