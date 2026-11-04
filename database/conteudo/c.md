# Fundamentos de C
> Compilação, tipos, entrada e saída e controle de fluxo.

## Projeto
**Calculadora de terminal:** leia dois números e um operador (`+ - * /`) com `scanf`, calcule com `switch` e repita até o usuário digitar `q`. Trate divisão por zero e entradas inválidas (verifique o retorno do `scanf`).

## Compilando o primeiro programa
### Teoria
C é uma linguagem **compilada**, criada nos anos 1970 para escrever o Unix. É a base de sistemas operacionais, drivers e sistemas embarcados, e influenciou C++, Java, C# e muitas outras.

O código passa pelo **compilador** (como o GCC) e vira um executável nativo:

```
gcc ola.c -o ola
./ola
```

Todo programa começa na função `main`. `#include <stdio.h>` traz funções de entrada e saída como `printf`. `main` retorna `0` para indicar sucesso.

### Prática
```c
#include <stdio.h>

int main(void) {
    printf("Olá, mundo gelado!\n");
    printf("2 + 3 = %d\n", 2 + 3);
    return 0;
}
```

`\n` quebra a linha e `%d` é substituído por um inteiro.

### Questão
O que `#include <stdio.h>` faz?
- [ ] Declara a função main
- [x] Inclui as declarações das funções de entrada e saída, como printf
- [ ] Compila o programa
- [ ] Cria uma variável
> stdio.h é o cabeçalho da biblioteca padrão de entrada e saída.

### Questão
O que `return 0;` em `main` indica?
- [ ] Um erro
- [x] Que o programa terminou com sucesso
- [ ] Que main não retorna nada
- [ ] Que o programa deve reiniciar
> Por convenção, 0 significa sucesso para o sistema operacional.

### Questão
Qual comando compila `ola.c` gerando o executável `ola`?
- [ ] run ola.c
- [x] gcc ola.c -o ola
- [ ] c ola
- [ ] make ola.c
> -o define o nome do executável gerado.

## Tipos, variáveis e printf/scanf
### Teoria
Tipos básicos: `int` (inteiro), `float` e `double` (decimais), `char` (um caractere). O tamanho depende da plataforma; use `sizeof(tipo)` para conferir.

Variáveis **não inicializadas têm lixo de memória**: sempre atribua um valor inicial.

`printf` usa **especificadores**: `%d` (int), `%f` (double/float), `%.2f` (duas casas), `%c` (char), `%s` (string).

`scanf` lê valores e precisa do **endereço** da variável, com `&`: `scanf("%d", &idade);`. Ele retorna quantos valores leu com sucesso.

### Prática
```c
#include <stdio.h>

int main(void) {
    int idade = 0;
    double altura = 0.0;

    printf("Idade e altura: ");
    if (scanf("%d %lf", &idade, &altura) != 2) {
        printf("Entrada inválida\n");
        return 1;
    }
    printf("Você tem %d anos e %.2f m\n", idade, altura);
    printf("Um int ocupa %zu bytes\n", sizeof(int));
    return 0;
}
```

### Questão
Por que usamos `&` em `scanf("%d", &n)`?
- [ ] Para multiplicar
- [x] Para passar o endereço de memória onde o valor será guardado
- [ ] Para declarar n
- [ ] É opcional
> scanf precisa saber onde escrever, então recebe o endereço da variável.

### Questão
Qual especificador imprime um double com duas casas decimais?
- [ ] %d
- [ ] %2d
- [x] %.2f
- [ ] %s
> %.2f formata números decimais com duas casas.

### Questão
O que contém uma variável local declarada sem valor inicial?
- [ ] Sempre zero
- [x] Um valor indefinido (lixo de memória)
- [ ] NULL
- [ ] Um erro de compilação
> Em C, locais não inicializadas têm valor imprevisível.

## Decisões e laços
### Teoria
C usa `if / else`, `switch` (com `break` em cada caso) e os laços `for`, `while` e `do-while`.

Em C, **0 é falso e qualquer outro valor é verdadeiro**. O tipo `bool` existe incluindo `<stdbool.h>`.

Cuidado clássico: `if (x = 5)` **atribui** 5 e é sempre verdadeiro. A comparação é `==`. Ative avisos do compilador (`gcc -Wall`) para pegar esse tipo de erro.

