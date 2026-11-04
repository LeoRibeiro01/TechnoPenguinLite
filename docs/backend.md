# Backend TechnoPenguin

## Objetivo e limites

O backend é uma camada PHP sem framework para persistência e regras de negócio. Não há API HTTP genérica. As páginas PHP da raiz carregam `app/web.php` e chamam os controllers diretamente. A única saída JSON é `iglu-chat.php`, usada pelo chat dos iglus. Os métodos dos controllers são chamadas PHP internas e não devem ser expostos diretamente a clientes.

As regras do ponto de vista do usuário estão em [funcionalidades.md](funcionalidades.md), e o histórico das mudanças em [historico.md](historico.md).

## Estrutura

```text
app/
  bootstrap.php             Autoload simples para o namespace TechnoPenguin\
  web.php                   Ponto de entrada das páginas: sessão, CSRF, cabeçalhos, usuário logado, helpers de view
  Core/
    Model.php               Operações PDO comuns e proteção de campos graváveis
    ResourceController.php  Operações internas comuns aos controllers
    ValidationException.php Erros de validação por campo, mostrados nos formulários
  Models/                   Uma model por tabela do domínio
  Controllers/              Um controller por recurso ou regra de negócio
  Support/                  Catálogos e regras fixas
  Views/                    Cabeçalho, rodapé, abas do painel e partes reutilizadas
database/
  Connection.php            Carregamento de .env e conexão PDO
  Conteudo.php              Leitor dos arquivos Markdown das trilhas
  conteudo/                 Conteúdo das trilhas
  migrations/               Schema e ordem de criação
docs/
  backend.md                Este documento
  funcionalidades.md        Regras de negócio do ponto de vista do usuário
  historico.md              Histórico das mudanças
```

O projeto não tem Composer nem framework instalado.

