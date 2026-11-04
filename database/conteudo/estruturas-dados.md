# Medindo e organizando dados
> Complexidade, arrays, listas ligadas, pilhas e filas.

## Projeto
**Desfazer e refazer:** implemente um editor de texto de linha de comando com os comandos `escrever`, `apagar`, `desfazer` e `refazer`, usando duas pilhas. Depois adicione uma fila de impressão. Meça quanto tempo cada operação leva com 100 mil ações e explique os resultados com Big-O.

## Complexidade e notação Big-O
### Teoria
Dois algoritmos podem resolver o mesmo problema com eficiência muito diferente. A **notação Big-O** descreve como o tempo (ou a memória) cresce quando a entrada cresce, ignorando constantes:

- **O(1)** constante: acessar `lista[5]`.
- **O(log n)** logarítmico: busca binária.
- **O(n)** linear: percorrer uma lista.
- **O(n log n)**: as boas ordenações (merge sort).
- **O(n²)** quadrático: dois laços aninhados sobre os mesmos dados.

Analisamos normalmente o **pior caso**. Regras práticas: laços em sequência somam (O(n) + O(n) = O(n)); laços aninhados multiplicam (O(n) × O(n) = O(n²)).

### Prática
```python
def tem_repetido_lento(lista):          # O(n²)
    for i in range(len(lista)):
        for j in range(i + 1, len(lista)):
            if lista[i] == lista[j]:
                return True
    return False

def tem_repetido_rapido(lista):         # O(n), usando um conjunto
    vistos = set()
    for item in lista:
        if item in vistos:
            return True
        vistos.add(item)
    return False
```

Com 100 mil itens, a primeira versão faz cerca de 5 bilhões de comparações; a segunda, 100 mil.

### Questão
Qual a complexidade de dois laços aninhados que percorrem a mesma lista de n itens?
- [ ] O(n)
- [x] O(n²)
- [ ] O(log n)
- [ ] O(1)
> Para cada um dos n itens, o laço interno percorre até n itens.

### Questão
O que a notação Big-O descreve?
- [ ] O tempo exato em segundos
- [x] Como o custo cresce em relação ao tamanho da entrada
- [ ] A quantidade de linhas de código
- [ ] A linguagem mais rápida
> Ela compara algoritmos independentemente de máquina e linguagem.

### Questão
Qual é a complexidade de acessar um elemento de um array pelo índice?
- [x] O(1)
- [ ] O(n)
- [ ] O(n²)
- [ ] O(log n)
> O endereço é calculado diretamente: início + índice × tamanho do elemento.

## Arrays e listas ligadas
### Teoria
Um **array** guarda elementos em memória **contígua**: acesso por índice é O(1), mas inserir no meio exige deslocar todos os seguintes, O(n). Arrays dinâmicos (como `list` em Python ou `ArrayList` em Java) dobram de tamanho quando enchem, e o custo médio do `append` fica O(1) (*custo amortizado*).

Uma **lista ligada** guarda cada elemento num **nó** com um ponteiro para o próximo. Inserir ou remover no início é O(1), mas acessar o 500º elemento exige percorrer 500 nós: O(n). Listas **duplamente ligadas** também apontam para o anterior.

Na prática, arrays costumam ser mais rápidos por aproveitarem o **cache** do processador; listas ligadas brilham quando há muitas inserções e remoções em posições já conhecidas.

### Prática
```python
class No:
    def __init__(self, valor, proximo=None):
        self.valor = valor
        self.proximo = proximo

class ListaLigada:
    def __init__(self):
        self.inicio = None

    def inserir_no_inicio(self, valor):     # O(1)
        self.inicio = No(valor, self.inicio)

    def contem(self, valor):                # O(n)
        atual = self.inicio
        while atual:
            if atual.valor == valor:
                return True
            atual = atual.proximo
        return False
```

### Questão
Qual operação é O(1) numa lista ligada e O(n) num array?
- [ ] Acessar o elemento de índice 100
- [x] Inserir um elemento no início
- [ ] Percorrer todos os elementos
- [ ] Contar os elementos (sem contador guardado)
> No array, inserir no início desloca todos os elementos; na lista ligada, basta criar um nó.

### Questão
O que cada nó de uma lista ligada simples guarda?
- [ ] Só o valor
- [x] O valor e uma referência para o próximo nó
- [ ] O índice e o tamanho da lista
- [ ] Todos os outros nós
> Os nós formam uma corrente de referências.

