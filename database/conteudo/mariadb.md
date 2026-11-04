# Conhecendo o MariaDB
> Origem, instalação, clientes e primeiros comandos SQL.

## Projeto
**Banco do clube de pinguins:** no MariaDB do XAMPP, crie o banco `clube` com as tabelas `pinguim` (id, nome, espécie, nascimento) e `atividade` (id, id_pinguim, tipo, data, minutos). Insira dados e escreva consultas para o total de minutos por pinguim e as atividades da última semana.

## MariaDB e MySQL
### Teoria
O **MariaDB** é um sistema de banco de dados relacional **livre e de código aberto**, criado em 2009 pelos desenvolvedores originais do MySQL (liderados por Michael "Monty" Widenius) depois que o MySQL foi comprado pela Oracle.

Ele começou como um **fork** (cópia que evolui separadamente) e mantém alta compatibilidade com o MySQL: os mesmos comandos SQL, conectores e ferramentas funcionam na maioria dos casos. Por isso, o "MySQL" que vem no **XAMPP** é, na verdade, o MariaDB!

Com o tempo, os dois divergiram: cada um tem recursos exclusivos, motores de armazenamento e versões próprias.

### Prática
No terminal do XAMPP (ou pela aba SQL do phpMyAdmin):

```sql
SELECT VERSION();
-- algo como 10.4.32-MariaDB

SHOW DATABASES;
CREATE DATABASE clube CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clube;
SHOW ENGINES;
```

No terminal: `C:\xampp\mysql\bin\mysql.exe -u root`.

### Questão
Como o MariaDB surgiu?
- [ ] Como produto da Oracle
- [x] Como um fork do MySQL criado pelos seus desenvolvedores originais
- [ ] Como uma versão do PostgreSQL
- [ ] Como um banco NoSQL
> Monty Widenius criou o MariaDB após a aquisição do MySQL pela Oracle.

### Questão
Qual banco o XAMPP instala, embora apareça como "MySQL" no painel?
- [ ] PostgreSQL
- [x] MariaDB
- [ ] SQLite
- [ ] Oracle
> O XAMPP usa MariaDB por ser livre e compatível com MySQL.

### Questão
O que significa ser "compatível com MySQL"?
- [ ] Que é exatamente o mesmo software
- [x] Que a maioria dos comandos, conectores e ferramentas do MySQL funcionam nele
- [ ] Que só funciona junto com o MySQL
- [ ] Que não aceita SQL
> A compatibilidade é alta, mas não total nas versões mais recentes.

## Criando tabelas e inserindo dados
### Teoria
A sintaxe de criação é a mesma do MySQL:
- `CREATE TABLE` com colunas, tipos e restrições (`PRIMARY KEY`, `NOT NULL`, `UNIQUE`, `DEFAULT`).
- `AUTO_INCREMENT` gera ids.
- Motor padrão: **InnoDB** (transações e chaves estrangeiras).

Charset recomendado: `utf8mb4`, que suporta todos os caracteres, inclusive emojis 🐧.

`INSERT INTO ... VALUES` adiciona linhas. `INSERT IGNORE` ignora duplicatas e `INSERT ... ON DUPLICATE KEY UPDATE` atualiza se a chave já existir. O MariaDB também aceita `INSERT ... RETURNING` para devolver as linhas inseridas.

### Prática
```sql
CREATE TABLE pinguim (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(60) NOT NULL UNIQUE,
  especie VARCHAR(40) NOT NULL DEFAULT 'Imperador',
  nascimento DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO pinguim (nome, especie, nascimento) VALUES
  ('Teco', 'Imperador', '2022-05-01'),
  ('Pipo', 'Adélia', '2023-01-12');

INSERT INTO pinguim (nome) VALUES ('Teco')
  ON DUPLICATE KEY UPDATE especie = 'Rei';

INSERT INTO pinguim (nome) VALUES ('Ana') RETURNING id, nome;
```

