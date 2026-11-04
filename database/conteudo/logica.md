# Primeiros passos
> O que é programar, algoritmos e como o computador guarda informações.

## Projeto
**Receita de algoritmo:** escreva, em português e passo a passo, o algoritmo de uma tarefa do seu dia (fazer café, ir para a escola, escovar os dentes). Depois troque com um amigo do seu iglu e peça para ele "executar" exatamente o que está escrito. Onde ele travou, faltou um passo! Por fim, reescreva o algoritmo usando variáveis (ex.: `xicaras_de_agua = 2`).

## O que é um algoritmo
### Teoria
Um **algoritmo** é uma sequência finita de passos, bem definidos, para resolver um problema. Uma receita de bolo é um algoritmo: ingredientes (entrada), modo de preparo (processamento) e o bolo pronto (saída).

O computador é muito rápido, mas não adivinha nada: ele executa **exatamente** o que mandamos, na ordem em que mandamos. Programar é escrever algoritmos numa linguagem que o computador entende.

Todo algoritmo segue o modelo:
- **Entrada:** os dados que recebemos.
- **Processamento:** o que fazemos com eles.
- **Saída:** o resultado que entregamos.

### Prática
Veja o algoritmo para calcular a média de duas notas, primeiro em português estruturado (pseudocódigo) e depois em Python:

```
início
  leia nota1
  leia nota2
  media <- (nota1 + nota2) / 2
  escreva media
fim
```

```python
nota1 = float(input("Nota 1: "))
nota2 = float(input("Nota 2: "))
media = (nota1 + nota2) / 2
print("Média:", media)
```

Repare: as duas versões têm as mesmas três partes: entrada (`leia`/`input`), processamento (a conta) e saída (`escreva`/`print`).

### Questão
O que melhor define um algoritmo?
- [ ] Um programa que só funciona em computadores
- [x] Uma sequência finita e ordenada de passos para resolver um problema
- [ ] Uma linguagem de programação
- [ ] Um tipo de computador
> Algoritmos existem até fora do computador, como uma receita. O essencial é ter passos finitos, claros e em ordem.

### Questão
No algoritmo da média, qual parte é o **processamento**?
- [ ] leia nota1
- [x] media <- (nota1 + nota2) / 2
- [ ] escreva media
- [ ] fim
> Ler as notas é a entrada, mostrar a média é a saída. O cálculo é o processamento.

### Questão
Por que a ordem dos passos importa?
- [ ] Não importa, o computador reorganiza sozinho
- [x] Porque o computador executa os passos exatamente na ordem escrita
- [ ] Porque o computador só lê o último passo
- [ ] Porque passos fora de ordem ficam mais rápidos
> Se você calcular a média antes de ler as notas, o resultado não faz sentido. O computador segue a ordem à risca.

## Variáveis e tipos de dados
### Teoria
Uma **variável** é um espaço com nome onde guardamos um valor que pode mudar. Pense numa caixa com etiqueta: a etiqueta é o nome, e o que está dentro é o valor.

Os valores têm **tipos**:
- **Inteiro:** números sem casas decimais (`10`, `-3`).
- **Real (float):** números com casas decimais (`3.14`).
- **Texto (string):** letras e símbolos entre aspas (`"Teco"`).
- **Lógico (booleano):** apenas `verdadeiro` ou `falso`.

Escolha nomes que expliquem o conteúdo: `idade` é melhor que `x`.

### Prática
```
idade <- 15
nome <- "Teco"
altura <- 1.20
gosta_de_peixe <- verdadeiro

idade <- idade + 1
escreva nome, " agora tem ", idade, " anos"
```

A linha `idade <- idade + 1` lê o valor atual (15), soma 1 e guarda o resultado (16) na mesma variável. O valor antigo é substituído.

### Questão
Qual o tipo mais adequado para guardar o nome de uma pessoa?
- [ ] Inteiro
- [ ] Real
- [x] Texto (string)
- [ ] Lógico
> Nomes são formados por letras, então usamos o tipo texto.

### Questão
Depois de executar `x <- 5` e depois `x <- x * 2`, qual o valor de `x`?
- [ ] 5
- [ ] 7
- [x] 10
- [ ] 25
> A segunda linha lê o valor atual de x (5), multiplica por 2 e guarda 10 em x.

### Questão
Qual destes valores é do tipo **lógico**?
- [ ] "verdadeiro"
- [x] falso
- [ ] 0.5
- [ ] 1000
> Entre aspas, "verdadeiro" é um texto. O valor lógico é falso (ou verdadeiro) sem aspas.

