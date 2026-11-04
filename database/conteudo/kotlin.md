# Fundamentos de Kotlin
> Variáveis, tipos, null safety e controle de fluxo.

## Projeto
**Quiz de bandeiras no terminal:** guarde perguntas e respostas num `Map`, embaralhe com `shuffled()`, leia as respostas com `readln()`, conte os acertos e mostre uma mensagem final com `when` conforme a pontuação.

## Primeiros passos
### Teoria
Kotlin é uma linguagem moderna e concisa da JetBrains, **oficial para Android** e totalmente interoperável com Java (roda na JVM). Também compila para JavaScript e código nativo.

Declarações:
- `val`: valor imutável (prefira).
- `var`: variável mutável.

O tipo é inferido, mas pode ser explícito: `val idade: Int = 3`. Não há `;` no fim das linhas. O ponto de entrada é `fun main()`.

**String templates** inserem valores: `"Olá, $nome"` ou `"${a + b}"`.

### Prática
```kotlin
fun main() {
    val nome = "Teco"
    var peixes = 3
    peixes += 2
    println("$nome tem $peixes peixes")
    println("Dobro: ${peixes * 2}")

    print("Seu nome: ")
    val usuario = readln()
    println("Bem-vindo, ${usuario.uppercase()}!")
}
```

### Questão
Qual palavra declara um valor que não pode ser reatribuído?
- [x] val
- [ ] var
- [ ] let
- [ ] const obrigatório
> val é imutável; var permite reatribuição.

### Questão
Como inserir uma expressão dentro de uma String em Kotlin?
- [ ] "{a + b}"
- [x] "${a + b}"
- [ ] "#{a + b}"
- [ ] "%a + b%"
> Expressões usam ${}; variáveis simples podem usar só $nome.

### Questão
Kotlin é a linguagem oficial de qual plataforma?
- [ ] iOS
- [x] Android
- [ ] Windows Phone
- [ ] PlayStation
> O Google anunciou Kotlin como preferencial para Android em 2019.

## Null safety
### Teoria
Kotlin elimina boa parte dos `NullPointerException` separando tipos que **podem** e que **não podem** ser nulos:

- `String`: nunca é nulo.
- `String?`: pode ser nulo.

Para usar um tipo anulável:
- **Chamada segura** `?.`: `nome?.length` devolve `null` se nome for nulo.
- **Elvis** `?:`: valor padrão: `nome?.length ?: 0`.
- **Smart cast**: depois de `if (nome != null)`, o compilador trata nome como `String`.
- `!!` força o acesso e lança exceção se for nulo: evite.

### Prática
```kotlin
fun tamanho(texto: String?): Int = texto?.length ?: 0

fun main() {
    val apelido: String? = null
    println(tamanho(apelido))     // 0
    println(tamanho("pinguim"))   // 7

    val entrada = readlnOrNull()
    if (entrada != null) {
        println(entrada.trim())   // smart cast para String
    }
    val numero = "42a".toIntOrNull() ?: -1
    println(numero)               // -1
}
```

### Questão
O que `nome?.length` retorna se `nome` for null?
- [ ] 0
- [x] null
- [ ] Lança exceção
- [ ] ""
> A chamada segura devolve null em vez de quebrar.

### Questão
Para que serve o operador Elvis `?:`?
- [ ] Comparar valores
- [x] Fornecer um valor padrão quando a expressão à esquerda é null
- [ ] Converter tipos
- [ ] Declarar variáveis
> a ?: b devolve a se não for null; senão devolve b.

### Questão
Uma variável do tipo `String` (sem ?) pode receber null?
- [ ] Sim
- [x] Não, o compilador impede
- [ ] Só em funções
- [ ] Só em Android
> Tipos sem ? são não anuláveis por garantia do compilador.

## Controle de fluxo
### Teoria
Em Kotlin, `if` e `when` são **expressões**: retornam valores.

`when` substitui o switch com muito mais poder: aceita valores, intervalos (`in 1..10`), tipos (`is String`) e condições livres.

Laços: `for (i in 1..5)`, `for (i in 10 downTo 0 step 2)`, `for (item in lista)` e `while`. `until` exclui o fim: `0 until 3` é 0, 1, 2.

### Prática
```kotlin
fun classificar(nota: Double): String = when {
    nota >= 9 -> "Excelente"
    nota >= 7 -> "Aprovado"
    nota >= 5 -> "Recuperação"
    else -> "Reprovado"
}

fun main() {
    val idade = 15
    val tipo = if (idade >= 18) "adulto" else "jovem"
    println(tipo)
    println(classificar(7.5))

    for (i in 1..3) print("$i ")
    for (i in 10 downTo 0 step 5) print("$i ")
}
```