### Prática
```c
#include <stdio.h>

int main(void) {
    for (int i = 1; i <= 15; i++) {
        if (i % 15 == 0)      printf("PinguimGelo\n");
        else if (i % 3 == 0)  printf("Pinguim\n");
        else if (i % 5 == 0)  printf("Gelo\n");
        else                  printf("%d\n", i);
    }

    char op = '+';
    switch (op) {
        case '+': printf("soma\n"); break;
        case '-': printf("subtração\n"); break;
        default:  printf("desconhecido\n");
    }
    return 0;
}
```

### Questão
Em C, qual valor é considerado falso numa condição?
- [ ] -1
- [x] 0
- [ ] 1
- [ ] Qualquer número negativo
> Somente zero é falso; qualquer outro valor é verdadeiro.

### Questão
O que acontece em `if (x = 5)`?
- [ ] Compara x com 5
- [x] Atribui 5 a x e a condição é sempre verdadeira
- [ ] Erro de compilação obrigatório
- [ ] x vira 0
> = é atribuição; == é comparação. O -Wall avisa sobre isso.

### Questão
O que acontece num `switch` se você esquecer o `break`?
- [ ] Erro de compilação
- [x] A execução continua nos casos seguintes (fall-through)
- [ ] O programa trava
- [ ] Nada muda
> Sem break, os casos abaixo também são executados.

# Memória e ponteiros
> Arrays, strings, ponteiros e alocação dinâmica.

## Projeto
**Lista de notas dinâmica:** pergunte quantos alunos existem, aloque um vetor com `malloc`, leia as notas, calcule média, maior e menor usando funções que recebem ponteiros, e libere a memória com `free`. Rode com `valgrind` (ou `-fsanitize=address`) para confirmar que não há vazamentos.

## Arrays e strings
### Teoria
Um **array** é um bloco contínuo de elementos do mesmo tipo: `int notas[5];`. Os índices vão de `0` a `tamanho - 1`, e **C não verifica limites**: acessar `notas[5]` é comportamento indefinido.

**Strings** em C são arrays de `char` terminados pelo caractere nulo `'\0'`. Por isso `char nome[5] = "Teco";` precisa de 5 posições (4 letras + `\0`).

Funções de `<string.h>`: `strlen` (tamanho), `strcpy`/`strncpy` (copiar), `strcmp` (comparar: devolve 0 se iguais), `strcat` (concatenar). Para ler texto com segurança, prefira `fgets`.

### Prática
```c
#include <stdio.h>
#include <string.h>

int main(void) {
    int notas[4] = {8, 6, 9, 7};
    int soma = 0;
    for (int i = 0; i < 4; i++) soma += notas[i];
    printf("Média: %.1f\n", soma / 4.0);

    char nome[20];
    printf("Nome: ");
    fgets(nome, sizeof nome, stdin);
    nome[strcspn(nome, "\n")] = '\0';
    printf("%s tem %zu letras\n", nome, strlen(nome));
    printf("%d\n", strcmp(nome, "Teco") == 0);
    return 0;
}
```

### Questão
Quantas posições são necessárias para guardar a string "gelo"?
- [ ] 4
- [x] 5
- [ ] 3
- [ ] 8
> 4 letras mais o terminador '\0'.

### Questão
O que `strcmp(a, b)` retorna quando as strings são iguais?
- [ ] 1
- [x] 0
- [ ] -1
- [ ] true
> strcmp devolve 0 para strings iguais.

### Questão
O que acontece ao acessar `v[10]` num array de 10 elementos?
- [ ] Retorna 0
- [ ] Erro de compilação
- [x] Comportamento indefinido: C não verifica limites
- [ ] O array cresce
> O índice válido máximo é 9. Acessar fora pode corromper memória.

## Ponteiros
### Teoria
Um **ponteiro** guarda um **endereço de memória**. Declaração: `int *p;`.

- `&x` obtém o endereço de `x`.
- `*p` acessa o valor no endereço (desreferência).

Ponteiros permitem que funções **alterem variáveis de quem chamou** (passagem por referência) e são a base de arrays: o nome do array funciona como ponteiro para o primeiro elemento, e `p + 1` aponta para o próximo elemento.

`NULL` indica um ponteiro que não aponta para nada; desreferenciá-lo trava o programa.

### Prática
```c
#include <stdio.h>

void trocar(int *a, int *b) {
    int temp = *a;
    *a = *b;
    *b = temp;
}

int main(void) {
    int x = 1, y = 2;
    trocar(&x, &y);
    printf("x=%d y=%d\n", x, y);   // x=2 y=1

    int v[3] = {10, 20, 30};
    int *p = v;
    printf("%d %d\n", *p, *(p + 2)); // 10 30
    return 0;
}
```

