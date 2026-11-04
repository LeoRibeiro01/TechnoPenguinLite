# Primeiras consultas
> Bancos relacionais, tabelas, tipos e SELECT.

## Projeto
**Banco da biblioteca:** crie o banco `biblioteca` com as tabelas `livro` (id, título, autor, ano, gênero) e `leitor`. Insira pelo menos 15 livros e escreva consultas para: livros de um gênero, livros publicados após 2000 ordenados por ano, e os 5 mais antigos.

## O que é um banco de dados relacional
### Teoria
Um **banco de dados relacional** organiza os dados em **tabelas**, formadas por **colunas** (atributos) e **linhas** (registros). Tabelas se relacionam por meio de chaves.

**SQL** (Structured Query Language) é a linguagem usada para criar, consultar e alterar os dados. O **MySQL** é um dos SGBDs (sistemas gerenciadores de banco de dados) mais usados do mundo, e vem no XAMPP!

Você pode usar o MySQL pelo **phpMyAdmin** (`http://localhost/phpmyadmin`) ou pelo terminal (`mysql -u root`).

A **chave primária** (`PRIMARY KEY`) identifica cada linha de forma única.

### Prática
```sql
CREATE DATABASE escola CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE escola;

CREATE TABLE aluno (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  nascimento DATE,
  nota DECIMAL(4, 2) DEFAULT 0
);

SHOW TABLES;
DESCRIBE aluno;
```

### Questão
Num banco relacional, como os dados são organizados?
- [ ] Em arquivos de texto soltos
- [x] Em tabelas com linhas e colunas
- [ ] Em pastas e imagens
- [ ] Em grafos apenas
> Cada tabela representa um tipo de entidade.

### Questão
Para que serve a chave primária?
- [ ] Criptografar a tabela
- [x] Identificar cada linha de forma única
- [ ] Ordenar os dados alfabeticamente
- [ ] Guardar senhas
> Não pode haver duas linhas com a mesma chave primária.

### Questão
O que `AUTO_INCREMENT` faz numa coluna?
- [ ] Soma os valores da coluna
- [x] Gera automaticamente o próximo número a cada inserção
- [ ] Aumenta o tamanho da tabela
- [ ] Cria um índice de texto
> Ideal para ids: 1, 2, 3...

## Tipos de dados e INSERT
### Teoria
Escolher bem o tipo de cada coluna economiza espaço e evita erros:
- Inteiros: `INT`, `BIGINT`, `TINYINT` (0/1 para booleanos). `UNSIGNED` aceita só positivos.
- Decimais exatos: `DECIMAL(10,2)` (dinheiro!). `FLOAT`/`DOUBLE` são aproximados.
- Texto: `VARCHAR(n)` (tamanho variável), `TEXT` (longo), `CHAR(n)` (fixo).
- Datas: `DATE`, `DATETIME`, `TIMESTAMP`.

Restrições: `NOT NULL`, `UNIQUE`, `DEFAULT`.

`INSERT INTO tabela (colunas) VALUES (...)` adiciona linhas; dá para inserir várias de uma vez.

### Prática
```sql
INSERT INTO aluno (nome, email, nascimento, nota)
VALUES ('Ana Pinguim', 'ana@iglu.com', '2008-03-15', 9.5);

INSERT INTO aluno (nome, email, nota) VALUES
  ('Beto Dev', 'beto@iglu.com', 6.0),
  ('Caio Gelo', 'caio@iglu.com', 7.25);

SELECT * FROM aluno;
```

Textos e datas vão entre **aspas simples**.

### Questão
Qual tipo é mais indicado para guardar preços?
- [ ] FLOAT
- [x] DECIMAL(10,2)
- [ ] VARCHAR(10)
- [ ] INT
> DECIMAL é exato; FLOAT pode gerar erros de arredondamento.

### Questão
O que `NOT NULL` garante?
- [ ] Que o valor seja zero
- [x] Que a coluna sempre tenha um valor informado
- [ ] Que o valor seja único
- [ ] Que a coluna seja chave primária
> A inserção falha se o valor estiver ausente.

### Questão
Como textos são escritos em comandos SQL?
- [ ] Sem aspas
- [x] Entre aspas simples
- [ ] Entre crases obrigatoriamente
- [ ] Entre colchetes
> 'Ana Pinguim' é um texto; crases servem para nomes de tabelas/colunas.

## SELECT, WHERE e ORDER BY
### Teoria
`SELECT` consulta dados:

```
SELECT colunas FROM tabela WHERE condição ORDER BY coluna LIMIT n;
```

