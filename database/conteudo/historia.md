# Das máquinas de calcular ao computador
> Os pioneiros, as primeiras máquinas e o nascimento da computação.

## Projeto
**Linha do tempo ilustrada:** crie uma página HTML com uma linha do tempo de 10 marcos da história da computação, de Ada Lovelace até hoje, com imagem, data e um parágrafo explicando a importância de cada um. Compartilhe com seu iglu e peça para cada membro sugerir um marco extra.

## Ada Lovelace e a máquina analítica
### Teoria
No século XIX, **Charles Babbage** projetou a **Máquina Analítica**, um computador mecânico de uso geral, com "moinho" (processador), "armazém" (memória) e entrada por **cartões perfurados**, ideia inspirada nos teares de Jacquard. Ela nunca foi totalmente construída.

Em **1843**, **Ada Lovelace** traduziu um artigo sobre a máquina e acrescentou notas próprias, incluindo um **algoritmo para calcular os números de Bernoulli**. Por isso é considerada a **primeira programadora** da história.

Ada também percebeu algo visionário: a máquina poderia manipular **símbolos**, não só números, e um dia compor música. A linguagem **Ada** (1980) foi batizada em sua homenagem.

### Prática
Reflita sobre a ideia de Ada com um exemplo atual: o mesmo processador que calcula sua média também mostra imagens, toca música e roda jogos. Tudo isso é a manipulação de símbolos (bits) que ela imaginou em 1843.

Para pesquisar: procure uma imagem do "Diagrama da Nota G" de Ada Lovelace, considerado o primeiro programa publicado.

### Questão
Quem é considerada a primeira programadora da história?
- [ ] Grace Hopper
- [x] Ada Lovelace
- [ ] Marie Curie
- [ ] Hedy Lamarr
> Suas notas de 1843 descrevem um algoritmo para a Máquina Analítica.

### Questão
Qual tecnologia de entrada a Máquina Analítica usaria?
- [ ] Teclado
- [x] Cartões perfurados
- [ ] Mouse
- [ ] Fita magnética
> A ideia veio dos teares de Jacquard.

### Questão
Qual foi a visão de Ada sobre a máquina que ia além dos cálculos?
- [ ] Que ela substituiria os humanos
- [x] Que ela poderia manipular símbolos e até compor música
- [ ] Que ela seria movida a vapor para sempre
- [ ] Que só serviria para astronomia
> Ela antecipou a computação de propósito geral.

## Turing e os fundamentos teóricos
### Teoria
Em **1936**, o matemático **Alan Turing** descreveu a **Máquina de Turing**, um modelo teórico simples (uma fita infinita, um cabeçote que lê e escreve e um conjunto de regras) capaz de executar **qualquer algoritmo**. Esse conceito define o que é "computável" até hoje.

Uma linguagem ou máquina capaz de simular uma Máquina de Turing é chamada **Turing-completa**: Python, JavaScript e até o Excel com fórmulas são!

Durante a Segunda Guerra Mundial, Turing ajudou a quebrar a criptografia da máquina alemã **Enigma** em Bletchley Park. Em 1950, propôs o **Teste de Turing** para discutir se máquinas podem pensar. O **Prêmio Turing** é considerado o "Nobel da computação".

### Prática
Simule uma Máquina de Turing que soma 1 a um número em unário (ex.: `111` = 3):

```
estado A: lê 1 → escreve 1, anda à direita, fica em A
estado A: lê vazio → escreve 1, para
fita inicial: 1 1 1 _
fita final:   1 1 1 1   (4)
```

Com poucas regras, a máquina transforma a entrada: essa é a essência de um algoritmo.

### Questão
O que a Máquina de Turing define?
- [ ] O primeiro computador físico
- [x] Um modelo teórico do que pode ser computado por um algoritmo
- [ ] Um tipo de memória RAM
- [ ] Uma linguagem de programação
> É um modelo matemático, não uma máquina construída.

### Questão
O que significa uma linguagem ser Turing-completa?
- [ ] Que foi criada por Turing
- [x] Que consegue expressar qualquer algoritmo computável
- [ ] Que não tem erros
- [ ] Que roda sem computador
> Com memória suficiente, ela pode simular qualquer Máquina de Turing.

### Questão
Qual sistema de criptografia Turing ajudou a quebrar?
- [ ] RSA
- [x] Enigma
- [ ] AES
- [ ] Morse
> O trabalho em Bletchley Park encurtou a guerra.

## Os primeiros computadores eletrônicos
### Teoria
Nos anos 1940 surgiram os primeiros computadores eletrônicos:
- **Colossus** (1943, Reino Unido): decifrava mensagens alemãs.
- **ENIAC** (1945, EUA): pesava cerca de 30 toneladas, usava ~18 mil válvulas e era programado religando cabos. Seis mulheres, entre elas **Jean Bartik** e **Kathleen McNulty**, foram suas primeiras programadoras.

