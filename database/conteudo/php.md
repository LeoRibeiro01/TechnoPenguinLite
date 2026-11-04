# Fundamentos de PHP
> Sintaxe, variáveis, arrays, decisões, laços e funções.

## Projeto
**Gerador de cardápio do iglu:** crie uma página PHP que guarda pratos num array associativo (nome, preço, categoria), mostra-os agrupados por categoria numa tabela HTML e calcula o total de um pedido recebido por `$_GET`. Escape toda saída com `htmlspecialchars`.

## Olá, PHP
### Teoria
PHP é uma linguagem de **servidor**: o código roda no servidor web (como o Apache do XAMPP) e o navegador recebe só o HTML gerado. Ele move boa parte da web, incluindo WordPress e o próprio TechnoPenguin!

O código fica entre `<?php` e `?>`. Variáveis começam com `$`, instruções terminam com `;` e `echo` envia texto para a página.

Para testar, salve o arquivo em `C:\xampp\htdocs` e abra `http://localhost/arquivo.php`, ou rode `php -S localhost:8000` na pasta.

Strings com aspas duplas interpretam variáveis (`"Olá, $nome"`); com aspas simples, não.

### Prática
```php
<?php
$nome = "Teco";
$peixes = 3;
echo "<h1>Olá, $nome!</h1>";
echo '<p>Peixes: ' . $peixes . '</p>';   // . concatena
echo "<p>Dobro: " . ($peixes * 2) . "</p>";
var_dump($peixes);                       // int(3)
```

### Questão
Onde o código PHP é executado?
- [ ] No navegador do usuário
- [x] No servidor, antes de a página ser enviada
- [ ] No banco de dados
- [ ] No editor de código
> O navegador recebe apenas o resultado (HTML), nunca o código PHP.

### Questão
Qual operador concatena strings em PHP?
- [ ] +
- [x] .
- [ ] &
- [ ] ,
> O ponto junta textos: "a" . "b" vira "ab".

### Questão
O que `echo 'Olá, $nome';` (aspas simples) mostra?
- [ ] Olá, Teco
- [x] Olá, $nome
- [ ] Nada
- [ ] Um erro
> Aspas simples não interpolam variáveis.

## Arrays e controle de fluxo
### Teoria
Arrays em PHP são muito flexíveis:
- **Indexados:** `$linguagens = ["PHP", "JS"];`
- **Associativos:** `$aluno = ["nome" => "Ana", "nota" => 9];`

Funções úteis: `count`, `in_array`, `array_keys`, `array_map`, `array_filter`, `sort`, `implode`.

Controle de fluxo: `if / elseif / else`, `switch`, a expressão `match` (PHP 8, comparação estrita e retorna valor), `for`, `while` e `foreach`, o mais usado para arrays: `foreach ($aluno as $chave => $valor)`.

Compare com `===` (valor e tipo) em vez de `==`.

### Prática
```php
<?php
$notas = ["Ana" => 9.5, "Beto" => 5.0, "Caio" => 7.5];

foreach ($notas as $aluno => $nota) {
    $situacao = match (true) {
        $nota >= 7 => "aprovado",
        $nota >= 5 => "recuperação",
        default => "reprovado",
    };
    echo "$aluno: $nota ($situacao)<br>";
}

$aprovados = array_filter($notas, fn ($n) => $n >= 7);
echo implode(", ", array_keys($aprovados));   // Ana, Caio
```

### Questão
Como percorrer chave e valor de um array associativo?
- [ ] for ($i in $array)
- [x] foreach ($array as $chave => $valor)
- [ ] foreach ($chave, $valor of $array)
- [ ] while ($array)
> foreach com => entrega a chave e o valor.

### Questão
Qual a diferença entre `==` e `===`?
- [ ] Nenhuma
- [x] === compara valor e tipo; == converte os tipos antes
- [ ] == é mais rigoroso
- [ ] === só serve para strings
> Com ===, "1" === 1 é false, evitando surpresas.

### Questão
O que `count(["a", "b", "c"])` retorna?
- [ ] 2
- [x] 3
- [ ] "abc"
- [ ] 0
> count devolve a quantidade de elementos.

## Funções
### Teoria
Funções são declaradas com `function` e podem ter **tipos** nos parâmetros e no retorno, o que ajuda a evitar erros. Ative `declare(strict_types=1);` no topo do arquivo para tipos rigorosos.

- Parâmetros com valor padrão: `function saudar(string $nome = "pinguim")`.
- **Argumentos nomeados** (PHP 8): `saudar(nome: "Teco")`.
- **Arrow functions**: `fn ($x) => $x * 2`, que enxergam automaticamente as variáveis de fora.
- Tipos anuláveis: `?string`; uniões: `int|float`.

