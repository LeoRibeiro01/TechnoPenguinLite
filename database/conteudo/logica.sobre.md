@historia
A ideia de **algoritmo** é muito mais antiga que o computador. O nome vem do matemático persa **al-Khwarizmi** (século IX), cujos livros ensinavam procedimentos passo a passo para fazer contas. Euclides já descrevia, por volta de 300 a.C., um algoritmo para achar o maior divisor comum de dois números, ainda ensinado hoje.

No século XX, **Alan Turing** e **Alonzo Church** formalizaram o que significa "computar". Nos anos 1940 e 1950 surgiram os **fluxogramas**, desenhos com caixas e setas para planejar programas antes de escrevê-los. Nos anos 1970, **Edsger Dijkstra** e **Niklaus Wirth** defenderam a **programação estruturada**: todo programa pode ser escrito só com sequência, decisão e repetição. No Brasil, o ensino de lógica ganhou o **Portugol**, um pseudocódigo em português usado até hoje em escolas e faculdades.

@hoje
Lógica de programação é a base comum de todas as linguagens. Quem domina variáveis, condições, laços e funções aprende qualquer linguagem nova muito mais rápido, porque só precisa aprender a sintaxe. Ela também aparece em entrevistas de emprego, olimpíadas de programação, automação de planilhas e até no pensamento do dia a dia, quando dividimos um problema grande em passos menores.

@iniciante
- Entender o que é um algoritmo e descrever soluções passo a passo
- Usar variáveis, tipos de dados e operadores
- Montar expressões aritméticas, relacionais e lógicas
- Ler e escrever pseudocódigo e o equivalente em Python

@intermediario
- Tomar decisões com se/senão e condições compostas
- Repetir tarefas com laços "para" e "enquanto" sem cair em laço infinito
- Usar contadores, acumuladores e encontrar maior e menor valor
- Escrever programas completos como jogos de adivinhação

@avancado
- Organizar dados em vetores e percorrê-los com laços
- Dividir problemas em funções com parâmetros e retorno
- Implementar busca linear, busca binária e ordenação
- Comparar a eficiência de algoritmos diferentes para o mesmo problema

@referencias
**Livros**
- *Lógica de Programação* — André Forbellone e Henri Eberspächer: o clássico brasileiro, com pseudocódigo em português.
- *Entendendo Algoritmos* — Aditya Bhargava: algoritmos explicados com desenhos, ótimo para iniciantes.
- *Pense em Python* — Allen B. Downey: aprende a pensar como cientista da computação usando Python (gratuito em inglês como *Think Python*).
- *Algoritmos: Teoria e Prática* — Cormen, Leiserson, Rivest e Stein (CLRS): a referência para quem quiser se aprofundar.

**Na internet**
- [Portugol Webstudio](https://portugol.dev) — escreva e rode pseudocódigo em português no navegador.
- [Python Tutor](https://pythontutor.com) — veja seu código executar passo a passo, variável por variável.
- [CS50 de Harvard](https://cs50.harvard.edu/x) — curso gratuito e famoso de introdução à computação.

@modulo 1
**Algoritmos precisam ser não ambíguos.** No livro *The Art of Computer Programming*, Donald Knuth define cinco propriedades de um algoritmo: **finitude** (sempre termina), **definição** (cada passo é preciso), **entrada**, **saída** e **efetividade** (cada passo pode ser feito de verdade). "Tempere a gosto" numa receita não é definido; "adicione 5 g de sal" é.

Outra ideia central é o **estado**: o conjunto de valores das variáveis em cada momento. Rastrear o estado à mão, com uma tabela linha a linha (o "teste de mesa"), é a técnica mais usada para encontrar erros de lógica. Ferramentas como o Python Tutor fazem exatamente isso de forma visual.

Por fim, lembre que computadores representam tudo em **binário**. Por isso números decimais como 0,1 não são exatos em float: `0.1 + 0.2` dá `0.30000000000000004` em quase todas as linguagens.

@fontes 1
- Knuth, *The Art of Computer Programming*, vol. 1, seção 1.1 (as cinco propriedades de um algoritmo)
- Forbellone e Eberspächer, *Lógica de Programação*, capítulos 1 e 2
- [What Every Programmer Should Know About Floating-Point](https://floating-point-gui.de)

@modulo 2
**Toda lógica pode ser escrita com três estruturas.** O teorema de Böhm-Jacopini (1966) prova que sequência, seleção (se) e repetição (enquanto) bastam para expressar qualquer algoritmo. Foi a base do famoso texto de Dijkstra "*Go To Statement Considered Harmful*" (1968), que mudou o jeito de programar.

Para escrever laços corretos, pense em três perguntas: **onde começa** (inicialização), **quando para** (condição) e **o que muda a cada volta** (atualização). Um erro clássico é o **erro de um a mais** (off-by-one): repetir uma vez a mais ou a menos. Teste sempre os casos extremos: lista vazia, um elemento, o primeiro e o último.

Em condições compostas, as **leis de De Morgan** ajudam a simplificar: `não (A e B)` é o mesmo que `(não A) ou (não B)`.

@fontes 2
- Dijkstra, "Go To Statement Considered Harmful" (Communications of the ACM, 1968)
- Downey, *Pense em Python*, capítulos 5 (condicionais) e 7 (iteração)
- Forbellone e Eberspächer, *Lógica de Programação*, capítulo 3 (estruturas de controle)

@modulo 3
**Eficiência importa.** A notação **Big-O** descreve como o tempo de um algoritmo cresce com o tamanho da entrada. A busca linear é O(n): com 1 milhão de itens, até 1 milhão de comparações. A busca binária é O(log n): cerca de 20 comparações para 1 milhão! A ordenação por bolha é O(n²); algoritmos como **merge sort** e **quicksort** são O(n log n), por isso são os usados na prática.

Funções bem escritas seguem o princípio da **responsabilidade única**: cada função faz uma coisa. Uma função que chama a si mesma é **recursiva**, útil para problemas que se dividem em partes menores iguais ao problema original (como o fatorial ou percorrer pastas).

Estruturas de dados e algoritmos andam juntos. Como escreveu Niklaus Wirth no título de seu livro: *Algoritmos + Estruturas de Dados = Programas*.

@fontes 3
- Bhargava, *Entendendo Algoritmos*, capítulos 1 (busca binária e Big-O) e 4 (quicksort)
- Wirth, *Algorithms + Data Structures = Programs* (1976)
- Cormen et al., *Algoritmos: Teoria e Prática*, capítulos 2 e 3