- `*` seleciona todas as colunas (prefira listar as necessárias).
- `WHERE` filtra com `=`, `<>`, `>`, `BETWEEN`, `IN (...)`, `LIKE 'An%'` (`%` = qualquer sequência), `IS NULL`.
- Combine condições com `AND`, `OR`, `NOT`.
- `ORDER BY coluna DESC` ordena decrescente.
- `LIMIT` restringe o número de linhas.
- `AS` cria apelidos: `SELECT nome AS aluno`.

### Prática
```sql
SELECT nome, nota
FROM aluno
WHERE nota >= 7 AND email LIKE '%@iglu.com'
ORDER BY nota DESC
LIMIT 3;

SELECT nome FROM aluno WHERE nascimento IS NULL;

SELECT nome, nota * 10 AS nota_percentual
FROM aluno
WHERE nota BETWEEN 6 AND 8;
```

### Questão
Qual cláusula filtra as linhas de uma consulta?
- [ ] ORDER BY
- [x] WHERE
- [ ] FROM
- [ ] LIMIT
> WHERE aplica a condição a cada linha.

### Questão
O que `LIKE 'An%'` encontra?
- [ ] Textos iguais a "An%"
- [x] Textos que começam com "An"
- [ ] Textos que terminam com "An"
- [ ] Textos com exatamente 3 letras
> % representa qualquer sequência de caracteres.

### Questão
Como verificar se uma coluna está vazia (nula)?
- [ ] coluna = NULL
- [x] coluna IS NULL
- [ ] coluna == NULL
- [ ] coluna EMPTY
> NULL não é comparável com =; use IS NULL.

# Manipulando e relacionando dados
> UPDATE, DELETE, funções de agregação, GROUP BY e JOINs.

## Projeto
**Loja do iglu:** crie as tabelas `cliente`, `produto`, `pedido` e `item_pedido` com chaves estrangeiras. Popule com dados e escreva consultas para: total gasto por cliente, produto mais vendido, pedidos do último mês e clientes que nunca compraram (LEFT JOIN).

## UPDATE e DELETE
### Teoria
`UPDATE` altera dados existentes e `DELETE` remove linhas:

```
UPDATE tabela SET coluna = valor WHERE condição;
DELETE FROM tabela WHERE condição;
```

**Cuidado:** sem `WHERE`, o comando afeta **todas** as linhas! Antes de alterar, rode um `SELECT` com o mesmo `WHERE` para conferir.

Para operações críticas, use **transações**: `START TRANSACTION`, faça as alterações e confirme com `COMMIT` ou desfaça com `ROLLBACK`.

### Prática
```sql
SELECT * FROM aluno WHERE id = 2;      -- confira antes

UPDATE aluno SET nota = nota + 1 WHERE id = 2;

START TRANSACTION;
DELETE FROM aluno WHERE nota < 5;
SELECT COUNT(*) FROM aluno;            -- confira o resultado
ROLLBACK;                              -- desfaz (ou COMMIT para confirmar)
```

### Questão
O que acontece com `UPDATE aluno SET nota = 10;` sem WHERE?
- [ ] Nada
- [x] Todas as linhas recebem nota 10
- [ ] Só a primeira linha muda
- [ ] Erro de sintaxe
> Sem WHERE, o comando vale para a tabela inteira.

### Questão
Qual comando desfaz as alterações de uma transação?
- [ ] COMMIT
- [x] ROLLBACK
- [ ] UNDO
- [ ] REVERT
> ROLLBACK descarta tudo desde o START TRANSACTION.

### Questão
Qual a boa prática antes de um DELETE?
- [ ] Desligar o servidor
- [x] Rodar um SELECT com o mesmo WHERE para ver o que será apagado
- [ ] Apagar a tabela inteira
- [ ] Nenhuma
> Assim você confirma as linhas atingidas.

## Agregações e GROUP BY
### Teoria
**Funções de agregação** resumem várias linhas num valor: `COUNT`, `SUM`, `AVG`, `MIN`, `MAX`.

`GROUP BY` agrupa linhas com o mesmo valor numa coluna e aplica a agregação a cada grupo.

`HAVING` filtra **grupos** (depois da agregação), enquanto `WHERE` filtra **linhas** (antes).

Ordem de execução lógica: `FROM → WHERE → GROUP BY → HAVING → SELECT → ORDER BY → LIMIT`.

### Prática
```sql
SELECT COUNT(*) AS total, AVG(nota) AS media, MAX(nota) AS maior
FROM aluno;

SELECT turma, COUNT(*) AS alunos, ROUND(AVG(nota), 1) AS media
FROM aluno
WHERE nota IS NOT NULL
GROUP BY turma
HAVING COUNT(*) >= 2
ORDER BY media DESC;
```

### Questão
Qual função conta o número de linhas?
- [ ] SUM
- [x] COUNT
- [ ] TOTAL
- [ ] LEN
> COUNT(*) conta todas as linhas.

