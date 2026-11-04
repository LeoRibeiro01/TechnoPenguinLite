# Estrutura com HTML
> Tags, semântica, links, imagens e formulários.

## Projeto
**Página de perfil:** crie uma página pessoal com cabeçalho, foto, seção "Sobre mim", lista das linguagens que você estuda, uma tabela com sua rotina de estudos e um formulário de contato. Use apenas HTML semântico e valide o código em validator.w3.org.

## Anatomia de uma página
### Teoria
**HTML** (HyperText Markup Language) descreve a **estrutura** e o **significado** do conteúdo. Ele é formado por **elementos**, escritos com tags: `<p>Olá</p>`. Algumas tags têm **atributos**: `<a href="https://...">`.

Todo documento tem a mesma base:
- `<!doctype html>` diz ao navegador que é HTML moderno.
- `<html lang="pt-BR">` envolve tudo.
- `<head>`: informações sobre a página (título, charset, CSS).
- `<body>`: o que aparece na tela.

Títulos vão de `<h1>` a `<h6>`, parágrafos usam `<p>`. Use só um `<h1>` por página.

### Prática
```html
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meu primeiro site</title>
</head>
<body>
  <h1>Olá, eu sou o Teco!</h1>
  <p>Estou aprendendo <strong>HTML</strong> no TechnoPenguin.</p>
</body>
</html>
```

Salve como `index.html` e abra no navegador.

### Questão
Onde fica o conteúdo visível da página?
- [ ] No head
- [x] No body
- [ ] No doctype
- [ ] No title
> O head guarda informações sobre a página; o body tem o que aparece na tela.

### Questão
Qual a função do atributo `lang="pt-BR"`?
- [ ] Traduzir a página automaticamente
- [x] Informar o idioma do conteúdo para navegadores e leitores de tela
- [ ] Mudar a fonte
- [ ] Nenhuma
> O idioma ajuda na pronúncia de leitores de tela, na hifenização e na indexação.

### Questão
Quantos `<h1>` é recomendado ter por página?
- [x] Um
- [ ] Nenhum
- [ ] Um por parágrafo
- [ ] Seis
> O h1 é o título principal; os demais níveis organizam as seções.

## Links, imagens e listas
### Teoria
- **Links:** `<a href="pagina.html">texto</a>`. Para abrir em nova aba, use `target="_blank"` com `rel="noopener"`.
- **Imagens:** `<img src="foto.jpg" alt="descrição">`. O `alt` é obrigatório: descreve a imagem para quem não a vê e aparece se ela não carregar.
- **Listas:** `<ul>` (não ordenada) e `<ol>` (ordenada), com itens `<li>`.

Caminhos podem ser **absolutos** (`https://site.com/img.png`) ou **relativos** ao arquivo atual (`imgs/foto.png`, `../index.html`).

### Prática
```html
<nav>
  <a href="index.html">Início</a>
  <a href="https://developer.mozilla.org" target="_blank" rel="noopener">MDN</a>
</nav>
<img src="imgs/teco.png" alt="Pinguim Teco acenando" width="200">
<h2>Linguagens que estudo</h2>
<ol>
  <li>HTML</li>
  <li>CSS</li>
  <li>JavaScript</li>
</ol>
```

### Questão
Para que serve o atributo `alt` da imagem?
- [ ] Definir o tamanho
- [x] Descrever a imagem para acessibilidade e quando ela não carrega
- [ ] Criar um link
- [ ] Dar um título à página
> Leitores de tela leem o alt; ele é essencial para acessibilidade.

### Questão
Qual tag cria uma lista numerada?
- [ ] ul
- [x] ol
- [ ] li
- [ ] dl
> ol = ordered list (ordenada); ul = unordered list.

### Questão
O caminho `../index.html` aponta para:
- [ ] Um site externo
- [x] O arquivo index.html na pasta acima da atual
- [ ] Uma pasta chamada ..
- [ ] A raiz do computador
> .. significa "subir um nível" na estrutura de pastas.

