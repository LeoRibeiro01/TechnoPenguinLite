# Fundamentos da modelagem
> Por que modelar, entidades, atributos e o modelo conceitual.

## Projeto
**Modelo da escola de natação dos pinguins:** a partir de um texto de requisitos (alunos, turmas, professores, piscinas e horários), identifique entidades, atributos e chaves, e desenhe o modelo conceitual (DER) numa ferramenta como draw.io, brModelo ou dbdiagram.io.

## Por que modelar dados
### Teoria
**Modelar** é planejar como os dados serão organizados **antes** de criar as tabelas. Um bom modelo evita dados repetidos, inconsistências e retrabalho.

A modelagem acontece em três níveis:
- **Conceitual:** visão do negócio, independente de tecnologia (o DER: entidades e relacionamentos).
- **Lógico:** tabelas, colunas e chaves, seguindo um modelo (o relacional).
- **Físico:** o script SQL para um banco específico, com tipos, índices e restrições.

Tudo começa entendendo o problema: converse com quem conhece o negócio e leia os requisitos procurando **substantivos** (candidatos a entidades) e **verbos** (candidatos a relacionamentos).

### Prática
Requisito: *"Um pinguim se matricula em turmas de natação. Cada turma tem um professor e acontece numa piscina."*

- Substantivos: pinguim, turma, professor, piscina → **entidades**.
- Verbos: se matricula, tem, acontece → **relacionamentos**.
- Detalhes como nome do pinguim e horário da turma → **atributos**.

Esse exercício de "caça aos substantivos" é o primeiro passo de todo modelo.

### Questão
Qual nível de modelagem é independente de tecnologia?
- [x] Conceitual
- [ ] Lógico
- [ ] Físico
- [ ] Nenhum
> O modelo conceitual descreve o negócio, sem falar de tabelas ou SGBD.

### Questão
Num texto de requisitos, os substantivos costumam indicar:
- [ ] Relacionamentos
- [x] Entidades (ou atributos)
- [ ] Índices
- [ ] Consultas
> Verbos tendem a indicar relacionamentos.

### Questão
Qual a principal vantagem de modelar antes de criar o banco?
- [ ] Deixar o banco mais bonito
- [x] Evitar redundâncias, inconsistências e retrabalho
- [ ] Dispensar o SQL
- [ ] Aumentar o número de tabelas
> Corrigir um modelo no papel é muito mais barato que no banco em produção.

## Entidades e atributos
### Teoria
Uma **entidade** é algo do mundo real sobre o qual queremos guardar dados (Pinguim, Turma). Cada ocorrência é uma **instância**.

**Atributos** descrevem a entidade. Tipos:
- **Simples:** nome.
- **Composto:** endereço (rua, número, cidade).
- **Multivalorado:** telefones (vários valores).
- **Derivado:** idade (calculada a partir do nascimento).
- **Identificador (chave):** distingue cada instância, como a matrícula.

Uma boa chave é **única, estável e mínima**. Muitas vezes usamos uma **chave substituta** (id numérico) em vez de dados do mundo real que podem mudar.

### Prática
Entidade **Pinguim**:
- `matricula` (identificador)
- `nome` (simples)
- `endereco` (composto: rua, número, iceberg)
- `telefones` (multivalorado)
- `nascimento` (simples) e `idade` (derivado, não precisa ser armazenado)

Representação no DER: retângulo para a entidade, elipses para atributos, e o identificador sublinhado.

### Questão
"Telefones" de um pinguim que pode ter vários números é um atributo:
- [ ] Simples
- [ ] Derivado
- [x] Multivalorado
- [ ] Identificador
> Atributos multivalorados aceitam vários valores para a mesma instância.

### Questão
Por que "idade" é um atributo derivado?
- [ ] Porque é um número
- [x] Porque pode ser calculado a partir da data de nascimento
- [ ] Porque muda todo ano e deve ser apagado
- [ ] Porque é chave
> Guardar a data de nascimento evita atualizar a idade todo ano.

### Questão
Qual característica é desejável numa chave?
- [ ] Mudar com frequência
- [x] Ser única e estável
- [ ] Ser um texto longo
- [ ] Repetir-se entre instâncias
> Chaves identificam cada instância de forma permanente.

## Relacionamentos e cardinalidade
### Teoria
Um **relacionamento** liga entidades: Professor **ministra** Turma.

A **cardinalidade** diz quantas instâncias se relacionam:
- **1:1** — um pinguim tem um crachá.
- **1:N** — um professor ministra várias turmas; cada turma tem um professor.
- **N:N** — um pinguim se matricula em várias turmas e cada turma tem vários pinguins.

