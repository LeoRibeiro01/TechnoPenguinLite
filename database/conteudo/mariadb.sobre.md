@historia
Quando a Sun Microsystems comprou o MySQL em 2008, e principalmente quando a **Oracle** comprou a Sun em 2010, parte da comunidade temeu pelo futuro do banco livre: a Oracle já vendia o banco comercial mais famoso do mundo. **Michael "Monty" Widenius**, criador original do MySQL, saiu da empresa e, em 2009, criou um **fork** (cópia que segue seu próprio caminho): o **MariaDB**, batizado em homenagem à sua filha mais nova, **Maria** (o MySQL tinha o nome da filha mais velha, My).

O mascote é uma foca. A **MariaDB Foundation**, sem fins lucrativos, garante que o banco continue livre (licença GPL). Até a versão 5.5 o MariaDB acompanhou a numeração do MySQL; depois pulou para a 10.0 (2014), sinalizando que os dois passariam a evoluir de forma independente. Desde 2023 há versões de suporte longo e versões de inovação.

@hoje
O MariaDB é o banco padrão em várias distribuições Linux, como **Debian**, **Fedora**, **Red Hat Enterprise Linux** e **openSUSE**, e vem no **XAMPP** (o "MySQL" do painel do XAMPP é, na verdade, o MariaDB). É usado por empresas como Wikipedia (Wikimedia), Google, Booking.com e bancos. Os provedores de nuvem oferecem MariaDB gerenciado, e o **Galera Cluster** é muito usado para alta disponibilidade.

@iniciante
- Entender a relação entre MariaDB e MySQL
- Criar bancos e tabelas com utf8mb4 e InnoDB
- Inserir dados, usar upsert e RETURNING
- Fazer consultas com filtros, agregações e funções de data

@intermediario
- Escolher o motor de armazenamento certo para cada tabela
- Usar sequences, colunas virtuais, invisíveis e CHECK
- Criar tabelas versionadas pelo sistema para auditoria
- Guardar e consultar JSON com validação

@avancado
- Criar usuários, roles e aplicar o menor privilégio
- Fazer backup e restauração com mariadb-dump e Mariabackup
- Analisar desempenho com EXPLAIN, ANALYZE e slow query log
- Conhecer replicação, Galera Cluster e MaxScale

@referencias
**Livros**
- *MariaDB Crash Course* — Ben Forta: SQL com MariaDB, direto ao ponto.
- *Mastering MariaDB* — Federico Razzoli: administração e recursos avançados.
- *Aprendendo SQL* — Alan Beaulieu: os fundamentos do SQL valem igualmente para o MariaDB.

**Na internet**
- [MariaDB Knowledge Base](https://mariadb.com/kb/pt-br/) — documentação oficial (parte em português).
- [MariaDB Foundation](https://mariadb.org) — notícias e versões.
- [Compatibilidade entre MariaDB e MySQL](https://mariadb.com/kb/en/mariadb-vs-mysql-compatibility/)

@modulo 1
**Fork não é cópia parada.** O MariaDB manteve compatibilidade de protocolo e de comandos com o MySQL para facilitar a troca: conectores, o cliente de linha de comando e ferramentas como o phpMyAdmin funcionam com os dois. Mas, versão após versão, os dois divergiram: o MySQL 8 tem recursos que o MariaDB não tem (e vice-versa), e trocar um pelo outro em sistemas grandes exige testes.

No XAMPP, você pode conferir com `SELECT VERSION();`. A versão aparece como `10.x.y-MariaDB`.

Para começar, os mesmos conceitos valem: modelo relacional, chaves primárias, `utf8mb4` (o antigo `utf8` do MySQL/MariaDB só guardava até 3 bytes por caractere e não aceitava emojis) e o motor InnoDB, padrão desde o MariaDB 10.2.

@fontes 1
- Forta, *MariaDB Crash Course*, capítulos 1 a 5
- [MariaDB KB: MariaDB versus MySQL — Compatibility](https://mariadb.com/kb/en/mariadb-vs-mysql-compatibility/)
- [MariaDB KB: Character Sets](https://mariadb.com/kb/en/character-sets/)

@modulo 2
**Recursos próprios resolvem problemas reais.** As **tabelas versionadas pelo sistema** implementam o padrão temporal do SQL:2011: o banco guarda automaticamente o histórico de cada linha, com o intervalo em que ela foi válida. Isso responde perguntas de auditoria ("qual era o preço em março?") sem nenhuma tabela de log manual.

O MariaDB também implementa **sequences** do padrão SQL, colunas **geradas** (virtuais ou persistentes, que podem até ser indexadas), `RETURNING` em INSERT e DELETE e uma longa lista de motores de armazenamento, como o **ColumnStore** para análises e o **Spider** para dividir (*sharding*) dados entre servidores.

Em JSON, lembre que no MariaDB o tipo `JSON` é um apelido de `LONGTEXT`: a validação vem do `CHECK (JSON_VALID(...))`, aplicado automaticamente quando você declara a coluna como JSON nas versões recentes.

@fontes 2
- [MariaDB KB: System-Versioned Tables](https://mariadb.com/kb/en/system-versioned-tables/)
- [MariaDB KB: Sequences](https://mariadb.com/kb/en/sequences/)
- Razzoli, *Mastering MariaDB*, capítulos sobre motores de armazenamento

@modulo 3
**Disponibilidade é planejada, não improvisada.** Na **replicação assíncrona** clássica, o primário grava o *binary log* e as réplicas aplicam as mudanças com um pequeno atraso; se o primário cair, pode-se perder o que ainda não foi replicado. O **Galera Cluster** usa replicação síncrona baseada em certificação: uma transação só é confirmada quando todos os nós concordam, então nenhum dado confirmado se perde, ao custo de latência maior nas escritas.

Faça backups em camadas: dumps lógicos frequentes para bases pequenas, **Mariabackup** para bases grandes e *binary logs* para recuperar até um ponto exato no tempo (*point-in-time recovery*). Teste a restauração regularmente.

Para desempenho, o primeiro ajuste é o `innodb_buffer_pool_size` (em servidores dedicados, algo como 70% da RAM). O segundo é ler o slow query log e criar os índices certos.

@fontes 3
- [MariaDB KB: Galera Cluster](https://mariadb.com/kb/en/galera-cluster/)
- [MariaDB KB: Mariabackup Overview](https://mariadb.com/kb/en/mariabackup-overview/)
- Razzoli, *Mastering MariaDB*, capítulos sobre backup e replicação
