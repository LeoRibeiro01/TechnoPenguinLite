@historia
Swift foi criada pela **Apple** a partir de 2010 por **Chris Lattner**, o mesmo criador do LLVM, a infraestrutura de compiladores usada por clang, Rust e muitas outras linguagens. A Apple usava o **Objective-C**, uma linguagem dos anos 1980 com sintaxe pouco amigável e muitas armadilhas de segurança.

Swift foi apresentada de surpresa na conferência **WWDC 2014** como "Objective-C sem o C". Em 2015 tornou-se **código aberto**, rodando também em Linux. A linguagem mudou muito nas primeiras versões, mas estabilizou a ABI no Swift 5 (2019). Em 2019 veio o **SwiftUI**, para interfaces declarativas; em 2021, a concorrência com async/await e actors; e o Swift 6 (2024) passou a verificar condições de corrida em tempo de compilação.

@hoje
Swift é a linguagem principal para apps de **iPhone, iPad, Mac, Apple Watch, Apple TV e Vision Pro**. Apps como Airbnb, LinkedIn, Uber e muitos bancos brasileiros usam Swift no iOS. Também roda no servidor (frameworks Vapor e Hummingbird), em scripts e até em sistemas embarcados (Embedded Swift). O **Swift Playgrounds** é uma das formas mais populares de ensinar programação para crianças no iPad.

@iniciante
- Declarar constantes e variáveis com let e var
- Entender opcionais e desembrulhá-los com if let, guard let e ??
- Controlar o fluxo com if, switch exaustivo e intervalos
- Escrever funções com rótulos de argumento

@intermediario
- Usar arrays, dicionários, conjuntos e closures
- Escolher entre structs e classes (valor ou referência)
- Criar enums com valores associados
- Definir protocolos e extensões com implementações padrão

@avancado
- Tratar erros com throws, do/catch e try?
- Programar de forma assíncrona com async/await e actors
- Construir interfaces declarativas com SwiftUI
- Gerenciar estado com @State, @Binding e @Observable

@referencias
**Livros**
- *The Swift Programming Language* — Apple: o livro oficial e completo, [gratuito online](https://docs.swift.org/swift-book/).
- *Swift Apprentice* — equipe Kodeco: didático e atualizado.
- *Hacking with Swift* — Paul Hudson: livros e o curso gratuito "100 Days of SwiftUI".

**Na internet**
- [Swift.org — Getting Started](https://www.swift.org/getting-started/)
- [Apple Developer: SwiftUI Tutorials](https://developer.apple.com/tutorials/swiftui)
- [Hacking with Swift: 100 Days of SwiftUI](https://www.hackingwithswift.com/100/swiftui)

@modulo 1
**Swift foi projetada para eliminar classes inteiras de erros.** Variáveis precisam ser inicializadas antes do uso, arrays verificam limites, inteiros acusam estouro (em vez de "dar a volta") e o `switch` precisa ser exaustivo. Os **opcionais** tornam explícito onde pode faltar um valor, e o compilador obriga a tratar o caso `nil`.

Lattner descreve o objetivo da linguagem como ser "segura, rápida e expressiva", nessa ordem. Segurança vem primeiro: quando um erro acontece mesmo assim (como forçar `!` num `nil`), o programa para imediatamente em vez de continuar num estado corrompido.

Os rótulos de argumento (`mover(de: a, para: b)`) seguem as **API Design Guidelines** da Apple: o código deve ser lido como uma frase em inglês, com clareza no ponto de uso.

@fontes 1
- Apple, *The Swift Programming Language*, "The Basics" e "Control Flow"
- [Swift.org: API Design Guidelines](https://www.swift.org/documentation/api-design-guidelines/)
- Hudson, *100 Days of SwiftUI*, dias 1 a 14

@modulo 2
**Swift é orientada a protocolos.** Na palestra "Protocol-Oriented Programming in Swift" (WWDC 2015), a Apple propôs começar pelo protocolo, não pela classe. Com **extensões de protocolo**, um comportamento pode ser dado a muitos tipos (inclusive structs e enums) sem herança.

A preferência por **tipos de valor** (structs) evita estados compartilhados acidentais: cada cópia é independente. As coleções da biblioteca padrão usam **copy-on-write**, então copiar um array grande é barato: a cópia real só acontece quando um dos dois é modificado.

Enums com valores associados são **tipos de soma**, perfeitos para modelar estados ("carregando", "erro(mensagem)", "pronto(dados)") de forma que estados impossíveis nem possam ser escritos.

@fontes 2
- Apple, *The Swift Programming Language*, "Structures and Classes", "Protocols" e "Enumerations"
- Palestra "Protocol-Oriented Programming in Swift" (WWDC 2015)
- [Swift.org: Value and Reference Types](https://developer.apple.com/swift/blog/?id=10)

@modulo 3
**Concorrência estruturada e isolamento por actors.** Com async/await, tarefas filhas (`async let`, `TaskGroup`) vivem dentro do escopo da tarefa pai e são canceladas junto. **Actors** isolam estado mutável: só uma tarefa por vez acessa os dados do actor. O Swift 6 verifica esses isolamentos em tempo de compilação, e tipos que cruzam fronteiras de concorrência precisam ser `Sendable`.

No SwiftUI, a interface é **função do estado**: `body` é recalculado quando o estado muda, e a view em si é uma struct barata, recriada o tempo todo. A **fonte única da verdade** é o princípio central: cada dado deve ter um único dono (`@State` ou um modelo `@Observable`) e as outras views recebem acesso por `@Binding` ou pelo ambiente.

@fontes 3
- Apple, *The Swift Programming Language*, "Concurrency"
- Palestras da WWDC 2021: "Meet async/await in Swift" e "Protect mutable state with Swift actors"
- [Apple: Managing model data in your app](https://developer.apple.com/documentation/swiftui/managing-model-data-in-your-app)