### Questão
Quais valores `0 until 3` produz?
- [ ] 0, 1, 2, 3
- [x] 0, 1, 2
- [ ] 1, 2, 3
- [ ] 3
> until exclui o limite superior; .. inclui.

### Questão
O que significa dizer que `if` é uma expressão em Kotlin?
- [ ] Que não pode ter else
- [x] Que ele retorna um valor, que pode ser atribuído
- [ ] Que só funciona com números
- [ ] Que precisa de chaves
> val tipo = if (...) "a" else "b" é válido.

### Questão
O que `when` substitui em Kotlin?
- [ ] O for
- [x] O switch de outras linguagens, com mais recursos
- [ ] O try/catch
- [ ] O import
> when aceita valores, intervalos, tipos e condições.

# Funções, coleções e orientação a objetos
> Funções de alta ordem, coleções, data classes e herança.

## Projeto
**Gerenciador de tarefas:** crie uma `data class Tarefa(titulo, prioridade, feita)`, uma lista mutável de tarefas e funções para adicionar, concluir e listar ordenando por prioridade com `sortedBy`. Use `filter`, `groupBy` e `count` para mostrar estatísticas.

## Funções e lambdas
### Teoria
Funções são declaradas com `fun`. Recursos úteis:
- **Parâmetros padrão e nomeados:** `saudar(nome = "Teco")`.
- **Funções de expressão única:** `fun dobro(x: Int) = x * 2`.
- **Lambdas:** `{ x: Int -> x * 2 }`. Se a lambda tem um parâmetro, ele pode ser chamado de `it`.
- **Funções de alta ordem:** recebem ou devolvem funções.
- **Extension functions:** adicionam métodos a tipos existentes: `fun String.gritar() = uppercase() + "!"`.

### Prática
```kotlin
fun saudar(nome: String = "pinguim", emoji: String = "🐧") = "Oi, $nome $emoji"

fun aplicar(valor: Int, operacao: (Int) -> Int): Int = operacao(valor)

fun String.gritar() = uppercase() + "!"

fun main() {
    println(saudar())
    println(saudar(emoji = "❄️", nome = "Teco"))
    println(aplicar(5) { it * 10 })   // 50
    println("gelo".gritar())          // GELO!
}
```

### Questão
O que `it` representa numa lambda?
- [ ] O retorno da função
- [x] O parâmetro único da lambda, quando não é nomeado
- [ ] A classe atual
- [ ] Um iterador obrigatório
> Lambdas com um só parâmetro podem usar o nome implícito it.

### Questão
O que é uma extension function?
- [ ] Uma função muito longa
- [x] Uma função que adiciona comportamento a um tipo existente sem herdá-lo
- [ ] Uma função de outra linguagem
- [ ] Uma função assíncrona
> fun String.gritar() pode ser chamada como "texto".gritar().

### Questão
Como passar um argumento por nome?
- [ ] saudar("nome" = "Teco")
- [x] saudar(nome = "Teco")
- [ ] saudar(nome: "Teco")
- [ ] saudar(->nome "Teco")
> Argumentos nomeados podem vir em qualquer ordem.

## Coleções
### Teoria
Kotlin separa coleções **somente leitura** (`listOf`, `setOf`, `mapOf`) das **mutáveis** (`mutableListOf`, `mutableSetOf`, `mutableMapOf`).

Operações funcionais encadeadas: `filter`, `map`, `sortedBy`, `groupBy`, `sumOf`, `any`, `all`, `first`, `count`. Elas devolvem novas coleções, sem alterar a original.

Mapas usam `to` para criar pares: `mapOf("teco" to 120)`.

### Prática
```kotlin
data class Aluno(val nome: String, val nota: Double)

fun main() {
    val turma = listOf(Aluno("Ana", 9.5), Aluno("Beto", 5.0), Aluno("Caio", 7.0))

    val aprovados = turma.filter { it.nota >= 7 }.map { it.nome }
    val media = turma.sumOf { it.nota } / turma.size
    val porSituacao = turma.groupBy { if (it.nota >= 7) "aprovado" else "recuperação" }

    println(aprovados)                 // [Ana, Caio]
    println("%.1f".format(media))      // 7.2
    println(porSituacao.keys)

    val xp = mutableMapOf("teco" to 100)
    xp["teco"] = xp.getValue("teco") + 50
}
```

### Questão
Qual função cria uma lista que pode ser alterada?
- [ ] listOf
- [x] mutableListOf
- [ ] arrayListOnly
- [ ] setOf
> listOf é somente leitura; mutableListOf permite add/remove.

