# Histórico das mudanças

Este documento registra tudo o que mudou no projeto a partir do commit `9307358` ("Arrumando posicionamento na Landing Page"), o último antes desta etapa de desenvolvimento. Ele explica o ponto de partida, o que foi feito em cada fase e por quê, para quem vai revisar ou continuar o trabalho.

Nada desta etapa foi commitado ainda: todas as mudanças estão na árvore de trabalho.

## Ponto de partida

O repositório tinha:

- a landing page (`index.html`, `assets/css/style.css`, `global.css`, `index.css`, `js/main.js`);
- um login estático (`login.html`) e scripts PHP soltos em `php/` (`conexao.php`, `cadastrar.php`, `login.php`, `logout.php`) usando `mysqli`, sem proteção CSRF;
- `aulas.php`, que listava os cursos direto do banco com HTML e estilos inline;
- as migrations 001 a 012 (as 12 tabelas do modelo), o runner `database/migrate.php`, o seeder e uma camada de models e controllers PDO em `app/` que ainda não era usada por nenhuma página.

Além disso, uma colega da equipe mandou um protótipo de personalização do pinguim (`index.html`, `script.js` e `style.css`) com a foto do pinguim e as imagens das peças de vestuário.

## Fase 0: rodar no XAMPP

O banco local tinha sido criado fora das migrations (importado no phpMyAdmin). Por isso o `migrate.php up` falhava com "Table 'usuario' already exists" e o seeder com "Unknown column 'linguagem_curso'". A solução foi apagar e recriar o banco vazio e rodar as migrations e o seeder. Esse caso ficou documentado na seção "Notas" de [database/README.md](../database/README.md).

## Fase 1: a plataforma

Objetivo: transformar a base em uma plataforma de estudo completa, com conta, questionário inicial, avatar, trilhas, gamificação e parte social.

### Conta e autenticação

- `login.html` e a pasta `php/` foram **removidos** e substituídos por `cadastro.php`, `login.php` e `logout.php`, que usam a camada PDO de `app/`.
- `app/web.php` virou o ponto de entrada de todas as páginas: sessão com cookie `httponly`/`samesite`, token CSRF em todo formulário, usuário logado, mensagens de aviso e helpers de view.
- O cadastro pede nome, **@ único**, email e senha (hash bcrypt). O login aceita email ou @.
- `aulas.php` agora só redireciona para o painel, para não quebrar links antigos.
- `index.html`: os botões "Cadastro", "Entrar", "Começar" e "Criar conta" passaram a apontar para `cadastro.php` e `login.php`; o link "Contato" leva ao rodapé; o ano do rodapé foi atualizado.

### Questionário inicial com o Teco

`boas-vindas.php` conduz as etapas nível → trilhas → objetivo → meta diária → avatar (`personalizar.php`). Enquanto o questionário não termina, toda página leva de volta à etapa pendente.

- Quem responde "Não sei nada sobre programação" recebe só a trilha de Lógica; as outras são liberadas depois do primeiro módulo dela.
- Os demais escolhem várias linguagens e assuntos.
- A meta diária define um multiplicador de pontos (5 min ×1 até 60+ min ×3).

### Avatar e vestuário

- O avatar é a **foto original** do pinguim. `assets/js/pinguim.js` recolore no navegador as regiões marcadas numa máscara (`pinguim_mascara.png`: vermelho = corpo, verde = barriga e rosto, azul = bico e patas), preservando o sombreado da foto.
- `vestuario.php` reaproveita o protótipo da colega: as peças são camadas de 520x1024 empilhadas sobre o pinguim. Peças grátis podem ser vestidas na hora; as exclusivas são compradas com pontos.

### Trilhas, lições e gamificação

- 27 trilhas na primeira versão (Lógica, linguagens e assuntos), cada uma com módulos, lições (teoria, prática e questões) e um projeto por módulo. O conteúdo fica em arquivos Markdown em `database/conteudo/` e é carregado pelo seeder.
- XP e pontos na primeira conclusão de cada lição; nível = XP / 100 + 1.
- **Gelo** (ofensiva): começa na primeira lição e derrete depois de mais de 3 dias sem estudar, com alerta diário.

### Parte social

- Painel com as abas Minhas trilhas, Explorar, Amigos (busca por @) e Iglus.
- Amizades com pedido e aceite.
- **Iglus**: grupos de estudo com descrição, trilhas obrigatórias (mostradas na tela de entrada), convites, pedidos de entrada, papéis (dono, admin, membro) e um gelo do grupo que derrete e avisa os membros.
- Notificações (`notificacoes.php`).

### Banco

Migrations 013 a 030 (perfil e trilhas do usuário, conteúdo de módulos e lições, alternativas das questões, resultado do progresso, amizade, iglus, notificações e itens comprados). O runner `migrate.php` passou a aceitar uma lista de comandos SQL em `up`/`down`, para migrations que precisam preencher dados antes de criar uma restrição.

## Fase 2: certificados, conteúdo, comunidade e LGPD