### Questão
Se `int x = 7; int *p = &x;`, quanto vale `*p`?
- [ ] O endereço de x
- [x] 7
- [ ] 0
- [ ] p
> *p acessa o valor guardado no endereço apontado.

### Questão
Por que `trocar` recebe ponteiros?
- [ ] Porque é mais rápido
- [x] Para poder alterar as variáveis originais de quem chamou
- [ ] Porque C não aceita int como parâmetro
- [ ] Para economizar memória
> C passa argumentos por valor; com ponteiros, a função altera o original.

### Questão
Se `p` aponta para `v[0]`, para onde aponta `p + 1`?
- [ ] Para o byte seguinte
- [x] Para o próximo elemento, v[1]
- [ ] Para o fim do array
- [ ] Para NULL
> A aritmética de ponteiros avança de acordo com o tamanho do tipo.

## Alocação dinâmica
### Teoria
Variáveis locais ficam na **pilha (stack)** e somem ao fim da função. Para memória cujo tamanho só se conhece em execução, ou que deve sobreviver à função, use o **heap**:

- `malloc(bytes)`: aloca e devolve um ponteiro (ou `NULL` se falhar).
- `calloc(n, tamanho)`: aloca zerado.
- `realloc(p, novo)`: muda o tamanho.
- `free(p)`: devolve a memória.

Regras de ouro: **sempre verifique `NULL`**, **todo malloc tem um free** (senão há vazamento) e **nunca use memória depois de liberá-la**.

### Prática
```c
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int n;
    printf("Quantos números? ");
    if (scanf("%d", &n) != 1 || n <= 0) return 1;

    int *v = malloc(n * sizeof *v);
    if (v == NULL) return 1;

    for (int i = 0; i < n; i++) v[i] = i * i;
    for (int i = 0; i < n; i++) printf("%d ", v[i]);
    printf("\n");

    free(v);
    v = NULL;
    return 0;
}
```

### Questão
O que fazer sempre após um `malloc`?
- [ ] Chamar printf
- [x] Verificar se o retorno é NULL
- [ ] Converter para int
- [ ] Chamar free imediatamente
> malloc devolve NULL quando não há memória disponível.

### Questão
O que é um vazamento de memória (memory leak)?
- [ ] Memória liberada duas vezes
- [x] Memória alocada que nunca é liberada com free
- [ ] Um ponteiro NULL
- [ ] Um array fora do limite
> O programa perde a referência e a memória fica ocupada até ele terminar.

### Questão
Onde ficam as variáveis locais de uma função?
- [x] Na pilha (stack)
- [ ] No heap
- [ ] No disco
- [ ] Na GPU
> A pilha é liberada automaticamente quando a função retorna.

# C avançado
> Structs, arquivos, modularização e ferramentas.

## Projeto
**Cadastro de pinguins em arquivo:** defina uma `struct Pinguim` (nome, idade, peso), permita cadastrar, listar e buscar, e salve tudo num arquivo binário com `fwrite`/`fread`. Separe o código em `pinguim.h`, `pinguim.c` e `main.c`, e crie um `Makefile`.

## Structs
### Teoria
Uma **struct** agrupa variáveis de tipos diferentes num único tipo:

```
struct Ponto { int x; int y; };
```

Acesse campos com `.` (`p.x`) e, por ponteiro, com `->` (`ptr->x`, que equivale a `(*ptr).x`).

`typedef` cria apelidos de tipo: `typedef struct { ... } Pinguim;` permite escrever só `Pinguim`.

Passe structs grandes por ponteiro para evitar cópias, usando `const` quando a função não deve alterá-las.

### Prática
```c
#include <stdio.h>
#include <string.h>

typedef struct {
    char nome[30];
    int idade;
    double peso;
} Pinguim;

void engordar(Pinguim *p, double kg) {
    p->peso += kg;
}

void mostrar(const Pinguim *p) {
    printf("%s, %d anos, %.1f kg\n", p->nome, p->idade, p->peso);
}

int main(void) {
    Pinguim teco = {"Teco", 3, 12.5};
    engordar(&teco, 0.5);
    mostrar(&teco);
    return 0;
}
```

### Questão
Como acessar o campo `idade` através de um ponteiro `p` para struct?
- [ ] p.idade
- [x] p->idade
- [ ] *p.idade
- [ ] p::idade
> -> desreferencia e acessa o campo; equivale a (*p).idade.