### Questão
O que `turma.filter { it.nota >= 7 }` retorna?
- [ ] Um Boolean
- [x] Uma nova lista só com os alunos de nota >= 7
- [ ] A mesma lista alterada
- [ ] O primeiro aluno aprovado
> filter cria uma nova coleção com os elementos que passam no teste.

### Questão
Como criar o par chave-valor "teco" → 100 num mapa?
- [ ] "teco": 100
- [x] "teco" to 100
- [ ] "teco" => 100
- [ ] ("teco", 100) obrigatório
> to é uma função infixa que cria um Pair.

## Classes, data classes e herança
### Teoria
Classes Kotlin são concisas: o **construtor primário** fica no cabeçalho e pode declarar propriedades: `class Pinguim(val nome: String, var idade: Int)`.

**Data classes** geram automaticamente `equals`, `hashCode`, `toString` e `copy`. Ideais para modelos de dados.

Classes são **finais por padrão**: para permitir herança use `open`, e para sobrescrever, `override`. Interfaces podem ter implementações padrão.

**object** cria um singleton; **companion object** guarda membros "estáticos".

### Prática
```kotlin
interface Nadador {
    fun nadar() = "nadou"
}

open class Ave(val nome: String) {
    open fun som() = "..."
}

class Pinguim(nome: String) : Ave(nome), Nadador {
    override fun som() = "quá"
}

data class Ponto(val x: Int, val y: Int)

fun main() {
    val teco = Pinguim("Teco")
    println("${teco.nome}: ${teco.som()} e ${teco.nadar()}")

    val a = Ponto(1, 2)
    val b = a.copy(y = 5)
    println(a == Ponto(1, 2))  // true
    println(b)                  // Ponto(x=1, y=5)
}
```

### Questão
Por que é preciso `open` para herdar de uma classe Kotlin?
- [ ] Porque Kotlin não tem herança
- [x] Porque as classes são finais por padrão
- [ ] Para torná-la abstrata
- [ ] Para deixá-la pública
> O padrão final evita heranças acidentais.

### Questão
O que uma data class gera automaticamente?
- [ ] Uma tabela no banco
- [x] equals, hashCode, toString e copy
- [ ] Uma interface gráfica
- [ ] Getters assíncronos
> Por isso Ponto(1, 2) == Ponto(1, 2) é true.

### Questão
O que `a.copy(y = 5)` faz?
- [ ] Altera a
- [x] Cria uma nova instância igual a "a", mas com y = 5
- [ ] Copia a classe
- [ ] Gera erro
> copy cria cópias modificadas de objetos imutáveis.

# Kotlin moderno e Android
> Coroutines, sealed classes e o básico de apps Android com Compose.

## Projeto
**App de contador de gelo (Android):** crie um projeto no Android Studio com Jetpack Compose que mostra um cubo de gelo, um botão para registrar o estudo do dia e o número de dias seguidos. Guarde o estado com `remember`/`rememberSaveable` e, como desafio, persista com DataStore.

## Coroutines
### Teoria
**Coroutines** são a forma do Kotlin de escrever código assíncrono de modo sequencial e leve. Milhares de coroutines podem rodar sem criar milhares de threads.

- Funções `suspend` podem pausar sem bloquear a thread.
- **Builders:** `launch` (dispara e esquece, devolve um `Job`) e `async` (devolve um `Deferred`, cujo resultado se obtém com `await()`).
- **Escopos** controlam o ciclo de vida: `runBlocking`, `coroutineScope`, e no Android `viewModelScope`.
- **Dispatchers** escolhem a thread: `Dispatchers.IO` para rede e disco, `Dispatchers.Main` para a interface.

### Prática
```kotlin
import kotlinx.coroutines.*

suspend fun pescar(nome: String): Int {
    delay(1000)              // não bloqueia a thread
    return nome.length
}

fun main() = runBlocking {
    val a = async { pescar("Teco") }
    val b = async { pescar("Pipo") }
    println("Total de peixes: ${a.await() + b.await()}")   // ~1s, não 2s

    launch { println("Tarefa em paralelo") }
}
```

### Questão
O que caracteriza uma função `suspend`?
- [ ] Ela nunca termina
- [x] Pode pausar sua execução sem bloquear a thread
- [ ] Roda sempre numa thread nova
- [ ] É chamada só em testes
> Funções suspend só podem ser chamadas de coroutines ou de outras funções suspend.

