# Banco de dados TechnoPenguin

Este projeto usa migrations próprias em PHP com PDO e MySQL 8 (compatível com MariaDB 10.6+). Requer PHP 8.1 ou superior com `pdo_mysql` habilitado. As migrations criam as tabelas do modelo; elas não criam o schema/database.

A camada de models e controllers PHP está documentada em [docs/backend.md](../docs/backend.md).

## Configuração

1. Crie o banco manualmente em MySQL/MariaDB: `CREATE DATABASE technopenguin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
2. Copie `.env.example` para `.env` e informe host, porta, banco, usuário e senha. Não versione o `.env` real.
3. Confira que `APP_ENV=development` está definido para desenvolvimento.

O loader lê `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` do `.env`. A conexão usa PDO, exceções, prepared statements nativos e `utf8mb4`.

## Migrations

Na raiz do projeto, execute:

```sh
php database/migrate.php up
php database/migrate.php status
```

`up` aplica as migrations pendentes; `status` lista as migrations como aplicadas ou pendentes. O controle é armazenado em `schema_migrations`. Cada chamada de `up` forma um lote. Para reverter o último lote:

```sh
php database/migrate.php down
```

Para apagar e recriar as tabelas gerenciadas por migrations em desenvolvimento, use `fresh` e digite `FRESH` quando solicitado:

```sh
php database/migrate.php fresh
```

`fresh` é bloqueado se `APP_ENV=production`. O reset remove os dados dessas tabelas.

No XAMPP, use o PHP dele: `C:\xampp\php\php.exe database/migrate.php up`.

### Lista de migrations

| Faixa | Conteúdo |
| --- | --- |
| 001–012 | Schema original: usuário, curso, conquista, desafio, sequência, módulo, lição, questão, exercício, progresso e relacionamentos. |
| 013–015 | Perfil do usuário (cores do avatar e nível de conhecimento), trilhas do usuário (`usuario_linguagem`) e trilha de cada curso. |
| 016–018 | Conta (@ único, questionário, XP, pontos, peças vestidas) e slugs de trilha maiores. |
| 019–023 | Conteúdo de módulo e lição, alternativas e explicação das questões, resultado do progresso e início do gelo. |
| 024–030 | Amizade, iglus (membros, trilhas obrigatórias, convites), notificações e itens comprados. |
| 031–034 | LGPD e privacidade do usuário, dificuldade e aprofundamento do módulo, apresentação do curso e aproveitamento das lições. |
| 035–040 | Certificado, código da conquista, perguntas da comunidade, visual e chat do iglu, tentativas de login. |

## Seeder de desenvolvimento

Depois de aplicar as migrations, rode:

```sh
php database/seed.php
```

O seeder é idempotente e bloqueado em produção. Ele:

- carrega as 30 trilhas de `database/conteudo/` (Lógica, 16 linguagens e 13 assuntos). Cada uma tem 3 módulos, um por dificuldade (iniciante, intermediário, avançado), com 3 lições (teoria, prática e 3 questões) e um projeto por módulo. Rodar de novo atualiza os textos e mantém o progresso dos usuários. As alternativas são embaralhadas de forma fixa por questão;
- cria os usuários de teste `@usuario_teste`, `@ana.pinguim`, `@beto_dev` (16 anos, sem acesso ao chat) e a professora `@prof.glacia`, já com o questionário respondido, amizades, o iglu "Pinguins do Python" e o curso da comunidade "Lua para jogos" com uma pergunta respondida;
- sincroniza as conquistas de `app/Support/Conquistas.php` e cria dois desafios.

A senha de desenvolvimento é `PenguinDev2026!`; ela é armazenada com hash bcrypt gerado por `password_hash` e não deve ser usada fora do ambiente local.

## Conteúdo das trilhas

Cada trilha tem dois arquivos em `database/conteudo/` (os formatos estão descritos em `database/Conteudo.php`):

- `<slug>.md`: módulos, lições, teoria, prática, questões e projetos. Um módulo pode declarar a dificuldade no título (`# Título [intermediario]`); sem isso, vale a posição.
- `<slug>.sobre.md`: a apresentação (`@historia`, `@hoje`, `@iniciante`, `@intermediario`, `@avancado`, `@referencias`) e, por módulo, o aprofundamento (`@modulo 2`) e as referências (`@fontes 2`), baseados em livros clássicos e documentação oficial.

Depois de editar, rode o seeder de novo. Cursos da comunidade são criados pelos professores na própria plataforma e não usam esses arquivos.

## Notas

O MySQL confirma DDL implicitamente. Por isso, cada migration mexe numa única tabela e o runner registra a execução após o DDL. `up` e `down` podem ser uma string SQL ou uma lista de comandos executados em ordem (a 016 usa isso para preencher o @ dos usuários existentes antes de torná-lo obrigatório). Em caso de interrupção entre essas operações, confira o estado da tabela e `schema_migrations` antes de continuar.

Um banco criado fora das migrations (por exemplo, importado no phpMyAdmin) não tem `schema_migrations`, e o `up` vai falhar ao tentar recriar `usuario`. Nesse caso, apague e recrie o banco vazio (passo 1 da configuração) e rode `up` e o seeder; os dados de teste antigos serão perdidos.