### Questão
Por que arrays costumam ser mais rápidos na prática ao percorrer dados?
- [ ] Porque usam menos código
- [x] Porque a memória contígua aproveita melhor o cache do processador
- [ ] Porque não têm índices
- [ ] Porque são sempre menores
> Elementos vizinhos chegam juntos ao cache, reduzindo acessos à memória principal.

## Pilhas e filas
### Teoria
**Pilha** (*stack*) segue a regra **LIFO**: o último a entrar é o primeiro a sair. Operações: `push` (empilhar), `pop` (desempilhar) e `peek` (ver o topo). Usos: desfazer (Ctrl+Z), a pilha de chamadas de funções, verificar parênteses balanceados, navegação "voltar" do navegador.

**Fila** (*queue*) segue a regra **FIFO**: o primeiro a entrar é o primeiro a sair. Operações: `enqueue` (entrar no fim) e `dequeue` (sair do início). Usos: filas de impressão, atendimento, tarefas em segundo plano, busca em largura.

Ambas têm operações O(1) quando bem implementadas. Em Python, use `list` para pilhas e `collections.deque` para filas (`list.pop(0)` é O(n)!).

### Prática
```python
from collections import deque

def balanceado(expressao):
    pares = {')': '(', ']': '[', '}': '{'}
    pilha = []
    for c in expressao:
        if c in '([{':
            pilha.append(c)
        elif c in pares:
            if not pilha or pilha.pop() != pares[c]:
                return False
    return not pilha

print(balanceado("{[a + b] * (c)}"))  # True
print(balanceado("(]"))                # False

fila = deque(["Teco", "Pipo"])
fila.append("Ana")         # entra no fim
print(fila.popleft())      # Teco (sai do início)
```

### Questão
Qual estrutura segue a regra "o último a entrar é o primeiro a sair"?
- [x] Pilha
- [ ] Fila
- [ ] Árvore
- [ ] Grafo
> Pilha = LIFO; fila = FIFO.

### Questão
Qual estrutura é ideal para atender pedidos na ordem de chegada?
- [ ] Pilha
- [x] Fila
- [ ] Conjunto
- [ ] Pilha de chamadas
> Filas preservam a ordem de chegada (FIFO).

### Questão
Por que usar `deque` em vez de `list.pop(0)` para filas em Python?
- [ ] Porque list não existe
- [x] Porque remover do início de uma list é O(n), e no deque é O(1)
- [ ] Porque deque ordena os itens
- [ ] Não há diferença
> A list precisa deslocar todos os elementos ao remover o primeiro.

# Estruturas não lineares [intermediario]
> Tabelas hash, árvores, heaps e grafos.

## Projeto
**Mapa do campus:** represente os prédios da sua escola como um grafo com distâncias, encontre o menor caminho entre dois pontos com o algoritmo de Dijkstra e use uma tabela hash para buscar prédios pelo nome. Desafio: mostre a rota passo a passo.

## Tabelas hash
### Teoria
Uma **tabela hash** guarda pares chave-valor num array. Uma **função hash** transforma a chave num índice: `indice = hash(chave) % tamanho`. Busca, inserção e remoção são O(1) em média.

Quando duas chaves caem no mesmo índice há uma **colisão**. Soluções comuns:
- **Encadeamento:** cada posição guarda uma pequena lista.
- **Endereçamento aberto:** procura a próxima posição livre.

Quando a tabela enche demais (alto **fator de carga**), ela é redimensionada. Dicionários do Python, `HashMap` do Java e objetos/Map do JavaScript são tabelas hash. Chaves precisam ser **imutáveis** (ou ao menos não mudar enquanto estão na tabela).

### Prática
```python
class TabelaHash:
    def __init__(self, tamanho=8):
        self.baldes = [[] for _ in range(tamanho)]

    def _indice(self, chave):
        return hash(chave) % len(self.baldes)

    def colocar(self, chave, valor):
        balde = self.baldes[self._indice(chave)]
        for par in balde:
            if par[0] == chave:
                par[1] = valor
                return
        balde.append([chave, valor])

    def pegar(self, chave):
        for k, v in self.baldes[self._indice(chave)]:
            if k == chave:
                return v
        raise KeyError(chave)

xp = TabelaHash()
xp.colocar("teco", 120)
print(xp.pegar("teco"))
```

### Questão
Qual a complexidade média de busca numa tabela hash?
- [x] O(1)
- [ ] O(n)
- [ ] O(n²)
- [ ] O(n log n)
> A função hash leva direto à posição, na média.