## Operadores e expressões
### Teoria
**Operadores aritméticos** fazem contas: `+`, `-`, `*` (multiplicação), `/` (divisão) e `%` (resto da divisão). O resto é muito útil: `n % 2` é 0 quando `n` é par.

**Operadores relacionais** comparam valores e resultam em verdadeiro ou falso: `>`, `<`, `>=`, `<=`, `==` (igual) e `!=` (diferente).

**Operadores lógicos** combinam condições:
- `E`: verdadeiro só se as duas forem verdadeiras.
- `OU`: verdadeiro se pelo menos uma for verdadeira.
- `NÃO`: inverte o valor.

Como na matemática, a multiplicação e a divisão vêm antes da soma e da subtração. Use parênteses para deixar claro.

### Prática
```
a <- 7
b <- 2
escreva a + b * 3      // 13 (multiplica primeiro)
escreva (a + b) * 3    // 27
escreva a % b          // 1 (7 dividido por 2 sobra 1)
escreva a > b E b > 5  // falso
escreva a > b OU b > 5 // verdadeiro
```

### Questão
Quanto vale `2 + 3 * 4`?
- [ ] 20
- [x] 14
- [ ] 24
- [ ] 9
> A multiplicação vem primeiro: 3 * 4 = 12, e 2 + 12 = 14.

### Questão
Qual expressão diz se `n` é par?
- [ ] n / 2 == 0
- [x] n % 2 == 0
- [ ] n * 2 == 0
- [ ] n - 2 == 0
> O operador % devolve o resto. Um número é par quando o resto da divisão por 2 é zero.

### Questão
Se `chovendo = verdadeiro` e `tenho_guarda_chuva = falso`, quanto vale `chovendo E tenho_guarda_chuva`?
- [ ] verdadeiro
- [x] falso
- [ ] chovendo
- [ ] Dá erro
> O operador E só resulta em verdadeiro quando as duas condições são verdadeiras.

# Decisões e repetições
> Fazer o programa escolher caminhos e repetir tarefas sem copiar código.

## Projeto
**Jogo de adivinhação:** o programa "pensa" num número de 1 a 50 e o jogador tenta adivinhar. A cada tentativa, diga "maior" ou "menor". Conte as tentativas e, no final, mostre uma mensagem diferente se o jogador acertou em até 5 tentativas. Escreva primeiro em pseudocódigo e depois na linguagem que você está estudando.

## Estruturas condicionais
### Teoria
Uma **estrutura condicional** faz o programa decidir. O formato básico é:

```
se condição então
  faça isto
senão
  faça aquilo
fimse
```

Quando há várias possibilidades, encadeamos `senão se`. O programa testa as condições de cima para baixo e executa **só o primeiro bloco** cuja condição for verdadeira.

### Prática
Classificando a nota de um aluno:

```
leia nota
se nota >= 7 então
  escreva "Aprovado"
senão se nota >= 5 então
  escreva "Recuperação"
senão
  escreva "Reprovado"
fimse
```

Em Python, a mesma ideia fica assim:

```python
nota = float(input("Nota: "))
if nota >= 7:
    print("Aprovado")
elif nota >= 5:
    print("Recuperação")
else:
    print("Reprovado")
```

### Questão
No exemplo da nota, o que aparece se `nota = 6`?
- [ ] Aprovado
- [x] Recuperação
- [ ] Reprovado
- [ ] Nada
> 6 não é maior ou igual a 7, mas é maior ou igual a 5, então o segundo bloco é executado.

### Questão
Num encadeamento `se / senão se / senão`, quantos blocos são executados?
- [ ] Todos cujas condições forem verdadeiras
- [x] Apenas o primeiro cuja condição for verdadeira (ou o senão)
- [ ] Sempre o último
- [ ] Nenhum
> As condições são testadas em ordem e, ao encontrar uma verdadeira, as demais são ignoradas.

### Questão
Qual condição verifica se alguém pode votar facultativamente no Brasil (16 ou 17 anos)?
- [ ] idade > 16 E idade < 17
- [x] idade >= 16 E idade <= 17
- [ ] idade >= 16 OU idade <= 17
- [ ] idade == 16 E idade == 17
> Precisamos das duas condições ao mesmo tempo: pelo menos 16 e no máximo 17.

## Laços de repetição
### Teoria
**Laços** repetem um bloco de código. Os dois principais:

- **enquanto (while):** repete enquanto uma condição for verdadeira. Use quando não sabe quantas vezes vai repetir.
- **para (for):** repete um número conhecido de vezes, com um contador.

Cuidado com o **laço infinito**: se a condição nunca ficar falsa, o programa nunca para. Sempre garanta que algo muda dentro do laço.

### Prática
Tabuada do 7 com `para`:

```
para i de 1 até 10 faça
  escreva "7 x ", i, " = ", 7 * i
fimpara
```

Somando números até o usuário digitar 0, com `enquanto`:

```
soma <- 0
leia n
enquanto n != 0 faça
  soma <- soma + n
  leia n
fimenquanto
escreva soma
```

O `leia n` dentro do laço é o que permite que a condição fique falsa um dia.

### Questão
Quantas vezes o laço `para i de 1 até 10` executa?
- [ ] 9
- [x] 10
- [ ] 11
- [ ] Infinitas vezes
> O contador vai de 1 a 10, incluindo os dois extremos: 10 repetições.

### Questão
Qual laço é mais indicado para "repetir até o usuário digitar sair"?
- [x] enquanto, porque não sabemos quantas vezes vai repetir
- [ ] para, porque sempre sabemos o número de repetições
- [ ] nenhum, isso não precisa de laço
- [ ] se/senão
> Quando a quantidade de repetições depende de algo que só se descobre na hora, usamos enquanto.

### Questão
O que causa um laço infinito?
- [ ] Usar o laço para
- [x] Uma condição de parada que nunca se torna falsa
- [ ] Ter mais de uma linha dentro do laço
- [ ] Usar variáveis
> Se nada dentro do laço altera a condição, ela continua verdadeira para sempre.

## Contadores e acumuladores
### Teoria
Dois padrões aparecem em quase todo programa com laço:

- **Contador:** variável que soma 1 a cada vez que algo acontece (`cont <- cont + 1`). Serve para contar.
- **Acumulador:** variável que soma valores variados (`total <- total + preco`). Serve para totalizar.

Ambos precisam ser **inicializados antes do laço**, normalmente com 0. Para encontrar o maior valor, guarde o primeiro e troque sempre que aparecer um maior.

### Prática
Lendo 5 idades e calculando a média e a maior:

```
total <- 0
maior <- 0
para i de 1 até 5 faça
  leia idade
  total <- total + idade
  se idade > maior então
    maior <- idade
  fimse
fimpara
escreva "Média: ", total / 5
escreva "Maior: ", maior
```

### Questão
Por que inicializamos o acumulador com 0 antes do laço?
- [ ] Porque o computador exige que toda variável seja 0
- [x] Para que a primeira soma parta de um valor conhecido
- [ ] Para o laço ficar mais rápido
- [ ] Não é necessário
> Sem valor inicial, a primeira soma usaria um valor indefinido e o resultado ficaria errado.

### Questão
Qual linha é um **contador**?
- [ ] total <- total + preco
- [x] aprovados <- aprovados + 1
- [ ] media <- total / n
- [ ] maior <- idade
> O contador sempre soma 1. O acumulador soma valores variados.

### Questão
Num laço que soma `1 + 2 + 3 + 4`, qual o valor final do acumulador iniciado em 0?
- [ ] 4
- [ ] 9
- [x] 10
- [ ] 24
> 0 + 1 + 2 + 3 + 4 = 10.

# Estruturas de dados e funções
> Organizar dados em listas e dividir problemas grandes em funções.

## Projeto
**Boletim da turma:** guarde em vetores os nomes e as notas de 5 alunos. Crie funções para calcular a média da turma, encontrar a maior nota e listar quem ficou abaixo da média. Desafio extra: ordene os alunos da maior para a menor nota usando o algoritmo de ordenação da última lição.

## Vetores e listas
### Teoria
Um **vetor** (ou lista) guarda vários valores numa única variável, acessados por **índice**. Na maioria das linguagens, o primeiro índice é **0**.

```
frutas <- ["maçã", "banana", "uva"]
// frutas[0] é "maçã", frutas[2] é "uva"
```

Vetores combinam muito bem com laços: percorremos todos os elementos usando o índice como contador. O tamanho do vetor diz quantos elementos ele tem.

### Prática
Somando os elementos de um vetor:

```python
notas = [8.5, 6.0, 9.0, 7.5]
soma = 0
for i in range(len(notas)):
    soma = soma + notas[i]
print("Média:", soma / len(notas))
```

Em Python ainda dá para percorrer direto os valores: `for nota in notas:`.