### Conteúdo e navegação

- **Teoria mais robusta**: cada trilha ganhou um arquivo `<slug>.sobre.md` com história, uso atual, o que se aprende por nível, referências e um aprofundamento por módulo baseado em livros clássicos e documentação oficial.
- **Três trilhas novas**: Estruturas de Dados e Algoritmos, Testes de Software e Inglês para entrevista de programação. Total: 30 trilhas, 272 lições e 813 questões.
- **Página de apresentação** de cada trilha (`trilha.php`): história, uso atual, conteúdo por nível com carga horária, botão "Começar" ou "Continuar", módulos agrupados por dificuldade com porcentagens e referências.
- **Página do módulo** (`modulo.php`): trilha visual das lições.
- Em `licao.php` dá para ler qualquer lição; as questões abrem em ordem. Refazer uma lição guarda a melhor tentativa.

### Certificados

`certificado.php` e `certificados.php`: um certificado por nível (Iniciante, Intermediário, Avançado) e um Completo, com nome, horas estimadas, aproveitamento e código de verificação público. Exigem 100% das lições do nível e 70% de acertos.

### Perfil, conquistas e vestuário

- **Perfil público** (`perfil.php`): avatar inteiro, nome, @, números, trilhas em andamento e completas, certificados, conquistas e iglus, respeitando a privacidade escolhida.
- **Conquistas**: catálogo de 14 conquistas em `Support\Conquistas`, desbloqueadas automaticamente.

### Iglus

Criação em página própria, com ícone, cor, prévia ao vivo e opção aberto ou só por convite. A página do iglu ganhou abas (Visão geral com o progresso de cada membro, Chat, Membros, Configurações). **Chat de texto** com polling a cada 4 segundos, que os admins ligam ou desligam e que só maiores de 18 anos usam.

### Professores e comunidade

- Perfil de professor (só maiores de 18).
- O professor cria cursos com módulos por dificuldade, lições e questões (`professor.php`, `professor-curso.php`, `professor-licao.php`) e publica na aba **Comunidade**.
- **Perguntas da comunidade** na apresentação dos cursos: públicas ou só para o professor. Só o professor responde, e o aluno é notificado com um link que abre direto na pergunta destacada.

### LGPD e segurança

- O cadastro pede data de nascimento e aceite dos termos. Menores de 12 anos precisam do email e do consentimento de um responsável. Contas antigas passam por uma etapa "dados" no próximo login.
- `conta.php`: editar dados, trocar senha, privacidade do perfil (todos, só amigos ou privado), aparecer na busca, mostrar quando está online, baixar todos os dados em JSON e excluir a conta.
- `privacidade.php` e `termos.php` com a Política de Privacidade e os Termos de Uso.
- Bloqueio de login depois de 5 falhas em 15 minutos, por login ou IP.
- Cabeçalhos de segurança em todas as páginas e arquivos `.htaccess` que bloqueiam `.env`, `.git`, `app/` e `database/` no Apache.

### Banco

Migrations 031 a 040 (LGPD do usuário, dificuldade do módulo, apresentação do curso, aproveitamento, certificado, código da conquista, pergunta, visual do iglu, mensagens do chat e tentativas de login).

## Ajustes posteriores

- **Perfil**: as informações do usuário (nome, números, botões, trilhas e certificados) ficam à direita da imagem do pinguim. Conquistas e iglus continuam abaixo. As conquistas mostram só as já conquistadas.
- **Vestuário**: a fase 2 tinha acrescentado peças desenhadas em SVG, camisetas estampadas e variações de cor. Tudo isso foi **removido** a pedido; ficaram só as 12 peças originais da colega: boné, cartola, chapéu de festa, chapéu de feiticeiro, óculos de leitura, óculos de sol, gravata borboleta, gravata longa, corrente, camisa polo, moletom e terno.

## Como foi testado

Cada fluxo foi testado de verdade no Apache do XAMPP, com `curl` e screenshots do Chrome headless:

- cadastro e questionário;
- lições, XP, pontos e gelo;
- emissão e recusa de certificado;
- chat de adulto e bloqueio de menor;
- pergunta privada com resposta e notificação;
- curso criado e publicado por professor;
- cadastro de criança com e sem responsável;
- perfil privado;
- exportação e exclusão de conta;
- bloqueio de login.

Todos os arquivos PHP passam no `php -l`. As migrations foram aplicadas do zero num banco separado e o rollback foi testado.

## Limitações conhecidas

- As horas dos certificados são estimativas (veja `Catalogo::DIFICULDADES`).
- O gelo é verificado de forma preguiçosa: só quando o usuário (ou um membro do iglu) abre a plataforma, uma vez por dia por sessão.
- O chat não é instantâneo: o XAMPP não tem WebSocket, então o navegador consulta o servidor a cada 4 segundos. Uma mensagem apagada some para os outros só quando eles recarregam a página.
- As referências bibliográficas citam livros e sites reais, mas os números de capítulo devem ser conferidos antes de uma apresentação formal.