### Questão
O que é uma colisão numa tabela hash?
- [ ] Um erro de sintaxe
- [x] Duas chaves diferentes mapeadas para o mesmo índice
- [ ] Duas tabelas com o mesmo nome
- [ ] Um valor nulo
> Colisões são normais e tratadas por encadeamento ou endereçamento aberto.

### Questão
Por que listas não podem ser chaves de dicionário em Python?
- [ ] Porque são lentas
- [x] Porque são mutáveis e seu hash poderia mudar
- [ ] Porque são muito grandes
- [ ] Porque não têm valores
> Se a chave mudasse, a tabela não a encontraria mais na posição certa.

## Árvores e heaps
### Teoria
Uma **árvore** tem um nó raiz e nós filhos, sem ciclos. Na **árvore binária de busca (BST)**, cada nó tem até dois filhos: à esquerda os menores, à direita os maiores. Busca, inserção e remoção são O(log n) se a árvore estiver **balanceada**; se ficar torta (inserindo dados ordenados), degenera para O(n). Árvores **AVL** e **rubro-negras** se rebalanceiam sozinhas.

Percursos: **em ordem** (esquerda, raiz, direita) devolve os valores ordenados; **pré-ordem** e **pós-ordem** são úteis para copiar e apagar árvores.

Um **heap** é uma árvore em que o pai é sempre menor (min-heap) ou maior (max-heap) que os filhos. Pegar o mínimo é O(1) e inserir ou remover é O(log n): é a base das **filas de prioridade**. Bancos de dados usam **árvores B+** nos índices.

### Prática
```python
import heapq

class NoArvore:
    def __init__(self, valor):
        self.valor, self.esq, self.dir = valor, None, None

def inserir(raiz, valor):
    if raiz is None:
        return NoArvore(valor)
    if valor < raiz.valor:
        raiz.esq = inserir(raiz.esq, valor)
    else:
        raiz.dir = inserir(raiz.dir, valor)
    return raiz

def em_ordem(raiz):
    return em_ordem(raiz.esq) + [raiz.valor] + em_ordem(raiz.dir) if raiz else []

raiz = None
for v in [50, 30, 70, 20, 40]:
    raiz = inserir(raiz, v)
print(em_ordem(raiz))           # [20, 30, 40, 50, 70]

tarefas = []
heapq.heappush(tarefas, (2, "estudar"))
heapq.heappush(tarefas, (1, "comer peixe"))
print(heapq.heappop(tarefas))   # (1, 'comer peixe')
```

### Questão
Que percurso de uma árvore binária de busca devolve os valores ordenados?
- [ ] Pré-ordem
- [x] Em ordem
- [ ] Pós-ordem
- [ ] Em largura
> Esquerda (menores), raiz, direita (maiores).

### Questão
O que acontece com uma BST se inserirmos valores já ordenados (1, 2, 3, 4...)?
- [ ] Ela fica perfeitamente balanceada
- [x] Ela vira praticamente uma lista e as operações ficam O(n)
- [ ] Ela apaga os valores
- [ ] Ela ordena ao contrário
> Por isso existem árvores autobalanceadas como AVL e rubro-negra.

### Questão
Qual estrutura é a base de uma fila de prioridade?
- [ ] Pilha
- [ ] Lista ligada
- [x] Heap
- [ ] Tabela hash
> O heap dá acesso rápido ao menor (ou maior) elemento.

## Grafos
### Teoria
Um **grafo** tem **vértices** (nós) e **arestas** (ligações). Pode ser **direcionado** (seguir alguém numa rede social) ou não (amizade), e **ponderado** (arestas com pesos, como distâncias).

Representações:
- **Lista de adjacência:** cada vértice guarda seus vizinhos. Econômica para grafos esparsos.
- **Matriz de adjacência:** tabela n × n. Rápida para saber se duas arestas existem, mas usa muita memória.

Percursos fundamentais:
- **Busca em largura (BFS):** usa uma fila e visita por camadas; encontra o menor caminho em número de arestas.
- **Busca em profundidade (DFS):** usa uma pilha (ou recursão) e vai fundo antes de voltar.

Com pesos, o **algoritmo de Dijkstra** (com um heap) encontra o menor caminho. Mapas, redes, recomendações e dependências de pacotes são grafos.