### Questão
Qual a diferença entre `launch` e `async`?
- [ ] Nenhuma
- [x] async devolve um resultado via await(); launch não devolve resultado
- [ ] launch é mais lento
- [ ] async bloqueia a thread
> launch retorna Job; async retorna Deferred<T>.

### Questão
Qual dispatcher é indicado para leitura de arquivos e rede?
- [ ] Dispatchers.Main
- [x] Dispatchers.IO
- [ ] Dispatchers.Unconfined
- [ ] Dispatchers.UI
> IO é otimizado para operações de entrada e saída.

## Sealed classes e tratamento de estados
### Teoria
Uma **sealed class** (ou sealed interface) define um conjunto **fechado** de subtipos, todos conhecidos pelo compilador. Combinada com `when`, o compilador exige que **todos os casos** sejam tratados, sem precisar de `else`.

É perfeita para representar estados de tela ou resultados de operações: carregando, sucesso, erro.

Para erros, Kotlin oferece também `runCatching`, que embrulha o resultado num `Result`.

### Prática
```kotlin
sealed interface Estado {
    data object Carregando : Estado
    data class Sucesso(val dados: List<String>) : Estado
    data class Erro(val mensagem: String) : Estado
}

fun descrever(estado: Estado): String = when (estado) {
    Estado.Carregando -> "Carregando..."
    is Estado.Sucesso -> "${estado.dados.size} itens"
    is Estado.Erro -> "Ops: ${estado.mensagem}"
}

fun main() {
    println(descrever(Estado.Sucesso(listOf("peixe", "lula"))))
    val numero = runCatching { "abc".toInt() }.getOrDefault(0)
    println(numero) // 0
}
```

### Questão
Qual a vantagem de usar sealed classes com `when`?
- [ ] Deixa o código mais lento
- [x] O compilador verifica se todos os subtipos foram tratados
- [ ] Permite herança ilimitada de qualquer arquivo
- [ ] Remove a necessidade de classes
> Se surgir um novo estado, o compilador aponta os when incompletos.

### Questão
Em `is Estado.Sucesso -> estado.dados`, por que `dados` está acessível?
- [ ] Por reflexão
- [x] Por smart cast: após o is, estado é tratado como Sucesso
- [ ] Porque todos os estados têm dados
- [ ] Por acaso
> O compilador converte o tipo automaticamente dentro do ramo.

### Questão
O que `runCatching { ... }` devolve?
- [ ] Sempre o valor ou lança exceção
- [x] Um Result que contém o valor ou a exceção
- [ ] Um Boolean
- [ ] Nada
> Result permite tratar sucesso e falha sem try/catch explícito.

## Android com Jetpack Compose
### Teoria
**Jetpack Compose** é o kit moderno de interfaces Android: a tela é descrita por **funções @Composable** que recebem dados e emitem UI. Quando o estado muda, o Compose redesenha só o necessário (**recomposição**).

- `remember { mutableStateOf(0) }` guarda estado entre recomposições.
- `rememberSaveable` sobrevive também à rotação da tela.
- Layouts: `Column`, `Row`, `Box`; componentes: `Text`, `Button`, `Image`.
- **ViewModel** guarda o estado e a lógica fora da tela, sobrevivendo a mudanças de configuração.

### Prática
```kotlin
@Composable
fun ContadorDeGelo() {
    var dias by rememberSaveable { mutableStateOf(0) }

    Column(
        modifier = Modifier.fillMaxSize().padding(24.dp),
        horizontalAlignment = Alignment.CenterHorizontally,
        verticalArrangement = Arrangement.Center
    ) {
        Text("🧊", fontSize = 64.sp)
        Text("$dias dias de gelo", style = MaterialTheme.typography.headlineMedium)
        Button(onClick = { dias++ }) {
            Text("Estudei hoje!")
        }
    }
}
```

### Questão
O que é uma função `@Composable`?
- [ ] Uma função de banco de dados
- [x] Uma função que descreve parte da interface do usuário
- [ ] Um teste automatizado
- [ ] Uma coroutine
> Composables emitem UI a partir dos dados recebidos.

### Questão
O que acontece quando um estado lido por um Composable muda?
- [ ] O app reinicia
- [x] O Compose recompõe (redesenha) as partes afetadas
- [ ] Nada até o usuário rolar a tela
- [ ] Uma exceção é lançada
> A recomposição mantém a tela sincronizada com o estado.

### Questão
Qual a vantagem de `rememberSaveable` sobre `remember`?
- [ ] É mais rápido
- [x] Mantém o estado mesmo após rotação da tela ou recriação da Activity
- [ ] Salva no banco de dados
- [ ] Funciona sem Compose
> rememberSaveable guarda o valor no estado salvo da instância.
