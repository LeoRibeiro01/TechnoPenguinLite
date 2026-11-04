@historia
A base de tudo é o **modelo relacional**, proposto por **Edgar F. Codd**, da IBM, em 1970: dados em tabelas (relações), manipulados por álgebra de conjuntos. A IBM criou a linguagem SEQUEL, depois **SQL**, nos anos 1970, e o SQL virou padrão ANSI/ISO em 1986.

O **MySQL** foi criado em 1995 pela empresa sueca **MySQL AB**, fundada por **Michael "Monty" Widenius**, David Axmark e Allan Larsson. O nome vem de **My**, filha de Monty, e o golfinho do logo se chama Sakila. Gratuito e rápido, o MySQL virou o "M" da pilha **LAMP** (Linux, Apache, MySQL, PHP) que construiu a web dos anos 2000. A Sun comprou a MySQL AB em 2008 e a **Oracle** comprou a Sun em 2010, o que levou Monty a criar o MariaDB.

@hoje
MySQL é um dos bancos de dados mais populares do mundo. Está por trás do **WordPress**, de lojas virtuais, sistemas escolares, ERPs e de gigantes como Facebook (que mantém sua própria versão), Booking.com e Uber. Os grandes provedores de nuvem oferecem MySQL gerenciado (Amazon RDS e Aurora, Google Cloud SQL, Azure). Saber SQL é exigido em quase toda vaga de desenvolvimento, análise de dados e ciência de dados.

@iniciante
- Entender bancos relacionais, tabelas, linhas e chaves primárias
- Criar bancos e tabelas com os tipos adequados
- Inserir dados com INSERT
- Consultar com SELECT, WHERE, ORDER BY e LIMIT

@intermediario
- Alterar e remover dados com UPDATE e DELETE com segurança
- Usar transações com COMMIT e ROLLBACK
- Resumir dados com funções de agregação, GROUP BY e HAVING
- Relacionar tabelas com chaves estrangeiras e JOINs

@avancado
- Escrever subconsultas, CTEs e window functions
- Acelerar consultas com índices e EXPLAIN
- Criar views, stored procedures e triggers
- Aplicar segurança: usuários, privilégios e backups

@referencias
**Livros**
- *Aprendendo SQL* — Alan Beaulieu: introdução clara e prática ao SQL, com exemplos em MySQL.
- *SQL Antipatterns* — Bill Karwin: os erros mais comuns em bancos de dados e como evitá-los.
- *High Performance MySQL* — Silvia Botros e Jeremy Tinley: desempenho e operação em produção.
- *Sistemas de Banco de Dados* — Elmasri e Navathe: a teoria completa (modelo relacional, álgebra, normalização).

**Na internet**
- [Manual de referência do MySQL](https://dev.mysql.com/doc/refman/8.4/en/)
- [SQLBolt](https://sqlbolt.com) — exercícios interativos de SQL.
- [Use The Index, Luke!](https://use-the-index-luke.com/pt) — guia gratuito sobre índices e desempenho.

@modulo 1
**SQL é uma linguagem declarativa.** Você descreve *o que* quer, e o **otimizador** do banco decide *como* buscar: qual índice usar, em que ordem juntar tabelas. Codd baseou o modelo relacional em teoria dos conjuntos: uma tabela é um conjunto de linhas, sem ordem garantida. Sem `ORDER BY`, a ordem do resultado pode mudar a qualquer momento.

O valor `NULL` significa "desconhecido", e a lógica com ele tem **três valores**: verdadeiro, falso e desconhecido. Por isso `NULL = NULL` não é verdadeiro e é preciso usar `IS NULL`. Funções de agregação ignoram nulos (`AVG` só considera os valores preenchidos).

Escolha tipos com cuidado: `DECIMAL` para dinheiro, `DATETIME` ou `TIMESTAMP` para datas (nunca texto) e sempre `utf8mb4` para texto.

@fontes 1
- Beaulieu, *Aprendendo SQL*, capítulos 1 a 3
- Codd, "A Relational Model of Data for Large Shared Data Banks" (Communications of the ACM, 1970)
- [MySQL: Data Types](https://dev.mysql.com/doc/refman/8.4/en/data-types.html)

@modulo 2
**Transações garantem as propriedades ACID:** **A**tomicidade (tudo ou nada), **C**onsistência (regras respeitadas), **I**solamento (transações simultâneas não se atrapalham) e **D**urabilidade (o que foi confirmado sobrevive a uma queda de energia). O InnoDB, motor padrão do MySQL, usa o nível de isolamento *REPEATABLE READ* por padrão.

Para pensar em JOINs, lembre do **produto cartesiano** filtrado pela condição `ON`. Um JOIN sem condição multiplica as linhas: 1.000 clientes × 10.000 pedidos = 10 milhões de linhas.

Bill Karwin, em *SQL Antipatterns*, lista armadilhas como guardar listas separadas por vírgula numa coluna ("Jaywalking"), usar `FLOAT` para dinheiro e deixar de criar chaves estrangeiras "por desempenho". A integridade referencial é muito mais barata do que corrigir dados inconsistentes depois.

@fontes 2
- Karwin, *SQL Antipatterns*, capítulos 2 (Jaywalking) e 5 (Keyless Entry)
- Beaulieu, *Aprendendo SQL*, capítulos 5 (JOINs) e 12 (transações)
- [MySQL: InnoDB e o modelo ACID](https://dev.mysql.com/doc/refman/8.4/en/mysql-acid.html)

@modulo 3
**Índices são árvores B+.** O InnoDB guarda a tabela inteira ordenada pela chave primária (o *índice clusterizado*), e cada índice secundário guarda a chave primária para chegar à linha. Por isso uma chave primária pequena e crescente (como `AUTO_INCREMENT`) é eficiente. Em índices compostos vale a **regra do prefixo à esquerda**: um índice `(a, b, c)` ajuda filtros em `a`, `a, b` ou `a, b, c`, mas não só em `b`.

Um índice **cobre** a consulta quando contém todas as colunas necessárias, e o banco nem precisa ler a tabela (`Using index` no EXPLAIN). Funções na coluna (`WHERE YEAR(data) = 2026`) impedem o uso do índice; prefira intervalos (`WHERE data >= '2026-01-01'`).

Segurança: menor privilégio, consultas parametrizadas na aplicação e backups testados. Um backup que nunca foi restaurado não é um backup.

@fontes 3
- Botros e Tinley, *High Performance MySQL*, capítulos 7 (índices) e 8 (otimização de consultas)
- Markus Winand, [Use The Index, Luke!](https://use-the-index-luke.com/pt)
- [MySQL: EXPLAIN Output Format](https://dev.mysql.com/doc/refman/8.4/en/explain-output.html)
