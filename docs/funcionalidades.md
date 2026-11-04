# Funcionalidades e regras

Como a plataforma funciona do ponto de vista de quem usa, com as regras de negócio e onde cada uma é definida no código. Para a arquitetura e as tabelas, veja [backend.md](backend.md).

## Conta

### Cadastro (`cadastro.php`)

- **Campos:** papel (aluno ou professor), nome de exibição, @, email, data de nascimento, senha e aceite dos termos.
- **@:** único, de 3 a 30 caracteres `a-z 0-9 _ .`, guardado sem o `@`.
- **Senha:** de 8 a 72 caracteres, guardada com hash bcrypt.
- **Menores de 12 anos** (`Catalogo::IDADE_CRIANCA`) informam o email de um responsável e marcam o consentimento dele (LGPD, art. 14).
- **Professor:** só para maiores de 18 anos (`Catalogo::IDADE_ADULTA`).

As validações ficam em `UsuarioController::validarCadastro`.

### Login (`login.php`)

- Aceita email ou @.
- Depois de 5 falhas em 15 minutos para o mesmo login ou o mesmo IP, bloqueia novas tentativas até a janela passar. As constantes ficam em `UsuarioController`.
- Contas sem data de nascimento passam pela etapa "dados" antes de continuar.

### Minha conta (`conta.php`)

- Editar nome, @, email e data de nascimento, e trocar a senha.
- Privacidade:
  - quem vê o perfil (todos, só amigos ou privado);
  - aparecer ou não na busca por @;
  - mostrar ou não quando está online.
- Ativar o perfil de professor (maiores de 18).
- Baixar todos os dados pessoais em JSON.
- Excluir a conta. É preciso digitar a senha e a palavra EXCLUIR. Os iglus da pessoa passam para o membro mais antigo, e os cursos que ela criou são despublicados.

## Questionário inicial (`boas-vindas.php`)

O Teco guia as etapas `dados → nível → trilhas → objetivo → meta → avatar` (`UsuarioController::ETAPAS`).

- **Nível:**
  - "Não sei nada sobre programação" recebe só a trilha de Lógica. As outras trilhas abrem depois do primeiro módulo dela (`AprendizadoController::podeExplorar`).
  - "Sei vários assuntos detalhados" responde as lições em qualquer ordem.
- **Trilhas:** várias linguagens e assuntos. Elas aparecem em "Minhas trilhas"; as outras ficam em "Explorar".
- **Meta diária:** 5, 10, 15, 20, 30 ou 60+ minutos, com multiplicador de pontos de 1×, 1,25×, 1,5×, 1,75×, 2× e 3× (`Catalogo::METAS`).
- **Avatar:** cores do corpo, da barriga, do bico e patas e do fundo (`personalizar.php`).

## Trilhas e lições

São 30 trilhas oficiais (`Catalogo::TRILHAS`):

- **Base:** Lógica de Programação.
- **Linguagens:** Python, JavaScript, TypeScript, HTML & CSS, Java, C, C++, C#, Kotlin, PHP, Go, Ruby, Rust, Swift, MySQL e MariaDB.
- **Assuntos:**
  - Modelagem de Banco de Dados, A história da programação, Sistemas Ciberfísicos e IoT, Redes de Computadores, Engenharia de Requisitos e Sistemas Operacionais;
  - Computação em nuvem, DevOps e CI/CD, Cibersegurança, Engenharia de Prompt e vibe coding;
  - Estruturas de Dados e Algoritmos, Testes de Software, Inglês para entrevista de programação.

Cada trilha oficial tem 3 módulos, um por dificuldade (iniciante, intermediário, avançado). Cada módulo tem 3 lições e um projeto.

### Apresentação (`trilha.php`)

- História da trilha e como ela é usada hoje.
- O que se aprende em cada nível, com a carga horária.
- Botão "Começar" ou "Continuar de onde parei".
- Módulos por dificuldade, com a porcentagem concluída e a que falta.
- Certificados disponíveis e referências (livros e links).
- Nos cursos da comunidade, as perguntas e respostas.

### Módulo (`modulo.php`)

- Trilha visual das lições.
- Porcentagem concluída e de acertos.
- Aprofundamento do módulo, referências e projeto.

### Lição (`licao.php`)

- Teoria, prática, aprofundamento do módulo e questões de múltipla escolha com explicação na correção.
- Botões para a lição anterior e a seguinte.
- Qualquer lição pode ser lida. Para responder, é preciso ter a trilha no perfil e a lição anterior concluída.
- A lição é concluída com 60% de acertos (`AprendizadoController::APROVACAO`).
- Pode ser refeita quantas vezes quiser. Vale sempre a melhor tentativa.

### Recompensas

Só na primeira conclusão de cada lição:

- **XP** = 10 por acerto + 20.
- **Pontos** = (5 por acerto + pontos da lição) × multiplicador da meta.
- **Nível** = XP ÷ 100 + 1.

## Gelo (`Support\Gelo`, `GeloController`)

- Cada dia com pelo menos uma lição concluída soma um dia ao gelo.
- Depois de mais de 3 dias sem lição (`Gelo::DIAS_TOLERANCIA`), o gelo derrete e volta a zero.
- Enquanto o usuário não faz a lição do dia, a plataforma mostra um alerta, uma vez por dia.
- Cada iglu tem o próprio gelo: uma lição de qualquer membro conta para todos os iglus dele. Quando o gelo do iglu derrete, os membros são notificados.
- A verificação é preguiçosa: acontece quando o usuário abre a plataforma, uma vez por dia por sessão.

## Certificados (`CertificadoController`)