- **Autoload:** `app/bootstrap.php` segue o namespace `TechnoPenguin\` e converte o restante do namespace em caminho sob `app/`.
- **Conexão:** é obtida com `criarConexao()` de `database/Connection.php`. Nas páginas, use `conexao()` de `web.php`, que reaproveita a mesma conexão.
- **Parâmetros:** a conexão usa prepares nativos, então um mesmo parâmetro nomeado não pode aparecer duas vezes na consulta. Nesses casos, use parâmetros posicionais (`?`).

## Mapeamento do domínio

| Tabela              | Model              | Controller                   |
| ---                 | ---                | ---                          |
| `usuario`           | `Usuario`          | `UsuarioController`          |
| `curso`             | `Curso`            | `CursoController`, `ProfessorController` |
| `conquista`         | `Conquista`        | `ConquistaController`        |
| `desafio`           | `Desafio`          | `DesafioController`          |
| `sequencia`         | `Sequencia`        | `SequenciaController`, `GeloController` |
| `modulo`            | `Modulo`           | `ModuloController`           |
| `usuario_conquista` | `UsuarioConquista` | `UsuarioConquistaController` |
| `usuario_desafio`   | `UsuarioDesafio`   | `UsuarioDesafioController`   |
| `licao`             | `Licao`            | `LicaoController`            |
| `questao`           | `Questao`          | `QuestaoController`          |
| `exercicio`         | `Exercicio`        | `ExercicioController`        |
| `progresso`         | `Progresso`        | `ProgressoController`, `AprendizadoController` |
| `usuario_linguagem` | `UsuarioLinguagem` | `UsuarioLinguagemController` |
| `amizade`           | `Amizade`          | `AmizadeController`          |
| `iglu`, `iglu_membro`, `iglu_trilha` | `Iglu` | `IgluController`, `GeloController` |
| `iglu_convite`      | `IgluConvite`      | `IgluController`             |
| `iglu_mensagem`     | `IgluMensagem`     | `ChatController`             |
| `notificacao`       | `Notificacao`      | (usada por vários controllers) |
| `usuario_item`      | `UsuarioItem`      | `VestuarioController`        |
| `certificado`       | `Certificado`      | `CertificadoController`      |
| `pergunta`          | `Pergunta`         | `PerguntaController`         |
| `tentativa_login`   | (consulta direta)  | `UsuarioController`          |

`AprendizadoController` concentra o mapa das trilhas, a abertura das lições e a correção das questões. `ProfessorController` cuida dos cursos da comunidade, com verificação de dono em cada operação.

As models de relacionamento também aceitam chaves primárias compostas. Para `UsuarioConquista` e `UsuarioDesafio`, passe a chave como array associativo, por exemplo `['id_usuario' => 4, 'id_conquista' => 2]`.

## Model e controller base

`TechnoPenguin\Core\Model`:

- oferece `all()`, `find($id)`, `create($dados)`, `update($id, $dados)` e `delete($id)`;
- usa prepared statements em todas as instruções;
- exige que cada model declare tabela, chave primária e uma lista `FILLABLE`. Campos fora dessa lista são rejeitados, e nomes SQL não vêm da entrada do usuário;
- em `delete()`, devolve `false` quando o registro não existe. As restrições de FK continuam sendo aplicadas pelo banco.

`TechnoPenguin\Core\ResourceController` encaminha `index()`, `show($id)`, `store($dados)`, `update($id, $dados)` e `destroy($id)` para sua model. Os controllers lançam `ValidationException` com os erros por campo, e as páginas mostram esses erros no formulário.

`UsuarioController`:

- valida o cadastro: @ único com 3 a 30 caracteres `a-z 0-9 _ .`, email único, senha de 8 a 72 caracteres, data de nascimento, aceite dos termos e responsável para menores de 12 anos;
- gera o hash bcrypt da senha;
- cuida da autenticação com bloqueio por tentativas e das etapas do questionário inicial;
- cuida da conta: dados, senha, privacidade e papel de professor;
- exporta e exclui os dados do usuário.

O campo `senha` nunca é devolvido às views.

## Páginas e `app/web.php`

Toda página PHP começa com `require_once __DIR__ . '/app/web.php';` e depois chama `exigirLogin()` ou `exigirCadastroCompleto()`. O `web.php`:

- carrega o `.env` e trata exceções não capturadas com `Views/erro.php` (a mensagem real só aparece fora de produção);
- abre a sessão com cookie `httponly` e `samesite=Lax` (`secure` em HTTPS);
- envia os cabeçalhos `X-Content-Type-Options`, `X-Frame-Options: DENY`, `Referrer-Policy` e `Permissions-Policy`;
- oferece `csrfToken()`, `campoCsrf()` e `validarCsrf()`, usados em todos os formulários POST;
- redireciona para a etapa pendente do questionário enquanto o cadastro não termina;
- verifica o gelo uma vez por dia por sessão;
- tem os helpers de view, entre eles `e()` (escape HTML), `avatarDe()`, `teco()`, `iconeTrilha()`, `linkPerfil()`, `podeVerPerfil()`, `nivelXp()` e `plural()`.

As páginas seguem o padrão Post/Redirect/Get: POST, validação, `definirAviso()` e `redirecionar()`. O layout da área logada fica em `Views/app_inicio.php` e `Views/app_fim.php`. O do login e do questionário fica em `Views/auth_inicio.php` e `Views/auth_fim.php`.

## Classes de suporte (`app/Support`)

| Classe | Conteúdo |
| --- | --- |
| `Catalogo` | Trilhas oficiais, respostas do questionário, metas e multiplicadores, dificuldades e horas, aproveitamento do certificado, idades mínimas e paletas do avatar. |
| `Trilhas` | Junta as trilhas oficiais e os cursos publicados da comunidade (`comunidade-<id>`) numa mesma interface. |
| `Conquistas` | Catálogo das 14 conquistas. O seeder sincroniza esse catálogo com a tabela `conquista`. |
| `Gelo` | Estado do gelo (feito hoje, pendente, derretido), com tolerância de 3 dias. |
| `Vestuario` | Catálogo das peças, camadas e preços. |
| `Texto` | Formatador seguro de texto para HTML: parágrafos, listas, blocos e trechos de código, negrito, itálico e links `http(s)://`. |

## Exemplo de uso interno

Depois de configurar `.env` e aplicar as migrations conforme [database/README.md](../database/README.md):