## HTML semântico e formulários
### Teoria
Tags **semânticas** dizem o papel de cada parte: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`. Elas ajudam a acessibilidade e o SEO, ao contrário de usar `<div>` para tudo.

**Formulários** coletam dados:
- `<form action="..." method="post">` envolve os campos.
- `<label for="email">` ligado ao `<input id="email">`.
- Tipos de input: `text`, `email`, `password`, `number`, `date`, `checkbox`, `radio`.
- Validação nativa: `required`, `minlength`, `pattern`.

### Prática
```html
<main>
  <section>
    <h2>Fale comigo</h2>
    <form action="/contato" method="post">
      <label for="nome">Nome</label>
      <input id="nome" name="nome" required>

      <label for="email">Email</label>
      <input id="email" name="email" type="email" required>

      <label for="msg">Mensagem</label>
      <textarea id="msg" name="msg" minlength="10"></textarea>

      <button type="submit">Enviar</button>
    </form>
  </section>
</main>
```

### Questão
Qual tag representa o conteúdo principal da página?
- [ ] section
- [x] main
- [ ] body
- [ ] article
> main marca o conteúdo central, único por página.

### Questão
Como ligar um `<label>` a um campo?
- [ ] Colocando o label depois do input
- [x] Usando o atributo for do label igual ao id do input
- [ ] Usando name iguais
- [ ] Não é possível
> Com for/id, clicar no rótulo foca o campo e leitores de tela anunciam o nome.

### Questão
Qual tipo de input valida automaticamente o formato de email?
- [ ] text
- [x] email
- [ ] mail
- [ ] string
> type="email" faz o navegador conferir o formato e mostra teclado adequado no celular.

# Estilo com CSS
> Seletores, cascata, box model, cores e tipografia.

## Projeto
**Estilize sua página de perfil:** crie um `style.css` com variáveis de cor, uma fonte do Google Fonts, cartões com sombra e bordas arredondadas, e estados `:hover` nos links e botões. Busque contraste suficiente entre texto e fundo (verifique com uma ferramenta de contraste).

## Seletores e cascata
### Teoria
**CSS** (Cascading Style Sheets) define a aparência. Uma regra tem **seletor** e **declarações**: `p { color: navy; }`.

Seletores principais:
- Tag: `p`; classe: `.cartao`; id: `#topo`.
- Descendente: `nav a`; filho direto: `ul > li`.
- Pseudo-classes: `a:hover`, `li:first-child`, `input:focus`.

Quando regras conflitam, vence a de maior **especificidade** (id > classe > tag); em empate, a que vem **por último**. Prefira classes para estilizar.

### Prática
```css
/* style.css, ligado no head com <link rel="stylesheet" href="style.css"> */
body {
  font-family: "Montserrat", sans-serif;
  color: #1a2e46;
}
.cartao {
  padding: 16px;
  border-radius: 12px;
  background: #e3f1fb;
}
nav a:hover {
  text-decoration: underline;
}
```

### Questão
Qual seletor tem maior especificidade?
- [ ] p
- [ ] .destaque
- [x] #principal
- [ ] *
> Id é mais específico que classe, que é mais específica que tag.

### Questão
Como selecionar elementos com a classe `aviso`?
- [ ] #aviso
- [x] .aviso
- [ ] aviso
- [ ] *aviso
> O ponto indica classe; a cerquilha indica id.

### Questão
Duas regras com a mesma especificidade definem cores diferentes para `p`. Qual vence?
- [ ] A primeira
- [x] A que aparece por último
- [ ] Nenhuma
- [ ] A mais curta
> Na cascata, em empate de especificidade, vale a última declarada.

## Box model e unidades
### Teoria
Todo elemento é uma **caixa** com quatro camadas: **content** (conteúdo), **padding** (espaço interno), **border** (borda) e **margin** (espaço externo).

Por padrão, `width` vale só para o conteúdo. Com `box-sizing: border-box`, a largura inclui padding e borda: muito mais previsível, por isso quase todo projeto usa:

```
*, *::before, *::after { box-sizing: border-box; }
```

Unidades: `px` (fixa), `%` (relativa ao pai), `rem` (relativa à fonte da raiz), `vw`/`vh` (relativas à janela). Use `rem` para textos e espaçamentos acessíveis.

`display` muda o comportamento: `block` ocupa a linha toda, `inline` flui no texto, `inline-block` mistura os dois e `none` esconde.

### Prática
```css
*, *::before, *::after { box-sizing: border-box; }

.botao {
  display: inline-block;
  padding: 0.75rem 1.5rem;   /* interno */
  margin: 1rem 0;            /* externo */
  border: 2px solid #2897e6;
  border-radius: 8px;
  font-size: 1rem;
}
```