### Prática
```php
<?php
declare(strict_types=1);

function precoFinal(float $preco, float $desconto = 0.0): float
{
    return round($preco * (1 - $desconto), 2);
}

function formatar(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

echo formatar(precoFinal(100));                    // R$ 100,00
echo formatar(precoFinal(100, desconto: 0.15));     // R$ 85,00

$dobrar = fn (int $n): int => $n * 2;
print_r(array_map($dobrar, [1, 2, 3]));             // [2, 4, 6]
```

### Questão
O que `declare(strict_types=1);` faz?
- [ ] Desativa os tipos
- [x] Faz o PHP exigir os tipos declarados, sem conversões automáticas
- [ ] Deixa o código mais rápido
- [ ] Obriga a usar classes
> Com strict_types, passar "5" para um parâmetro int gera erro.

### Questão
O que `?string` como tipo de parâmetro significa?
- [ ] Uma string opcional sem valor
- [x] Uma string ou null
- [ ] Qualquer tipo
- [ ] Uma string vazia
> O ? torna o tipo anulável.

### Questão
Qual a sintaxe de arrow function em PHP?
- [ ] ($x) => $x * 2
- [x] fn ($x) => $x * 2
- [ ] function => $x * 2
- [ ] lambda $x: $x * 2
> Arrow functions começam com fn.

# PHP para a web
> Formulários, sessões, segurança e banco de dados com PDO.

## Projeto
**Mural de recados:** crie uma página com formulário (nome e mensagem) que grava os recados num banco MySQL usando PDO e prepared statements, e lista os últimos 20. Proteja contra XSS com `htmlspecialchars`, valide os campos e adicione um token CSRF na sessão.

## Formulários, GET e POST
### Teoria
Dados de formulários chegam em **superglobais**:
- `$_GET`: parâmetros da URL (`?busca=gelo`). Use para consultas.
- `$_POST`: dados enviados no corpo. Use para criar ou alterar dados.

Nunca confie na entrada do usuário:
- **Valide**: `filter_var($email, FILTER_VALIDATE_EMAIL)`, `trim`, tamanhos mínimos e máximos.
- **Escape na saída**: `htmlspecialchars($texto, ENT_QUOTES, 'UTF-8')` impede XSS.

Use o operador `??` para valores padrão: `$_POST['nome'] ?? ''`.

Depois de processar um POST com sucesso, **redirecione** (padrão Post/Redirect/Get) para evitar reenvio ao recarregar.

### Prática
```php
<?php
$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $erros[] = 'Email inválido.';
    }
    if ($erros === []) {
        // salvar...
        header('Location: obrigado.php', true, 303);
        exit;
    }
}
?>
<form method="post">
  <input name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <button>Enviar</button>
</form>
<?php foreach ($erros as $erro): ?>
  <p><?= htmlspecialchars($erro) ?></p>
<?php endforeach; ?>
```

### Questão
Qual superglobal recebe os dados de um formulário com `method="post"`?
- [ ] $_GET
- [x] $_POST
- [ ] $_FORM
- [ ] $_REQUEST_BODY
> Formulários POST enviam os dados no corpo, disponíveis em $_POST.

### Questão
Para que serve `htmlspecialchars` na saída?
- [ ] Deixar o texto em negrito
- [x] Impedir que o texto seja interpretado como HTML/JavaScript (XSS)
- [ ] Validar emails
- [ ] Criptografar senhas
> Caracteres como < e > viram entidades e não são executados.

### Questão
Por que redirecionar depois de processar um POST?
- [ ] Para limpar o cache
- [x] Para evitar que o formulário seja reenviado ao recarregar a página
- [ ] Porque o PHP exige
- [ ] Para apagar a sessão
> É o padrão Post/Redirect/Get.

## Sessões, cookies e senhas
### Teoria
HTTP não guarda estado entre requisições. **Sessões** resolvem isso: `session_start()` cria um identificador (num cookie) e os dados ficam no servidor em `$_SESSION`.

Fluxo de login seguro:
- Guarde senhas com `password_hash($senha, PASSWORD_DEFAULT)`; nunca em texto puro nem com MD5.
- Confira com `password_verify($senha, $hash)`.
- Após o login, chame `session_regenerate_id(true)` para evitar fixação de sessão.
- Proteja formulários com um **token CSRF** guardado na sessão.