```php
<?php
require_once __DIR__ . '/../app/bootstrap.php';

use TechnoPenguin\Controllers\CursoController;
use TechnoPenguin\Models\Curso;

$pdo = criarConexao();

$cursos = new CursoController($pdo);
$lista = $cursos->index();

$cursoModel = new Curso($pdo);
$cursoComModulos = $cursoModel->comModulos(1);
```

O exemplo é código de aplicação, não uma rota ou endpoint. Controllers e models recebem `PDO` por injeção, o que permite combiná-los sem depender de um framework.

## Plataforma: tabelas e regras de negócio

As migrations 013 a 030 acrescentam a parte de perfil, conteúdo, social e gamificação:

| Tabela / colunas                  | Model / controller                      | Para que serve |
| ---                               | ---                                     | --- |
| `usuario.arroba` e colunas novas  | `Usuario` / `UsuarioController`         | @ único (o `nome` virou nome de exibição), respostas do questionário inicial, XP, pontos, cores e peças vestidas. |
| `usuario_linguagem`               | `UsuarioLinguagem`                      | Trilhas (linguagens e assuntos) que o usuário estuda. |
| `curso.linguagem_curso`           | `Curso`                                 | Slug da trilha de cada curso. |
| `modulo`, `licao`, `questao`      | `Modulo`, `Licao`, `Questao`            | Descrição e projeto do módulo, teoria e prática da lição, alternativas e explicação das questões. |
| `progresso`                       | `Progresso` / `AprendizadoController`   | Acertos, XP e pontos ganhos em cada lição. |
| `sequencia`, `iglu`               | `GeloController`                        | Gelo (ofensiva) do usuário e de cada iglu. |
| `amizade`                         | `Amizade` / `AmizadeController`         | Pedidos e amizades. |
| `iglu`, `iglu_membro`, `iglu_trilha`, `iglu_convite` | `Iglu`, `IgluConvite` / `IgluController` | Grupos de estudo, papéis (dono, admin, membro), trilhas obrigatórias, convites e pedidos de entrada. |
| `notificacao`                     | `Notificacao`                           | Avisos de amizade, iglus, gelo, certificados, conquistas e perguntas. |
| `usuario_item`                    | `UsuarioItem` / `VestuarioController`   | Peças de vestuário compradas; o catálogo fica em `Support\Vestuario`. |

Regras (constantes em `Support\Catalogo`, `Support\Gelo` e `AprendizadoController`):

- **Questionário inicial:** dados (contas antigas) → nível → trilhas → objetivo → meta diária → cores do pinguim. Quem responde "não sei nada" recebe só a trilha de Lógica e libera as outras depois do primeiro módulo dela.
- **Lições:** todas podem ser lidas. Para responder, é preciso ter a trilha no perfil e a lição anterior concluída; quem sabe "vários assuntos detalhados" responde em qualquer ordem. A lição é concluída com 60% de acertos.
- **XP e pontos:** só na primeira conclusão. XP = 10 por acerto + 20. Pontos = (5 por acerto + pontos da lição) × multiplicador da meta diária (5 min ×1; 10 ×1,25; 15 ×1,5; 20 ×1,75; 30 ×2; 60+ ×3).
- **Gelo:**
  - cada dia com pelo menos uma lição concluída soma um dia;
  - depois de mais de 3 dias seguidos sem lição, o gelo derrete e volta a zero;
  - o derretimento é verificado de forma preguiçosa, uma vez por dia por sessão, quando o usuário (ou um membro do iglu) abre a plataforma;
  - uma lição de qualquer membro conta para o gelo de todos os iglus dele.
- **Iglus:**
  - qualquer membro convida;
  - dono e admins aprovam pedidos, editam o iglu e removem membros;
  - só o dono nomeia admins e apaga o iglu;
  - se o dono sai, o admin (ou membro) mais antigo assume;
  - ao entrar, as trilhas obrigatórias são somadas ao perfil.

O conteúdo das trilhas fica em `database/conteudo/<slug>.md` e `<slug>.sobre.md` e é carregado pelo seeder (formato documentado em `database/Conteudo.php`).

## Certificados, comunidade, chat e LGPD

