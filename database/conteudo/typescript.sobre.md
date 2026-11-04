@historia
TypeScript foi lançado pela **Microsoft** em outubro de 2012. O projeto foi liderado por **Anders Hejlsberg**, o mesmo criador do Turbo Pascal, do Delphi e do C#. O problema que ele queria resolver: aplicações JavaScript enormes (como o Outlook Web e o Office Online) ficavam muito difíceis de manter sem tipos.

A solução foi um **superconjunto** do JavaScript: todo JavaScript válido é TypeScript válido, e os tipos são apagados na compilação. No começo houve desconfiança, mas tudo mudou em 2016, quando o **Angular 2** passou a ser escrito em TypeScript. Depois vieram o VS Code (escrito em TypeScript), o suporte nativo em React, Vue e Node, e hoje ele é padrão em projetos profissionais.

@hoje
TypeScript é a escolha padrão para projetos JavaScript médios e grandes. Frameworks como **Angular**, **NestJS** e **Next.js** já vêm com ele configurado, e a maioria das bibliotecas publica seus tipos. Ele é usado no front-end, no back-end com Node.js, em apps com React Native e em ferramentas de linha de comando. Nas pesquisas do Stack Overflow e no relatório do GitHub, aparece entre as linguagens mais usadas e mais amadas. Desde 2025 o Node.js consegue executar arquivos `.ts` diretamente, removendo os tipos na hora.

@iniciante
- Entender por que tipos ajudam e configurar o compilador
- Anotar tipos primitivos, arrays, tuplas e funções
- Usar inferência e evitar any
- Ler e corrigir erros do compilador

@intermediario
- Modelar dados com interfaces e type aliases
- Usar uniões, tipos literais e narrowing
- Escrever código genérico com generics e restrições
- Aplicar utility types como Partial, Pick e Omit

@avancado
- Criar classes com modificadores de acesso, abstract e implements
- Validar dados externos com type guards e bibliotecas de schema
- Configurar tsconfig com strict e entender arquivos .d.ts
- Estruturar projetos reais com Vite, Node ou frameworks

@referencias
**Livros**
- *Programming TypeScript* — Boris Cherny: do básico ao sistema de tipos avançado.
- *Effective TypeScript* — Dan Vanderkam: 80+ dicas práticas para usar bem os tipos.
- *TypeScript Deep Dive* — Basarat Ali Syed ([gratuito online](https://basarat.gitbook.io/typescript)).

**Na internet**
- [TypeScript Handbook](https://www.typescriptlang.org/docs/handbook/intro.html) — o manual oficial.
- [TypeScript Playground](https://www.typescriptlang.org/play) — teste tipos direto no navegador.
- [Total TypeScript](https://www.totaltypescript.com) — exercícios e artigos de Matt Pocock.

@modulo 1
**O sistema de tipos do TypeScript é estrutural.** Diferente de Java ou C#, que comparam tipos pelo nome, TypeScript compara pela **forma**: se um objeto tem todas as propriedades exigidas, ele serve, não importa como foi criado. Isso combina com o jeito dinâmico do JavaScript.

O compilador faz duas coisas separadas: **verificar tipos** e **gerar JavaScript**. Mesmo com erros de tipo ele gera o arquivo (a menos que você use `noEmitOnError`). Por isso o TypeScript é chamado de *gradual*: dá para adotar aos poucos num projeto JavaScript.

Dan Vanderkam, em *Effective TypeScript*, recomenda deixar a inferência trabalhar: anote parâmetros de funções e retornos de APIs públicas, mas não cada variável local. Tipos demais poluem; tipos de menos escondem bugs.

@fontes 1
- Vanderkam, *Effective TypeScript*, capítulos 1 e 3 (inferência de tipos)
- Cherny, *Programming TypeScript*, capítulos 2 e 3
- [Handbook: The Basics](https://www.typescriptlang.org/docs/handbook/2/basic-types.html)

@modulo 2
**Pense em tipos como conjuntos de valores.** `string` é o conjunto de todas as strings; `"sim" | "não"` é um conjunto com dois valores; `never` é o conjunto vazio; `unknown` contém tudo. A união (`A | B`) junta conjuntos e a interseção (`A & B`) exige os dois. Essa visão, defendida em *Effective TypeScript*, torna os erros do compilador muito mais fáceis de entender.

**Torne estados impossíveis impossíveis de representar.** Em vez de um objeto com `carregando: boolean; erro?: string; dados?: T`, use uma união discriminada com `status: "carregando" | "erro" | "ok"`. Assim o compilador garante que nunca existem dados e erro ao mesmo tempo.

Generics são "funções de tipos": recebem tipos como parâmetros. Os **conditional types** (`T extends string ? A : B`) e **mapped types** permitem criar novos tipos a partir de outros, que é como Partial e Readonly são feitos.

@fontes 2
- Vanderkam, *Effective TypeScript*, item "Think of types as sets of values"
- [Handbook: Narrowing](https://www.typescriptlang.org/docs/handbook/2/narrowing.html)
- [Handbook: Generics](https://www.typescriptlang.org/docs/handbook/2/generics.html)

@modulo 3
**Tipos não existem em execução: a fronteira do sistema precisa de validação.** Tudo o que entra (respostas de API, formulários, arquivos, variáveis de ambiente) deve ser validado. Bibliotecas como **Zod** e **Valibot** declaram um schema uma vez e geram o tipo e o validador juntos, eliminando a duplicação.

Com `strict: true`, opções como `strictNullChecks` obrigam a tratar `null` e `undefined`, que Tony Hoare, criador da referência nula, chamou de seu "erro de um bilhão de dólares". Ative também `noUncheckedIndexedAccess`, que lembra que `lista[i]` pode ser `undefined`.

Em projetos grandes, use **referências de projeto** e compilação incremental para acelerar, e mantenha a checagem de tipos no CI (`tsc --noEmit`), separada da etapa de build feita por ferramentas rápidas como esbuild ou Vite.

@fontes 3
- Vanderkam, *Effective TypeScript*, capítulo 5 (any e unknown)
- [Zod — documentação](https://zod.dev)
- [TSConfig Reference](https://www.typescriptlang.org/tsconfig)