### Questão
Qual charset suporta emojis corretamente?
- [ ] latin1
- [ ] utf8 (de 3 bytes)
- [x] utf8mb4
- [ ] ascii
> utf8mb4 usa até 4 bytes por caractere.

### Questão
O que `ON DUPLICATE KEY UPDATE` faz?
- [ ] Apaga o registro duplicado
- [x] Atualiza a linha existente quando a inserção violaria uma chave única
- [ ] Gera um erro mais claro
- [ ] Cria uma nova tabela
> É um "upsert": insere ou atualiza.

### Questão
Qual recurso do MariaDB devolve as linhas recém-inseridas?
- [ ] SELECT LAST
- [x] INSERT ... RETURNING
- [ ] OUTPUT INSERTED
- [ ] SHOW INSERT
> RETURNING evita uma consulta extra para buscar o que foi inserido.

## Consultas essenciais
### Teoria
As consultas seguem o SQL padrão:
- `SELECT ... FROM ... WHERE ... ORDER BY ... LIMIT`.
- Agregações com `COUNT`, `SUM`, `AVG` e `GROUP BY`, filtrando grupos com `HAVING`.
- `JOIN` para combinar tabelas.

Funções de data são muito úteis: `CURDATE()`, `NOW()`, `DATE_SUB(CURDATE(), INTERVAL 7 DAY)`, `TIMESTAMPDIFF(YEAR, nascimento, CURDATE())`.

### Prática
```sql
SELECT nome, TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) AS idade
FROM pinguim
ORDER BY idade DESC;

SELECT p.nome, SUM(a.minutos) AS minutos_semana
FROM pinguim p
JOIN atividade a ON a.id_pinguim = p.id
WHERE a.data >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY p.id, p.nome
HAVING SUM(a.minutos) >= 30
ORDER BY minutos_semana DESC;
```

### Questão
Qual expressão representa "7 dias atrás"?
- [ ] CURDATE() - 7
- [x] DATE_SUB(CURDATE(), INTERVAL 7 DAY)
- [ ] NOW(-7)
- [ ] DAYS_AGO(7)
> DATE_SUB subtrai intervalos de datas com segurança.

### Questão
Qual cláusula filtra resultados depois do GROUP BY?
- [ ] WHERE
- [x] HAVING
- [ ] LIMIT
- [ ] ON
> HAVING atua sobre os grupos agregados.

### Questão
O que `TIMESTAMPDIFF(YEAR, nascimento, CURDATE())` calcula?
- [ ] A data de nascimento
- [x] A idade em anos completos
- [ ] O número de dias vividos
- [ ] O ano atual
> Diferença entre as datas, na unidade pedida.

# Recursos do MariaDB
> Motores de armazenamento, sequências, tabelas versionadas e JSON.

## Projeto
**Histórico do iglu:** crie uma tabela `iglu` **versionada pelo sistema** (`WITH SYSTEM VERSIONING`), faça várias alterações no nome e na descrição e consulte o estado da tabela em momentos passados com `FOR SYSTEM_TIME AS OF`. Use uma `SEQUENCE` para gerar códigos de convite.

## Motores de armazenamento
### Teoria
O MariaDB permite escolher o **motor de armazenamento** de cada tabela, ou seja, como os dados são gravados:

- **InnoDB:** padrão; transações ACID, chaves estrangeiras e bloqueio por linha. Use quase sempre.
- **Aria:** substituto do MyISAM, resistente a quedas; usado em tabelas internas.
- **MEMORY:** dados só na RAM; muito rápido, mas some ao reiniciar.
- **ColumnStore:** armazenamento colunar para análises em grande volume.
- **Spider** e **CONNECT:** acessam dados distribuídos ou externos (CSV, outras bases).

Veja os disponíveis com `SHOW ENGINES;`.

### Prática
```sql
CREATE TABLE sessao_cache (
  token CHAR(64) PRIMARY KEY,
  id_usuario INT UNSIGNED NOT NULL,
  expira_em DATETIME NOT NULL
) ENGINE=MEMORY;

ALTER TABLE pinguim ENGINE=InnoDB;

SELECT table_name, engine
FROM information_schema.tables
WHERE table_schema = 'clube';
```

