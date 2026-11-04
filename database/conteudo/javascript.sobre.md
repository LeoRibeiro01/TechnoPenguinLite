@historia
Em maio de 1995, **Brendan Eich**, da Netscape, criou em apenas **10 dias** uma linguagem para dar interatividade às páginas do navegador Netscape Navigator. Ela se chamou Mocha, depois LiveScript e, por marketing, **JavaScript**, pegando carona na fama do Java, com o qual não tem quase nada a ver.

Para evitar que cada navegador tivesse a sua versão, a linguagem foi padronizada como **ECMAScript** em 1997. Depois de anos de pouca evolução, o **ES5** (2009) e principalmente o **ES6/ES2015** trouxeram let/const, arrow functions, classes, módulos e Promises. Desde então sai uma versão nova por ano. Em 2009, **Ryan Dahl** criou o **Node.js**, que levou o JavaScript para fora do navegador, nos servidores.

@hoje
JavaScript é a única linguagem que roda nativamente em todos os navegadores, por isso está em praticamente todo site. Com **Node.js**, Deno e Bun, também roda servidores e ferramentas. Frameworks como **React**, **Vue**, **Angular** e **Svelte** constroem interfaces; **React Native** e **Electron** levam JavaScript para apps de celular e desktop (como o VS Code e o Discord). Há anos é a linguagem mais usada na pesquisa de desenvolvedores do Stack Overflow.

@iniciante
- Declarar variáveis com let e const e entender os tipos
- Usar operadores, comparação estrita e condicionais
- Percorrer arrays com for, for...of e métodos básicos
- Rodar código no console do navegador e no Node.js

@intermediario
- Escrever funções, arrow functions e entender closures
- Manipular objetos e arrays com map, filter, reduce e desestruturação
- Selecionar, criar e alterar elementos do DOM e reagir a eventos
- Salvar dados no navegador com localStorage

@avancado
- Programar de forma assíncrona com Promises e async/await
- Consumir APIs REST com fetch e tratar erros
- Organizar código em módulos ES
- Criar uma API simples com Node.js e Express

@referencias
**Livros**
- *JavaScript Eloquente* — Marijn Haverbeke: introdução completa e bem escrita ([gratuito online](https://eloquentjavascript.net)).
- *You Don't Know JS Yet* — Kyle Simpson: série que explica o funcionamento interno da linguagem ([gratuita no GitHub](https://github.com/getify/You-Dont-Know-JS)).
- *JavaScript: O Guia Definitivo* — David Flanagan: a referência completa da linguagem.

**Na internet**
- [MDN Web Docs (em português)](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript) — a documentação mais confiável da web.
- [javascript.info](https://javascript.info) — tutorial moderno, do básico ao avançado.
- [Node.js — documentação](https://nodejs.org/pt/learn)

@modulo 1
**Coerção de tipos é a maior armadilha do JavaScript.** O operador `==` converte os valores antes de comparar, o que gera resultados estranhos: `[] == false` é `true`. Por isso a regra é usar sempre `===`. Kyle Simpson, em *You Don't Know JS*, explica que a coerção segue regras precisas (definidas na especificação ECMAScript), mas que entendê-las é o que separa quem "acha que sabe" de quem sabe JavaScript.

O `let` e o `const` têm **escopo de bloco**; o antigo `var` tem escopo de função e sofre **hoisting** (é "içado" para o topo). `const` impede reatribuir a variável, mas não impede alterar um objeto: `const lista = []; lista.push(1)` é válido.

Números em JavaScript são **ponto flutuante de 64 bits**: inteiros grandes demais perdem precisão. Para isso existe o `BigInt` (`10n ** 20n`).

@fontes 1
- Simpson, *You Don't Know JS Yet: Get Started*, capítulo 2
- Haverbeke, *JavaScript Eloquente*, capítulos 1 e 2
- [MDN: Igualdade e comparações](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Equality_comparisons_and_sameness)

@modulo 2
**Funções são valores, e closures são consequência disso.** Uma função "lembra" o escopo onde foi criada, mesmo depois que ele terminou. Closures são a base de padrões como módulos, funções fábrica e manipuladores de eventos. *JavaScript Eloquente* trata funções de alta ordem (que recebem ou devolvem funções) como o coração da linguagem.

O valor de `this` depende de **como** a função é chamada, não de onde foi escrita. Arrow functions não têm `this` próprio: usam o do escopo externo, o que é ótimo dentro de callbacks.

No navegador, eventos "sobem" pelo DOM (**propagação** ou *bubbling*). Isso permite a **delegação de eventos**: um único listener no elemento pai trata os cliques de todos os filhos, inclusive os criados depois.

@fontes 2
- Haverbeke, *JavaScript Eloquente*, capítulos 5 (funções de alta ordem), 14 e 15 (DOM e eventos)
- Simpson, *You Don't Know JS Yet: Scope & Closures*
- [MDN: Introdução a eventos](https://developer.mozilla.org/pt-BR/docs/Learn/JavaScript/Building_blocks/Events)

@modulo 3
**O event loop explica tudo o que é assíncrono.** JavaScript tem uma única pilha de execução. Operações lentas (timers, rede) são entregues ao ambiente (navegador ou Node), e seus callbacks voltam para filas. As **microtarefas** (Promises) rodam antes das **macrotarefas** (setTimeout). Por isso `setTimeout(f, 0)` nunca roda antes de uma Promise já resolvida.

Boas práticas assíncronas: sempre trate rejeições (`try/catch` ou `.catch`), use `Promise.all` para tarefas independentes e `Promise.allSettled` quando algumas podem falhar. Para cancelar requisições use `AbortController`.

No back-end, o Node.js usa esse mesmo modelo para atender milhares de conexões com uma única thread, o que o torna ótimo para I/O, mas ruim para cálculos pesados (que bloqueiam o loop). Para esses casos existem as *worker threads*.

@fontes 3
- Haverbeke, *JavaScript Eloquente*, capítulo 11 (programação assíncrona)
- Palestra "What the heck is the event loop anyway?" — Philip Roberts (JSConf EU 2014)
- [MDN: Usando Promises](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Guide/Using_promises)