### Questão
Qual camada do box model fica entre o conteúdo e a borda?
- [ ] margin
- [x] padding
- [ ] outline
- [ ] content
> padding é o espaço interno; margin é o espaço fora da borda.

### Questão
O que `box-sizing: border-box` faz?
- [ ] Remove as bordas
- [x] Faz width incluir padding e borda
- [ ] Centraliza o elemento
- [ ] Transforma o elemento em flexbox
> Assim um elemento com width: 200px mede 200px de verdade, mesmo com padding.

### Questão
A unidade `rem` é relativa a quê?
- [ ] À largura da janela
- [ ] Ao elemento pai
- [x] Ao tamanho da fonte do elemento raiz (html)
- [ ] À altura da tela
> Se a fonte raiz for 16px, 2rem = 32px. Respeita o zoom do usuário.

## Cores, fontes e variáveis
### Teoria
Cores podem ser escritas como nome (`red`), hexadecimal (`#1a2e46`), `rgb(26 46 70)` ou `hsl(213 46% 19%)`. Transparência: `#00000080` ou `rgb(0 0 0 / 50%)`.

Tipografia: `font-family` (com fontes de reserva), `font-size`, `font-weight`, `line-height` (1.5 é confortável para leitura).

**Variáveis CSS** (custom properties) evitam repetir valores:

```
:root { --cor-primaria: #2897e6; }
.botao { background: var(--cor-primaria); }
```

Mudou a variável, muda o site todo. Garanta **contraste** suficiente entre texto e fundo (mínimo 4.5:1 para texto comum).

### Prática
```css
:root {
  --azul: #2897e6;
  --noite: #1a2e46;
  --raio: 12px;
}
body {
  background: var(--noite);
  color: #fff;
  line-height: 1.6;
}
.botao {
  background: var(--azul);
  border-radius: var(--raio);
  transition: transform .2s ease;
}
.botao:hover {
  transform: translateY(-2px);
}
```

### Questão
Como declarar uma variável CSS global?
- [ ] $cor: blue;
- [x] :root { --cor: blue; }
- [ ] var cor = blue;
- [ ] @cor: blue;
> Custom properties começam com -- e costumam ficar em :root.

### Questão
Qual valor de `line-height` deixa textos longos mais legíveis?
- [ ] 0.5
- [ ] 1
- [x] 1.5
- [ ] 5
> Algo entre 1.4 e 1.7 dá respiro entre as linhas.

### Questão
Por que o contraste entre texto e fundo importa?
- [ ] Deixa o site mais rápido
- [x] Garante que pessoas com baixa visão consigam ler
- [ ] É exigido pelo HTML
- [ ] Não importa
> Contraste baixo dificulta a leitura para muita gente, inclusive sob sol forte.

# Layouts modernos e responsivos
> Flexbox, Grid, responsividade e animações.

## Projeto
**Landing page responsiva:** recrie a página inicial de um app fictício com cabeçalho fixo, seção de destaque, grade de 3 cartões (que vira 1 coluna no celular, usando Grid) e rodapé. Use Flexbox no menu, media queries mobile-first e uma animação sutil nos cartões. Publique no GitHub Pages.

## Flexbox
### Teoria
**Flexbox** organiza itens em **uma dimensão** (linha ou coluna). Ative no pai com `display: flex`.

No pai (container):
- `flex-direction`: `row` (padrão) ou `column`.
- `justify-content`: alinha no eixo principal (`center`, `space-between`...).
- `align-items`: alinha no eixo cruzado.
- `gap`: espaço entre os itens.
- `flex-wrap: wrap`: permite quebrar linha.

Nos filhos: `flex: 1` faz o item crescer e ocupar o espaço disponível.

### Prática
```css
.menu {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
}
.centralizar {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
```

O segundo bloco é o jeito clássico de centralizar algo na vertical e na horizontal.

### Questão
Qual propriedade alinha os itens no eixo principal de um container flex?
- [x] justify-content
- [ ] align-items
- [ ] text-align
- [ ] flex-wrap
> justify-content atua no eixo principal; align-items no eixo cruzado.