### Questão
Qual motor é o padrão e suporta transações e chaves estrangeiras?
- [ ] MEMORY
- [x] InnoDB
- [ ] CSV
- [ ] Aria
> InnoDB é a escolha recomendada para a maioria das tabelas.

### Questão
O que acontece com os dados de uma tabela MEMORY ao reiniciar o servidor?
- [ ] São mantidos
- [x] São perdidos
- [ ] São copiados para InnoDB
- [ ] São compactados
> MEMORY guarda tudo na RAM; a estrutura permanece, os dados não.

### Questão
Para que serve o ColumnStore?
- [ ] Guardar imagens
- [x] Análises de grandes volumes com armazenamento colunar
- [ ] Criar usuários
- [ ] Cache de sessões
> Armazenar por coluna acelera agregações em tabelas enormes.

## Sequences e colunas especiais
### Teoria
Além do `AUTO_INCREMENT`, o MariaDB oferece:

- **SEQUENCE:** objeto independente que gera números (`NEXT VALUE FOR seq`), compartilhável entre tabelas e com início, incremento e ciclo configuráveis.
- **Colunas virtuais/geradas:** calculadas a partir de outras (`AS (expressão) VIRTUAL` ou `PERSISTENT`).
- **Colunas invisíveis:** existem, mas não aparecem no `SELECT *`.
- **CHECK constraints:** validam valores (`CHECK (minutos > 0)`).

### Prática
```sql
CREATE SEQUENCE seq_convite START WITH 1000 INCREMENT BY 1;

SELECT NEXT VALUE FOR seq_convite;   -- 1000
SELECT NEXT VALUE FOR seq_convite;   -- 1001

CREATE TABLE atividade (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pinguim INT UNSIGNED NOT NULL,
  minutos SMALLINT NOT NULL CHECK (minutos > 0),
  horas DECIMAL(5, 2) AS (minutos / 60) VIRTUAL,
  criado_por VARCHAR(30) INVISIBLE DEFAULT 'sistema'
);
```

### Questão
Qual a vantagem de uma SEQUENCE sobre AUTO_INCREMENT?
- [ ] Nenhuma
- [x] É um objeto independente, configurável e que pode ser usado por várias tabelas
- [ ] É sempre mais rápida
- [ ] Gera textos
> SEQUENCE separa a geração de números da tabela.

### Questão
O que é uma coluna VIRTUAL?
- [ ] Uma coluna que só existe em views
- [x] Uma coluna calculada a partir de outras colunas
- [ ] Uma coluna sem tipo
- [ ] Uma coluna criptografada
> VIRTUAL calcula na leitura; PERSISTENT grava o valor calculado.

### Questão
O que uma constraint `CHECK (minutos > 0)` faz?
- [ ] Cria um índice
- [x] Rejeita inserções ou alterações com minutos menor ou igual a zero
- [ ] Converte valores negativos
- [ ] Apenas documenta
> CHECK garante regras de validação no próprio banco.

## Tabelas versionadas e JSON
### Teoria
**Tabelas versionadas pelo sistema** (system-versioned tables) guardam automaticamente o **histórico** de todas as alterações. Você consulta como os dados estavam em qualquer momento:

```
SELECT * FROM tabela FOR SYSTEM_TIME AS OF '2026-01-01 10:00:00';
```

Ótimo para auditoria e para recuperar dados alterados por engano.

O MariaDB também tem **funções JSON** para guardar e consultar documentos JSON em colunas: `JSON_VALUE`, `JSON_EXTRACT`, `JSON_OBJECT`, `JSON_ARRAYAGG` e a validação `CHECK (JSON_VALID(coluna))`.

