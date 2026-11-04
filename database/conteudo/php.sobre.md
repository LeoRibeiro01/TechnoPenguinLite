@historia
Em 1994, o dinamarquês-canadense **Rasmus Lerdorf** escreveu alguns scripts em C para acompanhar as visitas ao seu currículo online. Ele chamou o conjunto de **Personal Home Page Tools**, o primeiro PHP. Em 1997, os israelenses **Zeev Suraski** e **Andi Gutmans** reescreveram o interpretador, criando o PHP 3, e o nome passou a significar o acrônimo recursivo *PHP: Hypertext Preprocessor*.

Os dois fundaram depois a Zend, cujo motor equipa o PHP desde a versão 4. O **PHP 5** (2004) trouxe orientação a objetos de verdade e o PDO. O PHP 6 nunca saiu, e o **PHP 7** (2015) dobrou o desempenho. O **PHP 8** (2020) trouxe compilação JIT, argumentos nomeados, `match`, union types, enums, readonly e muito mais, com versões anuais desde então.

@hoje
PHP roda em cerca de três quartos dos sites com linguagem de servidor conhecida. Move o **WordPress** (mais de 40% de todos os sites), o Wikipedia, partes do Facebook (que criou o Hack, derivado do PHP) e plataformas de e-commerce como Magento e WooCommerce. Frameworks como **Laravel** e **Symfony** são usados em sistemas modernos, e o **Composer** organiza um ecossistema enorme de pacotes. É ótimo para aprender back-end, porque roda fácil em qualquer hospedagem e no XAMPP. Inclusive, este site que você está usando é feito em PHP!

@iniciante
- Escrever páginas dinâmicas com variáveis, echo e strings
- Usar arrays indexados e associativos com foreach
- Controlar o fluxo com if, match e laços
- Criar funções tipadas com strict_types

@intermediario
- Receber dados de formulários com GET e POST e validar
- Proteger a saída contra XSS com htmlspecialchars
- Criar login seguro com sessões, password_hash e tokens CSRF
- Acessar bancos de dados com PDO e prepared statements

@avancado
- Programar orientado a objetos com classes modernas, enums e readonly
- Gerenciar dependências e autoload PSR-4 com Composer
- Organizar projetos no padrão MVC
- Conhecer Laravel, Symfony, PHPUnit e as PSRs

@referencias
**Livros**
- *Modern PHP* — Josh Lockhart: as práticas modernas do PHP, do autor do site PHP: The Right Way.
- *PHP Objects, Patterns, and Practice* — Matt Zandstra: orientação a objetos e padrões de projeto em PHP.
- *Laravel: Up & Running* — Matt Stauffer.

**Na internet**
- [PHP: The Right Way (em português)](https://phptherightway.com/?lang=pt-br) — boas práticas atualizadas.
- [Manual oficial do PHP](https://www.php.net/manual/pt_BR/)
- [OWASP Cheat Sheet Series](https://cheatsheetseries.owasp.org) — segurança web.

@modulo 1
**PHP tem tipagem dinâmica, mas pode (e deve) ser rigoroso.** Sem `declare(strict_types=1)`, o PHP converte valores silenciosamente: passar `"5 maçãs"` para um parâmetro `int` vira `5` com só um aviso. Com strict types, dá erro. Somado a tipos em parâmetros, retornos e propriedades, isso torna o PHP moderno tão seguro quanto linguagens estaticamente tipadas na maior parte do código. Ferramentas de análise estática como **PHPStan** e **Psalm** pegam erros antes de executar.

Cada requisição HTTP começa do zero no PHP: o script roda, gera a resposta e toda a memória é liberada (*shared nothing*). Isso torna o PHP simples e robusto, e é por isso que estado entre requisições precisa de sessão, cookies ou banco de dados.

Use `===` sempre: a comparação frouxa (`==`) teve regras confusas por muitos anos.

@fontes 1
- Lockhart, *Modern PHP*, capítulo 2 (recursos modernos)
- [PHP: The Right Way — Básico da linguagem](https://phptherightway.com/?lang=pt-br#language_highlights)
- [Manual do PHP: declarações de tipo](https://www.php.net/manual/pt_BR/language.types.declarations.php)

@modulo 2
**Nunca confie na entrada; escape na saída, no contexto certo.** A OWASP resume a defesa web em duas regras: validar o que entra e codificar o que sai conforme o contexto: `htmlspecialchars` para HTML, consultas parametrizadas para SQL, `json_encode` para JavaScript, `escapeshellarg` para comandos. Usar a função errada no contexto errado ainda deixa a falha aberta.

Sessões guardam um identificador no cookie; proteja-o com as flags **HttpOnly** (JavaScript não lê), **Secure** (só HTTPS) e **SameSite** (dificulta CSRF), e chame `session_regenerate_id(true)` no login. Senhas: `password_hash` usa bcrypt (ou Argon2) com salt automático e `password_needs_rehash` permite atualizar o algoritmo com o tempo.

Com PDO, desligue a emulação de prepares (`PDO::ATTR_EMULATE_PREPARES => false`) para que o próprio banco separe comando e dados.

@fontes 2
- [OWASP Cheat Sheet: Cross Site Scripting Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [OWASP Cheat Sheet: Session Management](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html)
- Lockhart, *Modern PHP*, capítulo 5 (boas práticas de segurança)

@modulo 3
**Padrões de projeto resolvem problemas recorrentes.** Matt Zandstra, em *PHP Objects, Patterns, and Practice*, adapta ao PHP os padrões do clássico *Design Patterns* (a "Gangue dos Quatro"): **Repository** isola o acesso a dados, **Strategy** troca algoritmos em tempo de execução, **Factory** centraliza a criação de objetos e **Front Controller** recebe todas as requisições num único ponto (como o `index.php` do Laravel).

As **PSRs** do PHP-FIG padronizam o ecossistema: PSR-4 (autoload), PSR-12 (estilo), PSR-7 e PSR-15 (requisições e middlewares HTTP), PSR-11 (contêiner de dependências). Graças a elas, pacotes de projetos diferentes conversam entre si.

Teste com **PHPUnit** ou **Pest**, analise com PHPStan e formate com PHP-CS-Fixer. Com o PHP 8 em diante, OPcache e JIT tornam o desempenho comparável ao de outras linguagens interpretadas populares.

@fontes 3
- Zandstra, *PHP Objects, Patterns, and Practice*, partes 3 (padrões) e 4 (prática)
- Gamma, Helm, Johnson e Vlissides, *Padrões de Projeto* (1994)
- [PHP-FIG: PSRs aceitas](https://www.php-fig.org/psr/)
