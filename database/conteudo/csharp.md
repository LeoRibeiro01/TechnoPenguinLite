# Fundamentos de C#
> .NET, tipos, entrada e saída e controle de fluxo.

## Projeto
**Gerenciador de notas no console:** crie um app `dotnet new console` que lê nomes e notas de alunos, guarda numa `List`, mostra a média, o melhor aluno e quem precisa de recuperação. Use `int.TryParse`/`double.TryParse` para validar a entrada.

## Olá, .NET
### Teoria
C# é uma linguagem moderna, orientada a objetos, criada pela Microsoft e executada na plataforma **.NET**, que roda em Windows, Linux e macOS. É usada em APIs web (ASP.NET Core), aplicativos desktop, jogos (Unity) e muito mais.

Com o SDK instalado:

```
dotnet new console -o MeuApp
cd MeuApp
dotnet run
```

Nas versões atuais, um programa pode começar direto com instruções (**top-level statements**), sem escrever a classe e o `Main`.

### Prática
```csharp
Console.Write("Seu nome: ");
string? nome = Console.ReadLine();
int ano = DateTime.Now.Year;
Console.WriteLine($"Olá, {nome}! Bem-vindo ao C# em {ano}.");
```

O `$` antes das aspas cria uma **string interpolada**.

### Questão
Em qual plataforma programas C# são executados?
- [ ] JVM
- [x] .NET
- [ ] Node.js
- [ ] Python
> C# compila para uma linguagem intermediária executada pelo runtime do .NET.

### Questão
Qual comando cria um novo projeto de console?
- [x] dotnet new console
- [ ] csharp init
- [ ] npm create console
- [ ] dotnet build console
> dotnet new usa modelos de projeto; console é um deles.

### Questão
O que `$"Olá, {nome}"` faz?
- [ ] Cria um comentário
- [x] Interpola o valor de nome dentro do texto
- [ ] Converte nome em número
- [ ] Gera erro
> Strings interpoladas avaliam as expressões entre chaves.

## Tipos e conversões
### Teoria
Tipos comuns: `int`, `long`, `double`, `decimal` (ideal para dinheiro), `bool`, `char`, `string`. Use `var` para deixar o compilador inferir o tipo.

Converter texto em número:
- `int.Parse("42")`: lança exceção se o texto for inválido.
- `int.TryParse(texto, out int n)`: devolve `true`/`false` sem exceção. Prefira para entradas do usuário.

**Nullable:** `int?` pode ser `null`. Com nullable reference types ativado, `string?` indica que a string pode ser nula, e o compilador avisa usos perigosos.

### Prática
```csharp
Console.Write("Idade: ");
if (int.TryParse(Console.ReadLine(), out int idade))
{
    Console.WriteLine($"Ano que vem: {idade + 1}");
}
else
{
    Console.WriteLine("Isso não é um número.");
}

decimal preco = 19.90m;
var quantidade = 3;
Console.WriteLine($"Total: {preco * quantidade:C}");
```

### Questão
Qual tipo é mais indicado para valores monetários?
- [ ] float
- [ ] double
- [x] decimal
- [ ] int
> decimal evita erros de arredondamento binário em valores financeiros.

### Questão
Qual a vantagem de `int.TryParse` sobre `int.Parse`?
- [ ] É mais rápido sempre
- [x] Não lança exceção quando o texto é inválido
- [ ] Aceita letras
- [ ] Converte para double
> TryParse informa o sucesso pelo retorno booleano.

### Questão
O que `int?` significa?
- [x] Um inteiro opcional que também pode ser null
- [ ] Um inteiro positivo
- [ ] Um inteiro desconhecido sem valor
- [ ] Um erro
> O ? cria um tipo anulável.

## Controle de fluxo
### Teoria
C# tem `if/else`, `switch`, `for`, `while`, `do-while` e `foreach` para coleções.

A **switch expression** moderna é compacta e retorna valores, com suporte a **pattern matching**:

```
var faixa = idade switch
{
    < 13 => "criança",
    < 18 => "adolescente",
    _ => "adulto"
};
```

O `_` é o caso padrão (descarte).

### Prática
```csharp
int[] xp = { 120, 45, 300 };
int total = 0;
foreach (var valor in xp)
{
    total += valor;
}

string nivel = total switch
{
    >= 400 => "Pinguim imperador",
    >= 200 => "Pinguim explorador",
    _ => "Filhote"
};
Console.WriteLine($"{total} XP: {nivel}");

for (int i = 3; i > 0; i--) Console.WriteLine(i);
```

