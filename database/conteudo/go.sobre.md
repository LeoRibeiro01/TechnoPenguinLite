@historia
Go nasceu no **Google** em 2007, quando três engenheiros, **Robert Griesemer**, **Rob Pike** e **Ken Thompson** (cocriador do Unix e do C), se cansaram de esperar a compilação de enormes projetos em C++. Eles queriam uma linguagem com a simplicidade do C, compilação rapidíssima, coleta de lixo e suporte nativo a concorrência para os servidores multicore da época.

A linguagem foi anunciada como código aberto em 2009 e a versão **1.0** saiu em 2012, com uma promessa rara: código escrito para Go 1 continua compilando nas versões seguintes. O mascote é o **Gopher**, desenhado por Renée French. Em 2022, o Go 1.18 trouxe **generics**, o recurso mais pedido pela comunidade.

@hoje
Go é a linguagem da **infraestrutura de nuvem**: **Docker**, **Kubernetes**, Terraform, Prometheus, etcd e grande parte das ferramentas da CNCF são escritos em Go. Empresas como Google, Uber, Twitch, Dropbox, Cloudflare e Mercado Livre usam Go em serviços de alto tráfego. É ótima para APIs, microsserviços, ferramentas de linha de comando e programas de rede, graças ao binário único, à inicialização instantânea e às goroutines.

@iniciante
- Criar módulos e rodar programas com go run e go build
- Declarar variáveis, constantes e entender o valor zero
- Usar o for em todas as suas formas, if e switch
- Retornar múltiplos valores e tratar erros com err != nil

@intermediario
- Trabalhar com slices, maps e o idioma "comma ok"
- Criar structs, métodos e entender receptores ponteiro
- Definir interfaces implícitas
- Embrulhar e comparar erros com %w e errors.Is

@avancado
- Programar concorrência com goroutines, channels e WaitGroup
- Criar APIs HTTP com net/http e encoding/json
- Escrever testes table-driven e benchmarks
- Detectar condições de corrida e usar as ferramentas go vet e gofmt

@referencias
**Livros**
- *A Linguagem de Programação Go* — Alan Donovan e Brian Kernighan: o "K&R do Go".
- *Learning Go* — Jon Bodner: Go idiomático e moderno, com generics.
- *Concurrency in Go* — Katherine Cox-Buday: padrões de concorrência.

**Na internet**
- [Um Tour de Go (em português)](https://go.dev/tour/list?lang=pt-BR) — tutorial interativo oficial.
- [Effective Go](https://go.dev/doc/effective_go) — como escrever Go idiomático.
- [Go by Example](https://gobyexample.com) — exemplos curtos e práticos.

@modulo 1
**A simplicidade do Go é proposital.** Rob Pike resumiu: "menos é exponencialmente mais". Go tem só 25 palavras-chave, um único laço e nenhuma herança. Isso faz o código de qualquer pessoa parecer com o de qualquer outra, o que facilita manter projetos grandes. O `gofmt` acaba com discussões de estilo formatando tudo do mesmo jeito.

O **valor zero** é um princípio de projeto: tipos devem ser úteis sem inicialização. Um `sync.Mutex` ou um `bytes.Buffer` zerados já funcionam.

Os erros são **valores**, não exceções. Isso deixa o caminho do erro explícito no código, uma escolha que Pike explica no artigo "Errors are values". Para falhas realmente irrecuperáveis existe `panic`, mas ele deve ser raro.

@fontes 1
- Donovan e Kernighan, *A Linguagem de Programação Go*, capítulos 1 e 2
- Rob Pike, "Errors are values" (blog oficial do Go, 2015)
- [Effective Go](https://go.dev/doc/effective_go)

@modulo 2
**Entenda o slice por dentro.** Um slice é um pequeno cabeçalho com três campos: ponteiro para um array, **comprimento** e **capacidade**. Fatiar (`s[1:3]`) cria outro cabeçalho que compartilha o mesmo array; por isso alterar um slice pode alterar o outro. Quando a capacidade acaba, `append` aloca um array maior e copia os dados. O artigo "Go Slices: usage and internals", do blog oficial, mostra isso com diagramas.

Interfaces devem ser **pequenas** e definidas por quem **usa**, não por quem implementa: "aceite interfaces, retorne structs". As interfaces de um método só (`io.Reader`, `io.Writer`, `fmt.Stringer`) são as mais poderosas da biblioteca padrão, porque qualquer coisa que lê ou escreve bytes se conecta a qualquer outra.

Go usa **composição por embutimento**: uma struct que embute outra ganha seus métodos, sem herança.

@fontes 2
- Donovan e Kernighan, *A Linguagem de Programação Go*, capítulos 4 (slices e maps) e 7 (interfaces)
- [Go Blog: Go Slices — usage and internals](https://go.dev/blog/slices-intro)
- Bodner, *Learning Go*, capítulo 7 (tipos, métodos e interfaces)

@modulo 3
**"Não comunique compartilhando memória; compartilhe memória comunicando."** Esse provérbio do Go resume o modelo **CSP** (*Communicating Sequential Processes*), de Tony Hoare (1978), que inspirou goroutines e channels. Padrões clássicos: **pipeline** (estágios ligados por channels), **fan-out/fan-in** (vários trabalhadores e um coletor) e **worker pool**.

Use o pacote `context` para propagar **prazos e cancelamento** por toda a cadeia de chamadas: quando o cliente HTTP desiste, todo o trabalho relacionado deve parar. Todo servidor de produção define timeouts.

Concorrência não é paralelismo, como Pike explica em sua palestra famosa: concorrência é estruturar o programa em tarefas independentes; paralelismo é executá-las ao mesmo tempo. E rode os testes com `-race` no CI.

@fontes 3
- Cox-Buday, *Concurrency in Go*, capítulos 3 e 4
- Rob Pike, "Concurrency is not Parallelism" (palestra, 2012)
- [Go Blog: Go Concurrency Patterns — Context](https://go.dev/blog/context)
