# Fundamentos de JavaScript
> Variáveis, tipos, operadores, decisões e laços.

## Projeto
**Conversor de moedas no console:** crie um script com uma tabela de cotações num objeto e uma função que converte valores entre moedas. Mostre os resultados formatados com `toFixed(2)` e trate moedas inexistentes. Rode com Node.js (`node conversor.js`) ou no console do navegador.

## Variáveis e tipos
### Teoria
JavaScript roda no navegador e no servidor (Node.js). Declare variáveis com:
- `const`: não pode ser reatribuída (use por padrão).
- `let`: pode ser reatribuída.
- Evite `var`, que tem regras de escopo confusas.

Tipos primitivos: `number`, `string`, `boolean`, `undefined`, `null`, `bigint` e `symbol`. Use `typeof` para descobrir o tipo.

**Template strings** são escritas entre crases e usam `${}` para inserir valores, como no exemplo abaixo.

### Prática
```javascript
const nome = "Teco";
let peixes = 3;
peixes = peixes + 2;
console.log(`${nome} tem ${peixes} peixes`);
console.log(typeof peixes, typeof nome, typeof undefined);
console.log("5" + 2);  // "52" (concatena)
console.log("5" * 2);  // 10 (converte para número)
```

### Questão
Qual declaração impede reatribuir a variável?
- [ ] let
- [ ] var
- [x] const
- [ ] static
> const cria uma ligação que não pode receber outro valor.

### Questão
Qual o resultado de `"5" + 2` em JavaScript?
- [ ] 7
- [x] "52"
- [ ] NaN
- [ ] Erro
> Com uma string, o + concatena: "5" + "2" = "52".

### Questão
O que `typeof 3.14` retorna?
- [ ] "float"
- [ ] "decimal"
- [x] "number"
- [ ] "double"
> JavaScript tem um único tipo numérico: number.

## Operadores e condicionais
### Teoria
Use sempre `===` (igualdade estrita) e `!==`. O `==` converte tipos antes de comparar e gera surpresas como `0 == ""` ser `true`.

Condicionais: `if / else if / else`, o **operador ternário** `condicao ? a : b` e o `switch` para muitos casos fixos.

Lógicos: `&&` (e), `||` (ou), `!` (não). O `??` (coalescência nula) devolve o lado direito só se o esquerdo for `null` ou `undefined`.

Valores **falsy**: `false`, `0`, `""`, `null`, `undefined`, `NaN`. Todo o resto é truthy.

### Prática
```javascript
const idade = 17;
const status = idade >= 18 ? "adulto" : "menor";
console.log(status);

const apelido = null;
console.log(apelido ?? "sem apelido");

switch (new Date().getDay()) {
  case 0:
  case 6:
    console.log("Fim de semana: dia de iglu!");
    break;
  default:
    console.log("Dia de estudar");
}
```

### Questão
Qual o resultado de `0 === ""`?
- [ ] true
- [x] false
- [ ] undefined
- [ ] Erro
> A igualdade estrita compara tipo e valor: number e string são tipos diferentes.

### Questão
O que `null ?? "padrão"` retorna?
- [ ] null
- [x] "padrão"
- [ ] undefined
- [ ] false
> ?? devolve o lado direito quando o esquerdo é null ou undefined.

### Questão
Qual destes valores é **truthy**?
- [ ] 0
- [ ] ""
- [x] "0"
- [ ] null
> "0" é uma string não vazia, portanto truthy.

## Laços e arrays básicos
### Teoria
Laços disponíveis: `for` clássico, `while`, `for...of` (percorre **valores** de arrays e strings) e `for...in` (percorre **chaves** de objetos).

Arrays: `const lista = [1, 2, 3]`. Métodos básicos: `push()` adiciona no fim, `pop()` remove do fim, `length` dá o tamanho, `includes()` testa se contém e `indexOf()` acha a posição.

### Prática
```javascript
const linguagens = ["JS", "Python"];
linguagens.push("Go");

for (let i = 0; i < linguagens.length; i++) {
  console.log(i, linguagens[i]);
}
for (const linguagem of linguagens) {
  console.log(linguagem.toLowerCase());
}
console.log(linguagens.includes("Go")); // true
```

