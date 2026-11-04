@historia
C# (pronuncia-se "C sharp") foi criado pela **Microsoft** em 2000, por uma equipe liderada por **Anders Hejlsberg**, criador do Turbo Pascal e do Delphi. Ele nasceu junto com a plataforma **.NET**, a resposta da Microsoft ao Java: uma linguagem orientada a objetos, segura e com coleta de lixo, rodando numa máquina virtual (o CLR).

O nome vem da música: o sustenido (♯) sobe meio tom, e o símbolo lembra quatro "+" juntos. C# evoluiu rápido e muitas vezes abriu caminho para outras linguagens: generics (2005), **LINQ** (2007), **async/await** (2012), pattern matching e records. Em 2016 veio a virada: o **.NET Core** passou a ser de código aberto e multiplataforma, rodando em Linux e macOS. Hoje se chama simplesmente .NET, com uma versão nova por ano.

@hoje
C# é muito usado em **sistemas corporativos** e **APIs** com ASP.NET Core, um dos frameworks web mais rápidos em benchmarks. É a linguagem do **Unity**, motor de jogos usado em milhares de jogos para celular, consoles e PC. Também cria apps desktop (WPF, WinForms), apps multiplataforma (.NET MAUI), serviços na nuvem Azure e até aplicações web no navegador com Blazor (WebAssembly).

@iniciante
- Criar e executar projetos com o SDK do .NET
- Usar tipos, var, strings interpoladas e conversões seguras
- Controlar o fluxo com if, switch expressions e laços
- Ler dados do console com TryParse

@intermediario
- Criar classes com propriedades, construtores e records
- Aplicar herança, interfaces e polimorfismo
- Trabalhar com List, Dictionary e HashSet
- Consultar coleções com LINQ

@avancado
- Tratar exceções e liberar recursos com using
- Programar de forma assíncrona com async/await e Task
- Criar APIs com ASP.NET Core e injeção de dependência
- Persistir dados com Entity Framework Core e migrations

@referencias
**Livros**
- *C# 12 and .NET 8 – Modern Cross-Platform Development* — Mark J. Price: completo e atualizado anualmente.
- *C# in Depth* — Jon Skeet: como e por que a linguagem evoluiu, de um dos maiores especialistas.
- *Código Limpo* — Robert C. Martin: princípios que valem para qualquer linguagem orientada a objetos.

**Na internet**
- [Documentação do C# (Microsoft Learn, em português)](https://learn.microsoft.com/pt-br/dotnet/csharp/)
- [Microsoft Learn: trilhas gratuitas de C#](https://learn.microsoft.com/pt-br/training/browse/?terms=c%23)
- [Unity Learn](https://learn.unity.com) — para quem quer fazer jogos.

@modulo 1
**No .NET, há tipos de valor e tipos de referência.** `int`, `double`, `bool`, `struct` e `enum` são **tipos de valor**: guardam o próprio dado e são copiados ao atribuir. `class`, `string`, arrays e `record` (por padrão) são **tipos de referência**: a variável aponta para o objeto no heap gerenciado. Entender isso explica por que alterar um objeto dentro de um método afeta quem chamou.

O compilador C# gera **IL** (linguagem intermediária); o runtime (CLR) compila para código nativo com JIT ou, com Native AOT, antes da execução. O **coletor de lixo** é geracional: objetos novos morrem rápido e são coletados com pouco custo.

Com *nullable reference types* ativado (padrão em projetos novos), o compilador avisa quando você pode estar usando um `null` por engano.

@fontes 1
- Price, *C# 12 and .NET 8*, capítulos 2 e 3
- Skeet, *C# in Depth*, capítulo 2 (tipos de valor e referência)
- [Microsoft Learn: Tipos de referência anuláveis](https://learn.microsoft.com/pt-br/dotnet/csharp/nullable-references)

@modulo 2
**LINQ trouxe o pensamento funcional para o C#.** Jon Skeet explica em *C# in Depth* que LINQ só foi possível porque a linguagem ganhou, ao mesmo tempo, lambdas, métodos de extensão, tipos anônimos e inferência de tipo. O mesmo código LINQ funciona em coleções na memória (`IEnumerable`) ou é traduzido para SQL (`IQueryable`, no Entity Framework).

Cuidado com a **execução adiada**: uma consulta LINQ é reavaliada cada vez que é percorrida. Se você percorrer a mesma consulta duas vezes, ela roda duas vezes. Materialize com `ToList()` quando precisar reutilizar.

Para modelos de dados, prefira `record` (igualdade por valor e imutabilidade) e use interfaces para separar contratos de implementações, seguindo os princípios SOLID.

@fontes 2
- Skeet, *C# in Depth*, parte 3 (C# 3 e LINQ)
- [Microsoft Learn: LINQ](https://learn.microsoft.com/pt-br/dotnet/csharp/linq/)
- [Microsoft Learn: Records](https://learn.microsoft.com/pt-br/dotnet/csharp/language-reference/builtin-types/record)

@modulo 3
**async/await não cria threads: libera a thread enquanto espera.** Quando um método `await` uma operação de I/O, a thread volta para o pool e pode atender outras requisições. É por isso que o ASP.NET Core escala tanto. A regra de ouro é "async até o fim": misturar `.Result` ou `.Wait()` com código assíncrono pode causar deadlocks.

No ASP.NET Core, a **injeção de dependência** tem três tempos de vida: *singleton* (uma instância para a aplicação toda), *scoped* (uma por requisição, como o DbContext) e *transient* (uma nova a cada pedido). Errar o tempo de vida é fonte comum de bugs.

O Entity Framework Core rastreia mudanças nos objetos e gera o SQL no `SaveChanges`. Use `AsNoTracking()` em consultas só de leitura e cuidado com o problema **N+1** (uma consulta extra para cada item da lista), resolvido com `Include`.

@fontes 3
- Stephen Cleary, *Concurrency in C# Cookbook*, capítulos 1 e 2
- [Microsoft Learn: Injeção de dependência no ASP.NET Core](https://learn.microsoft.com/pt-br/aspnet/core/fundamentals/dependency-injection)
- [Microsoft Learn: Desempenho no EF Core](https://learn.microsoft.com/pt-br/ef/core/performance/)