### Prática
```python
from collections import deque

amigos = {
    "teco": ["pipo", "ana"],
    "pipo": ["teco", "beto"],
    "ana": ["teco", "beto"],
    "beto": ["pipo", "ana", "caio"],
    "caio": ["beto"],
}

def distancia(grafo, origem, destino):
    fila = deque([(origem, 0)])
    visitados = {origem}
    while fila:
        atual, passos = fila.popleft()
        if atual == destino:
            return passos
        for vizinho in grafo[atual]:
            if vizinho not in visitados:
                visitados.add(vizinho)
                fila.append((vizinho, passos + 1))
    return None

print(distancia(amigos, "teco", "caio"))  # 3
```

### Questão
Qual algoritmo encontra o menor caminho (em número de arestas) num grafo sem pesos?
- [x] Busca em largura (BFS)
- [ ] Busca em profundidade (DFS)
- [ ] Ordenação por bolha
- [ ] Busca binária
> A BFS visita por camadas de distância.

### Questão
Qual representação é mais econômica para um grafo com muitos vértices e poucas arestas?
- [ ] Matriz de adjacência
- [x] Lista de adjacência
- [ ] Array ordenado
- [ ] Pilha
> A matriz ocupa n² posições mesmo quando quase todas estão vazias.

### Questão
Qual algoritmo encontra o menor caminho com pesos positivos nas arestas?
- [ ] BFS simples
- [x] Dijkstra
- [ ] Quicksort
- [ ] Busca linear
> Dijkstra usa uma fila de prioridade para expandir sempre o caminho mais curto conhecido.

# Algoritmos e estratégias [avancado]
> Ordenação eficiente, recursão, divisão e conquista e programação dinâmica.

## Projeto
**Laboratório de algoritmos:** implemente bubble sort, merge sort e quicksort, gere listas aleatórias de 1.000, 10.000 e 100.000 itens, meça os tempos e monte um gráfico. Depois resolva com programação dinâmica o problema da mochila para escolher peças do vestuário com pontos limitados.

## Recursão e divisão e conquista
### Teoria
Uma função **recursiva** chama a si mesma com um problema menor. Ela precisa de:
- **Caso base:** quando parar.
- **Passo recursivo:** reduzir o problema em direção ao caso base.

Sem caso base, ocorre estouro da pilha de chamadas (*stack overflow*).

**Divisão e conquista** divide o problema em partes, resolve cada uma (muitas vezes recursivamente) e combina os resultados. A **busca binária** divide a lista ao meio a cada passo (O(log n)). O **merge sort** divide a lista, ordena as metades e as intercala (O(n log n) sempre). O **quicksort** escolhe um pivô e separa menores e maiores; é O(n log n) em média e muito rápido na prática, mas O(n²) no pior caso.

### Prática
```python
def merge_sort(lista):
    if len(lista) <= 1:              # caso base
        return lista
    meio = len(lista) // 2
    esquerda = merge_sort(lista[:meio])
    direita = merge_sort(lista[meio:])
    resultado, i, j = [], 0, 0
    while i < len(esquerda) and j < len(direita):
        if esquerda[i] <= direita[j]:
            resultado.append(esquerda[i]); i += 1
        else:
            resultado.append(direita[j]); j += 1
    return resultado + esquerda[i:] + direita[j:]

print(merge_sort([38, 27, 43, 3, 9, 82, 10]))
```

### Questão
O que acontece com uma função recursiva sem caso base?
- [ ] Ela retorna zero
- [x] Ela chama a si mesma até estourar a pilha de chamadas
- [ ] Ela vira um laço for
- [ ] Nada
> O caso base é o que encerra a recursão.

### Questão
Qual a complexidade do merge sort no pior caso?
- [ ] O(n²)
- [x] O(n log n)
- [ ] O(n)
- [ ] O(log n)
> Ele sempre divide ao meio (log n níveis) e intercala em O(n) por nível.

### Questão
Qual a principal ideia da divisão e conquista?
- [ ] Testar todas as possibilidades
- [x] Dividir o problema em partes menores, resolvê-las e combinar os resultados
- [ ] Ordenar antes de tudo
- [ ] Usar sempre uma pilha
> Busca binária, merge sort e quicksort seguem essa estratégia.

## Programação dinâmica
### Teoria
Alguns problemas recursivos recalculam as mesmas partes muitas vezes. O Fibonacci recursivo ingênuo é O(2ⁿ): `fib(40)` faz centenas de milhões de chamadas.

A **programação dinâmica** resolve cada subproblema uma vez e guarda o resultado:
- **Memoização (top-down):** recursão com cache.
- **Tabulação (bottom-up):** preenche uma tabela dos casos menores aos maiores.