### Questão
O que `for...of` percorre num array?
- [ ] Os índices
- [x] Os valores
- [ ] As chaves e valores
- [ ] Somente o primeiro item
> for...of entrega cada valor; for...in entrega as chaves (índices como strings).

### Questão
Qual método adiciona um item ao final do array?
- [ ] shift()
- [ ] pop()
- [x] push()
- [ ] unshift()
> push adiciona no fim; unshift adiciona no começo.

### Questão
Qual o valor de `[10, 20, 30].length`?
- [ ] 2
- [x] 3
- [ ] 30
- [ ] 60
> length é a quantidade de elementos.

# Funções, objetos e o DOM
> Funções modernas, métodos de array, objetos e interação com a página.

## Projeto
**Lista de tarefas no navegador:** crie uma página com um campo e um botão para adicionar tarefas. Cada tarefa pode ser marcada como feita e removida. Use `addEventListener`, `createElement` e salve a lista no `localStorage` para ela não sumir ao recarregar.

## Funções e arrow functions
### Teoria
Funções podem ser declaradas de várias formas:

```
function somar(a, b) { return a + b; }
const somar2 = (a, b) => a + b;
```

**Arrow functions** são curtas e, com uma única expressão, retornam o valor implicitamente. Parâmetros podem ter **padrão** (`(nome = "Teco") =>`) e o **rest** (`...numeros`) junta argumentos num array.

Funções são valores: podem ser passadas como argumento (**callbacks**) e retornadas por outras funções. Uma função que lembra variáveis do escopo onde foi criada forma uma **closure**.

### Prática
```javascript
const dobrar = (n) => n * 2;
const saudar = (nome = "pinguim") => `Olá, ${nome}!`;
const somarTudo = (...nums) => nums.reduce((total, n) => total + n, 0);

function criarContador() {
  let contagem = 0;
  return () => ++contagem;
}
const contar = criarContador();
contar(); contar();
console.log(dobrar(4), saudar(), somarTudo(1, 2, 3), contar()); // 8 "Olá, pinguim!" 6 3
```

### Questão
O que `const f = (x) => x + 1;` retorna em `f(4)`?
- [ ] undefined
- [x] 5
- [ ] 4
- [ ] "41"
> Arrow function com uma expressão retorna o resultado implicitamente.

### Questão
O que é uma closure?
- [ ] Uma função que fecha o navegador
- [x] Uma função que lembra variáveis do escopo onde foi criada
- [ ] Um tipo de laço
- [ ] Um objeto congelado
> criarContador devolve uma função que continua acessando a variável contagem.

### Questão
O que `...nums` faz em `(...nums) =>`?
- [ ] Copia um objeto
- [x] Junta todos os argumentos num array
- [ ] Cria três parâmetros
- [ ] Ignora os argumentos
> É o parâmetro rest: recebe quantos argumentos vierem.

## Objetos e métodos de array
### Teoria
Objetos agrupam propriedades: `const p = { nome: "Teco", idade: 3 }`. Acesse com `p.nome` ou `p["nome"]`.

**Desestruturação** extrai valores: `const { nome } = p;`. O **spread** copia e combina: `{ ...p, idade: 4 }`.

Métodos de array que todo dev usa:
- `map`: transforma cada item e devolve um novo array.
- `filter`: mantém os itens que passam no teste.
- `reduce`: reduz o array a um valor.
- `find`: devolve o primeiro item que passa no teste.

Eles **não modificam** o array original.

### Prática
```javascript
const alunos = [
  { nome: "Ana", nota: 9 },
  { nome: "Beto", nota: 5 },
  { nome: "Caio", nota: 7 },
];
const aprovados = alunos.filter((a) => a.nota >= 7).map((a) => a.nome);
const media = alunos.reduce((s, a) => s + a.nota, 0) / alunos.length;
const { nome, ...resto } = alunos[0];
console.log(aprovados, media.toFixed(1), nome, resto); // ["Ana","Caio"] "7.0" "Ana" {nota: 9}
```

