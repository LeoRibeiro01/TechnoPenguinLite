# Fundamentos de Swift
> Constantes, tipos, opcionais e controle de fluxo.

## Projeto
**Calculadora de gorjeta (Playground):** num Swift Playground, crie uma função que recebe o valor da conta, a porcentagem de gorjeta e o número de pessoas, e devolve quanto cada um paga. Trate entradas opcionais com `if let`/`guard let` e formate o resultado com duas casas decimais.

## Olá, Swift
### Teoria
Swift é a linguagem moderna da Apple para iOS, iPadOS, macOS, watchOS e visionOS, e também roda em Linux e servidores. É rápida, segura e expressiva.

Para experimentar, use o **Xcode** (com Playgrounds) ou o app **Swift Playgrounds** no iPad/Mac.

- `let` declara constantes (prefira).
- `var` declara variáveis.
- Tipos são inferidos, mas podem ser anotados: `let idade: Int = 3`.
- Interpolação: `"Olá, \(nome)"`.

Não há `;` no fim das linhas.

### Prática
```swift
let nome = "Teco"
var peixes = 3
peixes += 2
print("Olá, \(nome)!")
print("\(nome) tem \(peixes) peixes")

let pi: Double = 3.14159
let texto = "Área: \(pi * 2 * 2)"
print(texto)
```

### Questão
Como declarar uma constante em Swift?
- [ ] const nome = "Teco"
- [x] let nome = "Teco"
- [ ] var nome = "Teco"
- [ ] val nome = "Teco"
> let cria valores que não mudam; var, variáveis.

### Questão
Qual a sintaxe de interpolação de strings em Swift?
- [ ] "${nome}"
- [ ] "#{nome}"
- [x] "\(nome)"
- [ ] "{nome}"
> Swift usa barra invertida seguida de parênteses.

### Questão
Qual ferramenta da Apple é usada para desenvolver apps em Swift?
- [ ] Android Studio
- [x] Xcode
- [ ] Visual Studio
- [ ] Eclipse
> Xcode é a IDE oficial, com simulador e Playgrounds.

## Tipos e opcionais
### Teoria
Tipos básicos: `Int`, `Double`, `Bool`, `String`, `Character`. Swift não converte tipos implicitamente: `Double(inteiro)`.

**Opcionais** representam valores que podem estar ausentes: `String?` é "uma String ou `nil`". Para usar o valor, é preciso **desembrulhar**:
- `if let nome { ... }`: usa o valor se existir.
- `guard let nome else { return }`: sai cedo se não existir.
- `??`: valor padrão: `apelido ?? "sem apelido"`.
- `!` força o desembrulho e trava se for `nil`: evite.
- **Optional chaining**: `usuario?.endereco?.cidade`.

### Prática
```swift
let entrada = "42"
let numero = Int(entrada)          // Int? (pode falhar)

if let numero {
    print("Dobro: \(numero * 2)")
}

func saudar(_ nome: String?) {
    guard let nome, !nome.isEmpty else {
        print("Olá, visitante")
        return
    }
    print("Olá, \(nome)")
}

saudar(nil)
saudar("Teco")
let apelido: String? = nil
print(apelido ?? "sem apelido")
```

### Questão
Qual o tipo de `Int("42")`?
- [ ] Int
- [x] Int? (opcional)
- [ ] String
- [ ] Double
> A conversão pode falhar (ex.: "abc"), então retorna um opcional.

### Questão
Qual a vantagem de `guard let`?
- [ ] Repetir código
- [x] Sair cedo da função quando o valor é nil, deixando o resto do código sem aninhamento
- [ ] Forçar o desembrulho
- [ ] Criar variáveis globais
> Depois do guard, o valor desembrulhado está disponível no resto do escopo.

### Questão
O que acontece ao usar `!` num opcional que é `nil`?
- [ ] Retorna 0
- [ ] Retorna ""
- [x] O app trava (erro em tempo de execução)
- [ ] Nada
> Por isso o desembrulho forçado deve ser evitado.

## Controle de fluxo e funções
### Teoria
`if` não exige parênteses, mas exige chaves. O `switch` do Swift é poderoso: deve ser **exaustivo**, não precisa de `break` e aceita intervalos, tuplas e condições com `where`.

Laços: `for item in colecao`, `for i in 1...5` (inclui o 5), `for i in 0..<5` (exclui o 5), `while` e `repeat-while`.

Funções usam `func`, com **rótulos de argumento** que deixam as chamadas legíveis: `func mover(de origem: Int, para destino: Int)`. O `_` remove o rótulo. O retorno é indicado com `->`.

