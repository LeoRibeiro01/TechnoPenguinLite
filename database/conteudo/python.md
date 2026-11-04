# Fundamentos de Python
> Sintaxe, tipos, entrada e saída, decisões e laços.

## Projeto
**Calculadora de IMC e conversor:** crie um programa de terminal com um menu (1 - IMC, 2 - Celsius para Fahrenheit, 3 - Sair). Use `input()`, conversão de tipos, `if/elif/else` e um laço `while` que só termina quando o usuário escolher sair. Valide entradas inválidas com mensagens amigáveis.

## Olá, Python: variáveis e tipos
### Teoria
Python é uma linguagem **interpretada**, de sintaxe limpa, onde a **indentação** (os espaços no começo da linha) define os blocos de código. Não há `;` no fim das linhas nem chaves.

Variáveis são criadas na primeira atribuição e o tipo é descoberto automaticamente (**tipagem dinâmica**). Tipos básicos:
- `int` (inteiros), `float` (decimais), `str` (textos), `bool` (`True`/`False`).

Use `type(valor)` para ver o tipo e funções como `int()`, `float()` e `str()` para converter. As **f-strings** (`f"Olá, {nome}"`) montam textos com variáveis.

### Prática
```python
nome = input("Seu nome: ")
idade = int(input("Sua idade: "))
altura = 1.62
print(f"{nome} tem {idade} anos e {altura} m")
print(type(idade), type(altura), type(nome))
print("Ano que vem:", idade + 1)
```

`input()` sempre devolve texto. Por isso convertemos a idade com `int()` antes de fazer contas.

### Questão
O que `input()` sempre retorna em Python?
- [ ] Um número inteiro
- [x] Um texto (str)
- [ ] Um booleano
- [ ] Depende do que o usuário digitar
> Mesmo que o usuário digite 42, input() devolve "42". Para fazer contas, converta com int() ou float().

### Questão
Qual o resultado de `print(f"{2 + 3} pinguins")`?
- [ ] {2 + 3} pinguins
- [x] 5 pinguins
- [ ] 23 pinguins
- [ ] Erro de sintaxe
> Dentro das chaves de uma f-string, a expressão é avaliada: 2 + 3 vira 5.

### Questão
O que define um bloco de código em Python?
- [ ] Chaves { }
- [ ] Ponto e vírgula
- [x] A indentação
- [ ] A palavra end
> Python usa a indentação (normalmente 4 espaços) para delimitar blocos.

## Condicionais em Python
### Teoria
Decisões usam `if`, `elif` (senão se) e `else`. A linha da condição termina com `:` e o bloco vem indentado.

Operadores de comparação: `==`, `!=`, `>`, `<`, `>=`, `<=`. Operadores lógicos são palavras: `and`, `or`, `not`.

Python permite comparações encadeadas como `0 <= nota <= 10` e o operador `in` para testar se um valor está numa coleção: `if letra in "aeiou":`.

Valores "vazios" (`0`, `""`, `[]`, `None`) contam como falsos numa condição.

### Prática
```python
temperatura = float(input("Temperatura: "))
if temperatura < 0:
    print("Clima perfeito para pinguins!")
elif 0 <= temperatura <= 15:
    print("Fresquinho")
else:
    print("Calor demais")

resposta = input("Gosta de peixe? ").strip().lower()
if resposta in ("sim", "s"):
    print("Somos amigos!")
```

### Questão
Qual palavra-chave equivale a "senão se" em Python?
- [ ] else if
- [x] elif
- [ ] elseif
- [ ] otherwise
> Em Python é elif, numa palavra só.

### Questão
O que imprime `print(3 > 2 and not 5 < 1)`?
- [x] True
- [ ] False
- [ ] None
- [ ] Erro
> 3 > 2 é True; 5 < 1 é False e not False é True. True and True dá True.

### Questão
Como verificar se `x` está entre 1 e 10 (inclusive) de forma idiomática?
- [ ] if x > 1 or x < 10:
- [x] if 1 <= x <= 10:
- [ ] if x in 1..10:
- [ ] if x between 1 and 10:
> Python aceita comparações encadeadas, que se leem como na matemática.

## Laços: for e while
### Teoria
O `for` percorre qualquer **sequência**: listas, textos, intervalos. `range(início, fim, passo)` gera números e **não inclui o fim**: `range(1, 4)` produz 1, 2, 3.