### Prática
```php
<?php
session_start();

$hash = password_hash('PenguinDev2026!', PASSWORD_DEFAULT);   // guardado no banco

if (password_verify($_POST['senha'] ?? '', $hash)) {
    session_regenerate_id(true);
    $_SESSION['usuario'] = 'teco';
}

$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
// no formulário: <input type="hidden" name="csrf" value="...">
if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(419);
    exit('Sessão expirada');
}
```

### Questão
Qual a forma correta de armazenar senhas?
- [ ] Em texto puro
- [ ] Com md5()
- [x] Com password_hash()
- [ ] Com base64_encode()
> password_hash usa algoritmos lentos e com salt, próprios para senhas.

### Questão
Onde ficam os dados de `$_SESSION`?
- [ ] No navegador do usuário
- [x] No servidor; o navegador só guarda o identificador da sessão
- [ ] No banco de dados obrigatoriamente
- [ ] Na URL
> O cookie carrega apenas o id que liga o usuário aos dados no servidor.

### Questão
Contra o que o token CSRF protege?
- [ ] Senhas fracas
- [x] Envio de formulários forjados por outros sites em nome do usuário
- [ ] Injeção de SQL
- [ ] Imagens pesadas
> Só formulários gerados pelo seu site conhecem o token da sessão.

## Banco de dados com PDO
### Teoria
**PDO** é a forma moderna de acessar bancos em PHP. Conecte com um DSN e ative exceções:

```
$pdo = new PDO('mysql:host=127.0.0.1;dbname=loja;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

Use sempre **prepared statements**: a consulta tem marcadores (`?` ou `:nome`) e os valores são enviados separadamente. Isso impede **SQL injection**, um dos ataques mais comuns da web.

Para várias operações que devem acontecer juntas, use **transações**: `beginTransaction`, `commit`, `rollBack`.

### Prática
```php
<?php
$busca = $_GET['q'] ?? '';
$consulta = $pdo->prepare('SELECT nome, preco FROM produto WHERE nome LIKE ? ORDER BY nome');
$consulta->execute(["%{$busca}%"]);
foreach ($consulta->fetchAll() as $produto) {
    echo htmlspecialchars($produto['nome']) . ': ' . $produto['preco'] . '<br>';
}

$inserir = $pdo->prepare('INSERT INTO recado (nome, mensagem) VALUES (:nome, :mensagem)');
$inserir->execute(['nome' => 'Teco', 'mensagem' => 'Oi, iglu!']);
echo $pdo->lastInsertId();
```

### Questão
Por que usar prepared statements?
- [ ] Para deixar o SQL mais bonito
- [x] Para enviar os valores separados da consulta e evitar SQL injection
- [ ] Porque o MySQL exige
- [ ] Para criar tabelas
> O banco nunca interpreta o valor como parte do comando SQL.

### Questão
O que `PDO::ERRMODE_EXCEPTION` faz?
- [ ] Ignora erros
- [x] Faz o PDO lançar exceções quando algo dá errado
- [ ] Mostra erros em vermelho
- [ ] Desconecta o banco
> Com exceções, erros não passam despercebidos.

### Questão
Para que serve uma transação?
- [ ] Acelerar consultas SELECT
- [x] Garantir que várias operações sejam aplicadas todas juntas ou nenhuma
- [ ] Criar usuários no banco
- [ ] Fazer backup
> Se algo falha, rollBack desfaz tudo o que foi feito na transação.

# PHP moderno e profissional
> Orientação a objetos, Composer, padrões e frameworks.

## Projeto
**Mini-framework MVC:** organize um pequeno sistema de tarefas em `Models`, `Controllers` e `Views`, com autoload PSR-4 via Composer, um roteador simples baseado na URL e uma camada de repositório com PDO. Escreva testes com PHPUnit para o repositório.

## Orientação a objetos em PHP
### Teoria
Classes em PHP 8 ficaram enxutas:
- **Promoção de propriedades no construtor:** `public function __construct(private string $nome) {}`.
- **readonly:** propriedades que só podem ser definidas uma vez.
- **Enums** (PHP 8.1): `enum Status: string { case Ativo = 'ativo'; }`.
- **Interfaces**, **classes abstratas** e **traits** (reuso horizontal de código).
- **Namespaces** organizam classes: `namespace App\Models;`.

Visibilidade: `public`, `protected`, `private`. Use `static` para membros da classe.

### Prática
```php
<?php
declare(strict_types=1);

namespace App\Models;

enum Papel: string
{
    case Dono = 'dono';
    case Membro = 'membro';
}

final class Membro
{
    public function __construct(
        public readonly string $arroba,
        private Papel $papel = Papel::Membro,
    ) {}