### Prática
```swift
func classificar(nota: Double) -> String {
    switch nota {
    case 9...10: return "excelente"
    case 7..<9: return "aprovado"
    case 0..<7: return "recuperação"
    default: return "inválida"
    }
}

func area(largura l: Double, altura a: Double) -> Double {
    l * a
}

for i in 1...3 {
    print(i, classificar(nota: Double(i) * 3))
}
print(area(largura: 2, altura: 5))
```

### Questão
Qual a diferença entre `1...5` e `1..<5`?
- [ ] Nenhuma
- [x] 1...5 inclui o 5; 1..<5 vai até 4
- [ ] 1..<5 inclui o 5
- [ ] 1...5 começa do 0
> ... é intervalo fechado; ..< é semiaberto.

### Questão
O `switch` em Swift precisa de `break` em cada caso?
- [ ] Sim
- [x] Não, e ele precisa cobrir todos os casos
- [ ] Só no default
- [ ] Só com strings
> Swift não tem fall-through implícito e exige exaustividade.

### Questão
Em `func area(largura l: Double, ...)`, qual nome é usado na chamada?
- [ ] l
- [x] largura
- [ ] Nenhum
- [ ] area
> largura é o rótulo externo; l é o nome usado dentro da função.

# Tipos, coleções e protocolos
> Arrays, dicionários, structs, classes, enums e protocolos.

## Projeto
**Cardápio do restaurante:** modele `enum Categoria`, `struct Prato` e uma classe `Pedido` que acumula itens. Use `filter`, `map` e `reduce` para listar pratos por categoria e calcular o total. Crie um protocolo `Descritivel` adotado por `Prato` e `Pedido`.

## Coleções e closures
### Teoria
Coleções principais:
- `Array`: `[1, 2, 3]`, ordenado.
- `Dictionary`: `["teco": 120]`; o acesso devolve opcional.
- `Set`: valores únicos.

Coleções declaradas com `let` são imutáveis; com `var`, mutáveis (`append`, `remove`).

**Closures** são funções anônimas: `{ n in n * 2 }`. Com parâmetros abreviados: `{ $0 * 2 }`. Funções de alta ordem: `map`, `filter`, `reduce`, `sorted`, `forEach`, `contains(where:)`.

### Prática
```swift
var notas = [9.5, 5.0, 7.0]
notas.append(8.0)

let aprovadas = notas.filter { $0 >= 7 }
let total = notas.reduce(0, +)
let ordenadas = notas.sorted(by: >)

var xp: [String: Int] = ["teco": 120]
xp["pipo", default: 0] += 30

print(aprovadas, total / Double(notas.count), ordenadas)
print(xp["teco"] ?? 0, xp["ana"] ?? 0)
```

### Questão
O que `notas.filter { $0 >= 7 }` retorna?
- [ ] Um Bool
- [x] Um novo array com as notas maiores ou iguais a 7
- [ ] A primeira nota aprovada
- [ ] O array original alterado
> filter cria um novo array.

### Questão
O que `$0` representa numa closure?
- [ ] O índice
- [x] O primeiro parâmetro da closure
- [ ] Um valor zero
- [ ] A variável global
> Parâmetros abreviados: $0, $1, $2...

### Questão
Qual o tipo de retorno de `xp["ana"]` num `[String: Int]`?
- [ ] Int
- [x] Int? (opcional)
- [ ] String
- [ ] Bool
> A chave pode não existir, então o resultado é opcional.

## Structs, classes e enums
### Teoria
**Structs** são **tipos de valor**: ao atribuir, o valor é copiado. São a escolha padrão em Swift. Métodos que alteram a struct são marcados com `mutating`.

**Classes** são **tipos de referência**: atribuir compartilha a mesma instância. Suportam herança. Use quando precisar de identidade compartilhada ou herança.

**Enums** podem ter valores associados e métodos: `enum Clima { case sol, neve(cm: Int) }`.

Propriedades podem ser **calculadas** (`var area: Double { largura * altura }`).

### Prática
```swift
struct Ponto {
    var x: Int, y: Int
    mutating func mover(dx: Int) { x += dx }
}

class Iglu {
    var membros: [String] = []
}

enum Clima {
    case sol
    case neve(cm: Int)
    var descricao: String {
        switch self {
        case .sol: return "Sol (ruim para pinguins)"
        case .neve(let cm): return "\(cm) cm de neve!"
        }
    }
}

var a = Ponto(x: 0, y: 0)
var b = a          // cópia
b.mover(dx: 5)
print(a.x, b.x)    // 0 5

let i1 = Iglu()
let i2 = i1        // mesma instância
i2.membros.append("Teco")
print(i1.membros)  // ["Teco"]
print(Clima.neve(cm: 30).descricao)
```

