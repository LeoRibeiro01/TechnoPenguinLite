# TechnoPenguin

Plataforma de aprendizado de programação com o pinguim Teco. O aluno escolhe linguagens e assuntos, estuda em trilhas com teoria, prática e questões, ganha XP e pontos, mantém o "gelo" (ofensiva) e emite certificados por nível. A plataforma também tem parte social (amigos, iglus com chat), avatar personalizável com vestuário e cursos criados por professores.

PHP 8.1+ sem framework, MySQL/MariaDB via PDO, HTML e CSS próprios, e só um script JavaScript de apoio.

## Documentação

| Documento | Conteúdo |
| --- | --- |
| [docs/funcionalidades.md](docs/funcionalidades.md) | Como cada parte funciona e as regras de negócio (XP, gelo, certificados, iglus, chat, LGPD...). |
| [docs/backend.md](docs/backend.md) | Arquitetura, models, controllers, classes de suporte, tabelas e segurança. |
| [database/README.md](database/README.md) | Banco: configuração, migrations, seeder e formato dos arquivos de conteúdo. |
| [docs/historico.md](docs/historico.md) | Histórico de tudo o que mudou a partir da versão original do repositório. |

## Rodando no XAMPP

1. Coloque o projeto em `C:\xampp\htdocs\technopenguin-Website` e inicie **Apache** e **MySQL** no painel do XAMPP.
2. Crie o banco vazio (phpMyAdmin ou terminal):

   ```sql
   CREATE DATABASE technopenguin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Copie `.env.example` para `.env`. Os valores padrão já servem para o XAMPP (usuário `root`, sem senha).
4. Na pasta do projeto, crie as tabelas e carregue o conteúdo:

   ```sh
   C:\xampp\php\php.exe database/migrate.php up
   C:\xampp\php\php.exe database/seed.php
   ```

5. Acesse <http://localhost/technopenguin-Website/>.

Fora do XAMPP também funciona com `php -S localhost:8000` na raiz do projeto. Se o banco tiver sido importado manualmente antes, veja a seção "Notas" de [database/README.md](database/README.md).

### Contas de teste

O seeder cria estas contas, todas com a senha `PenguinDev2026!` (só para desenvolvimento):

| Login | Perfil |
| --- | --- |
| `@usuario_teste` | Aluno com progresso em Python e o certificado Python Iniciante. |
| `@ana.pinguim` | Dona do iglu "Pinguins do Python", com mensagem no chat. |
| `@beto_dev` | Aluno de 16 anos: não tem acesso ao chat dos iglus. |
| `@prof.glacia` | Professora do curso da comunidade "Lua para jogos". |

## Páginas

| Página | Função |
| --- | --- |
| `index.html` | Landing page. |
| `cadastro.php` | Cria a conta: aluno ou professor, nome, @ único, email, data de nascimento, senha e aceite dos termos. Menores de 12 anos informam o email de um responsável. |
| `login.php`, `logout.php` | Entrada com email ou @ e senha; bloqueio de 15 minutos depois de 5 falhas. |
| `boas-vindas.php` | Questionário inicial com o Teco (nível, linguagens e assuntos, objetivo, meta diária). Contas antigas passam antes por uma etapa de nascimento e consentimento. |
| `personalizar.php` | Cores do pinguim e do fundo (última etapa do questionário e edição posterior). |
| `painel.php` | Abas **Minhas trilhas**, **Explorar**, **Comunidade**, **Amigos** e **Iglus**. |
| `trilha.php` | Apresentação da linguagem ou assunto: módulos por dificuldade, certificados, referências e perguntas da comunidade. |
| `modulo.php` | Trilha visual das lições do módulo, porcentagens, aprofundamento e projeto. |
| `licao.php` | Teoria, prática, aprofundamento e questões. Refazer guarda a melhor tentativa. |
| `certificado.php`, `certificados.php` | Certificado imprimível e público pelo código de verificação; lista dos certificados do usuário. |
| `perfil.php` | Perfil de um pinguim: avatar, números, trilhas, certificados, conquistas e iglus, respeitando a privacidade. |
| `iglu.php`, `iglu-chat.php` | Criação e páginas do iglu (visão geral, chat, membros, configurações); `iglu-chat.php` é a API JSON do chat. |
| `vestuario.php` | Veste as peças e troca pontos pelas exclusivas. |
| `notificacoes.php` | Avisos de amizade, iglus, gelo, certificados, conquistas e perguntas. |
| `professor.php`, `professor-curso.php`, `professor-licao.php` | Painel do professor: cursos, módulos, lições, questões e respostas às perguntas. |
| `conta.php` | Minha conta (LGPD): dados, senha, privacidade, perfil de professor, baixar meus dados e excluir conta. |
| `privacidade.php`, `termos.php` | Política de Privacidade e Termos de Uso. |
| `aulas.php` | Mantido por compatibilidade: redireciona para o painel. |

Enquanto o questionário inicial não termina, todas as páginas levam de volta à etapa pendente.

## Estrutura

```text
*.php, index.html     Páginas (cada página PHP carrega app/web.php)
app/
  web.php             Sessão, CSRF, cabeçalhos de segurança, usuário logado e helpers de view
  bootstrap.php       Autoload do namespace TechnoPenguin\
  Core/               Model e controller base, ValidationException
  Models/             Uma model por tabela
  Controllers/        Regras de negócio (aprendizado, gelo, iglus, certificados, chat...)
  Support/            Catálogos fixos: trilhas, conquistas, vestuário, gelo, formatação de texto
  Views/              Cabeçalho, rodapé, abas do painel e partes reutilizadas
assets/
  css/                global, style e index (landing); auth (login e questionário);
                      app e plataforma (área logada); certificado (impressão)
  js/pinguim.js       Recolore a foto do pinguim no navegador
  imgs/pinguim/       Foto original e máscara de cores do avatar
  imgs/vestuario/     Peças de vestuário (520x1024, mesmo quadro da foto)
database/
  migrations/         001 a 040
  conteudo/           Conteúdo das 30 trilhas (<slug>.md e <slug>.sobre.md)
  migrate.php, seed.php, Connection.php, Conteudo.php
docs/                 Documentação
```

No Apache, os arquivos `.htaccess` bloqueiam o acesso pelo navegador a `app/`, `database/`, `.env`, `.git` e arquivos `.md`.

## Avatar e vestuário

O avatar é a foto `assets/imgs/pinguim/pinguim_original.png`. O script `assets/js/pinguim.js` recolore no navegador as regiões marcadas em `pinguim_mascara.png` (vermelho: corpo; verde: barriga e rosto; azul: bico e patas), mantendo o sombreado. As peças de `assets/imgs/vestuario/` foram feitas pela equipe, têm o mesmo quadro da foto (520x1024) e são empilhadas como camadas. O catálogo, com os preços das peças exclusivas, está em `app/Support/Vestuario.php`.