### Questão
O que `_` representa numa switch expression?
- [ ] Um erro
- [x] O caso padrão, que combina com qualquer valor
- [ ] Uma variável vazia obrigatória
- [ ] O primeiro caso
> _ é o padrão de descarte, equivalente ao default.

### Questão
Qual laço percorre os elementos de uma coleção diretamente?
- [ ] for
- [ ] while
- [x] foreach
- [ ] loop
> foreach entrega cada elemento sem usar índices.

### Questão
Com `total = 250`, qual nível o exemplo mostra?
- [ ] Pinguim imperador
- [x] Pinguim explorador
- [ ] Filhote
- [ ] Nenhum
> 250 não é >= 400, mas é >= 200.

# Orientação a objetos e coleções
> Classes, propriedades, herança, interfaces, LINQ.

## Projeto
**Loja de vestuário de pinguins:** modele `Item` (nome, categoria, preço) e `Inventario` com uma `List<Item>`. Use LINQ para filtrar por categoria, ordenar por preço e calcular o total. Crie uma interface `IDesconto` com duas implementações (porcentagem e valor fixo).

## Classes e propriedades
### Teoria
Classes têm campos, **propriedades** e métodos. Propriedades expõem dados com controle:

```
public string Nome { get; set; }
public int Idade { get; private set; }
public bool Adulto => Idade >= 18;
```

O construtor inicializa o objeto. **Records** (`record Ponto(int X, int Y);`) criam tipos de dados imutáveis com igualdade por valor em uma linha.

Modificadores de acesso: `public`, `private`, `protected`, `internal`.

### Prática
```csharp
var teco = new Pinguim("Teco");
teco.Comer(3);
Console.WriteLine($"{teco.Nome} tem {teco.Peixes} peixes. Feliz? {teco.Feliz}");

var a = new Coordenada(1, 2);
var b = new Coordenada(1, 2);
Console.WriteLine(a == b); // True: records comparam por valor

public class Pinguim
{
    public string Nome { get; }
    public int Peixes { get; private set; }
    public bool Feliz => Peixes > 2;

    public Pinguim(string nome) => Nome = nome;

    public void Comer(int quantidade)
    {
        if (quantidade <= 0) throw new ArgumentException("Quantidade inválida");
        Peixes += quantidade;
    }
}

public record Coordenada(int X, int Y);
```

### Questão
O que `public int Peixes { get; private set; }` permite?
- [ ] Qualquer código altera Peixes
- [x] Qualquer código lê, mas só a própria classe altera
- [ ] Ninguém lê
- [ ] Peixes é constante
> O set privado restringe a escrita à classe.

### Questão
O que caracteriza um `record`?
- [ ] É sempre mutável
- [x] Igualdade por valor e sintaxe curta para tipos de dados
- [ ] Não pode ter propriedades
- [ ] Só existe em Java
> Dois records com os mesmos valores são considerados iguais.

### Questão
O que `public bool Feliz => Peixes > 2;` define?
- [ ] Um campo
- [x] Uma propriedade calculada somente leitura
- [ ] Um método void
- [ ] Um construtor
> A propriedade é recalculada a cada acesso.

## Herança e interfaces
### Teoria
Herança com `:`: `class Imperador : Pinguim`. Para permitir que um método seja sobrescrito, marque-o com `virtual` na base e `override` na derivada. `abstract` define classes e métodos que precisam ser completados.

**Interfaces** (por convenção começam com `I`) definem contratos, e uma classe pode implementar várias: `class Pinguim : Animal, INadador, IComparable<Pinguim>`.

C# só permite herdar de uma classe, mas de muitas interfaces.

### Prática
```csharp
List<Animal> zoo = new() { new Pinguim(), new Foca() };
foreach (var animal in zoo)
{
    Console.WriteLine(animal.Som());
    if (animal is INadador nadador) Console.WriteLine(nadador.Nadar());
}

public interface INadador { string Nadar(); }

public abstract class Animal
{
    public abstract string Som();
}

public class Pinguim : Animal, INadador
{
    public override string Som() => "quá";
    public string Nadar() => "mergulhou a 20 m";
}

public class Foca : Animal
{
    public override string Som() => "arf";
}
```

### Questão
Qual palavra permite que um método seja sobrescrito na classe derivada?
- [ ] static
- [x] virtual (ou abstract)
- [ ] sealed
- [ ] const
> Métodos precisam ser virtual ou abstract para receber override.