### Questão
Qual a principal diferença entre struct e class em Swift?
- [ ] Structs não têm métodos
- [x] Structs são tipos de valor (copiados); classes são tipos de referência
- [ ] Classes são mais rápidas sempre
- [ ] Não há diferença
> Por isso alterar b não afetou a, mas alterar i2 afetou i1.

### Questão
Para que serve `mutating` num método de struct?
- [ ] Tornar o método privado
- [x] Permitir que o método altere as propriedades da struct
- [ ] Criar uma cópia
- [ ] Herdar de outra struct
> Métodos de struct são somente leitura por padrão.

### Questão
O que `case neve(cm: Int)` demonstra?
- [x] Um enum com valor associado
- [ ] Uma função
- [ ] Uma struct
- [ ] Um erro
> Cada caso de enum pode carregar dados próprios.

## Protocolos
### Teoria
**Protocolos** definem requisitos (propriedades e métodos) que tipos adotam, como interfaces. Structs, classes e enums podem adotá-los.

**Extensions** adicionam funcionalidades a tipos existentes e podem dar **implementações padrão** a protocolos: é a base da "programação orientada a protocolos" do Swift.

Protocolos padrão importantes: `Equatable` (==), `Comparable` (<), `Hashable`, `Codable` (conversão para JSON), `Identifiable` (usado no SwiftUI).

### Prática
```swift
protocol Descritivel {
    var nome: String { get }
    func descrever() -> String
}

extension Descritivel {
    func descrever() -> String { "Eu sou \(nome)" }
}

struct Pinguim: Descritivel, Codable, Equatable {
    let nome: String
    var peixes: Int
}

let teco = Pinguim(nome: "Teco", peixes: 3)
print(teco.descrever())
print(teco == Pinguim(nome: "Teco", peixes: 3))   // true

let json = try JSONEncoder().encode(teco)
print(String(data: json, encoding: .utf8)!)
```

### Questão
O que um protocolo define?
- [ ] Uma instância
- [x] Um conjunto de requisitos que os tipos que o adotam devem cumprir
- [ ] Uma variável global
- [ ] Um banco de dados
> Protocolos são contratos.

### Questão
Como dar uma implementação padrão a um método de protocolo?
- [ ] Não é possível
- [x] Com uma extension do protocolo
- [ ] Com uma subclasse
- [ ] Com um enum
> extension Descritivel { func descrever() ... } vale para todos que adotam.

### Questão
Qual protocolo permite converter um tipo para JSON e de volta?
- [ ] Equatable
- [ ] Hashable
- [x] Codable
- [ ] Identifiable
> Codable combina Encodable e Decodable.

# Swift moderno e SwiftUI
> Erros, concorrência com async/await e interfaces com SwiftUI.

## Projeto
**App de lista de estudos (SwiftUI):** crie um app com uma `List` de tópicos, um campo para adicionar novos, marcação de concluído e um contador de dias de gelo. Use `@State`, `@Observable` para o modelo e salve os dados com `Codable` + `UserDefaults` ou SwiftData.

## Tratamento de erros
### Teoria
Funções que podem falhar são marcadas com `throws` e lançam erros com `throw`. Erros costumam ser enums que adotam o protocolo `Error`.

Para chamar uma função que lança, use `try` dentro de `do / catch`:

```
do {
    try algo()
} catch {
    print(error)
}
```

Atalhos: `try?` transforma o resultado em opcional (nil em caso de erro) e `try!` assume que não falha (trava se falhar).

### Prática
```swift
enum ErroDePesca: Error {
    case semIsca
    case marAgitado(ondas: Int)
}

func pescar(isca: Int, ondas: Int) throws -> Int {
    guard isca > 0 else { throw ErroDePesca.semIsca }
    guard ondas < 3 else { throw ErroDePesca.marAgitado(ondas: ondas) }
    return isca * 2
}

do {
    let peixes = try pescar(isca: 2, ondas: 5)
    print(peixes)
} catch ErroDePesca.marAgitado(let ondas) {
    print("Ondas de \(ondas) m, melhor esperar")
} catch {
    print("Erro: \(error)")
}

let talvez = try? pescar(isca: 0, ondas: 1)   // nil
```

### Questão
Como indicar que uma função pode lançar erros?
- [ ] Com a palavra error
- [x] Com throws na assinatura
- [ ] Com async
- [ ] Com mutating
> func pescar(...) throws -> Int.