**John von Neumann** descreveu a arquitetura com **programa armazenado na memória**, junto com os dados: a base de quase todos os computadores atuais.

Depois vieram o **transistor** (1947), que substituiu as válvulas, e o **circuito integrado** (1958), que colocou vários transistores num chip. A **Lei de Moore** (1965) previu que o número de transistores num chip dobraria a cada ~2 anos.

### Prática
Compare as gerações:

```
Válvulas (1940s)    → salas inteiras, muito calor e falhas
Transistores (1950s)→ menores, mais confiáveis
Circuitos integrados→ milhares de transistores num chip
Microprocessadores  → um computador inteiro num chip (Intel 4004, 1971)
Hoje                → bilhões de transistores no seu celular
```

### Questão
Qual a principal ideia da arquitetura de von Neumann?
- [ ] Usar apenas válvulas
- [x] Guardar o programa na memória junto com os dados
- [ ] Programar religando cabos
- [ ] Eliminar a memória
> Isso permitiu trocar de programa sem reconstruir a máquina.

### Questão
Qual componente substituiu as válvulas e permitiu computadores menores?
- [ ] O disco rígido
- [x] O transistor
- [ ] O mouse
- [ ] A fibra óptica
> O transistor (1947) é menor, mais rápido e mais confiável.

### Questão
O que a Lei de Moore previu?
- [ ] Que os computadores ficariam mais caros
- [x] Que o número de transistores num chip dobraria a cada cerca de dois anos
- [ ] Que a internet surgiria em 1990
- [ ] Que as válvulas voltariam
> A previsão guiou a indústria por décadas.

# A evolução das linguagens
> Do código de máquina às linguagens de alto nível e paradigmas.

## Projeto
**Olá, mundo através do tempo:** escreva (ou pesquise) o "Olá, mundo" em Assembly, FORTRAN, COBOL, C, Java, Python e JavaScript. Monte um quadro comparativo com ano de criação, criador, paradigma e onde a linguagem é usada hoje.

## Do código de máquina ao compilador
### Teoria
No início, programava-se em **código de máquina**: sequências de 0 e 1 específicas de cada processador. Depois veio o **Assembly**, que troca números por mnemônicos (`MOV`, `ADD`), traduzidos por um montador.

**Grace Hopper** criou um dos primeiros **compiladores** (A-0, 1952) e defendeu linguagens próximas do inglês, o que levou ao **COBOL** (1959), usado até hoje em bancos. Ela também popularizou o termo **"bug"** após encontrar uma mariposa num relé do computador Mark II.

O **FORTRAN** (1957, John Backus/IBM) foi a primeira linguagem de alto nível amplamente usada, voltada a cálculos científicos.

### Prática
A mesma ideia em três níveis:

```
Código de máquina:  10110000 01100001
Assembly:           MOV AL, 61h
Alto nível (C):     char letra = 'a';
```

Quanto mais alto o nível, mais fácil para humanos e mais trabalho para o compilador.

### Questão
Quem criou um dos primeiros compiladores e influenciou o COBOL?
- [ ] Ada Lovelace
- [x] Grace Hopper
- [ ] Alan Turing
- [ ] Linus Torvalds
> Grace Hopper defendia linguagens próximas da linguagem humana.

### Questão
O que um compilador faz?
- [ ] Desenha a interface
- [x] Traduz código de alto nível para código que a máquina executa
- [ ] Guarda arquivos na nuvem
- [ ] Corrige erros de lógica automaticamente
> Ele converte o programa inteiro antes da execução.

### Questão
Qual linguagem de 1957 foi pioneira em cálculos científicos?
- [x] FORTRAN
- [ ] COBOL
- [ ] Python
- [ ] HTML
> FORmula TRANslation, criada na IBM.

## C, Unix e a programação estruturada
### Teoria
Nos anos 1970, nos Laboratórios Bell, **Ken Thompson** e **Dennis Ritchie** criaram o sistema operacional **Unix** e a linguagem **C** (1972). C era próxima do hardware, mas portável, e o Unix foi reescrito nela, podendo rodar em vários computadores. A filosofia Unix ("faça uma coisa e faça bem") influencia até hoje Linux, macOS e Android.

Na mesma época cresceu a **programação estruturada**: o cientista **Edsger Dijkstra** criticou o uso do `goto` e defendeu blocos, decisões e laços bem definidos, como fazemos hoje.

**Linus Torvalds** criou o kernel **Linux** em 1991, inspirado no Unix, como projeto de código aberto.