A **participação** pode ser **obrigatória** (toda turma tem um professor) ou **opcional** (um professor pode estar sem turma). Relacionamentos também podem ter atributos, como a **data da matrícula**.

### Prática
```
Professor (1) ──ministra── (N) Turma
Pinguim   (N) ──matricula── (N) Turma   [data_matricula]
Turma     (N) ──acontece em── (1) Piscina
```

Leia nos dois sentidos para conferir: "um professor ministra **várias** turmas" e "uma turma é ministrada por **um** professor" → 1:N.

### Questão
"Um pinguim faz várias turmas e cada turma tem vários pinguins." Qual a cardinalidade?
- [ ] 1:1
- [ ] 1:N
- [x] N:N
- [ ] 0:0
> Muitos para muitos nos dois sentidos.

### Questão
Onde guardar a "data da matrícula" num relacionamento Pinguim–Turma?
- [ ] Na entidade Pinguim
- [ ] Na entidade Turma
- [x] Como atributo do relacionamento
- [ ] Não é possível guardar
> A data pertence à matrícula (à ligação), não a um dos lados isolado.

### Questão
Como conferir a cardinalidade de um relacionamento?
- [ ] Contando as letras dos nomes
- [x] Lendo o relacionamento nos dois sentidos
- [ ] Olhando apenas a entidade mais importante
- [ ] Pela ordem alfabética
> Cada sentido revela um lado da cardinalidade.

# Modelo lógico e normalização
> Do DER às tabelas, chaves estrangeiras e formas normais.

## Projeto
**Normalizando a planilha da cantina:** receba uma planilha "tudo numa tabela" (pedido, cliente, telefone, itens, preços, categoria), identifique as anomalias e normalize até a 3FN. Entregue o modelo lógico com chaves primárias e estrangeiras.

## Do conceitual para tabelas
### Teoria
Regras de mapeamento para o modelo relacional:
- Cada **entidade** vira uma **tabela**; cada atributo vira uma coluna; o identificador vira **chave primária**.
- **1:N:** a chave primária do lado "1" vira **chave estrangeira** no lado "N".
- **N:N:** cria-se uma **tabela associativa** com as chaves das duas entidades (e os atributos do relacionamento).
- **1:1:** a chave estrangeira vai para um dos lados (geralmente o de participação obrigatória), ou as tabelas são fundidas.
- **Multivalorado:** vira uma tabela própria (ex.: `telefone_pinguim`).

### Prática
```
professor (id_professor PK, nome)
turma     (id_turma PK, horario, id_professor FK → professor, id_piscina FK → piscina)
pinguim   (id_pinguim PK, nome, nascimento)
matricula (id_pinguim FK, id_turma FK, data_matricula, PK(id_pinguim, id_turma))
telefone_pinguim (id_pinguim FK, numero, PK(id_pinguim, numero))
```

### Questão
Num relacionamento 1:N, onde fica a chave estrangeira?
- [ ] No lado 1
- [x] No lado N
- [ ] Numa tabela nova sempre
- [ ] Em nenhum lado
> Cada turma (lado N) guarda o id do seu professor (lado 1).

### Questão
Como representar um relacionamento N:N no modelo relacional?
- [ ] Com uma coluna de texto com vários ids
- [x] Com uma tabela associativa contendo as chaves das duas entidades
- [ ] Repetindo as linhas
- [ ] Não é possível
> A tabela matricula liga pinguins e turmas.

### Questão
Como mapear um atributo multivalorado, como telefones?
- [ ] Colunas telefone1, telefone2, telefone3...
- [x] Numa tabela própria ligada à entidade
- [ ] Separando por vírgulas numa coluna
- [ ] Ignorando o atributo
> Uma tabela separada permite quantos telefones forem necessários.

## Normalização: 1FN e 2FN
### Teoria
A **normalização** organiza as tabelas para eliminar redundância e **anomalias** (de inserção, atualização e exclusão).

**1ª Forma Normal (1FN):** todos os valores são **atômicos** (indivisíveis) e não há grupos repetidos. Nada de "Python, Java" numa célula ou colunas `item1, item2, item3`.

**2ª Forma Normal (2FN):** está na 1FN e todo atributo não-chave depende da **chave inteira**, não de parte dela. Só é um problema em tabelas com **chave composta**.

### Prática
Tabela original `item_pedido (id_pedido, id_produto, quantidade, nome_produto, preco_produto)`, com chave `(id_pedido, id_produto)`:

- `quantidade` depende do pedido **e** do produto → OK.
- `nome_produto` e `preco_produto` dependem só de `id_produto` → **viola a 2FN**.

