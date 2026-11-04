@historia
Em **1970**, **Edgar F. Codd**, pesquisador da IBM, publicou o artigo que criou o **modelo relacional**: organizar dados em tabelas ligadas por valores, e não por ponteiros, como faziam os bancos hierárquicos da época. Codd também definiu a **normalização** (1ª, 2ª e 3ª formas normais) entre 1971 e 1972.

Em **1976**, **Peter Chen**, do MIT, publicou o **Modelo Entidade-Relacionamento**, com o diagrama de retângulos, losangos e elipses usado até hoje para desenhar bancos de dados antes de criá-los. Depois vieram a forma normal de Boyce-Codd (1974), notações como a "pé de galinha" (*crow's foot*) e a UML. No Brasil, o livro de **Carlos Alberto Heuser** se tornou a referência de ensino de projeto de banco de dados.

@hoje
Todo sistema que guarda dados precisa de um modelo: aplicativos, sites, ERPs, jogos online. Um bom modelo evita inconsistências, facilita consultas e deixa o sistema crescer. Ferramentas como **dbdiagram.io**, **draw.io**, **brModelo** (criado na UFSC), **MySQL Workbench** e **DBeaver** desenham diagramas e geram o SQL. Mesmo com bancos NoSQL, as ideias de entidades, relacionamentos e redundância continuam valendo para decidir como guardar os dados.

@iniciante
- Entender por que modelar e os níveis conceitual, lógico e físico
- Identificar entidades e atributos (simples, compostos, multivalorados, derivados)
- Escolher bons identificadores
- Representar relacionamentos e cardinalidades no DER

@intermediario
- Transformar o modelo conceitual em tabelas e chaves
- Mapear relacionamentos 1:1, 1:N e N:N
- Normalizar até a 3ª forma normal
- Saber quando a desnormalização faz sentido

@avancado
- Criar o modelo físico com tipos, restrições e ações de chave estrangeira
- Planejar índices a partir das consultas da aplicação
- Aplicar padrões como auto-relacionamento, histórico e exclusão lógica
- Documentar o banco com DER e dicionário de dados

@referencias
**Livros**
- *Projeto de Banco de Dados* — Carlos Alberto Heuser: o clássico brasileiro do ensino de modelagem.
- *Sistemas de Banco de Dados* — Ramez Elmasri e Shamkant Navathe: teoria completa, do modelo ER à normalização.
- *Sistema de Banco de Dados* — Silberschatz, Korth e Sudarshan: referência acadêmica mundial.
- *SQL Antipatterns* — Bill Karwin: erros de modelagem comuns na prática.

**Na internet**
- [brModelo](https://www.brmodeloweb.com) — ferramenta brasileira para desenhar modelos conceituais e lógicos.
- [dbdiagram.io](https://dbdiagram.io) — desenhe tabelas escrevendo texto.
- [Database Design (curso aberto da Stanford)](https://www.edx.org/learn/databases)

@modulo 1
**Modelar é conversar.** O modelo conceitual existe para validar o entendimento com quem conhece o negócio, numa linguagem que não seja técnica. Heuser recomenda montar o DER aos poucos, testando cada entidade com perguntas: "precisamos guardar informações sobre isso?", "há várias ocorrências?", "como identificamos cada uma?".

Uma dúvida clássica é decidir se algo é **entidade ou atributo**. Regra prática: se a coisa tem propriedades próprias ou se relaciona com outras, provavelmente é entidade. "Cidade" pode ser um atributo de Cliente num sistema simples, mas vira entidade se precisarmos de estado, população ou de listar clientes por cidade.

Na escolha de chaves, Elmasri e Navathe distinguem **chaves candidatas** (todas que identificam unicamente) da **chave primária** escolhida. Chaves naturais (CPF, email) mudam ou podem faltar; por isso sistemas reais costumam usar chaves substitutas (ids) e manter as naturais como `UNIQUE`.

@fontes 1
- Heuser, *Projeto de Banco de Dados*, capítulos 1 e 2 (modelagem conceitual)
- Elmasri e Navathe, *Sistemas de Banco de Dados*, capítulo 3 (modelo ER)
- Chen, "The Entity-Relationship Model — Toward a Unified View of Data" (1976)

@modulo 2
**Normalizar é eliminar redundância controladamente.** Cada forma normal remove um tipo de dependência problemática: a 1FN remove grupos repetidos, a 2FN remove dependências parciais da chave e a 3FN remove dependências transitivas. A **Forma Normal de Boyce-Codd (FNBC)** é uma versão mais rigorosa da 3FN: todo determinante deve ser uma chave candidata.

O conceito central é a **dependência funcional**: A → B significa que, conhecendo A, B fica determinado (CPF → nome). Identificar as dependências de cada tabela é o que permite decidir onde cada atributo deve morar.

Na prática, um modelo bem feito a partir do DER já costuma nascer em 3FN. A normalização serve como **verificação**: se uma tabela tem anomalias de inserção, atualização ou exclusão, algo ficou no lugar errado.

@fontes 2
- Elmasri e Navathe, *Sistemas de Banco de Dados*, capítulo 14 (dependências funcionais e normalização)
- Heuser, *Projeto de Banco de Dados*, capítulo 5 (normalização)
- Codd, "Further Normalization of the Data Base Relational Model" (1971)

@modulo 3
**O modelo físico é onde a modelagem encontra o desempenho.** Karwin, em *SQL Antipatterns*, alerta para modelos que parecem flexíveis mas cobram caro depois: o **EAV** (entidade-atributo-valor, uma tabela genérica de "chave e valor" para tudo) dificulta consultas e validação; **associações polimórficas** (uma coluna que aponta para tabelas diferentes conforme o tipo) impedem chaves estrangeiras.

Restrições são documentação executável: `NOT NULL`, `UNIQUE`, `CHECK` e `FOREIGN KEY` garantem regras mesmo que a aplicação tenha um bug. Decida conscientemente a ação de cada chave estrangeira (`CASCADE`, `SET NULL`, `RESTRICT`).

Para dados que mudam com o tempo e precisam de histórico (preços, cargos), considere tabelas de histórico com datas de vigência ou tabelas temporais, em vez de sobrescrever o valor antigo.

@fontes 3
- Karwin, *SQL Antipatterns*, capítulos 6 (EAV) e 7 (associações polimórficas)
- Silberschatz, Korth e Sudarshan, *Sistema de Banco de Dados*, capítulo sobre projeto físico e índices
- Martin Fowler, *Patterns of Enterprise Application Architecture* (padrões de mapeamento objeto-relacional)