### Prática
O livro *"The C Programming Language"* (1978), de Kernighan e Ritchie, popularizou o famoso primeiro programa:

```c
#include <stdio.h>

int main(void) {
    printf("hello, world\n");
    return 0;
}
```

Desde então, quase todo tutorial começa com um "Olá, mundo".

### Questão
Qual sistema operacional foi reescrito em C, tornando-se portável?
- [ ] Windows
- [x] Unix
- [ ] MS-DOS
- [ ] Android
> Unix e C nasceram juntos nos Laboratórios Bell.

### Questão
O que a programação estruturada desencoraja?
- [ ] O uso de funções
- [x] O uso indiscriminado do goto
- [ ] O uso de laços
- [ ] O uso de variáveis
> Dijkstra defendeu fluxos claros com blocos, decisões e laços.

### Questão
Quem criou o kernel Linux?
- [ ] Dennis Ritchie
- [ ] Bill Gates
- [x] Linus Torvalds
- [ ] Steve Jobs
> Em 1991, como projeto de código aberto.

## Paradigmas e linguagens modernas
### Teoria
**Paradigmas** são estilos de programar:
- **Imperativo/procedural:** sequência de comandos (C).
- **Orientado a objetos:** objetos com dados e comportamentos. Nasceu com **Simula** (1967) e **Smalltalk** (Alan Kay, 1970s), e se popularizou com **C++** (1985) e **Java** (1995).
- **Funcional:** funções puras e imutabilidade (Lisp, 1958; Haskell; recursos em JavaScript e Python).
- **Declarativo:** descreve o "quê", não o "como" (SQL, HTML).

Nos anos 1990 surgiram **Python** (1991, Guido van Rossum), **JavaScript** (1995, Brendan Eich, criado em 10 dias), **PHP** (1995) e **Ruby** (1995). Nos 2000s e 2010s: **C#**, **Go**, **Rust**, **Swift**, **Kotlin** e **TypeScript**, focadas em segurança, produtividade e concorrência.

### Prática
O mesmo problema (somar os números pares de uma lista) em dois paradigmas, ambos em Python:

```python
# Imperativo
total = 0
for n in [1, 2, 3, 4]:
    if n % 2 == 0:
        total += n

# Funcional
total = sum(filter(lambda n: n % 2 == 0, [1, 2, 3, 4]))
```

Linguagens modernas costumam ser **multiparadigma**.

### Questão
Qual paradigma descreve "o que" se quer, e não "como" fazer, como no SQL?
- [ ] Imperativo
- [x] Declarativo
- [ ] Procedural
- [ ] Estruturado
> SQL descreve o resultado desejado; o banco decide como obtê-lo.

### Questão
Em quanto tempo, segundo a história, a primeira versão do JavaScript foi criada?
- [ ] 10 anos
- [ ] 1 ano
- [x] 10 dias
- [ ] 10 horas
> Brendan Eich criou o protótipo na Netscape em 1995.

### Questão
Quem criou o Python?
- [ ] James Gosling
- [x] Guido van Rossum
- [ ] Bjarne Stroustrup
- [ ] Rasmus Lerdorf
> Python foi lançado em 1991.

# Da internet à inteligência artificial
> Redes, web, software livre, mobile, nuvem e IA.

## Projeto
**Podcast ou vídeo de 5 minutos:** com seu iglu, escolham um marco (criação da web, software livre, iPhone, ChatGPT...) e produzam um episódio explicando o contexto, as pessoas envolvidas e o impacto na vida de hoje.

## A internet e a World Wide Web
### Teoria
A **ARPANET** (1969), projeto do Departamento de Defesa dos EUA, conectou as primeiras universidades e deu origem à internet. Em 1974, **Vint Cerf** e **Bob Kahn** criaram o **TCP/IP**, os protocolos que permitem que redes diferentes conversem.

Em **1989**, **Tim Berners-Lee**, no CERN, inventou a **World Wide Web**: páginas em **HTML**, endereços **URL** e o protocolo **HTTP**. Ele decidiu não patentear a ideia, e a web se espalhou livremente.

Internet e web não são a mesma coisa: a **internet** é a infraestrutura de redes; a **web** é um dos serviços que rodam sobre ela (como o email).

### Prática
Quando você digita um endereço no navegador, várias invenções históricas trabalham juntas:

```
1. DNS (1983) traduz technopenguin.com num endereço IP
2. TCP/IP (1974) leva os pacotes pela internet
3. HTTP (1989/1991) pede a página ao servidor
4. HTML (1991) descreve o conteúdo exibido
```

### Questão
Quem inventou a World Wide Web?
- [ ] Bill Gates
- [x] Tim Berners-Lee
- [ ] Vint Cerf
- [ ] Mark Zuckerberg
> Em 1989, no CERN.