### Prática
```sql
CREATE TABLE iglu (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(60) NOT NULL,
  config JSON CHECK (JSON_VALID(config))
) WITH SYSTEM VERSIONING;

INSERT INTO iglu (nome, config) VALUES ('Pinguins do SQL', '{"publico": true, "trilhas": ["mariadb"]}');
UPDATE iglu SET nome = 'Pinguins do MariaDB' WHERE id = 1;

SELECT * FROM iglu FOR SYSTEM_TIME ALL;   -- todas as versões
SELECT nome, JSON_VALUE(config, '$.publico') AS publico FROM iglu;
```

### Questão
O que uma tabela WITH SYSTEM VERSIONING guarda?
- [ ] Apenas a versão do MariaDB
- [x] O histórico de todas as versões das linhas ao longo do tempo
- [ ] Backups em outro servidor
- [ ] Logs de acesso
> Cada alteração preserva a versão anterior com seu intervalo de validade.

### Questão
Qual cláusula consulta os dados como estavam num momento passado?
- [ ] WHERE data = ...
- [x] FOR SYSTEM_TIME AS OF '...'
- [ ] HISTORY('...')
- [ ] SNAPSHOT '...'
> FOR SYSTEM_TIME é a sintaxe para tabelas versionadas.

### Questão
Como validar que uma coluna contém JSON válido?
- [ ] Com o tipo TEXT
- [x] Com CHECK (JSON_VALID(coluna))
- [ ] Com UNIQUE
- [ ] Não é possível
> No MariaDB, JSON é um apelido de LONGTEXT; a validação vem do CHECK.

# Administração e desempenho
> Usuários, backups, índices, replicação e Galera Cluster.

## Projeto
**Plano de operação do banco:** para o banco `clube`, crie um usuário só de leitura e outro de aplicação, agende um backup diário com `mariadb-dump` (ou `mysqldump` no XAMPP), restaure o backup numa base de teste, e analise três consultas com `EXPLAIN` e `ANALYZE` criando os índices necessários.

## Usuários e permissões
### Teoria
Cada aplicação deve ter seu próprio usuário, com o **mínimo de privilégios** necessários:

- `CREATE USER 'app'@'localhost' IDENTIFIED BY 'senha';`
- `GRANT SELECT, INSERT, UPDATE, DELETE ON clube.* TO 'app'@'localhost';`
- `REVOKE` retira privilégios; `SHOW GRANTS` mostra os atuais.
- **Roles** agrupam privilégios e podem ser atribuídas a vários usuários.

No XAMPP, o `root` vem **sem senha**: tudo bem para estudos locais, mas nunca em produção.

### Prática
```sql
CREATE ROLE leitura;
GRANT SELECT ON clube.* TO leitura;

CREATE USER 'relatorio'@'localhost' IDENTIFIED BY 'Gelo!Forte2026';
GRANT leitura TO 'relatorio'@'localhost';
SET DEFAULT ROLE leitura FOR 'relatorio'@'localhost';

CREATE USER 'app_clube'@'localhost' IDENTIFIED BY 'OutraSenha!2026';
GRANT SELECT, INSERT, UPDATE, DELETE ON clube.* TO 'app_clube'@'localhost';

SHOW GRANTS FOR 'app_clube'@'localhost';
```

### Questão
Qual comando concede privilégios a um usuário?
- [ ] ALLOW
- [x] GRANT
- [ ] PERMIT
- [ ] GIVE
> GRANT concede; REVOKE retira.

### Questão
Para que servem as roles?
- [ ] Criar tabelas
- [x] Agrupar privilégios para atribuí-los a vários usuários de uma vez
- [ ] Fazer backup
- [ ] Definir o charset
> Mudou a role, mudam as permissões de todos que a usam.

### Questão
Por que a aplicação não deve usar o usuário root?
- [ ] Porque root é lento
- [x] Porque, se a aplicação for invadida, o atacante teria controle total do servidor de banco
- [ ] Porque root não acessa tabelas
- [ ] Não há problema
> Privilégio mínimo limita o estrago de uma falha.

## Backup e restauração
### Teoria
Sem backup, não há banco seguro. Opções principais:

