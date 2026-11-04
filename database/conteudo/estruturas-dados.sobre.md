@historia
Muitos algoritmos que usamos hoje são anteriores aos computadores: Euclides descreveu o MDC por volta de 300 a.C., e a ideia de busca binária aparece em tabelas antigas. Com os computadores, a área ganhou corpo: o **merge sort** foi descrito por **John von Neumann** em 1945; a **pilha** foi formalizada por Friedrich Bauer e Klaus Samelson (anos 1950); **Tony Hoare** inventou o **quicksort** em 1959; **Edsger Dijkstra** publicou seu algoritmo de menor caminho em 1959, pensado em cerca de vinte minutos num café em Amsterdã.

Em 1968, **Donald Knuth** começou a publicar *The Art of Computer Programming*, que organizou a área e popularizou a análise de algoritmos. A notação Big-O veio da matemática (Paul Bachmann e Edmund Landau) e foi trazida para a computação por Knuth. O livro de **Cormen, Leiserson, Rivest e Stein** (1990) virou o texto padrão no mundo todo.

@hoje
Estruturas de dados estão em tudo: tabelas hash nos dicionários e caches, árvores B+ nos índices de bancos de dados, grafos em mapas, redes sociais e recomendações, heaps em escalonadores e filas de prioridade, filas em sistemas de mensagens. Em entrevistas técnicas de empresas de tecnologia, perguntas de algoritmos são muito comuns (plataformas como LeetCode e HackerRank treinam isso). E escolher a estrutura certa pode transformar um programa que leva horas em um que leva segundos.

@iniciante
- Medir a eficiência de algoritmos com a notação Big-O
- Comparar arrays e listas ligadas
- Usar pilhas e filas e reconhecer seus usos
- Escolher a estrutura certa para problemas simples

@intermediario
- Entender como funcionam as tabelas hash e as colisões
- Trabalhar com árvores binárias de busca, percursos e heaps
- Representar grafos com listas e matrizes de adjacência
- Percorrer grafos com BFS e DFS e conhecer Dijkstra

@avancado
- Escrever funções recursivas corretas
- Aplicar divisão e conquista com merge sort e quicksort
- Resolver problemas com programação dinâmica
- Reconhecer algoritmos gulosos e seguir um roteiro de resolução de problemas

@referencias
**Livros**
- *Entendendo Algoritmos* — Aditya Bhargava: a introdução mais amigável, toda ilustrada.
- *Algoritmos: Teoria e Prática* — Cormen, Leiserson, Rivest e Stein: a referência completa.
- *Estruturas de Dados e Seus Algoritmos* — Jayme Szwarcfiter e Lilian Markenzon: clássico brasileiro.
- *Cracking the Coding Interview* — Gayle Laakmann McDowell: preparação para entrevistas técnicas.

**Na internet**
- [VisuAlgo](https://visualgo.net/pt) — animações de estruturas de dados e algoritmos.
- [Big-O Cheat Sheet](https://www.bigocheatsheet.com) — tabela de complexidades.
- [OBI — Olimpíada Brasileira de Informática](https://olimpiada.ic.unicamp.br) — problemas para treinar.

@modulo 1
**A análise assintótica mede o que importa quando os dados crescem.** Cormen e colegas definem formalmente O (limite superior), Ω (limite inferior) e Θ (limite justo). Na prática, falamos em Big-O para o pior caso, mas também vale olhar o caso médio e o **custo amortizado**: o `append` de um array dinâmico às vezes custa O(n) (quando precisa copiar tudo), mas, como isso é raro, a média por operação é O(1).

As constantes também importam no mundo real. Uma lista ligada tem a mesma complexidade teórica de um array para percorrer os dados, mas costuma ser várias vezes mais lenta por causa dos saltos na memória, que desperdiçam o cache. Bjarne Stroustrup demonstrou isso numa palestra famosa comparando `vector` e `list`.

@fontes 1
- Cormen et al., *Algoritmos: Teoria e Prática*, capítulos 2 e 3 (crescimento de funções) e 17 (análise amortizada)
- Bhargava, *Entendendo Algoritmos*, capítulos 1 e 2
- Szwarcfiter e Markenzon, *Estruturas de Dados e Seus Algoritmos*, capítulos 1 e 2

@modulo 2
**A escolha da função hash e do balanceamento faz toda a diferença.** Uma função hash ruim concentra as chaves em poucas posições e transforma a busca O(1) em O(n). Linguagens como Python e Java usam funções hash com componentes aleatórios para que atacantes não consigam gerar colisões de propósito e travar servidores (ataques de *hash flooding*).

Árvores balanceadas garantem O(log n) mesmo no pior caso. Com 1 bilhão de chaves, uma árvore binária balanceada tem só cerca de 30 níveis; uma árvore B+ de banco de dados, com centenas de filhos por nó, tem 4 ou 5. Já em grafos, a escolha entre BFS e DFS depende da pergunta: BFS para distâncias, DFS para detectar ciclos e fazer ordenação topológica (como a ordem de instalar dependências).

@fontes 2
- Cormen et al., *Algoritmos: Teoria e Prática*, capítulos 11 (tabelas hash), 12 e 13 (árvores de busca) e 22 a 24 (grafos)
- Bhargava, *Entendendo Algoritmos*, capítulos 5 (hash), 6 (BFS) e 7 (Dijkstra)
- [VisuAlgo: BST, AVL e grafos](https://visualgo.net/pt/bst)

@modulo 3
**Escolher a estratégia é metade da solução.** Diante de um problema, pergunte: posso dividi-lo em partes independentes (divisão e conquista)? As partes se repetem (programação dinâmica)? Uma escolha local sempre leva ao ótimo (guloso)? Ou preciso explorar possibilidades (backtracking)?

Alguns problemas não têm solução eficiente conhecida: são os **NP-completos**, como o problema do caixeiro-viajante. Para eles, usamos aproximações e heurísticas. A pergunta "P = NP?" é um dos sete Problemas do Milênio, com prêmio de 1 milhão de dólares.

Para entrevistas, Gayle McDowell recomenda falar em voz alta enquanto resolve, começar pela força bruta, otimizar o gargalo e testar com casos extremos: lista vazia, um elemento, valores repetidos e números negativos.

@fontes 3
- Cormen et al., *Algoritmos: Teoria e Prática*, capítulos 15 (programação dinâmica), 16 (algoritmos gulosos) e 34 (NP-completude)
- McDowell, *Cracking the Coding Interview*, capítulos sobre a abordagem de problemas
- [Clay Mathematics Institute: P vs NP](https://www.claymath.org/millennium/p-vs-np/)