### Questão
Qual método cria um novo array com os itens transformados?
- [x] map
- [ ] filter
- [ ] find
- [ ] forEach
> map aplica a função a cada item e devolve um array do mesmo tamanho.

### Questão
O que `[1, 2, 3, 4].filter(n => n % 2 === 0)` retorna?
- [ ] [1, 3]
- [x] [2, 4]
- [ ] 2
- [ ] [true, false, true, false]
> filter mantém apenas os elementos para os quais a função retorna true.

### Questão
O que `const { a } = { a: 1, b: 2 }` faz?
- [ ] Cria um objeto { a }
- [x] Cria a constante a com o valor 1
- [ ] Apaga a propriedade b
- [ ] Gera erro
> É desestruturação: extrai a propriedade a para uma variável.

## Manipulando o DOM
### Teoria
O **DOM** é a representação da página HTML como uma árvore de objetos que o JavaScript pode ler e alterar.

- Selecionar: `document.querySelector(".classe")` e `querySelectorAll`.
- Alterar: `elemento.textContent`, `elemento.classList.add()`, `elemento.style`.
- Criar: `document.createElement("li")` e `pai.append(filho)`.
- Reagir: `elemento.addEventListener("click", funcao)`.

Prefira `textContent` a `innerHTML` para textos digitados pelo usuário: evita injeção de código (XSS).

### Prática
```html
<input id="tarefa"> <button id="add">Adicionar</button>
<ul id="lista"></ul>
<script>
  const campo = document.querySelector("#tarefa");
  const lista = document.querySelector("#lista");
  document.querySelector("#add").addEventListener("click", () => {
    if (!campo.value.trim()) return;
    const item = document.createElement("li");
    item.textContent = campo.value;
    item.addEventListener("click", () => item.classList.toggle("feita"));
    lista.append(item);
    campo.value = "";
  });
</script>
```

### Questão
Qual método seleciona o **primeiro** elemento que combina com um seletor CSS?
- [ ] getElementsByClassName
- [x] querySelector
- [ ] querySelectorAll
- [ ] selectOne
> querySelector devolve o primeiro; querySelectorAll devolve todos.

### Questão
Por que preferir `textContent` a `innerHTML` para texto do usuário?
- [ ] É mais bonito
- [x] Evita que o texto seja interpretado como HTML (XSS)
- [ ] innerHTML não existe mais
- [ ] textContent aceita imagens
> textContent insere o texto literalmente, sem executar tags ou scripts.

### Questão
Como reagir ao clique num botão?
- [ ] botao.click = funcao()
- [x] botao.addEventListener("click", funcao)
- [ ] botao.on("click")
- [ ] document.click(botao)
> addEventListener registra a função que será chamada quando o evento ocorrer.

# JavaScript assíncrono e moderno
> Promises, async/await, APIs, módulos e Node.js.

## Projeto
**Painel do clima:** crie uma página que busca a previsão de uma cidade numa API pública (por exemplo, Open-Meteo) com `fetch` e `async/await`, mostra os dados num card e trata erros de rede com uma mensagem amigável. Organize o código em módulos ES (`api.js`, `ui.js`, `main.js`).

## Promises e async/await
### Teoria
JavaScript executa uma coisa por vez, mas não espera tarefas lentas (rede, timers): elas são **assíncronas**. O **event loop** retoma o código quando o resultado chega.

Uma **Promise** representa um valor futuro: pode ser **resolvida** (`then`) ou **rejeitada** (`catch`).

`async/await` deixa o código assíncrono com cara de síncrono: dentro de uma função `async`, `await` pausa até a Promise terminar. Trate erros com `try/catch`. `Promise.all` executa várias promises em paralelo.

### Prática
```javascript
const esperar = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

async function preparar() {
  console.log("Pescando...");
  await esperar(1000);
  return "🐟";
}

async function main() {
  try {
    const [a, b] = await Promise.all([preparar(), preparar()]);
    console.log("Pronto:", a, b);
  } catch (erro) {
    console.error("Falhou:", erro);
  }
}
main();
```

### Questão
O que `await` faz?
- [ ] Bloqueia o navegador inteiro
- [x] Pausa a função async até a Promise ser resolvida ou rejeitada
- [ ] Cria uma nova thread
- [ ] Repete a Promise até dar certo
> await só pausa a função async atual; o resto da página continua respondendo.