### Questão
Qual a diferença entre internet e web?
- [ ] São a mesma coisa
- [x] A internet é a infraestrutura de redes; a web é um serviço que roda sobre ela
- [ ] A web é mais antiga que a internet
- [ ] A internet só existe em celulares
> Email, jogos online e streaming também usam a internet.

### Questão
Qual projeto deu origem à internet?
- [ ] Facebook
- [x] ARPANET
- [ ] CERN
- [ ] IBM PC
> A ARPANET conectou os primeiros computadores em 1969.

## Software livre e o computador pessoal
### Teoria
Nos anos 1970 e 1980, o computador saiu das empresas e chegou às casas: **Apple II** (1977), **IBM PC** (1981) e o **Macintosh** (1984), que popularizou a interface gráfica com janelas e mouse, ideias vindas do centro de pesquisa **Xerox PARC**. A **Microsoft** dominou com o MS-DOS e o Windows.

Em **1983**, **Richard Stallman** lançou o projeto **GNU** e o movimento do **software livre**, defendendo as liberdades de usar, estudar, modificar e compartilhar programas. Com o kernel **Linux** (1991), surgiu um sistema operacional livre completo.

O **código aberto** sustenta hoje boa parte da tecnologia: servidores, Android, navegadores e quase todas as linguagens. O **GitHub** (2008) tornou a colaboração global ainda mais fácil.

### Prática
Ferramentas livres ou de código aberto que você provavelmente usa (ou vai usar):

```
Linux, Android (baseado em Linux)
Firefox, Chromium
Python, PHP, Node.js, MySQL/MariaDB, Apache (o XAMPP é todo assim!)
VS Code (núcleo aberto), Git
```

Pesquise a licença de um projeto que você usa (arquivo LICENSE no repositório).

### Questão
Quem fundou o movimento do software livre e o projeto GNU?
- [ ] Linus Torvalds
- [x] Richard Stallman
- [ ] Steve Wozniak
- [ ] Tim Cook
> Stallman lançou o GNU em 1983.

### Questão
De onde vieram muitas ideias de interface gráfica popularizadas pelo Macintosh?
- [ ] Do CERN
- [x] Do Xerox PARC
- [ ] Da NASA
- [ ] Da ARPANET
> Janelas, ícones e mouse foram pesquisados no PARC.

### Questão
Qual destas liberdades faz parte da definição de software livre?
- [ ] Usar sem nunca ver o código
- [x] Estudar e modificar o programa
- [ ] Proibir cópias
- [ ] Pagar por cada execução
> Usar, estudar, modificar e compartilhar.

## Mobile, nuvem e inteligência artificial
### Teoria
O **iPhone** (2007) e o **Android** (2008) colocaram computadores poderosos no bolso e criaram a economia dos **apps**. Ao mesmo tempo, a **computação em nuvem** (AWS, 2006) permitiu alugar servidores sob demanda.

A **inteligência artificial** tem uma longa história: o termo surgiu em **1956** (conferência de Dartmouth). Houve fases de euforia e "invernos". O **aprendizado de máquina** e as **redes neurais profundas** decolaram nos anos 2010, com mais dados e GPUs.

Em 2017, a arquitetura **Transformer** abriu caminho para os **grandes modelos de linguagem (LLMs)**, como o ChatGPT (2022) e o Claude, que geram texto e código e mudaram a forma de programar. Hoje, saber usar IA de forma crítica e responsável é uma habilidade essencial de quem programa.

### Prática
Linha do tempo da IA:

```
1950  Teste de Turing
1956  Termo "inteligência artificial" (Dartmouth)
1997  Deep Blue vence Kasparov no xadrez
2012  Redes neurais profundas vencem o ImageNet
2016  AlphaGo vence no Go
2017  Arquitetura Transformer
2022+ Assistentes baseados em LLMs se popularizam
```

### Questão
Em que ano surgiu o termo "inteligência artificial"?
- [ ] 1990
- [x] 1956
- [ ] 2010
- [ ] 1843
> Na conferência de Dartmouth.

### Questão
Qual arquitetura de 2017 abriu caminho para os grandes modelos de linguagem?
- [ ] ENIAC
- [x] Transformer
- [ ] Von Neumann
- [ ] TCP/IP
> O artigo "Attention Is All You Need" apresentou o Transformer.

### Questão
O que impulsionou o avanço recente do aprendizado de máquina?
- [ ] O fim da internet
- [x] Mais dados disponíveis e GPUs mais poderosas
- [ ] A volta das válvulas
- [ ] Computadores menos potentes
> Dados e poder de processamento permitiram treinar redes profundas.