### Questão
De quantas interfaces uma classe C# pode herdar?
- [ ] Uma
- [ ] Duas
- [x] Várias
- [ ] Nenhuma
> Classe base é uma só; interfaces podem ser várias.

### Questão
O que `animal is INadador nadador` faz?
- [ ] Converte à força e pode lançar exceção
- [x] Testa se o objeto implementa INadador e, se sim, guarda numa variável
- [ ] Cria um novo nadador
- [ ] Compara dois animais
> É pattern matching com declaração de variável.

## Coleções e LINQ
### Teoria
Coleções genéricas em `System.Collections.Generic`: `List<T>`, `Dictionary<TKey, TValue>`, `HashSet<T>`, `Queue<T>`, `Stack<T>`.

**LINQ** (Language Integrated Query) consulta coleções com métodos encadeados:
- `Where` filtra, `Select` transforma, `OrderBy` ordena.
- `First`, `Any`, `Count`, `Sum`, `Average`, `GroupBy`.

LINQ usa **execução adiada**: a consulta só roda quando você percorre o resultado ou chama `ToList()`.

### Prática
```csharp
var alunos = new List<(string Nome, double Nota)>
{
    ("Ana", 9.5), ("Beto", 5.0), ("Caio", 7.5), ("Duda", 8.0)
};

var aprovados = alunos
    .Where(a => a.Nota >= 7)
    .OrderByDescending(a => a.Nota)
    .Select(a => a.Nome)
    .ToList();

Console.WriteLine(string.Join(", ", aprovados));   // Ana, Duda, Caio
Console.WriteLine(alunos.Average(a => a.Nota));    // 7.5

var xp = new Dictionary<string, int> { ["teco"] = 120 };
xp["teco"] += 30;
```

### Questão
Qual método LINQ filtra elementos?
- [ ] Select
- [x] Where
- [ ] OrderBy
- [ ] Join
> Where mantém os elementos que satisfazem a condição.

### Questão
O que significa execução adiada no LINQ?
- [ ] A consulta roda em outra thread
- [x] A consulta só é executada quando o resultado é percorrido ou materializado
- [ ] A consulta demora de propósito
- [ ] O resultado é guardado em cache para sempre
> ToList(), foreach e Count disparam a execução.

### Questão
Qual coleção guarda pares chave-valor?
- [ ] List<T>
- [ ] HashSet<T>
- [x] Dictionary<TKey, TValue>
- [ ] Queue<T>
> Dictionary acessa valores pela chave.

# C# avançado e ecossistema
> Exceções, async/await, ASP.NET Core e Entity Framework.

## Projeto
**API de iglus com ASP.NET Core:** crie uma Minimal API com endpoints para listar, criar e apagar iglus, persistindo com Entity Framework Core e SQLite. Use `async/await` em todas as operações e documente com Swagger.

## Exceções
### Teoria
Erros são **exceções**, tratadas com `try / catch / finally`. Capture tipos específicos primeiro (`FormatException`, `FileNotFoundException`) e deixe os genéricos (`Exception`) por último.

`throw` lança exceções; para relançar mantendo o histórico, use apenas `throw;` dentro do catch.

O `using` garante a liberação de recursos (`IDisposable`), como arquivos e conexões, mesmo se ocorrer erro: `using var arquivo = File.OpenRead("dados.txt");`.

Não use exceções para controle de fluxo comum: prefira validações e `TryParse`.

### Prática
```csharp
try
{
    using var leitor = new StreamReader("placar.txt");
    string conteudo = leitor.ReadToEnd();
    int pontos = int.Parse(conteudo);
    Console.WriteLine($"Pontos: {pontos}");
}
catch (FileNotFoundException)
{
    Console.WriteLine("Arquivo não encontrado.");
}
catch (FormatException ex)
{
    Console.WriteLine($"Conteúdo inválido: {ex.Message}");
}
finally
{
    Console.WriteLine("Leitura encerrada.");
}
```

### Questão
Em que ordem os blocos catch devem aparecer?
- [ ] Do mais genérico para o mais específico
- [x] Do mais específico para o mais genérico
- [ ] Ordem alfabética
- [ ] Não importa
> Se Exception vier primeiro, captura tudo e os demais nunca rodam.

