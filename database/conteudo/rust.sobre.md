@historia
Rust começou em 2006 como projeto pessoal de **Graydon Hoare**, engenheiro da Mozilla, depois que o elevador do seu prédio quebrou por causa de um bug de software. A **Mozilla** passou a patrocinar o projeto em 2009, porque queria uma linguagem segura para construir um navegador novo, o motor experimental Servo, sem os erros de memória que causavam boa parte das falhas de segurança do Firefox.

A versão **1.0** saiu em maio de 2015. Partes do Servo foram para o Firefox (como o motor de CSS Stylo). Em 2021 foi criada a **Rust Foundation**, com Amazon, Google, Microsoft, Huawei e Mozilla. Desde 2016, Rust é a linguagem "mais amada" ou "mais admirada" na pesquisa do Stack Overflow, todos os anos. O mascote é o caranguejo **Ferris**, e quem programa em Rust se chama *rustacean*.

@hoje
Rust entrou no **kernel do Linux** (desde 2022) e no **Windows**, e é usado em serviços críticos da **AWS** (Firecracker), Cloudflare, Discord, Dropbox e Microsoft. Governos e agências de segurança recomendam linguagens com segurança de memória, como Rust, no lugar de C e C++. Também é muito usado em ferramentas de linha de comando (ripgrep), **WebAssembly**, blockchain, sistemas embarcados e ferramentas de JavaScript ultrarrápidas (Turbopack, SWC, Biome).

@iniciante
- Criar projetos com cargo e usar println!
- Entender imutabilidade, mut, shadowing e tipos
- Controlar o fluxo com if, loop, while, for e match
- Escrever funções e entender expressões

@intermediario
- Dominar ownership, move e Copy
- Emprestar valores com referências & e &mut
- Modelar dados com structs, enums, Option e Result
- Propagar erros com o operador ?

@avancado
- Criar abstrações com traits e generics
- Usar coleções, iteradores e closures
- Programar concorrência segura com threads, Arc e Mutex
- Conhecer async com Tokio e o ecossistema de crates

@referencias
**Livros**
- *A Linguagem de Programação Rust* ("o livro") — Steve Klabnik e Carol Nichols: a introdução oficial, [gratuita online](https://doc.rust-lang.org/book/).
- *Programming Rust* — Jim Blandy, Jason Orendorff e Leonora Tindall: aprofundado e excelente.
- *Rust for Rustaceans* — Jon Gjengset: para quem já sabe o básico.

**Na internet**
- [Rust by Example](https://doc.rust-lang.org/rust-by-example/)
- [Rustlings](https://github.com/rust-lang/rustlings) — pequenos exercícios para praticar.
- [Rust Playground](https://play.rust-lang.org) — rode Rust no navegador.

@modulo 1
**O compilador do Rust é o seu professor mais exigente.** As mensagens de erro do `rustc` são famosas por explicarem o problema e sugerirem a correção, muitas vezes com o código pronto. Leia-as com calma: lutar contra o compilador no começo é normal e passa.

Rust não tem `null` nem exceções. A ausência de valor é `Option<T>` e a falha é `Result<T, E>`, ambos tratados obrigatoriamente com `match` ou métodos. A grande maioria dos erros que em outras linguagens só aparecem em execução, no Rust são pegos na compilação.

Tudo é **expressão** quando possível: blocos, `if` e `match` devolvem valores. E o desempenho segue o princípio das **abstrações de custo zero**, herdado do C++: o que você não usa não custa nada, e o que usa não poderia ser escrito à mão de forma mais eficiente.

@fontes 1
- Klabnik e Nichols, *A Linguagem de Programação Rust*, capítulos 1 a 3
- [The Rust Book: Common Programming Concepts](https://doc.rust-lang.org/book/ch03-00-common-programming-concepts.html)
- [Rust Compiler Error Index](https://doc.rust-lang.org/error_codes/error-index.html)

@modulo 2
**O borrow checker aplica regras que todo programador de C deveria seguir.** As regras de empréstimo (uma referência mutável ou várias imutáveis) impedem, ao mesmo tempo, ponteiros pendurados e **condições de corrida de dados**, porque uma corrida exige dois acessos simultâneos com pelo menos uma escrita. *Programming Rust* mostra que isso é a mesma disciplina que bons programadores de C++ tentam seguir de cabeça, só que verificada pelo compilador.

**Lifetimes** são os nomes que o compilador dá para "quanto tempo uma referência vale". Na maioria dos casos ele as deduz sozinho; quando uma função recebe duas referências e devolve uma, às vezes é preciso dizer qual delas a saída acompanha (`fn maior<'a>(x: &'a str, y: &'a str) -> &'a str`).

Use `String` quando precisa possuir o texto e `&str` quando só vai ler. O mesmo vale para `Vec<T>` e `&[T]`.

@fontes 2
- Klabnik e Nichols, *A Linguagem de Programação Rust*, capítulos 4 (ownership) e 10.3 (lifetimes)
- Blandy, Orendorff e Tindall, *Programming Rust*, capítulos 4 e 5 (ownership e referências)
- [Rust by Example: Scoping rules](https://doc.rust-lang.org/rust-by-example/scope.html)

@modulo 3
**Traits definem capacidades, e o compilador escolhe como despachar.** Com generics (`fn f<T: Display>(x: T)`), o compilador gera uma versão para cada tipo (*monomorfização*), sem custo em execução. Com trait objects (`&dyn Display`), a escolha do método acontece em execução por uma tabela virtual, com um pequeno custo, mas permitindo coleções de tipos diferentes.

Os traits `Send` e `Sync` são marcadores que dizem se um tipo pode ser enviado ou compartilhado entre threads; o compilador os usa para recusar programas concorrentes inseguros. Por isso a comunidade fala em **concorrência sem medo**.

Código `unsafe` existe para os casos em que o compilador não consegue provar a segurança (como falar com hardware ou C), mas fica isolado e bem delimitado, o que facilita auditorias. Jon Gjengset cobre esses temas em *Rust for Rustaceans*.

@fontes 3
- Klabnik e Nichols, *A Linguagem de Programação Rust*, capítulos 10, 13 e 16
- Gjengset, *Rust for Rustaceans*, capítulos 2 (tipos) e 10 (concorrência)
- [Tokio Tutorial](https://tokio.rs/tokio/tutorial)