    public function promover(): void
    {
        $this->papel = Papel::Dono;
    }

    public function papel(): string
    {
        return $this->papel->value;
    }
}

$m = new Membro('teco');
$m->promover();
echo "@{$m->arroba} agora é {$m->papel()}";
```

### Questão
O que `public function __construct(private string $nome) {}` faz?
- [ ] Declara um construtor vazio
- [x] Declara a propriedade privada $nome e a inicializa com o argumento
- [ ] Cria uma constante
- [ ] Gera erro em PHP 8
> É a promoção de propriedades no construtor.

### Questão
Uma propriedade `readonly` pode ser alterada depois de inicializada?
- [ ] Sim, a qualquer momento
- [x] Não
- [ ] Só por métodos estáticos
- [ ] Só dentro de traits
> readonly garante imutabilidade após a primeira atribuição.

### Questão
Para que servem os namespaces?
- [ ] Definir o nome do banco
- [x] Organizar classes e evitar conflitos de nomes
- [ ] Criar variáveis globais
- [ ] Importar arquivos CSS
> Duas classes Usuario podem coexistir em namespaces diferentes.

## Composer e autoload
### Teoria
**Composer** é o gerenciador de dependências do PHP. O arquivo `composer.json` lista os pacotes (do Packagist) e configura o **autoload**.

Com o padrão **PSR-4**, cada namespace corresponde a uma pasta, e o Composer carrega as classes automaticamente: nada de `require` em cada arquivo.

```
composer init
composer require monolog/monolog
composer dump-autoload
```

Inclua `vendor/autoload.php` uma vez no ponto de entrada. Não versione a pasta `vendor`; versione `composer.json` e `composer.lock`.

### Prática
```json
{
  "require": {
    "php": ">=8.1",
    "vlucas/phpdotenv": "^5.6"
  },
  "autoload": {
    "psr-4": { "App\\": "src/" }
  }
}
```

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use App\Models\Membro;   // carregado de src/Models/Membro.php

$membro = new Membro('teco');
```

### Questão
O que o autoload PSR-4 faz?
- [ ] Baixa pacotes da internet
- [x] Carrega automaticamente a classe a partir do namespace e da pasta correspondente
- [ ] Compila o PHP
- [ ] Cria o banco de dados
> App\Models\Membro é encontrado em src/Models/Membro.php.

### Questão
Qual pasta não deve ser versionada no Git num projeto Composer?
- [ ] src
- [x] vendor
- [ ] public
- [ ] tests
> vendor é recriada com composer install a partir do composer.lock.

### Questão
Qual comando adiciona um pacote ao projeto?
- [ ] composer add pacote
- [x] composer require pacote
- [ ] npm install pacote
- [ ] php install pacote
> composer require baixa o pacote e o registra no composer.json.

## MVC e frameworks
### Teoria
O padrão **MVC** separa responsabilidades:
- **Model:** dados e regras de negócio (acesso ao banco).
- **View:** apresentação (HTML).
- **Controller:** recebe a requisição, chama os models e escolhe a view.

Frameworks como **Laravel** e **Symfony** trazem MVC, roteamento, ORM (Eloquent/Doctrine), migrations, validação, filas e testes prontos.

Boas práticas: siga as **PSRs** (padrões da comunidade, como PSR-12 para estilo), escreva testes com **PHPUnit** e use variáveis de ambiente (`.env`) para senhas e configurações.

### Prática
Uma rota com controller no Laravel:

```php
<?php
// routes/web.php
use App\Http\Controllers\IgluController;
Route::get('/iglus', [IgluController::class, 'index']);

// app/Http/Controllers/IgluController.php
class IgluController extends Controller
{
    public function index()
    {
        $iglus = Iglu::orderBy('nome')->get();      // Model (Eloquent)
        return view('iglus.index', compact('iglus')); // View (Blade)
    }
}
```

### Questão
No MVC, qual camada cuida do HTML exibido?
- [ ] Model
- [x] View
- [ ] Controller
- [ ] Router
> A View é responsável pela apresentação.

### Questão
Qual camada recebe a requisição e coordena models e views?
- [ ] Model
- [ ] View
- [x] Controller
- [ ] Database
> O Controller é o intermediário entre a entrada e as demais camadas.

### Questão
Onde guardar senhas do banco num projeto profissional?
- [ ] No código, dentro dos controllers
- [x] Em variáveis de ambiente (.env), fora do controle de versão
- [ ] No HTML
- [ ] Num comentário do composer.json
> Assim as credenciais não vazam pelo repositório.