O `while` repete enquanto a condição for verdadeira. Dentro dos laços:
- `break` sai do laço imediatamente.
- `continue` pula para a próxima repetição.

`enumerate(lista)` entrega o índice e o valor ao mesmo tempo.

### Prática
```python
for i in range(1, 6):
    print(i, "x 3 =", i * 3)

senha = ""
while senha != "gelo123":
    senha = input("Senha: ")
print("Acesso liberado!")

for i, fruta in enumerate(["maçã", "uva"]):
    print(i, fruta)
```

### Questão
Quais números `range(2, 6)` produz?
- [ ] 2, 3, 4, 5, 6
- [x] 2, 3, 4, 5
- [ ] 3, 4, 5, 6
- [ ] 2, 6
> O range inclui o início e para antes do fim.

### Questão
O que `break` faz dentro de um laço?
- [ ] Pula para a próxima repetição
- [x] Encerra o laço imediatamente
- [ ] Reinicia o laço
- [ ] Encerra o programa
> break sai do laço; continue é que pula para a próxima volta.

### Questão
Quantas vezes "oi" é impresso?
```python
for i in range(0, 10, 3):
    print("oi")
```
- [ ] 3
- [x] 4
- [ ] 10
- [ ] 9
> O range produz 0, 3, 6 e 9: quatro valores.

# Estruturas de dados e funções
> Listas, dicionários, funções e organização de código.

## Projeto
**Agenda de contatos:** guarde contatos num dicionário (`nome -> telefone`) e ofereça um menu para adicionar, buscar, listar em ordem alfabética e remover. Separe cada ação numa função e salve a agenda num arquivo `.json` ao sair (módulo `json`) para os dados persistirem.

## Listas e tuplas
### Teoria
Uma **lista** guarda vários valores em ordem e é **mutável**: `pinguins = ["Teco", "Pipo"]`. Métodos úteis: `append()` adiciona no fim, `insert()` numa posição, `remove()` por valor, `pop()` retira e devolve.

**Fatiamento** pega partes: `lista[1:3]` (índices 1 e 2), `lista[-1]` (último).

**List comprehension** cria listas de forma compacta: `[x * 2 for x in numeros if x > 0]`.

Uma **tupla** é como uma lista **imutável**: `ponto = (3, 4)`. Use quando os dados não devem mudar.

### Prática
```python
notas = [7.5, 9.0, 6.0]
notas.append(8.0)
print(len(notas), max(notas), sum(notas) / len(notas))
print(sorted(notas, reverse=True))
aprovadas = [n for n in notas if n >= 7]
print(aprovadas)       # [7.5, 9.0, 8.0]
print(notas[-1])       # 8.0
```

### Questão
O que `[1, 2, 3, 4, 5][1:3]` retorna?
- [ ] [1, 2, 3]
- [x] [2, 3]
- [ ] [2, 3, 4]
- [ ] [1, 2]
> O fatiamento vai do índice 1 até antes do 3: elementos 2 e 3.

### Questão
Qual a principal diferença entre lista e tupla?
- [ ] Tuplas só guardam números
- [x] Listas são mutáveis e tuplas são imutáveis
- [ ] Listas não têm índice
- [ ] Não há diferença
> Depois de criada, uma tupla não pode ser alterada.

### Questão
O que gera `[x * x for x in range(4)]`?
- [ ] [1, 4, 9, 16]
- [x] [0, 1, 4, 9]
- [ ] [0, 2, 4, 6]
- [ ] [4, 4, 4, 4]
> range(4) é 0, 1, 2, 3; os quadrados são 0, 1, 4, 9.

## Dicionários e conjuntos
### Teoria
Um **dicionário** guarda pares **chave: valor**: `aluno = {"nome": "Ana", "nota": 9}`. Acesse com `aluno["nome"]`. Use `aluno.get("idade", 0)` para ter um valor padrão quando a chave não existe (evita erro).

Percorra com `for chave, valor in aluno.items():`. As chaves são únicas: atribuir de novo substitui o valor.

Um **conjunto** (`set`) guarda valores **sem repetição** e sem ordem: ótimo para remover duplicatas e testar pertinência rapidamente.