### Questão
Qual a diferença entre WHERE e HAVING?
- [ ] Nenhuma
- [x] WHERE filtra linhas antes do agrupamento; HAVING filtra grupos depois
- [ ] HAVING é mais rápido
- [ ] WHERE só funciona com números
> Condições sobre agregações (como COUNT) vão no HAVING.

### Questão
O que `GROUP BY turma` faz?
- [ ] Ordena por turma
- [x] Agrupa as linhas de cada turma para calcular agregações por grupo
- [ ] Remove turmas repetidas sem calcular nada
- [ ] Cria uma tabela turma
> Cada turma vira uma linha no resultado.

## Chaves estrangeiras e JOINs
### Teoria
Uma **chave estrangeira** (`FOREIGN KEY`) liga uma coluna à chave primária de outra tabela, garantindo **integridade referencial**: não dá para criar um pedido de um cliente que não existe.

`JOIN` combina tabelas relacionadas:
- `INNER JOIN`: só linhas com correspondência nos dois lados.
- `LEFT JOIN`: todas as linhas da esquerda, com `NULL` onde não há correspondência (ótimo para achar "quem não tem").

`ON DELETE CASCADE` apaga os filhos quando o pai é apagado.

### Prática
```sql
CREATE TABLE pedido (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT UNSIGNED NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_cliente) REFERENCES cliente (id) ON DELETE CASCADE
);

SELECT c.nome, COUNT(p.id) AS pedidos, COALESCE(SUM(p.total), 0) AS gasto
FROM cliente c
LEFT JOIN pedido p ON p.id_cliente = c.id
GROUP BY c.id, c.nome
ORDER BY gasto DESC;
```

### Questão
O que uma chave estrangeira garante?
- [ ] Que a coluna é única
- [x] Que o valor referencia uma linha existente em outra tabela
- [ ] Que a tabela é criptografada
- [ ] Que a coluna nunca muda
> É a integridade referencial.

### Questão
Qual JOIN retorna todos os clientes, mesmo os sem pedidos?
- [ ] INNER JOIN
- [x] LEFT JOIN (com cliente à esquerda)
- [ ] CROSS JOIN
- [ ] Nenhum
> LEFT JOIN mantém todas as linhas da tabela da esquerda.

### Questão
O que `ON DELETE CASCADE` faz?
- [ ] Impede apagar o pai
- [x] Apaga automaticamente as linhas filhas quando o pai é apagado
- [ ] Copia os dados
- [ ] Desativa a chave estrangeira
> Ao apagar o cliente, os pedidos dele também somem.

# MySQL avançado
> Subconsultas, índices, views, procedures e segurança.

## Projeto
**Otimizando a loja:** gere 100 mil pedidos de teste, meça consultas lentas com `EXPLAIN`, crie índices adequados e compare. Crie uma view `vw_resumo_cliente`, uma procedure que registra um pedido numa transação e um usuário MySQL só com permissões de leitura.

## Subconsultas e funções
### Teoria
Uma **subconsulta** é um SELECT dentro de outro:
- No `WHERE`: `WHERE nota > (SELECT AVG(nota) FROM aluno)`.
- Com `IN` / `EXISTS`: pertença a outro conjunto.
- No `FROM`: tabela derivada.

**CTEs** (`WITH nome AS (...)`) deixam consultas complexas legíveis.

Funções úteis: texto (`CONCAT`, `UPPER`, `SUBSTRING`), datas (`NOW()`, `DATE_FORMAT`, `DATEDIFF`, `DATE_SUB`), condicionais (`CASE WHEN`, `IF`, `COALESCE`).

**Window functions** (MySQL 8) calculam sobre "janelas" sem agrupar: `RANK() OVER (ORDER BY nota DESC)`.

### Prática
```sql
SELECT nome, nota
FROM aluno
WHERE nota > (SELECT AVG(nota) FROM aluno);

WITH medias AS (
  SELECT turma, AVG(nota) AS media FROM aluno GROUP BY turma
)
SELECT * FROM medias WHERE media >= 7;

SELECT nome, nota,
       RANK() OVER (ORDER BY nota DESC) AS posicao,
       CASE WHEN nota >= 7 THEN 'aprovado' ELSE 'recuperação' END AS situacao
FROM aluno;
```

### Questão
O que é uma subconsulta?
- [ ] Uma tabela temporária física
- [x] Um SELECT usado dentro de outro comando SQL
- [ ] Um índice
- [ ] Uma procedure
> O resultado da subconsulta alimenta a consulta externa.

### Questão
Para que serve `WITH ... AS (...)` (CTE)?
- [ ] Criar uma tabela permanente
- [x] Nomear uma consulta auxiliar e deixar a consulta principal mais legível
- [ ] Criar um usuário
- [ ] Fazer backup
> CTEs funcionam como tabelas temporárias nomeadas durante a consulta.

