@historia
C foi criada entre 1969 e 1973 por **Dennis Ritchie**, nos Laboratórios Bell, a partir da linguagem B de **Ken Thompson**. O objetivo era reescrever o sistema operacional **Unix**, até então em Assembly, numa linguagem de alto nível que ainda permitisse controlar o hardware. Deu certo: o Unix em C pôde ser levado para outros computadores, algo inédito.

Em 1978, Brian **Kernighan** e Ritchie publicaram *The C Programming Language*, o "K&R", que virou a bíblia da linguagem e popularizou o "hello, world". A linguagem foi padronizada pela ANSI em 1989 (C89) e evoluiu com C99, C11, C17 e **C23**. Ritchie e Thompson receberam o Prêmio Turing em 1983.

@hoje
C continua no coração da computação: kernels do **Linux**, Windows e macOS, **sistemas embarcados** (micro-ondas, carros, satélites, Arduino), firmwares, bancos de dados (SQLite, PostgreSQL), interpretadores (CPython, Ruby, PHP) e bibliotecas de alto desempenho. Quase toda linguagem consegue chamar código C. Aprender C ensina como a memória e o computador realmente funcionam, o que melhora o código em qualquer outra linguagem.

@iniciante
- Compilar e executar programas com GCC
- Usar tipos, printf e scanf com segurança
- Controlar o fluxo com if, switch e laços
- Entender avisos do compilador

@intermediario
- Trabalhar com arrays e strings terminadas em zero
- Dominar ponteiros, endereços e aritmética de ponteiros
- Passar dados por referência para funções
- Alocar e liberar memória com malloc e free

@avancado
- Criar tipos com struct e typedef
- Ler e gravar arquivos de texto e binários
- Dividir projetos em .h e .c com include guards
- Automatizar a compilação com Makefile e depurar com gdb

@referencias
**Livros**
- *C: A Linguagem de Programação* — Kernighan e Ritchie (K&R): curto, preciso e clássico.
- *C Programming: A Modern Approach* — K. N. King: o melhor livro didático completo.
- *Modern C* — Jens Gustedt: C moderno (C17/C23), [gratuito online](https://gustedt.gitlabpages.inria.fr/modern-c/).
- *Expert C Programming* — Peter van der Linden: os segredos e armadilhas da linguagem.

**Na internet**
- [cppreference — C](https://en.cppreference.com/w/c) — referência da linguagem e da biblioteca padrão.
- [Beej's Guide to C Programming](https://beej.us/guide/bgc/) — guia gratuito e bem-humorado.
- [Compiler Explorer](https://godbolt.org) — veja o Assembly gerado pelo seu código.

@modulo 1
**C confia em você, e isso é perigoso.** A linguagem tem o conceito de **comportamento indefinido** (*undefined behavior*): acessar fora de um array, usar variável não inicializada ou estourar um inteiro com sinal não geram erro garantido. O programa pode funcionar, travar ou fazer algo estranho, e o compilador pode otimizar assumindo que isso nunca acontece.

Por isso, compile sempre com avisos (`-Wall -Wextra -pedantic`) e, ao testar, com os **sanitizers**: `-fsanitize=address,undefined` detecta muitos erros de memória na hora em que acontecem.

Os tamanhos dos tipos variam por plataforma. Para tamanhos exatos, use `<stdint.h>` (`int32_t`, `uint8_t`). E o `scanf` devolve quantos itens leu: sempre confira esse retorno.

@fontes 1
- Kernighan e Ritchie, *C: A Linguagem de Programação*, capítulos 1 e 2
- Gustedt, *Modern C*, nível 1 (encontrando os fundamentos)
- [GCC: opções de avisos](https://gcc.gnu.org/onlinedocs/gcc/Warning-Options.html)

@modulo 2
**Arrays não são ponteiros, mas viram ponteiros.** Na maioria das expressões, o nome de um array "decai" para um ponteiro para o primeiro elemento. Por isso uma função que recebe um array não sabe seu tamanho: é preciso passar o tamanho separado. `sizeof` dentro da função mede o ponteiro, não o array, um erro clássico descrito em *Expert C Programming*.

A memória de um programa C se divide em **pilha** (variáveis locais, automática), **heap** (malloc/free, manual), área de dados **estáticos** e o código. Erros típicos do heap: vazamento (esquecer o free), *double free*, uso depois de liberar (*use-after-free*) e estouro de buffer. Muitas falhas de segurança famosas, como o **Heartbleed** (2014), vieram de erros assim.

Uma boa disciplina: quem aloca é quem libera, e todo ponteiro liberado vira `NULL`.

@fontes 2
- King, *C Programming: A Modern Approach*, capítulos 11, 12 e 17
- van der Linden, *Expert C Programming*, capítulo 4 ("The Shocking Truth: C Arrays and Pointers Are NOT the Same!")
- [Valgrind — Quick Start](https://valgrind.org/docs/manual/quick-start.html)

@modulo 3
**Organize C como uma biblioteca.** Bons projetos em C escondem os detalhes: o `.h` declara uma interface mínima e o `.c` implementa. Com **tipos opacos** (`typedef struct Pilha Pilha;` no cabeçalho, e a definição só no `.c`), quem usa a biblioteca não consegue mexer nos campos internos, uma forma de encapsulamento sem orientação a objetos.

Funções `static` dentro de um `.c` são privadas ao arquivo. Ao gravar structs em arquivos binários, lembre que o compilador pode inserir **preenchimento** (*padding*) entre campos e que a ordem dos bytes (*endianness*) muda entre arquiteturas: arquivos binários nem sempre são portáveis.

Para depurar, compile com `-g` e use o **gdb**: `break`, `run`, `next`, `print variavel`, `backtrace`. Saber ler um *core dump* é uma habilidade valiosa em sistemas reais.

@fontes 3
- Kernighan e Pike, *A Prática da Programação*, capítulo 4 (interfaces)
- King, *C Programming: A Modern Approach*, capítulos 15 e 19 (programas grandes e tipos abstratos)
- [GNU Make Manual](https://www.gnu.org/software/make/manual/)
