@historia
Kotlin foi criado pela **JetBrains**, empresa das IDEs IntelliJ IDEA e PyCharm, sediada em São Petersburgo e Praga. O projeto foi anunciado em 2011 e a versão 1.0 saiu em **2016**. O nome vem da **ilha de Kotlin**, perto de São Petersburgo, assim como Java é o nome de uma ilha da Indonésia.

A equipe, liderada por **Andrey Breslav**, queria uma linguagem mais concisa e segura que o Java, mas 100% compatível com ele, para usar no próprio código da JetBrains. O grande impulso veio em **2017**, quando o Google anunciou suporte oficial no Android, e em **2019**, quando declarou o Android "Kotlin-first". Hoje a linguagem também compila para JavaScript, WebAssembly e código nativo.

@hoje
Kotlin é a linguagem principal do **desenvolvimento Android**: a maioria dos apps populares da Play Store usa Kotlin, e o **Jetpack Compose** é escrito para ele. No back-end, roda na JVM com **Spring Boot** e **Ktor**. Com o **Kotlin Multiplatform** (estável desde 2023), equipes compartilham a lógica entre Android, iOS, web e desktop. Empresas como Google, Netflix, Pinterest, Duolingo e Nubank usam Kotlin.

@iniciante
- Declarar valores com val e var e usar string templates
- Entender a null safety e os operadores ?., ?: e smart cast
- Usar if e when como expressões
- Escrever laços com intervalos, until, downTo e step

@intermediario
- Criar funções com parâmetros padrão, nomeados e extension functions
- Usar lambdas e funções de alta ordem
- Trabalhar com coleções somente leitura e mutáveis
- Modelar dados com classes, data classes, herança e interfaces

@avancado
- Programar de forma assíncrona com coroutines e suspend
- Representar estados com sealed classes e when exaustivo
- Construir interfaces Android com Jetpack Compose
- Organizar estado com ViewModel e rememberSaveable

@referencias
**Livros**
- *Kotlin in Action* — Dmitry Jemerov e Svetlana Isakova (2ª ed. com Roman Elizarov e Sebastian Aigner): escrito pela própria equipe do Kotlin.
- *Atomic Kotlin* — Bruce Eckel e Svetlana Isakova: aprende Kotlin em lições curtas.
- *Head First Kotlin* — Dawn e David Griffiths.

**Na internet**
- [Documentação oficial do Kotlin](https://kotlinlang.org/docs/home.html)
- [Kotlin Koans](https://play.kotlinlang.org/koans) — exercícios interativos no navegador.
- [Android Developers: cursos de Compose](https://developer.android.com/courses)

@modulo 1
**A null safety do Kotlin é verificada pelo compilador.** O sistema de tipos separa `String` (nunca nulo) de `String?` (pode ser nulo), atacando o "erro de um bilhão de dólares" de Tony Hoare. Ao usar código Java, que não declara nulabilidade, os valores chegam como **tipos de plataforma** (`String!`): o compilador não sabe, e a responsabilidade volta para você. Anotações como `@Nullable` no Java ajudam.

Prefira `val` a `var`: valores imutáveis deixam o código mais previsível, ideia muito defendida em *Kotlin in Action*. Expressões (`if`, `when`, `try`) retornam valores, o que elimina variáveis temporárias.

O Kotlin compila para o mesmo bytecode da JVM que o Java, por isso classes Kotlin e Java podem conviver no mesmo projeto e chamar umas às outras.

@fontes 1
- Jemerov e Isakova, *Kotlin in Action*, capítulos 2 e 6 (sistema de tipos)
- [Kotlin Docs: Null safety](https://kotlinlang.org/docs/null-safety.html)
- [Kotlin Docs: Calling Java from Kotlin](https://kotlinlang.org/docs/java-interop.html)

@modulo 2
**Funções de escopo e coleções tornam o código expressivo.** `let`, `apply`, `also`, `run` e `with` executam um bloco no contexto de um objeto; `apply` é ótimo para configurar objetos e `let` para trabalhar com valores anuláveis (`nome?.let { ... }`).

Operações em coleções (`map`, `filter`) criam listas intermediárias a cada passo. Para cadeias longas sobre muitos dados, use **sequências** (`asSequence()`), que processam um item por vez de forma preguiçosa, como as streams do Java.

Classes são **finais por padrão**, seguindo o conselho de *Java Efetivo* de "projetar para herança ou proibi-la". `data class` gera `equals`, `hashCode`, `toString`, `copy` e funções de desestruturação, e *delegação* (`class Repo(lista: List<T>) : List<T> by lista`) facilita aplicar "composição em vez de herança".

@fontes 2
- Jemerov e Isakova, *Kotlin in Action*, capítulos 4 e 5 (classes e lambdas)
- [Kotlin Docs: Scope functions](https://kotlinlang.org/docs/scope-functions.html)
- [Kotlin Docs: Sequences](https://kotlinlang.org/docs/sequences.html)

@modulo 3
**Concorrência estruturada evita tarefas perdidas.** Nas coroutines, toda tarefa nasce dentro de um **escopo**; se o escopo é cancelado (por exemplo, quando a tela do Android é fechada), todas as tarefas filhas também são, e o escopo só termina quando todas acabam. Isso evita vazamentos que eram comuns com threads e callbacks.

Para fluxos de dados ao longo do tempo, use **Flow** (frio, recalculado para cada coletor) e **StateFlow** (estado atual observável, ótimo para a interface).

No Android moderno, a arquitetura recomendada separa camadas: **UI** (Composables sem lógica de negócio), **ViewModel** (estado da tela, sobrevive à rotação) e **dados** (repositórios, Room, Retrofit). O estado flui de cima para baixo e os eventos sobem: o padrão *unidirectional data flow*.

@fontes 3
- [Kotlin Docs: Coroutines guide](https://kotlinlang.org/docs/coroutines-guide.html)
- Roman Elizarov, "Structured concurrency" (artigo no Medium, 2018)
- [Android: Guia de arquitetura de apps](https://developer.android.com/topic/architecture)