### Prática
```python
estoque = {"sardinha": 10, "atum": 3}
estoque["salmão"] = 5
estoque["atum"] += 2
for peixe, qtd in estoque.items():
    print(peixe, qtd)
print(estoque.get("bacalhau", 0))   # 0

linguagens = ["py", "js", "py", "go"]
print(set(linguagens))              # {'py', 'js', 'go'}
```

### Questão
O que acontece em `d["x"]` se a chave "x" não existir?
- [ ] Retorna None
- [x] Lança um KeyError
- [ ] Cria a chave com valor 0
- [ ] Retorna uma string vazia
> O acesso direto com colchetes falha se a chave não existe. Use d.get("x") para evitar o erro.

### Questão
Qual estrutura é ideal para eliminar valores repetidos?
- [ ] list
- [ ] tuple
- [x] set
- [ ] str
> Conjuntos não guardam duplicatas.

### Questão
Como percorrer chaves e valores de um dicionário `d`?
- [ ] for k, v in d:
- [x] for k, v in d.items():
- [ ] for k, v in d.values():
- [ ] for k in d.pairs():
> items() devolve os pares (chave, valor).

## Funções e módulos
### Teoria
Funções são definidas com `def`. Parâmetros podem ter **valores padrão** (`def saudar(nome="pinguim"):`) e podem ser passados **por nome** (`saudar(nome="Teco")`).

`*args` recebe vários argumentos posicionais; `**kwargs`, vários nomeados. Uma função sem `return` devolve `None`.

Organize o código em **módulos** (arquivos `.py`) e importe com `import math` ou `from random import randint`. O bloco `if __name__ == "__main__":` só roda quando o arquivo é executado diretamente.

### Prática
```python
from random import randint

def rolar_dados(quantidade=2, lados=6):
    return [randint(1, lados) for _ in range(quantidade)]

def media(*numeros):
    return sum(numeros) / len(numeros) if numeros else 0

if __name__ == "__main__":
    print(rolar_dados())
    print(rolar_dados(lados=20))
    print(media(7, 8, 9))
```

### Questão
O que uma função sem `return` devolve?
- [ ] 0
- [ ] Uma string vazia
- [x] None
- [ ] Um erro
> Sem return explícito, Python devolve None.

### Questão
Em `def f(a, b=10):`, quanto vale `b` em `f(1)`?
- [ ] 1
- [x] 10
- [ ] None
- [ ] Dá erro
> Quando o argumento não é passado, vale o valor padrão.

### Questão
Para que serve `if __name__ == "__main__":`?
- [ ] Para declarar a função principal obrigatória
- [x] Para rodar um código só quando o arquivo é executado diretamente, e não importado
- [ ] Para importar módulos
- [ ] Para criar classes
> Ao importar o arquivo como módulo, o bloco não é executado.

# Python intermediário e avançado
> Orientação a objetos, erros, arquivos e bibliotecas do ecossistema.

## Projeto
**Gerenciador de tarefas em POO:** crie as classes `Tarefa` (título, prioridade, concluída) e `ListaDeTarefas` (adicionar, concluir, filtrar por prioridade, salvar e carregar de JSON). Trate erros de arquivo inexistente com `try/except`. Desafio: escreva testes com `pytest` para as principais operações.

## Orientação a objetos
### Teoria
Uma **classe** é um molde; um **objeto** é uma instância dela. O método `__init__` inicializa o objeto e `self` representa a própria instância.

Pilares da POO:
- **Encapsulamento:** dados e comportamentos juntos; atributos "privados" começam com `_` por convenção.
- **Herança:** uma classe filha reaproveita a mãe (`class Imperador(Pinguim):`). `super()` chama a mãe.
- **Polimorfismo:** classes diferentes respondem ao mesmo método do seu jeito.

`@dataclass` gera `__init__` e `__repr__` automaticamente para classes de dados.

### Prática
```python
class Pinguim:
    def __init__(self, nome, peixes=0):
        self.nome = nome
        self.peixes = peixes

    def comer(self, quantidade):
        self.peixes += quantidade
        return f"{self.nome} comeu {quantidade} peixes"

class Imperador(Pinguim):
    def comer(self, quantidade):
        return super().comer(quantidade * 2) + " (é imperador!)"

for p in [Pinguim("Teco"), Imperador("Rei")]:
    print(p.comer(3))
```

### Questão
O que `self` representa num método?
- [ ] A classe
- [x] A instância (o objeto) que chamou o método
- [ ] O módulo
- [ ] O método pai
> self é a referência para o próprio objeto.

