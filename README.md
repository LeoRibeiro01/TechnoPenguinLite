# TechnoPenguin

Plataforma para ensinar programação de forma interativa. O **aluno** escolhe uma linguagem, estuda cursos divididos em módulos e lições, responde exercícios corrigidos na hora, ganha pontos, mantém uma sequência de dias de estudo e desbloqueia conquistas. O **professor** também cria cursos para a aba Comunidade. O **administrador** gerencia usuários e modera os cursos.

**Equipe:** Gustavo Vinicius Ribeiro, Leonardo da Silva Sobrinho, Leonardo Farias Ribeiro, Maria Fernanda Grodzki, Nathan Eliakim Banach da Silva e Pedro Henrique Nunes Lara (PUCPR, Engenharia de Software).

---

## Sumário

0. [A atividade avaliativa (25/11)](#0-a-atividade-avaliativa-2511)
1. [Tecnologias](#1-tecnologias)
2. [Como rodar o projeto](#2-como-rodar-o-projeto)
3. [Os 3 tipos de usuário](#3-os-3-tipos-de-usuário)
4. [Como o Laravel funciona (MVC)](#4-como-o-laravel-funciona-mvc)
5. [Estrutura de pastas](#5-estrutura-de-pastas)
6. [Banco de dados](#6-banco-de-dados)
7. [Cada tela: rota, controller e view](#7-cada-tela-rota-controller-e-view)
8. [Onde está cada requisito](#8-onde-está-cada-requisito)
9. [Regras de negócio](#9-regras-de-negócio)
10. [Acessibilidade](#10-acessibilidade)
11. [Padrões usados no projeto](#11-padrões-usados-no-projeto)
12. [**Como criar um CRUD novo do zero**](#12-como-criar-um-crud-novo-do-zero)
13. [Outras mudanças que o professor pode pedir](#13-outras-mudanças-que-o-professor-pode-pedir)
14. [Comandos úteis](#14-comandos-úteis)
15. [Perguntas prováveis e respostas curtas](#15-perguntas-prováveis-e-respostas-curtas)
16. [O que foi removido da versão anterior](#16-o-que-foi-removido-da-versão-anterior)

---

## 0. A atividade avaliativa (25/11)

Enunciado do professor:

> No dia 25/11, quarta-feira, teremos uma atividade avaliativa sobre o trabalho final da disciplina.
>
> É importante:
> - Ter conhecimento sobre a estrutura geral do seu projeto
> - Ter seu projeto rodando corretamente
> - Ter conhecimentos sobre as funcionalidades, linguagens utilizadas e padrões de seu projeto
>
> Esta atividade terá valoração de 1 ponto sobre a nota total do projeto.

Ele provavelmente também vai pedir para **codar algo novo** no projeto (um CRUD, um campo, uma tela).

### Onde estudar cada item

| O professor cobra | Onde está neste guia |
| --- | --- |
| Estrutura geral do projeto | [4. MVC](#4-como-o-laravel-funciona-mvc), [5. Pastas](#5-estrutura-de-pastas), [6. Banco](#6-banco-de-dados) |
| Projeto rodando corretamente | [2. Como rodar](#2-como-rodar-o-projeto) |
| Funcionalidades | [3. Tipos de usuário](#3-os-3-tipos-de-usuário), [7. Telas](#7-cada-tela-rota-controller-e-view), [8. Requisitos](#8-onde-está-cada-requisito), [9. Regras](#9-regras-de-negócio), [10. Acessibilidade](#10-acessibilidade) |
| Linguagens utilizadas | [1. Tecnologias](#1-tecnologias) |
| Padrões do projeto | [11. Padrões](#11-padrões-usados-no-projeto) |
| Codar algo novo | [12. CRUD do zero](#12-como-criar-um-crud-novo-do-zero), [13. Outras mudanças](#13-outras-mudanças-que-o-professor-pode-pedir) |

### Checklist para o dia

- [ ] Rodei `php artisan migrate:fresh --seed` e o projeto abre em <http://127.0.0.1:8000>.
- [ ] Entrei com os 3 usuários de teste (aluno, professor, admin) e vi o menu mudar.
- [ ] Como aluno, fiz uma lição. Como professor, criei um curso com módulo, lição e exercício e publiquei. Como admin, troquei o tipo de alguém.
- [ ] Sei explicar o caminho **rota → middleware → controller → model → view** de uma tela.
- [ ] Fiz o CRUD de Anotações da seção 12 pelo menos uma vez, sozinho, e depois apaguei.
- [ ] Sei responder as perguntas da seção 15.

---

## 1. Tecnologias

| Tecnologia | Para quê |
| --- | --- |
| **PHP 8.2+** | Linguagem do servidor (back-end). |
| **Laravel 12** | Framework PHP no padrão MVC: rotas, middlewares, controllers, models (Eloquent), views (Blade), validação, login e migrations. |
| **MySQL / MariaDB** (XAMPP) | Banco de dados relacional. |
| **Blade** | Linguagem de templates do Laravel: HTML com `{{ }}`, `@if`, `@foreach`. |
| **HTML5 + CSS3** | Telas. CSS próprio, sem framework (`public/css/app.css`). |
| **JavaScript** | `public/js/pinguim.js` pinta a foto do pinguim com as cores do usuário (usa `<canvas>`). Também há a prévia ao vivo nas telas "Meu pinguim" e "Vestuário" e os `confirm()` antes de excluir. |
| **Composer** | Gerenciador de pacotes do PHP (instala o Laravel na pasta `vendor/`). |

Não usamos npm, Vite, Tailwind, nem pacotes de login (Breeze/Jetstream). O login usa o `Auth` e o `Password` que já vêm no Laravel.

---

## 2. Como rodar o projeto

**Pré-requisitos:** XAMPP (só o **MySQL** precisa estar ligado), PHP 8.2 ou mais novo e Composer.

```sh
# 1) Instalar as dependências (cria a pasta vendor/). Na primeira vez ou depois de um git pull que mude o composer.json
composer install

# 2) Criar o arquivo de configuração (só na primeira vez)
copy .env.example .env          # no Git Bash/Linux: cp .env.example .env
php artisan key:generate
```

3. Abra o painel do XAMPP e inicie o **MySQL**.
4. No phpMyAdmin (<http://localhost/phpmyadmin>), crie um banco vazio chamado **`technopenguin`** (agrupamento `utf8mb4_unicode_ci`).
5. Crie as tabelas e preencha o conteúdo:

```sh
php artisan migrate:fresh --seed
```

> `migrate:fresh` **apaga todas as tabelas** do banco `technopenguin` e cria de novo; `--seed` cadastra os 30 cursos oficiais, o curso da comunidade de exemplo, as conquistas e os usuários de teste. Sempre que uma migration for alterada, rode este comando de novo.

6. Suba o servidor e abra no navegador:

```sh
php artisan serve
```

<http://127.0.0.1:8000>

### Usuários de teste (senha de todos: `Teste123`)

| Tipo | E-mail | Nome de usuário |
| --- | --- | --- |
| Aluno | `teste@technopenguin.com` | `teste` (começa com 150 pontos para testar o vestuário) |
| Professor | `professor@technopenguin.com` | `prof.glacia` (dona do curso "Lua para jogos") |
| Admin | `admin@technopenguin.com` | `admin` |

### Problemas comuns

| Erro | Solução |
| --- | --- |
| `could not find driver` | Ative `extension=pdo_mysql` no `php.ini` do PHP que você está usando (`php --ini` mostra qual é). |
| `SQLSTATE[HY000] [2002]` / conexão recusada | O MySQL do XAMPP não está ligado. |
| `Unknown database 'technopenguin'` | Crie o banco no phpMyAdmin (passo 4). |
| `Unknown column 'tipo'` (ou outra coluna) | O banco está com tabelas antigas: rode `php artisan migrate:fresh --seed`. |
| `No application encryption key` | Rode `php artisan key:generate`. |
| `419 Page Expired` | Faltou `@csrf` no formulário, ou a sessão expirou: recarregue a página. |
| `403 Forbidden` | O seu tipo de usuário não pode abrir aquela página (é o middleware funcionando). |
| `Class ... not found` depois de criar um arquivo | Rode `composer dump-autoload`. |
| Mudei algo e não aparece | `php artisan optimize:clear` limpa os caches. |

**E-mails:** o `.env` usa `MAIL_MAILER=log`, então o e-mail não é enviado de verdade: o texto (com o link) é escrito no final de `storage/logs/laravel.log`. Para enviar de verdade, basta configurar um SMTP no `.env` (seção `MAIL_`).

---

## 3. Os 3 tipos de usuário

A coluna `users.tipo` guarda `aluno`, `professor` ou `admin`.

| O que pode fazer | Aluno | Professor | Admin |
| --- | :---: | :---: | :---: |
| Estudar (painel, cursos, lições, conquistas, perfil, configurações) | ✔ | ✔ | ✔ |
| Ver a aba **Comunidade** (cursos publicados por professores) | ✔ | ✔ | ✔ |
| **Área do professor**: criar, editar e excluir os **próprios** cursos, módulos, lições e exercícios | | ✔ | ✔ |
| **Painel Admin**: números gerais, buscar usuários, trocar o tipo, excluir usuários | | | ✔ |
| Editar ou excluir **qualquer** curso da comunidade (moderação) | | | ✔ |

- **Cadastro:** a pessoa escolhe "Aluno" ou "Professor". Ninguém se cadastra como admin: o validador só aceita `in:aluno,professor`. Um admin promove outro usuário no painel Admin.
- **Como é protegido:** cada área tem um grupo de rotas com o middleware `tipo` (`app/Http/Middleware/VerificarTipo.php`):
  - `Route::middleware('tipo:professor,admin')` → área do professor
  - `Route::middleware('tipo:admin')` → painel Admin
  Quem não tem o tipo certo recebe **403 (proibido)**. O menu também esconde os links (`@if (auth()->user()->ehProfessor())`), mas a proteção de verdade é o middleware.
- **Dono do curso:** além do tipo, os controllers do professor conferem `Auth::user()->podeEditar($curso)`. Um professor não mexe no curso de outro professor; o admin mexe em todos.
- **Rascunho:** curso da comunidade não publicado só aparece para o dono e para os admins (`Curso::visivelPara()`). Para os outros, dá 404.

---

## 4. Como o Laravel funciona (MVC)

**MVC** separa o código em três partes:

| Parte | O que faz | Onde fica |
| --- | --- | --- |
| **Model** | Representa uma tabela e guarda as regras dos dados. | `app/Models/` |
| **View** | O HTML que o usuário vê. | `resources/views/` |
| **Controller** | Recebe o pedido, usa os models e escolhe a view. | `app/Http/Controllers/` |

A **rota** (`routes/web.php`) liga cada endereço a um método de controller. O **middleware** é um "porteiro" que roda antes do controller: `auth` (está logado?), `guest` (está deslogado?) e `tipo` (é professor/admin?).

### O caminho de um pedido (exemplo: abrir o perfil)

```text
Navegador: GET http://127.0.0.1:8000/perfil
   │
   ▼
routes/web.php
   Route::get('/perfil', [PerfilController::class, 'editar'])->name('perfil.editar');
   (dentro do grupo middleware('auth'): quem não está logado vai para /login)
   │
   ▼
app/Http/Controllers/PerfilController.php → método editar()
   pega o usuário logado (Auth::user()) e as linguagens (Linguagem::orderBy('nome')->get())
   │                                              │
   │                                              ▼
   │                                   app/Models/Linguagem.php → SELECT * FROM linguagens ORDER BY nome
   ▼
return view('perfil.editar', [...dados...])
   │
   ▼
resources/views/perfil/editar.blade.php   (que "herda" layouts/app.blade.php)
   │
   ▼
HTML pronto volta para o navegador
```

### E quando um formulário é enviado (professor cria um módulo)

```text
<form method="POST" action="{{ route('professor.modulos.store', $curso) }}"> @csrf ...
   │
   ▼
Route::post('/cursos/{curso}/modulos', [Professor\ModuloController::class, 'store'])
   (grupo: middleware 'auth' + 'tipo:professor,admin', prefixo /professor)
   │
   ▼
Professor\ModuloController@store(Request $request, Curso $curso)
   1. confere se pode editar o curso      → senão abort(403)
   2. $request->validate([...])           → se falhar, volta sozinho para o formulário com os erros
   3. $curso->modulos()->create($dados)   → INSERT INTO modulos (...)
   4. return redirect()->route('professor.modulos.edit', $modulo)->with('aviso', 'Módulo criado!')
```

---

## 5. Estrutura de pastas

Só o que importa para o projeto (o resto é padrão do Laravel e não foi mexido):

```text
technopenguin-Website/
├── app/
│   ├── Http/
│   │   ├── Controllers/              CONTROLLERS (C do MVC)
│   │   │   ├── AuthController.php        cadastro (aluno/professor), login, logout
│   │   │   ├── SenhaController.php       esqueci senha/e-mail, redefinir, alterar
│   │   │   ├── PerfilController.php      perfil de estudos, cores do pinguim, foto
│   │   │   ├── VestuarioController.php   roupas do pinguim: vestir e comprar com pontos
│   │   │   ├── ConfiguracaoController.php conta, acessibilidade, excluir conta
│   │   │   ├── PainelController.php      painel do aluno e aba Comunidade
│   │   │   ├── CursoController.php       módulos e lições de um curso
│   │   │   ├── LicaoController.php       lição, correção, pontos, sequência
│   │   │   ├── ConquistaController.php   conquistas e medalhas
│   │   │   ├── AdminController.php       painel do administrador
│   │   │   └── Professor/                ÁREA DO PROFESSOR (um CRUD por nível)
│   │   │       ├── CursoController.php
│   │   │       ├── ModuloController.php
│   │   │       ├── LicaoController.php
│   │   │       └── ExercicioController.php
│   │   └── Middleware/
│   │       └── VerificarTipo.php     o "porteiro" dos tipos de usuário
│   ├── Models/                       MODELS (M do MVC) - um por tabela
│   │   ├── User.php                      usuário + tipos/permissões + regras + acessibilidade + saldo de pontos
│   │   ├── Curso.php                     cursos oficiais e da comunidade
│   │   ├── Linguagem.php, Modulo.php, Licao.php, Exercicio.php
│   │   └── Progresso.php, Conquista.php, Compra.php
│   └── Support/
│       └── Pinguim.php                   catálogo do pinguim: cores padrão, paletas, camadas e roupas (com preços)
├── bootstrap/app.php                 registra o middleware 'tipo'
├── config/                           configurações do Laravel (só mudamos o fuso horário em app.php)
├── database/
│   ├── migrations/                   criação das tabelas (uma por arquivo)
│   └── seeders/
│       ├── DatabaseSeeder.php            conquistas, 3 usuários de teste e o curso "Lua para jogos"
│       ├── ConteudoSeeder.php            lê o JSON e cadastra os 30 cursos oficiais
│       └── dados/conteudo.json           todo o conteúdo escrito pela equipe
├── lang/pt_BR/                       mensagens de validação e de senha em português
├── public/                           a ÚNICA pasta acessível pelo navegador
│   ├── css/app.css                       estilo das telas do sistema (inclui acessibilidade)
│   ├── css/global.css, style.css         estilo da landing page
│   ├── js/pinguim.js                     pinta a foto do pinguim com as cores do usuário
│   ├── imgs/                             logo e imagens do Teco
│   ├── imgs/pinguim/                     foto original do pinguim + máscara das regiões de cor
│   ├── imgs/vestuario/                   as 12 roupas (520x1024, mesmo quadro da foto)
│   └── uploads/                          fotos de perfil enviadas pelos usuários
├── resources/views/                  VIEWS (V do MVC) - arquivos .blade.php
│   ├── layouts/app.blade.php             moldura de todas as telas (topo, menu, avisos, rodapé)
│   ├── partials/                         pedaços reutilizados: avatar (pinguim em camadas), erros, cartão de curso
│   ├── welcome.blade.php                 landing page
│   ├── auth/  senha/  perfil/  configuracoes/   telas de conta
│   ├── vestuario/                        roupas do pinguim
│   ├── painel.blade.php, comunidade.blade.php
│   ├── cursos/  licoes/  conquistas/     telas de estudo
│   ├── professor/                        área do professor (cursos/, modulos/, licoes/)
│   └── admin/                            painel do administrador
├── routes/web.php                    ROTAS: endereço → controller
├── storage/                          logs, sessões e cache (gerado pelo Laravel)
├── vendor/                           Laravel e bibliotecas (gerado pelo composer install, não vai pro Git)
├── .env                              configuração local: banco, e-mail... (não vai pro Git)
└── composer.json                     dependências do PHP
```

---

## 6. Banco de dados

Banco `technopenguin`, criado pelas **migrations** (`database/migrations/`) e preenchido pelos **seeders**.

```text
linguagens 1 ──── N users              (o usuário escolhe uma linguagem)
linguagens 1 ──── N cursos             (cursos de assunto e da comunidade não têm linguagem)
users      1 ──── N cursos             (professor_id: cursos da comunidade criados pelo professor)
cursos     1 ──── N modulos
modulos    1 ──── N licoes
licoes     1 ──── N exercicios
users      1 ──── N progressos N ──── 1 licoes      (resultado do usuário em cada lição)
users      N ──── N conquistas   (tabela pivô conquista_user)
users      1 ──── N compras      (roupas exclusivas compradas com pontos)
```

| Tabela | Colunas principais |
| --- | --- |
| `linguagens` | id, nome, sigla, cor |
| `users` | id, **tipo** (aluno/professor/admin), nome, **nome_usuario** (único), **email** (único), **password** (hash), foto, **cor_corpo, cor_barriga, cor_detalhe, cor_fundo** (cores do pinguim), **roupa, pescoco, oculos, chapeu** (peça vestida em cada camada, ou NULL), linguagem_id, nivel, meta_diaria, pontos, sequencia, ultimo_estudo, **tamanho_fonte**, **alto_contraste**, **destacar_links** |
| `password_reset_tokens` | email, token, created_at (usada pela recuperação de senha) |
| `cursos` | id, linguagem_id, **professor_id** (NULL = curso oficial), **publicado**, titulo, tipo (base/linguagem/assunto/comunidade), sigla, cor, descricao, historia, hoje, referencias |
| `modulos` | id, curso_id, titulo, descricao, dificuldade, ordem, projeto, aprofundamento, referencias |
| `licoes` | id, modulo_id, titulo, teoria, pratica (opcional), ordem |
| `exercicios` | id, licao_id, enunciado, alternativa_a, alternativa_b, alternativa_c e alternativa_d (C e D opcionais), resposta_correta ('a' a 'd'), explicacao |
| `progressos` | id, user_id, licao_id, acertos, total, aprovado, concluido_em (único: user_id + licao_id) |
| `conquistas` | id, nome, descricao, icone, tipo (licoes/sequencia/pontos), meta |
| `conquista_user` | id, conquista_id, user_id, created_at (= quando ganhou) |
| `compras` | id, user_id, item (código da roupa, ex.: `terno`), preco (único: user_id + item) |

As roupas **não** são uma tabela: o catálogo é fixo e fica em `app/Support/Pinguim.php` (código, nome, camada, imagem, preço). No banco ficam só a peça vestida em cada camada (colunas de `users`) e as compras.

Todas têm também `created_at` e `updated_at` (`$table->timestamps()`).

**Exclusão em cascata (`cascadeOnDelete`)**: se um curso é excluído, o banco apaga sozinho os módulos → lições → exercícios → progressos dele. Se um usuário é excluído, vão junto o progresso, as conquistas e (se for professor) os cursos que ele criou.

**Conteúdo:** 30 cursos oficiais (Lógica, 16 linguagens e 13 assuntos), 90 módulos, 270 lições e 810 exercícios, mais o curso de exemplo da comunidade.
- Os textos oficiais já estão em **HTML** no `conteudo.json`.
- Os textos dos professores são **texto simples**. Por isso o model tem `Licao::teoriaHtml()` e `Exercicio::enunciadoHtml()`: nos cursos da comunidade eles escapam o texto com `e()` (ninguém consegue injetar `<script>`) e trocam as quebras de linha por `<br>`.

**Nomes das tabelas:** o Laravel adivinha o nome da tabela pelo nome do Model em inglês. `Curso` → `cursos` funciona, mas `Licao` → `licaos` e `Linguagem` → `linguagems` não. Por isso esses models têm `protected $table = 'licoes';` e `protected $table = 'linguagens';`.

---

## 7. Cada tela: rota, controller e view

### Telas de todos os usuários

| Tela | URL | Controller@método | View |
| --- | --- | --- | --- |
| Landing page | `/` | (Route::view) | `welcome` |
| Cadastro | `/cadastro` | `AuthController@formCadastro` / `@cadastrar` | `auth/cadastro` |
| Login | `/login` | `AuthController@formLogin` / `@login` | `auth/login` |
| Sair | `POST /logout` | `AuthController@logout` | (redireciona) |
| Esqueci senha ou e-mail | `/esqueci-senha` | `SenhaController@formEsqueci` / `@enviarLink` | `senha/esqueci` |
| Nova senha (link do e-mail) | `/redefinir-senha/{token}` | `SenhaController@formRedefinir` / `@redefinir` | `senha/redefinir` |
| Painel | `/painel` | `PainelController@index` | `painel` |
| Comunidade | `/comunidade` | `PainelController@comunidade` | `comunidade` |
| Curso | `/cursos/{curso}` | `CursoController@show` | `cursos/show` |
| Lição | `/licoes/{licao}` | `LicaoController@show` / `@responder` | `licoes/show` |
| Conquistas | `/conquistas` | `ConquistaController@index` | `conquistas/index` |
| Perfil (estudos) | `/perfil` | `PerfilController@editar` / `@atualizar` | `perfil/editar` |
| Meu pinguim (cores) | `/perfil/avatar` | `PerfilController@formAvatar` / `@salvarAvatar` | `perfil/avatar` |
| Vestuário (roupas) | `/vestuario` (+ `POST /vestuario/vestir` e `POST /vestuario/comprar`) | `VestuarioController@index` / `@vestir` / `@comprar` | `vestuario/index` |
| Foto | `/perfil/foto` | `PerfilController@formFoto` / `@salvarFoto` | `perfil/foto` |
| Configurações | `/configuracoes` | `ConfiguracaoController@index`, `@atualizarConta`, `@atualizarAcessibilidade`, `@excluirConta` | `configuracoes/index` |
| Alterar senha | `/senha` | `SenhaController@formAlterar` / `@alterar` | `senha/alterar` |

### Área do professor (professor e admin), prefixo `/professor`

| Tela | URL | Controller@método | View |
| --- | --- | --- | --- |
| Meus cursos | `/professor` | `Professor\CursoController@index` | `professor/cursos/index` |
| Novo curso | `/professor/cursos/criar` | `@create` / `@store` | `professor/cursos/create` |
| Editar curso + módulos + publicar + excluir | `/professor/cursos/{curso}/editar` | `@edit` / `@update` / `@destroy` e `Professor\ModuloController@store` | `professor/cursos/edit` |
| Editar módulo + lições | `/professor/modulos/{modulo}/editar` | `Professor\ModuloController@edit` / `@update` / `@destroy` | `professor/modulos/edit` |
| Nova lição | `/professor/modulos/{modulo}/licoes/criar` | `Professor\LicaoController@create` / `@store` | `professor/licoes/create` |
| Editar lição + exercícios | `/professor/licoes/{licao}/editar` | `Professor\LicaoController@edit` / `@update` / `@destroy` e `Professor\ExercicioController@store` / `@destroy` | `professor/licoes/edit` |

### Admin, prefixo `/admin`

| Tela | URL | Controller@método | View |
| --- | --- | --- | --- |
| Painel Admin | `/admin` (busca: `/admin?busca=...`) | `AdminController@index` | `admin/index` |
| Trocar tipo | `PUT /admin/usuarios/{usuario}/tipo` | `AdminController@alterarTipo` | (volta para o painel) |
| Excluir usuário | `DELETE /admin/usuarios/{usuario}` | `AdminController@excluir` | (volta para o painel) |

Lista completa no terminal: `php artisan route:list --except-vendor`.

### O que cada tela faz

- **Cadastro:** tipo (aluno ou professor), nome, nome de usuário (único), e-mail (único), senha e confirmação. Salva a senha com `Hash::make`, já faz login e manda para o perfil.
- **Login:** `Auth::attempt(['email' => ..., 'password' => ...])` confere a senha com o hash.
- **Esqueci senha ou e-mail:** aceita o **e-mail ou o nome de usuário**. Quem esqueceu o e-mail digita o nome de usuário, e a tela mostra para onde o link foi, de forma mascarada (`t***e@technopenguin.com`).
  - `Password::sendResetLink()` gera um token e chama `User::sendPasswordResetNotification()`, que manda o e-mail com o link.
  - `Password::reset()` confere o token e troca a senha.
- **Painel:** pontos, dias seguidos, meta de hoje, conquistas, o curso da linguagem escolhida e os cursos oficiais com barra de progresso.
- **Comunidade:** cursos publicados por professores, com o nome do autor. Professores veem o botão "Criar curso".
- **Curso:** progresso, "Sobre o curso" e cada módulo com as lições:
  - ✅ concluída
  - ▶️ liberada
  - 🔒 bloqueada
- **Lição:** teoria, prática e exercícios de múltipla escolha. Ao enviar, a mesma tela mostra a correção:
  - acertos e ✔/✘ em cada exercício;
  - a letra certa e a explicação quando errou;
  - pontos e conquistas novas;
  - o botão "Próxima lição", se foi aprovado.
- **Conquistas:** todas as conquistas; as ganhas mostram a data.
- **Perfil:** linguagem, nível e meta diária, mais os botões de avatar, foto e configurações.
- **Meu pinguim (cores):** paletas para pele, barriga e rosto, bico e patas, e fundo. Também dá para escolher qualquer cor no seletor (bolinha de arco-íris). Tem os botões "Surpreenda-me" (sorteia) e "Cores originais", e a prévia muda ao vivo.
- **Vestuário:** quatro camadas (cabeça, óculos, pescoço e corpo), com uma peça por camada.
  - Peças grátis: é só clicar para vestir.
  - Peças exclusivas: custam pontos e são compradas e vestidas no mesmo clique.
  - Passar o mouse numa peça mostra como ela fica antes de escolher.
  - "Nenhum" tira a peça daquela camada.
- **Foto:** upload de imagem (jpg, png, gif ou webp, até 2 MB) para `public/uploads/`. No banco fica só o nome do arquivo; a foto antiga é apagada.
- **Configurações:**
  - Minha conta: nome, nome de usuário e e-mail.
  - Acessibilidade: veja a seção 10.
  - Link para alterar a senha.
  - Excluir conta: pede a senha.
- **Alterar senha:** pede a senha atual. Depois de trocar, desloga e manda para o login (RF05).
- **Área do professor:** monta o curso na ordem curso → módulo → lição → exercício. O curso começa como **rascunho**; "Ver como aluno" mostra como ele vai ficar.
  - Para **publicar**, o curso precisa ter pelo menos uma lição, e toda lição precisa de ao menos um exercício.
  - Exercícios têm de 2 a 4 alternativas, e a resposta certa precisa ser uma alternativa preenchida.
- **Admin:**
  - Números gerais: alunos, professores, admins, cursos e lições concluídas.
  - Tabela de usuários com busca, troca de tipo e exclusão. O admin não pode mudar o próprio tipo nem se excluir por ali.
  - Lista dos cursos da comunidade com "Ver", "Editar" e "Excluir".

### Como o pinguim (avatar) é desenhado

É a parte mais "visual" do projeto. A explicação curta:

1. **Camadas:** o avatar (`partials/avatar.blade.php`) é uma `<div>` com várias `<img>` **do mesmo tamanho** (520x1024) empilhadas com CSS (`position: absolute`): a foto do pinguim e, por cima, a roupa, o pescoço, os óculos e o chapéu. As imagens das roupas já foram desenhadas na posição certa, então basta empilhar. O fundo é a cor da variável CSS `--fundo`.
2. **Cores:** a foto (`imgs/pinguim/pinguim_original.png`) tem uma "irmã", a **máscara** (`pinguim_mascara.png`), em que vermelho = corpo, verde = barriga e azul = bico e patas. O `public/js/pinguim.js` desenha a foto num `<canvas>`, troca a cor de cada pixel que está numa região da máscara (mantendo o sombreado da foto) e coloca a imagem nova no lugar. As 3 cores vêm do atributo `data-pinguim="#corpo,#barriga,#detalhe"`.
3. **Banco:** só guarda as escolhas, que são as 4 cores (`cor_corpo`, `cor_barriga`, `cor_detalhe`, `cor_fundo`) e o código da peça vestida em cada camada (`roupa`, `pescoco`, `oculos`, `chapeu`). O catálogo das peças, com nome, imagem e preço, é uma lista fixa em `app/Support/Pinguim.php`.
4. **Formatos:** `avatar--corpo` mostra o pinguim inteiro, e `avatar--rosto` mostra só a cabeça num círculo (é o do menu). Os tamanhos são `avatar--palco`, `avatar--perfil` e `avatar--mini`.

---

## 8. Onde está cada requisito

| ID | Requisito | Onde está |
| --- | --- | --- |
| RF01 | Criar conta com nome, e-mail e senha | `AuthController@cadastrar`, `auth/cadastro` |
| RF02 | Login com e-mail e senha | `AuthController@login`, `auth/login` |
| RF03 | Recuperar senha por e-mail | `SenhaController@enviarLink` / `@redefinir`, `User::sendPasswordResetNotification` (aceita também o nome de usuário, para quem esqueceu o e-mail) |
| RF04 | Modificar a senha | `SenhaController@alterar`, `senha/alterar` |
| RF05 | Pedir login com a nova senha | `SenhaController@alterar` (faz `Auth::logout()` e redireciona) |
| RF06 | Escolher nome de usuário único | regra `unique:users` em `AuthController@cadastrar` + coluna `unique()` na migration |
| RF07 | Editar nome de usuário | `ConfiguracaoController@atualizarConta` (`unique:users,nome_usuario,ID`) |
| RF08 | Criar avatar | cores: `PerfilController@salvarAvatar`, `perfil/avatar`; roupas: `VestuarioController`, `vestuario/index`; desenho: `partials/avatar` + `public/js/pinguim.js` |
| RF09 | Editar avatar | idem RF08 (as cores e as roupas podem ser trocadas a qualquer momento) |
| RF10 | Escolher linguagem | `PerfilController@atualizar` (`linguagem_id`) |
| RF11 | Editar linguagem | idem RF10 |
| RF12 | Adicionar foto de perfil | `PerfilController@salvarFoto`, `perfil/foto` |
| RF13 | Alterar foto de perfil | idem RF12 (apaga a antiga) |
| RF14 | Informar nível de conhecimento | `PerfilController@atualizar` (`nivel`), `User::NIVEIS` |
| RF15 | Alterar nível | idem RF14 |
| RF16 | Ver progresso nos cursos | `PainelController` (barras), `CursoController@show` |
| RF17 | Ver conquistas e medalhas | `ConquistaController@index`, `User::verificarConquistas()` |
| RF18 | Ver pontuação acumulada | coluna `users.pontos`, painel; pontos dados em `LicaoController@responder` |
| RF19 | Ver sequência de dias | `User::sequenciaAtual()` / `registrarEstudo()`, painel |
| RF20 | Definir meta diária | `PerfilController@atualizar` (`meta_diaria`), painel mostra `licoesHoje()` |
| RF21 | Alterar meta diária | idem RF20 |
| RF22 | Escolher um curso | painel e Comunidade (lista de cursos) → `CursoController@show` |
| RF23 | Apresentar módulos | `CursoController@show`, `cursos/show` |
| RF24 | Apresentar lições | `cursos/show` (lista) e `LicaoController@show` (conteúdo) |
| RF25 | Responder exercícios | formulário em `licoes/show` |
| RF26 | Avaliar automaticamente | `LicaoController@responder` (compara com `resposta_correta`) |
| RF27 | Informar se está correta | `licoes/show` (✔ Correto! / ✘ Resposta errada) |
| RF28 | Mostrar a solução quando errar | `licoes/show` (letra certa + explicação) |
| RF29 | Avançar só depois de cumprir os critérios | `Curso::licoesDoUsuario()` (liberada) + nota mínima de 60% |
| RNF01 | Páginas em até 3 s | páginas leves: só HTML/CSS, sem frameworks JavaScript |
| RNF02 | Senhas criptografadas | `Hash::make()` (bcrypt) no cadastro, alteração e redefinição |
| RNF03 | Disponível 24 h | depende da hospedagem (fora do código) |
| RNF04 | Interface responsiva | `<meta viewport>`, grids flexíveis, tabela com rolagem e `@media (max-width: 600px)` em `app.css` |
| RNF05 | Compatível com navegadores atuais | HTML5/CSS3 padrão, sem recursos experimentais |
| — | 3 tipos de usuário (aluno, professor, admin) | coluna `users.tipo`, middleware `VerificarTipo`, `User::ehProfessor()` / `ehAdmin()` / `podeEditar()` |
| — | Professor cria conteúdo (Comunidade) | `Professor\*Controller`, views `professor/`, `PainelController@comunidade` |
| — | Admin gerencia usuários | `AdminController`, `admin/index` |
| — | Acessibilidade, editar e excluir conta | `ConfiguracaoController`, `configuracoes/index`, `User::classesAcessibilidade()` |

---

## 9. Regras de negócio

Constantes no `app/Models/User.php`, fáceis de mudar:

- **`NOTA_MINIMA = 60`**: precisa acertar pelo menos 60% dos exercícios para ser aprovado na lição.
- **`PONTOS_POR_ACERTO = 10`**: os pontos entram só na **primeira** aprovação da lição. Refazer não dá pontos de novo, mas guarda o melhor resultado.
- **Liberação de lições** (`Curso::licoesDoUsuario`): a 1ª lição do curso está sempre liberada; as outras só depois que a anterior foi aprovada. O controller recusa abrir ou responder uma lição bloqueada.
- **Sequência** (`User::registrarEstudo`): ao ser aprovado numa lição, se o último estudo foi ontem a sequência soma 1; se foi hoje não muda; se foi antes, recomeça em 1. Na tela (`sequenciaAtual`), se o último estudo foi antes de ontem, mostra 0.
- **Meta diária**: lições aprovadas hoje comparadas com `meta_diaria`.
- **Vestuário** (`VestuarioController`):
  - Peças com preço 0 são grátis para todos. As outras precisam ser compradas com pontos.
  - Saldo para gastar = `pontos` − soma dos preços em `compras` (`User::saldoPontos()`). A pontuação acumulada (RF18) e as medalhas **não** diminuem com as compras.
  - Só dá para vestir uma peça que se tem (`User::temItem()`) e na camada certa.
- **Conquistas** (`User::verificarConquistas`): depois de cada resposta, compara lições aprovadas, sequência e pontos com a `meta` de cada conquista que o usuário ainda não tem, e grava as novas em `conquista_user`.
- **Cursos da comunidade**:
  - Nascem como rascunho e só podem ser publicados com lições que tenham exercícios.
  - Só o dono e os admins editam.
  - Rascunhos são invisíveis para os outros.
  - Não aparecem no painel, só na aba Comunidade.
- **Admin**: não pode trocar o próprio tipo nem se excluir pelo painel. Para isso existe Configurações > Excluir conta.

---

## 10. Acessibilidade

Na tela **Configurações > Acessibilidade**, cada usuário escolhe:

| Opção | Coluna | Como funciona |
| --- | --- | --- |
| Tamanho do texto (normal, grande, muito grande) | `tamanho_fonte` | Classe `fonte-grande` ou `fonte-muito_grande` na tag `<html>`. O CSS muda o `font-size` da raiz para 112,5% ou 125%, e tudo que usa `rem` cresce junto. |
| Alto contraste | `alto_contraste` | Classe `contraste`: o CSS troca as variáveis de cor (fundo preto, texto branco, destaques em amarelo) e reforça as bordas. |
| Destacar links | `destacar_links` | Classe `links-destacados`: links sublinhados e em negrito. |

O caminho: `User::classesAcessibilidade()` monta as classes, e o layout coloca na tag: `<html class="{{ auth()->user()->classesAcessibilidade() }}">`. As regras estão no fim de `public/css/app.css`, seção ACESSIBILIDADE.

Também valem para todo mundo:
- link **"Pular para o conteúdo"**, que aparece ao apertar Tab;
- contorno amarelo bem visível no elemento com foco do teclado (`:focus-visible`);
- `alt` nas imagens;
- todo campo de formulário dentro de um `<label>`;
- `aria-label` no menu e nos seletores sem texto visível.

---

## 11. Padrões usados no projeto

| Padrão / conceito | Onde aparece |
| --- | --- |
| **MVC** | Models em `app/Models`, Views em `resources/views`, Controllers em `app/Http/Controllers`. |
| **Front Controller** | Todo pedido entra por `public/index.php`, e o Laravel decide a rota. |
| **Rotas nomeadas** | `->name('perfil.editar')` e, nas views, `route('perfil.editar')`. Se a URL mudar, os links continuam funcionando. |
| **Grupos de rotas** | `Route::middleware(...)->prefix('professor')->name('professor.')->group(...)`: aplica o middleware, o prefixo da URL e o prefixo do nome a todas as rotas de dentro. |
| **Middleware** | `auth` e `guest` (do Laravel) e o nosso `tipo` (`VerificarTipo`) para controle de acesso por tipo de usuário. |
| **Controle de acesso** | Por tipo (middleware) + por dono do registro (`podeEditar()` → `abort(403)`). |
| **Namespaces / subpastas** | `app/Http/Controllers/Professor/` → `namespace App\Http\Controllers\Professor`. Por isso pode haver dois `LicaoController` (aluno e professor). |
| **Eloquent ORM (Active Record)** | Cada Model é uma tabela: `User::create()`, `$user->update()`, `$curso->delete()`. Relacionamentos `belongsTo`, `hasMany`, `hasManyThrough`, `belongsToMany`. |
| **Route Model Binding** | `public function show(Curso $curso)`: o Laravel busca o curso pelo `{curso}` da URL (ou dá 404). |
| **Migrations e Seeders** | O banco é criado por código versionado no Git, e os dados iniciais vêm dos seeders. |
| **Integridade referencial** | Chaves estrangeiras (`constrained()`) com exclusão em cascata (`cascadeOnDelete()`). |
| **Validação no servidor** | `$request->validate([...])` em todos os formulários, com mensagens em `lang/pt_BR`. |
| **PRG (Post/Redirect/Get)** | Depois de salvar, `redirect()`: apertar F5 não reenvia o formulário. |
| **Flash message** | `->with('aviso', '...')` e `session('aviso')` no layout. |
| **Herança de templates** | `@extends('layouts.app')` + `@section` / `@yield`; pedaços reutilizados com `@include`. |
| **Segurança** | Senha com hash (bcrypt), `@csrf` em todo formulário, `{{ }}` e `e()` escapam HTML (XSS), Eloquent usa prepared statements (SQL Injection), upload só de imagens, 403/404 para quem não tem acesso. |

---

## 12. Como criar um CRUD novo do zero

**CRUD** = **C**reate (criar), **R**ead (listar), **U**pdate (editar), **D**elete (apagar).

> **Exemplos reais no projeto:** os 4 controllers de `app/Http/Controllers/Professor/` são CRUDs completos. O mais simples de ler é o `ModuloController` (store, edit, update e destroy), com as views em `resources/views/professor/`.

Abaixo, um CRUD completo do zero: **Anotações** (cada usuário escreve anotações de estudo). O código foi testado neste projeto. A receita é sempre a mesma: para outro CRUD, é só trocar os nomes.

```text
1. Migration   → cria a tabela
2. Model       → representa a tabela
3. Controller  → os 6 métodos do CRUD
4. Rotas       → ligam as URLs aos métodos
5. Views       → lista, formulário de criar, formulário de editar
6. Link no menu
```

### Passo 1: Migration (a tabela)

```sh
php artisan make:migration create_anotacoes_table
```

Abra o arquivo criado em `database/migrations/` e deixe o `up()` assim:

```php
public function up(): void
{
    Schema::create('anotacoes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();   // dono da anotação
        $table->string('titulo', 100);
        $table->text('texto');
        $table->timestamps();
    });
}
```

(O `down()` já vem com `Schema::dropIfExists('anotacoes');`.)

Crie a tabela no banco:

```sh
php artisan migrate
```

> **`migrate`** roda só as migrations novas e mantém os dados. **`migrate:fresh --seed`** apaga tudo e recria.

### Passo 2: Model

```sh
php artisan make:model Anotacao
```

`app/Models/Anotacao.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anotacao extends Model
{
    protected $table = 'anotacoes';   // sem isso o Laravel procuraria "anotacaos"

    protected $fillable = ['user_id', 'titulo', 'texto'];   // colunas que o create()/update() podem preencher

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Passo 3: Controller

```sh
php artisan make:controller AnotacaoController
```

`app/Http/Controllers/AnotacaoController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Anotacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnotacaoController extends Controller
{
    // READ (lista) - GET /anotacoes
    public function index()
    {
        $anotacoes = Anotacao::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('anotacoes.index', ['anotacoes' => $anotacoes]);
    }

    // CREATE (formulário) - GET /anotacoes/criar
    public function create()
    {
        return view('anotacoes.create');
    }

    // CREATE (salvar) - POST /anotacoes
    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo' => 'required|max:100',
            'texto'  => 'required',
        ]);
        $dados['user_id'] = Auth::id();   // a anotação é de quem está logado

        Anotacao::create($dados);

        return redirect()->route('anotacoes.index')->with('aviso', 'Anotação criada!');
    }

    // UPDATE (formulário) - GET /anotacoes/{anotacao}/editar
    public function edit(Anotacao $anotacao)
    {
        $this->verificarDono($anotacao);
        return view('anotacoes.edit', ['anotacao' => $anotacao]);
    }

    // UPDATE (salvar) - PUT /anotacoes/{anotacao}
    public function update(Request $request, Anotacao $anotacao)
    {
        $this->verificarDono($anotacao);

        $dados = $request->validate([
            'titulo' => 'required|max:100',
            'texto'  => 'required',
        ]);
        $anotacao->update($dados);

        return redirect()->route('anotacoes.index')->with('aviso', 'Anotação atualizada!');
    }

    // DELETE - DELETE /anotacoes/{anotacao}
    public function destroy(Anotacao $anotacao)
    {
        $this->verificarDono($anotacao);
        $anotacao->delete();

        return redirect()->route('anotacoes.index')->with('aviso', 'Anotação apagada!');
    }

    // Ninguém mexe na anotação de outra pessoa (erro 403 = proibido).
    private function verificarDono(Anotacao $anotacao)
    {
        if ($anotacao->user_id != Auth::id()) {
            abort(403);
        }
    }
}
```

### Passo 4: Rotas

Em `routes/web.php`, adicione o `use` no topo:

```php
use App\Http\Controllers\AnotacaoController;
```

E, **dentro** do grupo `Route::middleware('auth')->group(function () { ... });` (só logado):

```php
Route::get('/anotacoes', [AnotacaoController::class, 'index'])->name('anotacoes.index');
Route::get('/anotacoes/criar', [AnotacaoController::class, 'create'])->name('anotacoes.create');
Route::post('/anotacoes', [AnotacaoController::class, 'store'])->name('anotacoes.store');
Route::get('/anotacoes/{anotacao}/editar', [AnotacaoController::class, 'edit'])->name('anotacoes.edit');
Route::put('/anotacoes/{anotacao}', [AnotacaoController::class, 'update'])->name('anotacoes.update');
Route::delete('/anotacoes/{anotacao}', [AnotacaoController::class, 'destroy'])->name('anotacoes.destroy');
```

> O nome entre chaves `{anotacao}` precisa ser **igual** ao nome da variável no controller (`Anotacao $anotacao`). É isso que faz o Route Model Binding funcionar.
>
> **Só para um tipo de usuário?** Coloque as rotas dentro de um grupo com o middleware: `Route::middleware('tipo:professor,admin')->group(function () { ...rotas... });`
>
> **Atalho:** essas 6 linhas equivalem a `Route::resource('anotacoes', AnotacaoController::class)->except('show')->parameters(['anotacoes' => 'anotacao']);`, mas aí a URL de criar fica `/anotacoes/create` e a de editar `/anotacoes/{anotacao}/edit`. As 6 linhas são mais fáceis de explicar.

Confira com `php artisan route:list --except-vendor`.

### Passo 5: Views

Crie a pasta `resources/views/anotacoes/` com 3 arquivos.

**`index.blade.php`** (lista + botão apagar):

```blade
@extends('layouts.app')

@section('titulo', 'Minhas anotações')

@section('conteudo')
<h1>Minhas anotações</h1>
<p><a class="botao" href="{{ route('anotacoes.create') }}">+ Nova anotação</a></p>

@forelse ($anotacoes as $anotacao)
  <section class="cartao">
    <h2>{{ $anotacao->titulo }}</h2>
    <p>{{ $anotacao->texto }}</p>
    <small class="suave">{{ $anotacao->created_at->format('d/m/Y H:i') }}</small>

    <div class="botoes">
      <a class="botao botao--claro" href="{{ route('anotacoes.edit', $anotacao) }}">Editar</a>

      <form method="POST" action="{{ route('anotacoes.destroy', $anotacao) }}"
            onsubmit="return confirm('Apagar esta anotação?')">
        @csrf
        @method('DELETE')
        <button class="botao botao--perigo" type="submit">Apagar</button>
      </form>
    </div>
  </section>
@empty
  <p class="suave">Você ainda não tem anotações.</p>
@endforelse
@endsection
```

**`create.blade.php`** (formulário vazio → `POST`):

```blade
@extends('layouts.app')

@section('titulo', 'Nova anotação')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>Nova anotação</h1>

  @include('partials.erros')

  <form method="POST" action="{{ route('anotacoes.store') }}" class="formulario">
    @csrf
    <label>Título
      <input type="text" name="titulo" value="{{ old('titulo') }}" required>
    </label>
    <label>Texto
      <textarea name="texto" rows="5" required>{{ old('texto') }}</textarea>
    </label>
    <button class="botao" type="submit">Salvar</button>
  </form>

  <p class="centro"><a href="{{ route('anotacoes.index') }}">Voltar</a></p>
</section>
@endsection
```

**`edit.blade.php`** (formulário preenchido → `PUT`):

```blade
@extends('layouts.app')

@section('titulo', 'Editar anotação')

@section('conteudo')
<section class="cartao cartao--estreito">
  <h1>Editar anotação</h1>

  @include('partials.erros')

  <form method="POST" action="{{ route('anotacoes.update', $anotacao) }}" class="formulario">
    @csrf
    @method('PUT')
    <label>Título
      <input type="text" name="titulo" value="{{ old('titulo', $anotacao->titulo) }}" required>
    </label>
    <label>Texto
      <textarea name="texto" rows="5" required>{{ old('texto', $anotacao->texto) }}</textarea>
    </label>
    <button class="botao" type="submit">Salvar</button>
  </form>

  <p class="centro"><a href="{{ route('anotacoes.index') }}">Voltar</a></p>
</section>
@endsection
```

### Passo 6: Link no menu

Em `resources/views/layouts/app.blade.php`, dentro do `<nav class="menu">`:

```blade
<a href="{{ route('anotacoes.index') }}">Anotações</a>
```

Pronto: abra <http://127.0.0.1:8000/anotacoes>.

### Detalhes que costumam dar erro

| Sintoma | Causa |
| --- | --- |
| Erro 419 ao enviar | Faltou `@csrf` no `<form>`. |
| `The PUT method is not supported` / `DELETE ...` | Faltou `@method('PUT')` ou `@method('DELETE')` (formulário HTML só envia GET e POST). |
| `Route [anotacoes.xxx] not defined` | O `->name()` da rota está diferente do usado em `route()`. Dentro de um grupo com `->name('professor.')`, o nome completo fica `professor.xxx`. |
| `Base table or view not found: ... anotacaos` | Faltou `protected $table = 'anotacoes';` no model. |
| `Add [titulo] to fillable property` | A coluna não está no `$fillable` do model. |
| A variável chega vazia ou dá 404 em editar | O `{anotacao}` da rota não bate com `Anotacao $anotacao` no controller. |
| Mensagem de erro mostra o nome cru do campo | Adicione o nome em `attributes` de `lang/pt_BR/validation.php`. |
| Excluir um usuário dá erro de chave estrangeira | Faltou `->cascadeOnDelete()` no `foreignId('user_id')` da migration nova. |

### Variação: CRUD ligado a outra tabela (com `<select>`)

Se o CRUD tiver uma chave estrangeira (ex.: anotação de **um curso**):

1. Migration: `$table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();`
2. Model: coloque `'curso_id'` no `$fillable` e o relacionamento `public function curso() { return $this->belongsTo(Curso::class); }`
3. Controller (`create` e `edit`): mande a lista `'cursos' => Curso::orderBy('titulo')->get()` para a view. Na validação, use `'curso_id' => 'required|exists:cursos,id'`.
4. View: veja o `<select>` de linguagens em `resources/views/perfil/editar.blade.php`, que é igual.
5. Na lista: `{{ $anotacao->curso->titulo }}`.

**Variação "filho dentro do pai"** (como módulo dentro de curso): a rota leva o pai na URL (`POST /professor/cursos/{curso}/modulos`) e o controller cria pelo relacionamento: `$curso->modulos()->create($dados)`. Veja `Professor\ModuloController@store`.

---

## 13. Outras mudanças que o professor pode pedir

### Adicionar um campo numa tabela que já existe (ex.: "bio" no usuário)

```sh
php artisan make:migration add_bio_to_users_table
```

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {   // Schema::table = ALTERAR (não criar)
        $table->string('bio', 200)->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('bio');
    });
}
```

```sh
php artisan migrate
```

Depois:
1. Coloque `'bio'` no `$fillable` de `User.php`.
2. Adicione a regra `'bio' => 'nullable|max:200'` em `ConfiguracaoController@atualizarConta`.
3. Coloque o campo no formulário "Minha conta" de `configuracoes/index.blade.php`:

```blade
<label>Bio
  <input type="text" name="bio" value="{{ old('bio', $user->bio) }}">
</label>
```

### Criar um 4º tipo de usuário (ex.: "monitor")

1. Adicione `'monitor' => 'Monitor'` em `User::TIPOS`.
2. No `AdminController@alterarTipo`, troque a regra para `in:aluno,professor,admin,monitor` (o `<select>` do admin já lista tudo que está em `TIPOS`).
3. Se ele puder se cadastrar sozinho, troque também a regra do `AuthController@cadastrar` para `in:aluno,professor,monitor` e coloque o rádio na view `auth/cadastro`.
4. Para liberar uma área: `Route::middleware('tipo:monitor,admin')->group(...)`.

### Nova opção de acessibilidade (ex.: fonte para dislexia)

1. Migration adicionando `$table->boolean('fonte_dislexia')->default(false);` na tabela `users` (ou edite a migration de users e rode `migrate:fresh --seed`).
2. `User.php`: coloque a coluna no `$fillable` e nos `$casts` (`boolean`), e em `classesAcessibilidade()` adicione `if ($this->fonte_dislexia) { $classes .= ' fonte-dislexia'; }`.
3. `ConfiguracaoController@atualizarAcessibilidade`: `'fonte_dislexia' => $request->boolean('fonte_dislexia')`.
4. Checkbox em `configuracoes/index.blade.php`.
5. CSS: `html.fonte-dislexia body { font-family: Verdana, sans-serif; letter-spacing: .05em; word-spacing: .1em; }`.

### Criar uma página simples nova (sem banco)

1. Rota: `Route::get('/sobre', [PaginaController::class, 'sobre'])->name('sobre');`
2. `php artisan make:controller PaginaController` com `public function sobre() { return view('sobre'); }`
3. View `resources/views/sobre.blade.php` com `@extends('layouts.app')` e `@section('conteudo') ... @endsection`.

(Para uma página sem lógica, também dá para usar só `Route::view('/sobre', 'sobre');`.)

### Nova conquista

Adicione uma linha na lista do `DatabaseSeeder.php` (ex.: `['Pinguim lendário', 'Conclua 100 lições.', '👑', 'licoes', 100]`) e rode `php artisan migrate:fresh --seed`. Ou cadastre direto pelo phpMyAdmin na tabela `conquistas`. Nenhum código muda.

### Mudar uma regra

Nota mínima e pontos por acerto: constantes no topo de `app/Models/User.php`.

---

## 14. Comandos úteis

| Comando | O que faz |
| --- | --- |
| `php artisan serve` | Sobe o site em <http://127.0.0.1:8000> |
| `php artisan migrate` | Roda as migrations novas |
| `php artisan migrate:fresh --seed` | Apaga tudo, recria as tabelas e preenche o conteúdo |
| `php artisan migrate:status` | Lista as migrations e se já rodaram |
| `php artisan migrate:rollback` | Desfaz o último `migrate` |
| `php artisan route:list --except-vendor` | Lista as rotas do projeto |
| `php artisan make:migration create_xxx_table` | Cria uma migration |
| `php artisan make:model Xxx` | Cria um model |
| `php artisan make:controller XxxController` | Cria um controller |
| `php artisan make:middleware Xxx` | Cria um middleware |
| `php artisan tinker` | Terminal para testar código: `App\Models\User::where('tipo', 'admin')->get()` |
| `php artisan optimize:clear` | Limpa caches (rotas, views, config) |

---

## 15. Perguntas prováveis e respostas curtas

**Qual a arquitetura do projeto?**
MVC com Laravel. A rota (`routes/web.php`) passa pelos middlewares e chama um método do controller, que usa os models (Eloquent) para ler e gravar no MySQL e devolve uma view Blade.

**Quais linguagens foram usadas?**
PHP (Laravel) no back-end, SQL (MySQL/MariaDB), HTML e Blade nas telas, CSS no estilo e um pouco de JavaScript (prévia do avatar e confirmações).

**Quais são os tipos de usuário e como o acesso é controlado?**
Aluno, professor e admin, na coluna `users.tipo`. As rotas do professor ficam num grupo com `middleware('tipo:professor,admin')` e as do admin com `middleware('tipo:admin')`. O middleware `VerificarTipo` devolve 403 se o tipo não estiver na lista. Além disso, `podeEditar()` garante que o professor só mexe nos próprios cursos.

**O que é um middleware?**
Um código que roda antes do controller e pode barrar o pedido. Usamos três:
- `auth`: está logado?
- `guest`: não está logado?
- `tipo`: é do tipo certo?

**Como o professor cria um curso?**
Área do professor → Novo curso (rascunho) → adiciona módulos → em cada módulo, adiciona lições → em cada lição, adiciona exercícios → marca "Publicado". Aí o curso aparece na aba Comunidade.

**O que o admin pode fazer?**
- Ver números gerais.
- Buscar usuários.
- Trocar o tipo de um usuário (por exemplo, promover a professor ou admin).
- Excluir usuários.
- Editar ou excluir qualquer curso da comunidade.

**O que acontece quando um usuário é excluído?**
As chaves estrangeiras têm `cascadeOnDelete`, então o próprio banco apaga o progresso, as conquistas e, se for professor, os cursos dele (com módulos, lições e exercícios).

**Quais recursos de acessibilidade existem?**
- Tamanho do texto, alto contraste e destacar links: escolhas salvas no usuário, que viram classes CSS na tag `<html>`.
- Para todo mundo: link "pular para o conteúdo", foco visível, `alt` nas imagens e `label` em todos os campos.

**Como funciona o login?**
`Auth::attempt()` busca o usuário pelo e-mail e compara a senha digitada com o hash salvo. Se der certo, o id fica na sessão.

**Como a senha é guardada?**
Com `Hash::make()` (bcrypt). É um hash de mão única: nem quem tem acesso ao banco consegue ver a senha (RNF02).

**Como funciona a recuperação de senha? E se a pessoa esqueceu o e-mail?**
A pessoa digita o e-mail **ou o nome de usuário**.
1. O sistema encontra a conta e `Password::sendResetLink()` cria um token e manda um e-mail com o link `/redefinir-senha/{token}`.
2. A tela mostra o e-mail mascarado (ex.: `t***e@technopenguin.com`), para a pessoa saber qual e-mail olhar.
3. No link, `Password::reset()` confere o token (vale 60 min) e troca a senha.

No projeto local, o e-mail vai para `storage/logs/laravel.log`.

**Como o avatar funciona?**
São imagens do mesmo tamanho empilhadas: a foto do pinguim e as roupas por cima.
- As cores são aplicadas no navegador pelo `pinguim.js`. Ele usa uma imagem-máscara que marca as regiões (corpo, barriga, bico) e repinta os pixels de cada região num `<canvas>`, mantendo o sombreado.
- O banco guarda só as 4 cores e o código da peça vestida em cada camada.
- As peças exclusivas são compradas com pontos (tabela `compras`).

**Por que `{{ }}` e às vezes `{!! !!}`?**
`{{ }}` escapa o HTML (protege contra XSS) e é usado para tudo que vem do usuário. `{!! !!}` mostra HTML puro e só é usado no conteúdo das lições:
- nos cursos oficiais, o HTML foi escrito pela equipe;
- nos cursos da comunidade, o texto do professor passa antes por `e()` em `teoriaHtml()`.

**O que é migration? E seeder?**
Migration é a criação/alteração das tabelas em código PHP, versionada no Git. Seeder preenche o banco com dados iniciais (cursos, conquistas, usuários de teste).

**Como a lição é corrigida?**
O formulário manda `respostas[id_do_exercicio] = letra`. O `LicaoController@responder` compara cada uma com `resposta_correta`, conta os acertos, calcula a porcentagem e aprova com 60% ou mais.

**Qual a diferença entre `hasMany`, `belongsTo` e `belongsToMany`?**
- `Curso hasMany Modulo`: 1:N, visto do lado "1".
- `Modulo belongsTo Curso`: o mesmo 1:N, visto do lado "N", que tem a chave estrangeira.
- `User belongsToMany Conquista`: N:N, pela tabela pivô `conquista_user`.

---

## 16. O que foi removido da versão anterior

A versão anterior tinha cerca de 13 mil linhas, numa estrutura MVC própria (sem framework) e com muitos recursos fora da lista de requisitos. O projeto foi refeito em Laravel e estes recursos saíram:

- iglus (grupos), chat, amigos e **perguntas da comunidade**;
- certificados;
- notificações;
- LGPD (termos, política de privacidade, idade, responsável, exportar dados);
- bloqueio de login após tentativas erradas;
- questionário de boas-vindas (as mesmas escolhas agora ficam no perfil);
- o sistema próprio de migrations/seeder e o parser de Markdown do conteúdo.

Voltaram:
- **o pinguim original**, igual ao de antes: foto recolorida no navegador, fundo colorido e o vestuário com as 12 roupas, sendo as exclusivas compradas com pontos;
- de forma simplificada, a área do professor e a aba Comunidade (só conteúdo e exercícios);
- a exclusão de conta (em Configurações).

E foram adicionados o administrador e a acessibilidade.

**Todo o conteúdo dos cursos foi mantido** (30 cursos, 90 módulos, 270 lições, 810 exercícios, projetos, aprofundamentos e referências), agora em `database/seeders/dados/conteudo.json`. A landing page e as imagens do Teco também foram mantidas.