- **Backup lógico** com `mariadb-dump` (antigo `mysqldump`): gera um arquivo `.sql` com os comandos para recriar estrutura e dados. Simples e portátil.
- **Backup físico** com **Mariabackup**: copia os arquivos de dados com o banco ligado; mais rápido para bases grandes.

Use `--single-transaction` para um backup consistente de tabelas InnoDB sem travá-las.

E o mais importante: **teste a restauração** regularmente. Um backup que nunca foi restaurado não é confiável.

### Prática
```
:: Backup (Windows/XAMPP)
C:\xampp\mysql\bin\mysqldump.exe -u root --single-transaction --routines clube > clube_2026-10-30.sql

:: Restaurar numa base de teste
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE clube_teste"
C:\xampp\mysql\bin\mysql.exe -u root clube_teste < clube_2026-10-30.sql
```

```sql
-- confira a restauração
SELECT COUNT(*) FROM clube_teste.pinguim;
```

### Questão
O que um backup lógico gera?
- [ ] Uma cópia dos arquivos binários do disco
- [x] Um arquivo com comandos SQL para recriar estrutura e dados
- [ ] Uma imagem do sistema operacional
- [ ] Um índice
> mariadb-dump/mysqldump exportam CREATE e INSERT.

### Questão
Para que serve `--single-transaction`?
- [ ] Acelerar a restauração
- [x] Fazer um backup consistente de tabelas InnoDB sem travá-las
- [ ] Exportar só uma tabela
- [ ] Compactar o arquivo
> O dump lê um instantâneo consistente dentro de uma transação.

### Questão
Qual a prática mais importante em relação a backups?
- [ ] Guardar no mesmo disco do banco
- [x] Testar a restauração regularmente
- [ ] Fazer só uma vez por ano
- [ ] Apagar os antigos imediatamente
> Só a restauração testada comprova que o backup funciona.

## Desempenho e alta disponibilidade
### Teoria
Desempenho começa nas consultas:
- `EXPLAIN` mostra o plano; o `ANALYZE` do MariaDB executa a consulta e mostra números reais.
- Crie **índices** para colunas de filtros, junções e ordenação.
- Ative o **slow query log** para encontrar consultas lentas.
- Ajuste `innodb_buffer_pool_size` (memória para dados e índices).

Alta disponibilidade:
- **Replicação:** um servidor primário envia as alterações para réplicas, que podem atender leituras e assumir em caso de falha.
- **Galera Cluster:** replicação **síncrona multi-primária**: todos os nós aceitam escrita e ficam consistentes.
- **MaxScale:** proxy que distribui consultas entre os servidores.

### Prática
```sql
ANALYZE SELECT p.nome, SUM(a.minutos)
FROM pinguim p JOIN atividade a ON a.id_pinguim = p.id
WHERE a.data >= '2026-10-01'
GROUP BY p.id;

CREATE INDEX idx_atividade_data_pinguim ON atividade (data, id_pinguim);

SET GLOBAL slow_query_log = 1;
SET GLOBAL long_query_time = 1;   -- registra consultas acima de 1 segundo
SHOW VARIABLES LIKE 'innodb_buffer_pool_size';
```

### Questão
Qual a diferença entre `EXPLAIN` e `ANALYZE` no MariaDB?
- [ ] Nenhuma
- [x] ANALYZE executa a consulta e mostra estatísticas reais; EXPLAIN só estima o plano
- [ ] EXPLAIN apaga índices
- [ ] ANALYZE só funciona em views
> Os números reais revelam onde a estimativa errou.

### Questão
O que caracteriza o Galera Cluster?
- [ ] Backup em nuvem
- [x] Replicação síncrona em que vários nós aceitam escrita
- [ ] Um motor de armazenamento em memória
- [ ] Uma interface gráfica
> Todos os nós confirmam as transações juntos.

### Questão
Para que serve o slow query log?
- [ ] Deixar consultas mais lentas
- [x] Registrar consultas que demoram mais que um limite, para otimização
- [ ] Guardar backups
- [ ] Listar usuários
> Ele aponta as consultas que mais merecem atenção.