### Questão
O que `super().comer(...)` faz na classe Imperador?
- [ ] Cria um novo pinguim
- [x] Chama o método comer da classe mãe
- [ ] Apaga o método da mãe
- [ ] Chama o método da classe filha de novo
> super() acessa a implementação da classe mãe.

### Questão
Qual conceito é ilustrado quando Pinguim e Imperador respondem a `comer` de formas diferentes?
- [ ] Encapsulamento
- [ ] Abstração de dados
- [x] Polimorfismo
- [ ] Compilação
> Mesmo método, comportamentos diferentes por classe: polimorfismo.

## Exceções e arquivos
### Teoria
Erros em tempo de execução são **exceções**. Trate-as com `try/except` para o programa não quebrar:

- `except ValueError:` captura um tipo específico.
- `else:` roda se não houve erro; `finally:` roda sempre.
- `raise` lança uma exceção sua.

Para arquivos, use `with open(...)`: ele fecha o arquivo automaticamente, mesmo se houver erro. Modos: `"r"` ler, `"w"` escrever (apaga o conteúdo), `"a"` acrescentar. Sempre informe `encoding="utf-8"`.

### Prática
```python
import json

def carregar(caminho):
    try:
        with open(caminho, encoding="utf-8") as arquivo:
            return json.load(arquivo)
    except FileNotFoundError:
        return []

tarefas = carregar("tarefas.json")
tarefas.append({"titulo": "Estudar Python", "feita": False})
with open("tarefas.json", "w", encoding="utf-8") as arquivo:
    json.dump(tarefas, arquivo, ensure_ascii=False, indent=2)
```

### Questão
Qual a vantagem de `with open(...)`?
- [ ] Deixa a leitura mais rápida
- [x] Fecha o arquivo automaticamente ao sair do bloco
- [ ] Converte o arquivo para JSON
- [ ] Impede erros de digitação
> O gerenciador de contexto garante o fechamento, mesmo com exceções.

### Questão
Qual exceção ocorre em `int("abc")`?
- [ ] TypeError
- [x] ValueError
- [ ] KeyError
- [ ] IndexError
> O tipo (str) é aceito por int(), mas o valor "abc" não representa um número.

### Questão
Quando o bloco `finally` é executado?
- [ ] Só quando há erro
- [ ] Só quando não há erro
- [x] Sempre
- [ ] Nunca, é opcional
> finally roda em qualquer caso, ideal para liberar recursos.

## Ambientes virtuais e bibliotecas
### Teoria
O poder do Python está no ecossistema. O **pip** instala pacotes do PyPI e um **ambiente virtual** isola as dependências de cada projeto:

```
python -m venv .venv
.venv\Scripts\activate      (Windows)
pip install requests pandas
pip freeze > requirements.txt
```

Bibliotecas famosas:
- **requests:** chamadas HTTP e APIs.
- **pandas:** análise de dados em tabelas.
- **Flask / Django / FastAPI:** desenvolvimento web.
- **pytest:** testes automatizados.

### Prática
Consumindo uma API pública com `requests` e testando com `pytest`:

```python
import requests

def buscar_usuario(login):
    resposta = requests.get(f"https://api.github.com/users/{login}", timeout=10)
    resposta.raise_for_status()
    return resposta.json()["name"]

# arquivo test_contas.py
def soma(a, b):
    return a + b

def test_soma():
    assert soma(2, 3) == 5
```

Rode os testes com o comando `pytest` na pasta do projeto.

### Questão
Para que serve um ambiente virtual (venv)?
- [ ] Para rodar Python sem internet
- [x] Para isolar as dependências de cada projeto
- [ ] Para deixar o Python mais rápido
- [ ] Para criar máquinas virtuais
> Cada projeto pode ter suas próprias versões de bibliotecas sem conflito.

### Questão
Qual arquivo costuma listar as dependências de um projeto Python?
- [ ] package.json
- [x] requirements.txt
- [ ] composer.json
- [ ] pom.xml
> pip freeze > requirements.txt registra as versões instaladas; pip install -r requirements.txt as reinstala.

### Questão
Qual biblioteca é a escolha mais comum para manipular tabelas de dados?
- [ ] requests
- [ ] flask
- [x] pandas
- [ ] pytest
> pandas oferece DataFrames para ler, filtrar e agrupar dados.