As migrations 031 a 040 acrescentam:

| Tabela / colunas | Model / controller | Para que serve |
| --- | --- | --- |
| `usuario.data_nascimento`, `aceite_termos_em`, `email_responsavel`, `perfil_visibilidade`, `aparecer_busca`, `mostrar_online`, `papel_usuario` | `Usuario` / `UsuarioController` | Idade, consentimento, privacidade e papel (aluno ou professor). |
| `modulo.dificuldade_modulo`, `aprofundamento_modulo`, `referencias_modulo` | `Modulo` | Módulos agrupados por dificuldade, com aprofundamento e referências. |
| `curso.historia_curso`, `uso_atual_curso`, `objetivos_curso`, `referencias_curso`, `tipo_curso`, `sigla_curso`, `cor_curso`, `publicado_curso`, `id_professor` | `Curso` / `ProfessorController` | Apresentação da trilha. Cursos com `id_professor` são da comunidade (slug `comunidade-<id>`). |
| `progresso.total_questoes_progresso`, `tentativas_progresso` | `Progresso` / `AprendizadoController` | `acertos_progresso` guarda a **melhor** tentativa. |
| `certificado` | `Certificado` / `CertificadoController` | Certificados por nível e completo, com nome, horas e aproveitamento copiados na emissão e código de verificação. |
| `conquista.codigo_conquista`, `icone_conquista` | `Conquista` / `ConquistaController` | Conquistas do catálogo `Support\Conquistas`, avaliadas depois de lições, certificados, amizades, iglus e compras. |
| `pergunta` | `Pergunta` / `PerguntaController` | Perguntas da comunidade (públicas ou só para o professor). Só o professor do curso responde, e o aluno é notificado com link direto. |
| `iglu.cor_iglu`, `icone_iglu`, `aberto_iglu`, `chat_ativo` e `iglu_mensagem` | `Iglu`, `IgluMensagem` / `IgluController`, `ChatController` | Visual do iglu, pedidos só em iglus abertos e chat de texto. |
| `tentativa_login` | `UsuarioController::autenticar` | Bloqueio temporário contra força bruta (5 falhas em 15 minutos por login ou IP). |

Regras:

- **Certificados:**
  - um por nível (Iniciante, Intermediário, Avançado) e um Completo;
  - exigem 100% das lições do nível concluídas e 70% de acertos sobre **todas** as questões do nível, contando a melhor tentativa de cada lição;
  - horas estimadas: 2, 3 ou 4 h por lição e 6, 10 ou 16 h por projeto de módulo (iniciante, intermediário e avançado).
- **Chat:**
  - só membros com 18 anos ou mais, e só com o chat ligado pelos gestores;
  - mensagens de até 500 caracteres, com intervalo mínimo de 2 segundos;
  - o autor apaga a própria mensagem e os gestores apagam qualquer uma;
  - o cliente faz polling em `iglu-chat.php` a cada 4 segundos.
- **Professor:** o perfil é ativado no cadastro ou em Minha conta, só por maiores de 18. Os cursos nascem como rascunho e só são publicados com pelo menos uma questão.
- **LGPD:**
  - consentimento registrado no cadastro (contas antigas passam pela etapa "dados");
  - responsável obrigatório para menores de 12;
  - privacidade do perfil configurável;
  - exportação de todos os dados em JSON (`UsuarioController::exportar`);
  - exclusão da conta (`UsuarioController::excluir`), que transfere os iglus e despublica os cursos criados.

## Segurança

- **Senhas:** guardadas com `password_hash` (bcrypt). O login compara com um hash fictício quando o usuário não existe, para não revelar quais contas existem pelo tempo de resposta.
- **CSRF:** todo POST valida o token da sessão.
- **Saída:** toda saída passa por `e()` ou `Support\Texto::html`, que escapa o HTML antes e só gera as marcações conhecidas, com links apenas `http(s)://`.
- **SQL:** sempre com prepared statements; nomes de colunas vêm só das listas `FILLABLE`.
- **Apache:** os `.htaccess` bloqueiam `app/`, `database/`, `.env`, `.git` e arquivos `.md`.