Solução: mover para `produto (id_produto, nome, preco)` e deixar `item_pedido (id_pedido, id_produto, quantidade, preco_unitario)`.

### Questão
Uma coluna "linguagens" com o valor "Python, Java" viola qual forma normal?
- [x] 1FN
- [ ] 2FN
- [ ] 3FN
- [ ] Nenhuma
> Valores devem ser atômicos na 1FN.

### Questão
A 2FN trata de dependências de:
- [ ] Atributos multivalorados
- [x] Parte de uma chave primária composta
- [ ] Atributos não-chave entre si
- [ ] Índices
> Atributos devem depender da chave inteira.

### Questão
Qual anomalia ocorre quando mudar o preço de um produto exige alterar muitas linhas?
- [ ] De inserção
- [x] De atualização
- [ ] De índice
- [ ] De conexão
> Dados repetidos precisam ser atualizados em vários lugares, com risco de inconsistência.

## 3FN e desnormalização
### Teoria
**3ª Forma Normal (3FN):** está na 2FN e **nenhum atributo não-chave depende de outro atributo não-chave** (sem dependências transitivas).

Exemplo: em `aluno (id, nome, id_turma, nome_turma)`, `nome_turma` depende de `id_turma`, não do aluno. Ele deve ir para a tabela `turma`.

Regra mnemônica: cada atributo depende "da chave, da chave inteira e de nada além da chave".

A **desnormalização** é a escolha consciente de manter alguma redundância para ganhar desempenho em leituras (relatórios, contadores). Faça só quando houver necessidade medida, e mantenha a consistência (por exemplo, com triggers ou rotinas).

### Prática
Antes (viola 3FN):

```
pinguim (id_pinguim, nome, id_colonia, nome_colonia, regiao_colonia)
```

Depois:

```
colonia (id_colonia, nome, regiao)
pinguim (id_pinguim, nome, id_colonia FK → colonia)
```

Agora mudar a região de uma colônia altera **uma** linha só.

### Questão
O que a 3FN elimina?
- [ ] Chaves compostas
- [x] Dependências transitivas entre atributos não-chave
- [ ] Todas as chaves estrangeiras
- [ ] Atributos numéricos
> Atributos não-chave devem depender só da chave.

### Questão
Em `aluno (id, nome, id_turma, nome_turma)`, qual atributo causa a violação da 3FN?
- [ ] nome
- [x] nome_turma
- [ ] id
- [ ] id_turma
> nome_turma depende de id_turma, não do aluno.

### Questão
Quando a desnormalização é aceitável?
- [ ] Sempre, para simplificar
- [x] Quando há necessidade medida de desempenho e a consistência é garantida
- [ ] Nunca
- [ ] Apenas na 1FN
> É uma troca consciente: mais velocidade de leitura, mais cuidado na escrita.

# Modelo físico e boas práticas
> Tipos, restrições, índices e modelagem para o mundo real.

## Projeto
**Banco completo do TechnoPenguin:** modele usuários, trilhas, módulos, lições, questões, progresso, amizades e iglus. Gere o script físico (MySQL/MariaDB) com tipos adequados, chaves, `ON DELETE`, índices e restrições `CHECK`, e escreva 5 consultas que a aplicação precisaria.

## Do lógico ao físico
### Teoria
No modelo físico decidimos os detalhes de implementação:
- **Tipos de dados** adequados e tamanhos (`VARCHAR(60)`, `DECIMAL(10,2)`, `DATE`).
- **Restrições:** `NOT NULL`, `UNIQUE`, `DEFAULT`, `CHECK`.
- **Chaves estrangeiras** com ações: `ON DELETE CASCADE` (apaga junto), `SET NULL` ou `RESTRICT` (impede).
- **Convenções de nomes** consistentes (singular, minúsculas, `id_tabela`).
- Charset `utf8mb4` e motor InnoDB.