### Questão
No vetor `v <- [10, 20, 30, 40]`, quanto vale `v[1]`?
- [ ] 10
- [x] 20
- [ ] 30
- [ ] 40
> Os índices começam em 0, então v[0] = 10 e v[1] = 20.

### Questão
Qual o índice do último elemento de um vetor com 5 elementos?
- [ ] 5
- [x] 4
- [ ] 6
- [ ] 0
> Com índices de 0 a 4, o último é o 4 (tamanho - 1).

### Questão
Por que vetores são úteis?
- [ ] Porque deixam o programa mais lento
- [x] Porque guardam muitos valores relacionados sob um único nome
- [ ] Porque só aceitam números
- [ ] Porque substituem os laços
> Em vez de nota1, nota2, nota3..., usamos um único vetor de notas e percorremos com um laço.

## Funções
### Teoria
Uma **função** é um bloco de código com nome, que pode receber **parâmetros** e **retornar** um resultado. Ela evita repetição e organiza o programa em partes menores.

- **Parâmetros:** os valores que a função recebe.
- **Retorno:** o valor que a função devolve para quem a chamou.
- Variáveis criadas dentro da função são **locais**: só existem lá dentro.

Uma boa função faz uma coisa só, e o nome diz o que ela faz.

### Prática
```python
def area_retangulo(base, altura):
    return base * altura

def eh_par(n):
    return n % 2 == 0

print(area_retangulo(3, 4))  # 12
print(eh_par(7))             # False
```

Chamamos `area_retangulo` quantas vezes quisermos, com valores diferentes, sem reescrever a conta.

### Questão
O que a palavra `return` faz numa função?
- [ ] Repete a função
- [x] Devolve um valor para quem chamou a função
- [ ] Encerra o programa inteiro
- [ ] Imprime o valor na tela
> return devolve o resultado. Mostrar na tela é trabalho do print/escreva.

### Questão
Em `def soma(a, b):`, o que são `a` e `b`?
- [ ] Variáveis globais
- [x] Parâmetros da função
- [ ] Valores de retorno
- [ ] Constantes
> Parâmetros são os valores que a função recebe quando é chamada.

### Questão
Qual a principal vantagem de usar funções?
- [x] Reaproveitar código e dividir o problema em partes menores
- [ ] Deixar o código mais longo
- [ ] Evitar o uso de variáveis
- [ ] Fazer o programa rodar sem computador
> Funções evitam repetição e deixam cada parte do programa mais fácil de entender e testar.

## Busca e ordenação
### Teoria
Dois problemas clássicos:

**Busca linear:** percorrer o vetor do início ao fim até achar o valor. Simples, mas no pior caso olha todos os elementos.

**Busca binária:** funciona em vetores **ordenados**. Olha o meio; se o valor procurado for menor, descarta a metade da direita, senão a da esquerda. Muito mais rápida em listas grandes.

**Ordenação por bolha (bubble sort):** compara vizinhos e troca se estiverem fora de ordem, repetindo até não haver trocas. Fácil de entender, mas lenta para muitos dados. Linguagens modernas já trazem ordenações eficientes prontas.

### Prática
```python
def busca_linear(lista, alvo):
    for i in range(len(lista)):
        if lista[i] == alvo:
            return i
    return -1

def bolha(lista):
    n = len(lista)
    for i in range(n):
        for j in range(n - 1 - i):
            if lista[j] > lista[j + 1]:
                lista[j], lista[j + 1] = lista[j + 1], lista[j]
    return lista

print(bolha([5, 2, 9, 1]))  # [1, 2, 5, 9]
```

### Questão
Qual requisito a busca binária exige?
- [ ] Que o vetor tenha tamanho par
- [x] Que o vetor esteja ordenado
- [ ] Que os valores sejam textos
- [ ] Que o vetor tenha menos de 10 elementos
> A busca binária descarta metades com base na ordem dos elementos; sem ordenação ela não funciona.

### Questão
O que a função `busca_linear` devolve quando não encontra o valor?
- [ ] 0
- [x] -1
- [ ] O último índice
- [ ] Dá erro
> Retornar -1 é uma convenção para "não encontrado", já que nenhum índice válido é negativo.

### Questão
Como funciona a ordenação por bolha?
- [ ] Escolhe um elemento aleatório a cada passo
- [x] Compara elementos vizinhos e troca os que estão fora de ordem
- [ ] Divide a lista ao meio repetidamente
- [ ] Ordena apenas o primeiro e o último elemento
> A cada passada, os maiores valores "sobem" para o fim da lista, como bolhas.