- Existe um certificado por nível (Iniciante, Intermediário, Avançado) e um Completo, que considera todos os módulos da trilha.
- **Requisitos:**
  - 100% das lições do nível concluídas;
  - 70% de acertos sobre todas as questões do nível, contando a melhor tentativa de cada lição (`Catalogo::APROVEITAMENTO_CERTIFICADO`).
- **Horas estimadas:** 2, 3 ou 4 h por lição e 6, 10 ou 16 h por projeto, conforme o nível (`Catalogo::DIFICULDADES`).
- **Totais de uma trilha oficial:** Iniciante 12 h, Intermediário 19 h, Avançado 28 h e Completo 59 h.
- O certificado mostra o nome, a trilha, o nível, as horas, o aproveitamento, a data e um código.
- `certificado.php?codigo=...` é público, serve para verificação e pode ser impresso ou salvo em PDF.

## Conquistas (`Support\Conquistas`)

São 14 conquistas, avaliadas automaticamente depois de lições, certificados, amizades, iglus e compras (`ConquistaController::avaliar`):

| Conquista | Como ganhar |
| --- | --- |
| Primeiros passos | Concluir a primeira lição |
| Pinguim estudioso | Concluir 10 lições |
| Pinguim sábio | Concluir 50 lições |
| Gabaritou! | Acertar todas as questões de uma lição |
| Gelo firme | Manter o gelo por 3 dias |
| Geleira | Manter o gelo por 7 dias |
| Era do gelo | Manter o gelo por 30 dias |
| Poliglota | Estudar 3 trilhas diferentes |
| Certificado! | Emitir o primeiro certificado |
| Domínio total | Emitir um certificado completo |
| Pinguim sociável | Ter 3 amigos |
| Construtor de iglus | Criar um iglu |
| Bando unido | Participar de um iglu |
| Pinguim estiloso | Comprar 3 peças exclusivas |

O perfil mostra só as conquistas já desbloqueadas.

## Avatar e vestuário

O avatar é a foto original do pinguim, recolorida no navegador por `assets/js/pinguim.js` com a máscara `pinguim_mascara.png`.

O vestuário (`vestuario.php`, catálogo em `Support\Vestuario`) tem as 12 peças da equipe, em 4 camadas:

| Camada | Grátis | Exclusivas (pontos) |
| --- | --- | --- |
| Cabeça | Boné, Cartola clássica | Chapéu de festa (40), Chapéu de feiticeiro (80) |
| Óculos | Óculos de leitura | Óculos de sol (30) |
| Pescoço | Gravata borboleta, Gravata longa | Corrente (100) |
| Corpo | Camisa polo, Moletom | Terno (120) |

Uma peça comprada fica para sempre na conta, e só dá para vestir uma peça por camada.

## Perfil (`perfil.php`)

- O pinguim fica à esquerda. À direita ficam:
  - tipo (estudante ou professor) e se está online;
  - nome, @, nível, XP, gelo, número de conquistas e de certificados;
  - botões (editar pinguim, vestuário e privacidade no próprio perfil; adicionar amigo nos outros);
  - "Estudando agora" (com a porcentagem) e "Linguagens e assuntos completos" (com os certificados).
- Embaixo ficam as conquistas e os iglus.
- Se o dono escolheu "só amigos" ou "privado", os visitantes veem só o cabeçalho.

## Amigos e iglus

### Amigos

Busca por @ (respeitando "aparecer na busca"), pedido, aceite e recusa.

### Iglus (`iglu.php`)

- **Criação:** nome, descrição, ícone, cor, aberto ou só por convite, e trilhas obrigatórias. Uma prévia ao vivo mostra o resultado.
- **Abas:**
  - Visão geral: gelo do grupo, lições da semana e progresso de cada membro nas trilhas;
  - Chat;
  - Membros;
  - Configurações.
- **Papéis:**
  - qualquer membro convida;
  - dono e admins aprovam pedidos, editam o iglu, removem membros e ligam o chat;
  - só o dono nomeia admins e apaga o iglu;
  - se o dono sai, o admin (ou membro) mais antigo assume.
- **Entrada:** pedidos só em iglus abertos. As trilhas obrigatórias aparecem na tela de entrada e são somadas ao perfil de quem entra.
- **Chat:**
  - só membros com 18 anos ou mais, e só quando os gestores ligam;
  - mensagens de até 500 caracteres, com no mínimo 2 segundos entre elas;
  - o autor apaga a própria mensagem e os gestores apagam qualquer uma;
  - o navegador consulta `iglu-chat.php` a cada 4 segundos.

## Professores e comunidade

### Painel do professor (`professor.php`)

- O professor cria cursos com título, descrição, sigla, cor, história, uso atual, objetivos e referências.
- Dentro do curso, cria módulos por dificuldade, lições e questões.
- O curso nasce como rascunho e só pode ser publicado com pelo menos uma questão.

### Comunidade

- Os cursos publicados aparecem na aba Comunidade do painel, com o slug `comunidade-<id>`.
- Funcionam como as trilhas oficiais: apresentação, módulos, lições, progresso e certificados.

### Perguntas da comunidade

- O aluno pergunta na apresentação do curso e escolhe se a pergunta fica pública ou visível só para o professor.
- Só o professor do curso responde.
- O aluno recebe uma notificação com link direto para a pergunta, que aparece destacada.

## Notificações (`notificacoes.php`)

Avisos de:

- pedidos de amizade;
- convites e pedidos de iglu;
- gelo derretido (pessoal e do iglu);
- certificados e conquistas;
- respostas de perguntas.

O sino no topo mostra quantos avisos ainda não foram lidos.