### Questão
O que `using var leitor = ...` garante?
- [ ] Que o arquivo existe
- [x] Que o recurso é liberado (Dispose) ao sair do escopo
- [ ] Que não haverá exceções
- [ ] Que o arquivo é criado
> using chama Dispose automaticamente.

### Questão
Como relançar uma exceção preservando o stack trace original?
- [ ] throw ex;
- [x] throw;
- [ ] rethrow ex;
- [ ] return ex;
> throw; sozinho preserva o rastreamento original.

## Programação assíncrona
### Teoria
Operações de I/O (rede, banco, arquivos) são lentas. Com `async/await`, o programa espera sem bloquear a thread, liberando-a para outras tarefas.

- Métodos assíncronos retornam `Task` ou `Task<T>` e, por convenção, terminam com `Async`.
- `await` aguarda a conclusão.
- `Task.WhenAll` executa várias tarefas em paralelo.

Evite `.Result` e `.Wait()`, que bloqueiam e podem causar deadlocks. Seja "async até o fim".

### Prática
```csharp
using System.Net.Http;

var http = new HttpClient();

async Task<int> TamanhoDaPaginaAsync(string url)
{
    string html = await http.GetStringAsync(url);
    return html.Length;
}

var tarefas = new[]
{
    TamanhoDaPaginaAsync("https://example.com"),
    TamanhoDaPaginaAsync("https://dotnet.microsoft.com"),
};
int[] tamanhos = await Task.WhenAll(tarefas);
Console.WriteLine(string.Join(" | ", tamanhos));
```

### Questão
Qual tipo de retorno um método async que devolve um int deve ter?
- [ ] int
- [ ] async int
- [x] Task<int>
- [ ] Promise<int>
> Task<int> representa um int que estará disponível no futuro.

### Questão
Por que evitar `.Result` em código assíncrono?
- [ ] Porque não existe
- [x] Porque bloqueia a thread e pode causar deadlock
- [ ] Porque retorna sempre null
- [ ] Porque é mais lento para compilar
> Bloquear esperando uma Task anula as vantagens do async.

### Questão
O que `Task.WhenAll` faz?
- [ ] Executa as tarefas uma por vez
- [x] Aguarda várias tarefas concluírem, rodando em paralelo
- [ ] Cancela as tarefas
- [ ] Retorna apenas a primeira
> Ele completa quando todas as tarefas terminam.

## ASP.NET Core e Entity Framework
### Teoria
**ASP.NET Core** cria APIs e sites de alto desempenho. As **Minimal APIs** definem endpoints em poucas linhas com `app.MapGet`, `app.MapPost`...

**Injeção de dependência** é nativa: registre serviços com `builder.Services.Add...` e receba-os como parâmetros.

**Entity Framework Core** é o ORM do .NET: classes C# viram tabelas, e consultas LINQ viram SQL. As **migrations** (`dotnet ef migrations add`) versionam o banco.

### Prática
```csharp
using Microsoft.EntityFrameworkCore;

var builder = WebApplication.CreateBuilder(args);
builder.Services.AddDbContext<AppDb>(o => o.UseSqlite("Data Source=iglus.db"));
var app = builder.Build();

app.MapGet("/iglus", async (AppDb db) => await db.Iglus.ToListAsync());
app.MapPost("/iglus", async (Iglu iglu, AppDb db) =>
{
    db.Iglus.Add(iglu);
    await db.SaveChangesAsync();
    return Results.Created($"/iglus/{iglu.Id}", iglu);
});
app.Run();

public class Iglu { public int Id { get; set; } public string Nome { get; set; } = ""; }
public class AppDb(DbContextOptions<AppDb> o) : DbContext(o)
{
    public DbSet<Iglu> Iglus => Set<Iglu>();
}
```

### Questão
O que é o Entity Framework Core?
- [ ] Um servidor web
- [x] Um ORM que mapeia classes C# para tabelas do banco
- [ ] Um editor de código
- [ ] Um framework de testes
> Ele traduz objetos e consultas LINQ para SQL.

### Questão
Como o endpoint recebe o `AppDb`?
- [ ] Criando com new AppDb()
- [x] Por injeção de dependência, como parâmetro
- [ ] Por variável global
- [ ] Lendo de um arquivo
> Serviços registrados são injetados automaticamente nos handlers.

### Questão
Qual método persiste as alterações no banco?
- [ ] Commit()
- [x] SaveChangesAsync()
- [ ] Flush()
- [ ] Update()
> SaveChanges envia ao banco tudo que foi adicionado ou alterado no contexto.