### Questão
Como tratar erros com async/await?
- [ ] Com if/else
- [x] Com try/catch
- [ ] Com switch
- [ ] Não é possível
> Uma Promise rejeitada lança exceção no await, capturada pelo catch.

### Questão
Para que serve `Promise.all`?
- [ ] Executar promises uma depois da outra
- [x] Aguardar várias promises em paralelo
- [ ] Cancelar promises
- [ ] Converter callbacks em arrays
> Ele resolve quando todas terminam (ou rejeita na primeira falha).

## Fetch e APIs REST
### Teoria
`fetch(url)` faz requisições HTTP e devolve uma Promise com a resposta. Converta o corpo com `await resposta.json()`.

Atenção: `fetch` **só rejeita em falhas de rede**. Um erro 404 ou 500 resolve normalmente: confira `resposta.ok`.

Para enviar dados, informe método, cabeçalhos e corpo:

```
fetch("/api/tarefas", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({ titulo: "Estudar" })
})
```

### Prática
```javascript
async function buscarClima(lat, lon) {
  const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m`;
  const resposta = await fetch(url);
  if (!resposta.ok) {
    throw new Error(`Erro HTTP ${resposta.status}`);
  }
  const dados = await resposta.json();
  return dados.current.temperature_2m;
}

buscarClima(-23.55, -46.63)
  .then((t) => console.log(`Agora: ${t} °C`))
  .catch((e) => console.error(e.message));
```

### Questão
Uma resposta 404 faz o `fetch` rejeitar a Promise?
- [ ] Sim, sempre
- [x] Não; é preciso verificar resposta.ok
- [ ] Só em navegadores antigos
- [ ] Só se o corpo estiver vazio
> fetch só rejeita em falhas de rede. Status HTTP de erro precisam ser checados.

### Questão
Como converter o corpo da resposta em objeto JavaScript?
- [ ] JSON.parse(resposta)
- [x] await resposta.json()
- [ ] resposta.body
- [ ] resposta.toObject()
> json() lê o corpo e o converte, devolvendo uma Promise.

### Questão
Qual método HTTP é usado normalmente para **criar** um recurso?
- [ ] GET
- [x] POST
- [ ] DELETE
- [ ] HEAD
> GET busca, POST cria, PUT/PATCH atualizam e DELETE remove.

## Módulos e Node.js
### Teoria
**Módulos ES** dividem o código em arquivos: `export function soma() {}` num arquivo e `import { soma } from "./mat.js"` em outro. No navegador, use `<script type="module">`.

**Node.js** executa JavaScript fora do navegador: servidores, scripts e ferramentas. O **npm** gerencia pacotes, listados no `package.json`. Comandos básicos: `npm init -y`, `npm install express`, `npm run dev`.

Com o **Express** criamos uma API em poucas linhas.

### Prática
```javascript
// servidor.js  (npm install express)
import express from "express";

const app = express();
app.use(express.json());
const tarefas = [];

app.get("/tarefas", (req, res) => res.json(tarefas));
app.post("/tarefas", (req, res) => {
  const tarefa = { id: tarefas.length + 1, ...req.body };
  tarefas.push(tarefa);
  res.status(201).json(tarefa);
});

app.listen(3000, () => console.log("API em http://localhost:3000"));
```

Para usar `import` no Node, adicione `"type": "module"` no `package.json`.

### Questão
Qual arquivo descreve as dependências de um projeto Node?
- [ ] requirements.txt
- [x] package.json
- [ ] node.config
- [ ] index.html
> O package.json lista dependências e scripts do projeto.

### Questão
Como importar a função `soma` exportada de `mat.js`?
- [ ] require soma from mat
- [x] import { soma } from "./mat.js";
- [ ] include "mat.js"
- [ ] using mat.soma;
> Exportações nomeadas são importadas entre chaves.

### Questão
Qual status HTTP indica que um recurso foi criado?
- [ ] 200
- [x] 201
- [ ] 404
- [ ] 500
> 201 Created é a resposta adequada a um POST que criou algo.