### Questão
O que `try?` faz?
- [ ] Ignora a função
- [x] Converte o resultado em opcional, devolvendo nil se houver erro
- [ ] Repete até funcionar
- [ ] Trava o app em caso de erro
> É útil quando o erro em si não importa.

### Questão
Onde o `catch` captura erros?
- [ ] Em qualquer lugar do arquivo
- [x] Num bloco do/catch que envolve as chamadas com try
- [ ] Só em classes
- [ ] Em funções async apenas
> do agrupa as chamadas; catch trata os erros lançados.

## Concorrência com async/await
### Teoria
Swift tem concorrência estruturada embutida na linguagem:
- Funções `async` podem pausar sem bloquear; são chamadas com `await`.
- `async let` inicia tarefas em paralelo.
- `Task { }` cria uma tarefa a partir de código síncrono.
- **Actors** protegem estado mutável contra acessos simultâneos.
- `@MainActor` garante que um código rode na thread principal (necessário para atualizar a interface).

### Prática
```swift
struct Clima: Decodable {
    struct Atual: Decodable { let temperature_2m: Double }
    let current: Atual
}

func temperatura(lat: Double, lon: Double) async throws -> Double {
    let url = URL(string: "https://api.open-meteo.com/v1/forecast?latitude=\(lat)&longitude=\(lon)&current=temperature_2m")!
    let (dados, _) = try await URLSession.shared.data(from: url)
    return try JSONDecoder().decode(Clima.self, from: dados).current.temperature_2m
}

actor Placar {
    private var pontos = 0
    func somar(_ n: Int) { pontos += n }
    func total() -> Int { pontos }
}

Task {
    async let polo = temperatura(lat: -75, lon: 0)
    async let rio = temperatura(lat: -22.9, lon: -43.2)
    print(try await polo, try await rio)
}
```

### Questão
O que `async let` permite?
- [ ] Declarar constantes globais
- [x] Iniciar operações assíncronas em paralelo e aguardá-las depois
- [ ] Bloquear a thread principal
- [ ] Criar classes
> As duas temperaturas são buscadas ao mesmo tempo.

### Questão
Para que serve um `actor`?
- [ ] Desenhar animações
- [x] Proteger estado mutável de acessos concorrentes
- [ ] Substituir structs
- [ ] Fazer requisições HTTP
> Só uma tarefa por vez acessa o estado interno do actor.

### Questão
Por que atualizações de interface usam `@MainActor`?
- [ ] Por estilo
- [x] Porque a UI deve ser alterada na thread principal
- [ ] Para deixar mais rápido
- [ ] Porque async não funciona com UI
> Alterar a interface fora da thread principal causa erros.

## Interfaces com SwiftUI
### Teoria
**SwiftUI** constrói interfaces de forma **declarativa**: você descreve como a tela deve ser para cada estado, e o framework atualiza a tela quando o estado muda.

- Views são structs que adotam `View` e têm uma propriedade `body`.
- Layout com `VStack`, `HStack`, `ZStack`, `List`.
- **Modificadores** encadeados ajustam a aparência: `.font(.title)`, `.padding()`, `.foregroundStyle(.blue)`.
- `@State` guarda estado local da view; `@Binding` compartilha com views filhas; `@Observable` marca modelos observáveis.

### Prática
```swift
import SwiftUI

struct ContadorDeGelo: View {
    @State private var dias = 0

    var body: some View {
        VStack(spacing: 16) {
            Text("🧊")
                .font(.system(size: 72))
            Text("\(dias) dias de gelo")
                .font(.title)
                .bold()
            Button("Estudei hoje!") {
                dias += 1
            }
            .buttonStyle(.borderedProminent)
        }
        .padding()
    }
}

#Preview {
    ContadorDeGelo()
}
```

### Questão
O que significa SwiftUI ser declarativo?
- [ ] Que é preciso declarar todas as variáveis no topo
- [x] Que você descreve a interface para cada estado e o framework atualiza a tela
- [ ] Que só funciona com XML
- [ ] Que não usa código Swift
> Em vez de mandar a tela mudar passo a passo, você descreve o resultado.

### Questão
Para que serve `@State`?
- [ ] Guardar dados no servidor
- [x] Guardar estado local de uma view, que redesenha a tela ao mudar
- [ ] Criar uma variável global
- [ ] Definir constantes
> Mudanças em @State disparam a atualização do body.

### Questão
O que são os modificadores, como `.padding()`?
- [ ] Classes herdadas
- [x] Métodos encadeados que ajustam aparência e comportamento da view
- [ ] Comentários
- [ ] Testes de interface
> Cada modificador devolve uma nova view com o ajuste aplicado.