### Prática
```sql
CREATE TABLE colonia (
  id_colonia INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(60) NOT NULL UNIQUE,
  regiao VARCHAR(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pinguim (
  id_pinguim INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(60) NOT NULL,
  peso_kg DECIMAL(5, 2) CHECK (peso_kg > 0),
  id_colonia INT UNSIGNED NULL,
  CONSTRAINT fk_pinguim_colonia FOREIGN KEY (id_colonia)
    REFERENCES colonia (id_colonia) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Questão
Qual ação de chave estrangeira impede apagar um pai que tem filhos?
- [ ] CASCADE
- [ ] SET NULL
- [x] RESTRICT
- [ ] IGNORE
> RESTRICT bloqueia a exclusão enquanto houver referências.

### Questão
Em `ON DELETE SET NULL`, o que acontece com o pinguim quando a colônia é apagada?
- [ ] O pinguim é apagado
- [x] id_colonia do pinguim vira NULL
- [ ] A exclusão é bloqueada
- [ ] Nada muda
> A ligação é desfeita, mas o pinguim continua existindo.

### Questão
Para que serve uma restrição `CHECK`?
- [ ] Criar índices
- [x] Validar regras sobre os valores das colunas
- [ ] Conferir a conexão
- [ ] Gerar ids
> CHECK (peso_kg > 0) rejeita pesos inválidos.

## Índices no projeto
### Teoria
Modelar também é pensar em **como os dados serão consultados**. Liste as consultas mais frequentes e crie índices para elas:

- Chaves estrangeiras usadas em JOINs.
- Colunas de filtros frequentes (`email`, `status`, datas).
- **Índices compostos** na ordem de uso (filtro de igualdade primeiro, depois intervalo/ordenação).
- Índices `UNIQUE` também garantem regras de negócio (um email por usuário).

Evite índices em colunas de baixa seletividade isoladas (como um booleano) e não crie índices "por precaução".

### Prática
Consulta frequente: *"lições concluídas por um usuário, mais recentes primeiro"*.

```sql
SELECT id_licao, data_conclusao
FROM progresso
WHERE id_usuario = ? AND status = 'concluida'
ORDER BY data_conclusao DESC;

CREATE INDEX idx_progresso_usuario_status_data
  ON progresso (id_usuario, status, data_conclusao);
```

### Questão
Qual a melhor forma de decidir quais índices criar?
- [ ] Indexar todas as colunas
- [x] Partir das consultas mais frequentes da aplicação
- [ ] Não criar nenhum índice
- [ ] Indexar só colunas de texto
> Índices devem servir a consultas reais.

### Questão
Além de desempenho, o que um índice UNIQUE garante?
- [ ] Ordem alfabética
- [x] Que não haja valores repetidos na coluna (regra de negócio)
- [ ] Criptografia
- [ ] Backups automáticos
> Ex.: impedir dois usuários com o mesmo email.

### Questão
Num índice composto para `WHERE id_usuario = ? ORDER BY data DESC`, qual coluna vem primeiro?
- [x] id_usuario
- [ ] data
- [ ] Tanto faz
- [ ] Nenhuma, não use índice composto
> Primeiro a igualdade, depois a ordenação.

## Padrões de modelagem comuns
### Teoria
Algumas situações aparecem em quase todo sistema:

- **Auto-relacionamento:** uma tabela que referencia ela mesma (amizade entre usuários, categoria com subcategoria).
- **Histórico/auditoria:** tabelas de log ou colunas `criado_em`, `atualizado_em`.
- **Exclusão lógica:** coluna `excluido_em` em vez de apagar a linha.
- **Tabelas de domínio:** listas fixas (status, tipos) em tabela própria ou como `ENUM`.
- **Herança:** uma tabela por subtipo, uma tabela única com coluna de tipo, ou tabela base + específicas.

Documente decisões: um bom modelo vem com o DER e um **dicionário de dados** explicando cada tabela e coluna.

### Prática
Amizade entre usuários (auto-relacionamento N:N):

```sql
CREATE TABLE amizade (
  id_solicitante INT UNSIGNED NOT NULL,
  id_destinatario INT UNSIGNED NOT NULL,
  status ENUM('pendente', 'aceita') NOT NULL DEFAULT 'pendente',
  criada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_solicitante, id_destinatario),
  FOREIGN KEY (id_solicitante) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_destinatario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
  CHECK (id_solicitante <> id_destinatario)
);
```

### Questão
Como modelar "usuários que são amigos de outros usuários"?
- [ ] Com uma coluna amigos em texto
- [x] Com um auto-relacionamento: uma tabela que referencia usuario duas vezes
- [ ] Criando uma tabela por usuário
- [ ] Não é possível em bancos relacionais
> A tabela amizade liga dois registros da mesma tabela usuario.

### Questão
O que é exclusão lógica?
- [ ] Apagar a tabela inteira
- [x] Marcar o registro como excluído (ex.: excluido_em) sem removê-lo
- [ ] Usar DELETE com WHERE
- [ ] Apagar o banco
> Preserva histórico e permite restaurar.

### Questão
Para que serve um dicionário de dados?
- [ ] Traduzir o banco para outro idioma
- [x] Documentar o significado de cada tabela e coluna
- [ ] Guardar senhas
- [ ] Substituir o DER
> Ele complementa o diagrama com descrições, tipos e regras.