### Questão
Como empilhar os itens em coluna com flexbox?
- [ ] display: column
- [x] flex-direction: column
- [ ] align-items: column
- [ ] flex: column
> flex-direction define a direção do eixo principal.

### Questão
O que `gap: 16px` faz num container flex?
- [ ] Adiciona margem externa ao container
- [x] Cria espaço de 16px entre os itens
- [ ] Define a largura dos itens
- [ ] Nada, só funciona em tabelas
> gap espaça os itens sem precisar de margens individuais.

## CSS Grid
### Teoria
**Grid** organiza em **duas dimensões** (linhas e colunas). Ative com `display: grid` e defina as colunas:

- `grid-template-columns: 1fr 2fr` cria duas colunas proporcionais (`fr` = fração do espaço livre).
- `repeat(3, 1fr)` repete três colunas iguais.
- `repeat(auto-fill, minmax(220px, 1fr))` cria quantas colunas couberem, cada uma com no mínimo 220px: responsivo sem media query!
- Itens podem ocupar várias células: `grid-column: span 2`.

Regra prática: **Flexbox** para alinhar itens numa linha; **Grid** para o layout da página e grades de cartões.

### Prática
```css
.cartoes {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 20px;
}
.pagina {
  display: grid;
  grid-template-columns: 250px 1fr;
  grid-template-areas: "menu conteudo";
}
.pagina aside { grid-area: menu; }
.pagina main { grid-area: conteudo; }
```

### Questão
O que significa a unidade `fr`?
- [ ] Frame
- [x] Uma fração do espaço disponível no grid
- [ ] Font-relative
- [ ] Um valor fixo em pixels
> 1fr 2fr divide o espaço livre em três partes: uma para a primeira coluna e duas para a segunda.

### Questão
Qual a vantagem de `repeat(auto-fill, minmax(220px, 1fr))`?
- [ ] Deixa as colunas sempre com 220px
- [x] Ajusta automaticamente o número de colunas ao espaço disponível
- [ ] Cria exatamente 220 colunas
- [ ] Esconde itens que não cabem
> É uma grade responsiva sem media queries.

### Questão
Quando o Grid costuma ser melhor que o Flexbox?
- [ ] Para alinhar dois botões lado a lado
- [x] Para layouts em duas dimensões, com linhas e colunas
- [ ] Nunca
- [ ] Apenas para tabelas
> Grid controla linhas e colunas ao mesmo tempo.

## Responsividade e animações
### Teoria
Um site **responsivo** se adapta a qualquer tela. Ingredientes:
- A meta tag `viewport` no HTML.
- Unidades relativas e imagens com `max-width: 100%`.
- **Media queries**: estilos aplicados conforme a largura da tela.

A abordagem **mobile-first** escreve o CSS para o celular e usa `min-width` para telas maiores.

Para movimento, use `transition` (mudanças de estado) e `@keyframes` + `animation` (animações próprias). Respeite quem prefere menos movimento com `prefers-reduced-motion`.

### Prática
```css
.cartoes { display: grid; gap: 16px; }

@media (min-width: 768px) {
  .cartoes { grid-template-columns: repeat(3, 1fr); }
}

@keyframes flutuar {
  50% { transform: translateY(-6px); }
}
.teco { animation: flutuar 3s ease-in-out infinite; }

@media (prefers-reduced-motion: reduce) {
  .teco { animation: none; }
}
```

### Questão
Na abordagem mobile-first, qual tipo de media query é mais usado?
- [x] min-width
- [ ] max-width
- [ ] orientation
- [ ] print
> Começamos pelo celular e acrescentamos estilos a partir de larguras mínimas maiores.

### Questão
Qual a diferença entre `transition` e `animation`?
- [ ] Nenhuma
- [x] transition anima mudanças de estado; animation roda keyframes definidos
- [ ] animation só funciona em imagens
- [ ] transition precisa de JavaScript
> transition reage a mudanças (como :hover); animation segue um roteiro de @keyframes.

### Questão
Para que serve `prefers-reduced-motion`?
- [ ] Deixar animações mais rápidas
- [x] Respeitar usuários que pediram menos movimento no sistema
- [ ] Desativar o CSS
- [ ] Reduzir o tamanho das imagens
> Algumas pessoas sentem desconforto com animações; essa media query permite reduzi-las.