### Questão
Para que serve `typedef`?
- [ ] Converter tipos
- [x] Criar um nome alternativo para um tipo
- [ ] Definir constantes
- [ ] Alocar memória
> typedef evita escrever struct antes do nome toda vez.

### Questão
Por que `mostrar` recebe `const Pinguim *`?
- [ ] Para copiar a struct
- [x] Para evitar cópia e garantir que a função não altere os dados
- [ ] Porque structs não podem ser passadas
- [ ] Para alocar memória
> O ponteiro evita copiar a struct e const protege contra alterações.

## Arquivos
### Teoria
Arquivos são manipulados por um `FILE *`:

- `fopen(caminho, modo)`: modos `"r"`, `"w"`, `"a"`, e `"rb"`/`"wb"` para binário. Retorna `NULL` se falhar.
- Texto: `fprintf`, `fscanf`, `fgets`.
- Binário: `fwrite` e `fread`, que gravam e leem blocos de bytes (ótimo para structs).
- `fclose` sempre ao terminar.

### Prática
```c
#include <stdio.h>

int main(void) {
    FILE *f = fopen("placar.txt", "w");
    if (f == NULL) {
        perror("fopen");
        return 1;
    }
    fprintf(f, "teco %d\n", 150);
    fprintf(f, "pipo %d\n", 90);
    fclose(f);

    char nome[20];
    int pontos;
    f = fopen("placar.txt", "r");
    while (f && fscanf(f, "%19s %d", nome, &pontos) == 2) {
        printf("%s fez %d pontos\n", nome, pontos);
    }
    if (f) fclose(f);
    return 0;
}
```

### Questão
O que `fopen` retorna se não conseguir abrir o arquivo?
- [ ] 0 bytes
- [x] NULL
- [ ] -1
- [ ] Um arquivo vazio
> Sempre teste o retorno antes de usar o FILE *.

### Questão
Qual modo de abertura apaga o conteúdo existente?
- [ ] "r"
- [x] "w"
- [ ] "a"
- [ ] "rb"
> "w" cria ou trunca o arquivo; "a" acrescenta no fim.

### Questão
Quais funções gravam e leem structs inteiras em arquivo binário?
- [ ] printf e scanf
- [x] fwrite e fread
- [ ] fputs e fgets
- [ ] write e read de string.h
> fwrite/fread copiam blocos de bytes diretamente.

## Modularização e Makefile
### Teoria
Projetos maiores são divididos em arquivos:
- **`.h` (cabeçalho):** declarações (protótipos, structs, constantes).
- **`.c` (implementação):** o código das funções.

Use **include guards** (`#ifndef ... #define ... #endif`) para evitar inclusões duplicadas.

O **Makefile** automatiza a compilação: o comando `make` só recompila o que mudou. Ative avisos com `-Wall -Wextra` e use `-g` para depurar com o **gdb**.

### Prática
```c
/* pinguim.h */
#ifndef PINGUIM_H
#define PINGUIM_H
typedef struct { char nome[30]; int idade; } Pinguim;
void mostrar(const Pinguim *p);
#endif
```

```c
/* pinguim.c */
#include <stdio.h>
#include "pinguim.h"
void mostrar(const Pinguim *p) { printf("%s (%d)\n", p->nome, p->idade); }
```

```
# Makefile (as linhas de comando começam com TAB)
CFLAGS = -Wall -Wextra -g
app: main.o pinguim.o
	gcc $(CFLAGS) -o app main.o pinguim.o
```

### Questão
O que normalmente vai num arquivo `.h`?
- [ ] O código completo das funções
- [x] Declarações: protótipos, tipos e constantes
- [ ] Apenas comentários
- [ ] O executável
> O .h diz o que existe; o .c diz como funciona.

### Questão
Para que servem os include guards?
- [ ] Proteger o código de cópias
- [x] Evitar que o mesmo cabeçalho seja incluído duas vezes
- [ ] Criptografar o arquivo
- [ ] Acelerar a execução
> Sem eles, inclusões repetidas causam redefinições.

### Questão
Qual a vantagem do `make`?
- [ ] Executar o programa mais rápido
- [x] Recompilar apenas o que mudou, seguindo as regras do Makefile
- [ ] Substituir o compilador
- [ ] Gerar testes automaticamente
> make compara datas dos arquivos e reconstrói só o necessário.