Ela funciona quando o problema tem **subproblemas sobrepostos** e **subestrutura ótima** (a solução ótima é feita de soluções ótimas menores). Exemplos clássicos: Fibonacci, troco com menos moedas, mochila, maior subsequência comum (usada no `diff` e no Git).

### Prática
```python
from functools import lru_cache

@lru_cache(maxsize=None)
def fib(n):
    return n if n < 2 else fib(n - 1) + fib(n - 2)

print(fib(80))   # instantâneo graças ao cache

def menor_troco(moedas, valor):
    # tabela[v] = menor número de moedas para formar v
    infinito = float("inf")
    tabela = [0] + [infinito] * valor
    for v in range(1, valor + 1):
        for m in moedas:
            if m <= v:
                tabela[v] = min(tabela[v], tabela[v - m] + 1)
    return tabela[valor] if tabela[valor] != infinito else -1

print(menor_troco([1, 5, 10, 25], 63))  # 6 (25+25+10+1+1+1)
```

### Questão
Quando a programação dinâmica é útil?
- [ ] Quando não há repetição de cálculos
- [x] Quando há subproblemas sobrepostos e subestrutura ótima
- [ ] Só para ordenar listas
- [ ] Só em bancos de dados
> Guardar resultados evita recalcular os mesmos subproblemas.

### Questão
O que é memoização?
- [ ] Decorar o código
- [x] Guardar o resultado de chamadas já feitas para reutilizá-lo
- [ ] Apagar a memória
- [ ] Ordenar a entrada
> O decorador lru_cache faz memoização automaticamente em Python.

### Questão
Qual a complexidade do Fibonacci recursivo sem memoização?
- [ ] O(n)
- [ ] O(log n)
- [x] Exponencial, cerca de O(2ⁿ)
- [ ] O(1)
> Cada chamada gera outras duas, repetindo muito trabalho.

## Algoritmos gulosos e resolução de problemas
### Teoria
Um **algoritmo guloso** faz, a cada passo, a escolha que parece melhor no momento, sem voltar atrás. É simples e rápido, mas nem sempre dá a resposta ótima. Funciona, por exemplo, em:
- Troco com moedas "canônicas" (como as do real).
- Agendar o maior número de atividades sem conflito (escolher a que termina primeiro).
- Árvores geradoras mínimas (algoritmos de Kruskal e Prim).

Para resolver problemas em entrevistas e competições, um roteiro ajuda:
1. Entenda o problema e faça exemplos à mão, incluindo casos extremos.
2. Comece por uma solução simples (força bruta), mesmo lenta.
3. Identifique o gargalo e escolha uma estrutura melhor (hash, heap, dois ponteiros, ordenação).
4. Analise a complexidade e teste.

### Prática
```python
def max_atividades(atividades):
    """atividades: lista de (inicio, fim). Escolhe sempre a que termina primeiro."""
    escolhidas, fim_atual = [], 0
    for inicio, fim in sorted(atividades, key=lambda a: a[1]):
        if inicio >= fim_atual:
            escolhidas.append((inicio, fim))
            fim_atual = fim
    return escolhidas

print(max_atividades([(1, 4), (3, 5), (0, 6), (5, 7), (8, 9), (5, 9)]))
# [(1, 4), (5, 7), (8, 9)]

def dois_somam(numeros, alvo):
    """Técnica do hash: O(n) em vez de O(n²)."""
    vistos = {}
    for i, n in enumerate(numeros):
        if alvo - n in vistos:
            return vistos[alvo - n], i
        vistos[n] = i
    return None

print(dois_somam([2, 7, 11, 15], 9))  # (0, 1)
```

### Questão
O que caracteriza um algoritmo guloso?
- [ ] Testa todas as combinações
- [x] Faz a melhor escolha local a cada passo, sem voltar atrás
- [ ] Usa sempre recursão
- [ ] Sempre encontra a solução ótima
> Ele é rápido, mas só é ótimo em problemas com a estrutura certa.

### Questão
Para escolher o maior número de atividades sem conflito, qual critério guloso funciona?
- [ ] A que começa primeiro
- [ ] A mais longa
- [x] A que termina primeiro
- [ ] A mais curta sempre
> Terminar cedo deixa mais tempo livre para as próximas.

### Questão
Qual técnica transforma o problema "dois números que somam um alvo" de O(n²) para O(n)?
- [ ] Ordenação por bolha
- [x] Guardar os números vistos numa tabela hash
- [ ] Recursão sem caso base
- [ ] Busca linear dupla
> Para cada número, verificamos em O(1) se o complemento já apareceu.
