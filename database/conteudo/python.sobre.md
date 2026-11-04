@historia
Python foi criado pelo holandês **Guido van Rossum** no fim de 1989, como projeto de férias de Natal no instituto de pesquisa CWI, em Amsterdã. Ele queria uma linguagem fácil de ler, inspirada na linguagem educacional ABC, mas útil para tarefas reais. O nome vem do grupo de comédia britânico **Monty Python**, e não da cobra.

A versão 1.0 saiu em 1994, a 2.0 em 2000 e a **3.0 em 2008**, uma reescrita que quebrou compatibilidade para corrigir problemas antigos (a transição levou mais de dez anos). O **Zen of Python** (`import this`) resume a filosofia: "bonito é melhor que feio", "legibilidade conta" e "deve haver uma, e preferencialmente só uma, maneira óbvia de fazer algo". Guido liderou a linguagem como "ditador benevolente vitalício" até 2018; hoje um conselho eleito pela comunidade toma as decisões.

@hoje
Python é uma das linguagens mais usadas do mundo. É a principal linguagem de **ciência de dados e inteligência artificial** (NumPy, pandas, PyTorch, TensorFlow), muito usada em **back-end web** (Django, FastAPI, Flask), **automação** de tarefas, scripts de infraestrutura, testes, ensino e pesquisa científica. Empresas como Instagram, Spotify, Netflix e a NASA usam Python em produção. Também é a linguagem favorita para o primeiro contato com programação em escolas e universidades.

@iniciante
- Escrever programas com variáveis, tipos, entrada e saída
- Usar condicionais, laços for e while, range e f-strings
- Converter tipos e tratar entradas do usuário
- Criar programas de terminal com menus

@intermediario
- Dominar listas, tuplas, dicionários e conjuntos
- Escrever list comprehensions e fatiamentos
- Criar funções com parâmetros padrão, *args e **kwargs
- Organizar código em módulos e salvar dados em JSON

@avancado
- Programar orientado a objetos: classes, herança, polimorfismo e dataclasses
- Tratar exceções e trabalhar com arquivos com segurança
- Usar ambientes virtuais, pip e bibliotecas como requests e pandas
- Escrever testes automatizados com pytest

@referencias
**Livros**
- *Curso Intensivo de Python* — Eric Matthes: o melhor livro para começar, com três projetos completos.
- *Automatize Tarefas Maçantes com Python* — Al Sweigart: Python para resolver problemas do dia a dia ([gratuito em inglês](https://automatetheboringstuff.com)).
- *Python Fluente* — Luciano Ramalho: escrito por um brasileiro, é a referência para escrever Python idiomático.
- *Pense em Python* — Allen B. Downey: fundamentos de computação com Python.

**Na internet**
- [Tutorial oficial do Python (em português)](https://docs.python.org/pt-br/3/tutorial/)
- [PEP 8](https://peps.python.org/pep-0008/) — o guia de estilo oficial.
- [Real Python](https://realpython.com) — artigos e tutoriais aprofundados.

@modulo 1
**Em Python, tudo é objeto e variáveis são nomes.** Uma variável não é uma "caixa": é uma etiqueta que aponta para um objeto. Por isso `a = [1, 2]; b = a; b.append(3)` também altera `a`: os dois nomes apontam para a mesma lista. Luciano Ramalho dedica um capítulo inteiro de *Python Fluente* a essa ideia ("Variáveis não são caixas").

Tipos se dividem em **mutáveis** (list, dict, set) e **imutáveis** (int, float, str, tuple). Strings são imutáveis: métodos como `upper()` devolvem uma nova string.

A indentação não é só estilo: ela **define os blocos**. A PEP 8 recomenda 4 espaços, nomes em `snake_case` e linhas de até 79 caracteres. Use `type()`, `help()` e `dir()` no terminal interativo para explorar qualquer objeto.

@fontes 1
- Ramalho, *Python Fluente*, capítulo "Referências de objetos, mutabilidade e reciclagem"
- Matthes, *Curso Intensivo de Python*, capítulos 2 a 7
- [PEP 8 — Guia de estilo](https://peps.python.org/pep-0008/)

@modulo 2
**Escolha a estrutura de dados certa.** Buscar um item numa lista é O(n), mas num dicionário ou conjunto é, em média, O(1), porque eles usam **tabelas hash**. Por isso `if x in conjunto` é muito mais rápido que `if x in lista` com muitos dados.

Dicionários mantêm a **ordem de inserção** desde o Python 3.7. Comprehensions existem também para dicionários (`{k: v for ...}`) e conjuntos. Para contagens use `collections.Counter`; para valores padrão, `defaultdict`.

Funções são **objetos de primeira classe**: podem ser passadas como argumento (`sorted(alunos, key=lambda a: a["nota"])`). Cuidado com o argumento padrão mutável: `def f(x=[])` reaproveita a mesma lista entre chamadas; use `x=None`.

@fontes 2
- Ramalho, *Python Fluente*, capítulos sobre dicionários, conjuntos e funções de primeira classe
- Sweigart, *Automatize Tarefas Maçantes com Python*, capítulos 4 e 5
- [Documentação de collections](https://docs.python.org/pt-br/3/library/collections.html)

@modulo 3
**Python segue o princípio "é mais fácil pedir perdão do que permissão" (EAFP).** Em vez de checar tudo antes (`if os.path.exists(...)`), o estilo pythônico tenta a operação e trata a exceção (`try/except`).

Na orientação a objetos, os **métodos especiais** (`__init__`, `__repr__`, `__len__`, `__eq__`) fazem suas classes se comportarem como tipos nativos: é o "modelo de dados" do Python. Prefira **composição** a heranças profundas e use `@property` para validar atributos sem mudar a interface.

Para projetos reais: um **ambiente virtual** por projeto, dependências registradas (`requirements.txt` ou `pyproject.toml`), **type hints** para documentar tipos (verificados por ferramentas como mypy) e **testes** com pytest. Robert C. Martin, em *Código Limpo*, lembra: código sem testes não pode ser refatorado com segurança.

@fontes 3
- Ramalho, *Python Fluente*, capítulo 1 ("O modelo de dados do Python")
- Martin, *Código Limpo*, capítulo 9 (testes de unidade)
- [Python Packaging User Guide](https://packaging.python.org)