### Questão
O que `COALESCE(a, b)` retorna?
- [ ] Sempre b
- [x] O primeiro valor não nulo entre a e b
- [ ] A soma de a e b
- [ ] NULL
> Útil para trocar NULL por um valor padrão.

## Índices e desempenho
### Teoria
Um **índice** funciona como o índice de um livro: permite achar linhas sem ler a tabela inteira. Chaves primárias e colunas `UNIQUE` já têm índice.

Crie índices em colunas muito usadas em `WHERE`, `JOIN` e `ORDER BY`. Índices **compostos** (`(id_cliente, criado_em)`) servem para consultas que filtram pela primeira coluna (regra do prefixo à esquerda).

Índices aceleram leituras, mas ocupam espaço e deixam escritas um pouco mais lentas: não indexe tudo.

Use `EXPLAIN` antes de uma consulta para ver se ela usa índices (`type: ALL` indica varredura completa).

### Prática
```sql
EXPLAIN SELECT * FROM pedido WHERE id_cliente = 42 ORDER BY criado_em DESC;

CREATE INDEX idx_pedido_cliente_data ON pedido (id_cliente, criado_em);

EXPLAIN SELECT * FROM pedido WHERE id_cliente = 42 ORDER BY criado_em DESC;
-- agora o "key" mostra idx_pedido_cliente_data e o número de linhas lidas cai
```

### Questão
Qual a principal vantagem de um índice?
- [ ] Ocupar menos espaço
- [x] Acelerar buscas, evitando ler a tabela inteira
- [ ] Deixar inserções mais rápidas
- [ ] Criptografar dados
> O banco localiza as linhas diretamente pelo índice.

### Questão
Para que serve o comando `EXPLAIN`?
- [ ] Explicar a sintaxe SQL
- [x] Mostrar o plano de execução da consulta, incluindo os índices usados
- [ ] Apagar índices
- [ ] Traduzir a consulta
> Ele revela varreduras completas e índices escolhidos.

### Questão
Qual a desvantagem de criar índices demais?
- [ ] Nenhuma
- [x] Mais espaço em disco e escritas (INSERT/UPDATE) mais lentas
- [ ] Consultas SELECT ficam mais lentas sempre
- [ ] A tabela deixa de aceitar NULL
> Cada índice precisa ser atualizado a cada escrita.

## Views, procedures e segurança
### Teoria
- **View:** consulta salva que se comporta como tabela virtual. Simplifica consultas recorrentes e esconde colunas sensíveis.
- **Stored procedure:** bloco de SQL armazenado no banco e executado com `CALL`.
- **Trigger:** código executado automaticamente antes/depois de INSERT, UPDATE ou DELETE.

Segurança:
- Crie **usuários com o mínimo de privilégios** necessários (`GRANT SELECT ON loja.* TO ...`); não use `root` na aplicação.
- Na aplicação, use sempre **consultas parametrizadas** para evitar SQL injection.
- Faça **backups** regulares com `mysqldump`.

### Prática
```sql
CREATE VIEW vw_resumo_cliente AS
SELECT c.id, c.nome, COUNT(p.id) AS pedidos, COALESCE(SUM(p.total), 0) AS gasto
FROM cliente c LEFT JOIN pedido p ON p.id_cliente = c.id
GROUP BY c.id, c.nome;

SELECT * FROM vw_resumo_cliente WHERE gasto > 100;

DELIMITER //
CREATE PROCEDURE registrar_pedido(IN cliente INT, IN valor DECIMAL(10, 2))
BEGIN
  INSERT INTO pedido (id_cliente, total) VALUES (cliente, valor);
END //
DELIMITER ;

CALL registrar_pedido(1, 59.90);

CREATE USER 'relatorios'@'localhost' IDENTIFIED BY 'SenhaForte!2026';
GRANT SELECT ON loja.* TO 'relatorios'@'localhost';
```

Backup pelo terminal: `mysqldump -u root loja > loja.sql`.

### Questão
O que é uma view?
- [ ] Uma cópia física da tabela
- [x] Uma consulta salva que funciona como tabela virtual
- [ ] Um tipo de índice
- [ ] Um usuário do banco
> A view executa a consulta subjacente quando é usada.

### Questão
Qual o princípio de segurança ao criar usuários de banco para uma aplicação?
- [ ] Usar sempre root
- [x] Dar apenas os privilégios mínimos necessários
- [ ] Dar todos os privilégios para evitar erros
- [ ] Não usar senha
> Se a aplicação for comprometida, o estrago fica limitado.

### Questão
Como executar uma stored procedure?
- [ ] RUN nome()
- [x] CALL nome(...)
- [ ] EXEC nome
- [ ] SELECT nome
> CALL invoca procedures no MySQL.
