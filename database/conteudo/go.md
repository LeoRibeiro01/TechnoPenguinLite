# Fundamentos de Go
> Pacotes, variáveis, tipos e controle de fluxo.

## Projeto
**Contador de palavras:** escreva um programa que lê um arquivo de texto (nome passado como argumento em `os.Args`), conta as palavras com um `map[string]int` e mostra as 10 mais frequentes ordenadas com `sort.Slice`. Trate o erro de arquivo inexistente.

## Olá, Go
### Teoria
Go (ou Golang) foi criada no Google para ser **simples, rápida e boa em concorrência**. É compilada para um único binário, sem dependências, e muito usada em serviços de nuvem, APIs e ferramentas como Docker e Kubernetes.

Todo arquivo pertence a um **pacote**. O executável começa em `package main`, na função `main`. Importações ficam em `import`.

Comandos essenciais:

```
go mod init meuapp
go run .
go build
```

Go é rígido com organização: variáveis e imports não usados geram **erro de compilação**, e `gofmt` formata o código de forma padronizada.

### Prática
```go
package main

import "fmt"

func main() {
	nome := "Teco"
	peixes := 3
	fmt.Println("Olá,", nome)
	fmt.Printf("%s tem %d peixes\n", nome, peixes)
}
```

### Questão
Em qual pacote fica a função de entrada de um executável Go?
- [ ] package app
- [x] package main
- [ ] package start
- [ ] package go
> O compilador procura func main dentro de package main.

### Questão
O que acontece se você importar um pacote e não usá-lo?
- [ ] Nada
- [ ] Um aviso
- [x] Erro de compilação
- [ ] O pacote é removido automaticamente
> Go não compila com imports ou variáveis não usados.

### Questão
Qual comando executa um programa Go sem gerar o binário manualmente?
- [ ] go build
- [x] go run .
- [ ] go start
- [ ] go exec
> go run compila e executa em seguida.

## Variáveis e tipos
### Teoria
Formas de declarar:
- `var idade int = 3` (explícito).
- `idade := 3` (declaração curta com inferência, só dentro de funções).
- `const pi = 3.14`.

Variáveis sem valor recebem o **valor zero** do tipo: `0`, `""`, `false`, `nil`.

Tipos básicos: `int`, `float64`, `string`, `bool`, `rune` (caractere Unicode). Não há conversão implícita: `float64(x)` converte explicitamente.

Funções podem retornar **vários valores**, muito usado para devolver resultado e erro.

### Prática
```go
package main

import (
	"fmt"
	"strconv"
)

func dividir(a, b float64) (float64, error) {
	if b == 0 {
		return 0, fmt.Errorf("divisão por zero")
	}
	return a / b, nil
}

func main() {
	var contador int          // valor zero: 0
	n, err := strconv.Atoi("42")
	if err != nil {
		fmt.Println("erro:", err)
		return
	}
	resultado, err := dividir(float64(n), 4)
	fmt.Println(contador, resultado, err) // 0 10.5 <nil>
}
```

### Questão
Qual o valor zero de uma `string` em Go?
- [ ] nil
- [x] "" (string vazia)
- [ ] "0"
- [ ] undefined
> Cada tipo tem um valor zero; para strings é a string vazia.

### Questão
O que `x := 10` faz?
- [ ] Compara x com 10
- [x] Declara x e atribui 10, inferindo o tipo int
- [ ] Cria uma constante
- [ ] Gera erro fora de main
> := é a declaração curta, usada dentro de funções.

### Questão
Como funções Go costumam informar erros?
- [ ] Lançando exceções
- [x] Retornando um valor do tipo error junto com o resultado
- [ ] Imprimindo na tela
- [ ] Encerrando o programa
> O padrão é resultado, err := funcao() e checar err != nil.

## Controle de fluxo
### Teoria
Go tem só um laço: o **for**, que assume várias formas:
- Clássico: `for i := 0; i < 5; i++`.
- Como while: `for condicao { }`.
- Infinito: `for { }`.
- Com `range` para percorrer slices, maps e strings.

O `if` não usa parênteses e pode ter uma instrução de inicialização: `if v, err := f(); err != nil { }`.

O `switch` não precisa de `break`: cada caso termina sozinho.

### Prática
```go
package main

import "fmt"

func main() {
	for i := 1; i <= 3; i++ {
		fmt.Println("volta", i)
	}

	linguagens := []string{"Go", "Python", "Rust"}
	for indice, nome := range linguagens {
		fmt.Println(indice, nome)
	}

	nota := 8
	switch {
	case nota >= 9:
		fmt.Println("excelente")
	case nota >= 7:
		fmt.Println("aprovado")
	default:
		fmt.Println("estude mais")
	}
}
```

### Questão
Quantas palavras-chave de laço existem em Go?
- [x] Uma: for
- [ ] Duas: for e while
- [ ] Três: for, while e do
- [ ] Nenhuma
> for cobre todos os casos, inclusive o equivalente ao while.

### Questão
O que `range` devolve ao percorrer um slice?
- [ ] Só os valores
- [x] O índice e o valor
- [ ] Só o tamanho
- [ ] Um ponteiro
> for i, v := range slice entrega índice e valor; use _ para ignorar um deles.

### Questão
É preciso `break` em cada caso do switch?
- [ ] Sim
- [x] Não, os casos não "caem" para o próximo
- [ ] Só no default
- [ ] Só com números
> Para continuar no próximo caso existe a palavra fallthrough, raramente usada.

# Estruturas de dados e tipos
> Slices, maps, structs, métodos, interfaces e erros.

## Projeto
**Inventário de iglus:** defina `type Iglu struct` (nome, membros, diasDeGelo), métodos para adicionar membro e registrar atividade, e uma interface `Descritor` com `Descrever() string`. Guarde os iglus num `map[string]*Iglu` e grave/leia o inventário em JSON com `encoding/json`.

## Slices e maps
### Teoria
**Arrays** têm tamanho fixo (`[3]int`). Na prática usamos **slices** (`[]int`), visões dinâmicas sobre arrays:
- `append(s, v)` adiciona (e pode realocar: sempre use o retorno).
- `len(s)` e `cap(s)`: tamanho e capacidade.
- Fatiamento: `s[1:3]`.
- `make([]int, 0, 10)` cria com capacidade inicial.

**Maps** são dicionários: `m := map[string]int{"teco": 10}`. Leitura com verificação: `v, ok := m["pipo"]`; `ok` é false se a chave não existe. `delete(m, chave)` remove.

### Prática
```go
package main

import "fmt"

func main() {
	notas := []int{7, 9}
	notas = append(notas, 10, 6)
	fmt.Println(notas, len(notas), notas[1:3])

	xp := map[string]int{"teco": 120}
	xp["pipo"] += 30
	if v, ok := xp["ana"]; !ok {
		fmt.Println("ana não encontrada", v)
	}
	for nome, pontos := range xp {
		fmt.Println(nome, pontos)
	}
}
```

### Questão
Por que fazemos `s = append(s, v)` em vez de só `append(s, v)`?
- [ ] Por estilo
- [x] Porque append pode criar um novo array por baixo e devolve o slice atualizado
- [ ] Porque append não existe sem atribuição
- [ ] Para converter tipos
> Se a capacidade acabar, append aloca outro array; sem guardar o retorno, você perde o novo slice.

### Questão
Em `v, ok := m["x"]`, o que `ok` indica?
- [ ] Se o valor é positivo
- [x] Se a chave existe no map
- [ ] Se o map está vazio
- [ ] Se houve erro de rede
> É o idioma "comma ok" para diferenciar ausência de valor zero.

### Questão
O que `s[1:3]` retorna num slice?
- [ ] Os elementos 1, 2 e 3
- [x] Os elementos dos índices 1 e 2
- [ ] Três elementos a partir do 1
- [ ] Um erro
> O limite superior é exclusivo.

## Structs, métodos e ponteiros
### Teoria
Go não tem classes: usa **structs** e **métodos** associados a elas.

```
type Pinguim struct { Nome string; Peixes int }
func (p *Pinguim) Comer(n int) { p.Peixes += n }
```

O `(p *Pinguim)` é o **receptor**. Com receptor **ponteiro** (`*Pinguim`), o método pode alterar o struct; com receptor valor, recebe uma cópia.

Ponteiros: `&x` pega o endereço e `*p` acessa o valor. Não há aritmética de ponteiros.

Campos com inicial **maiúscula** são exportados (visíveis fora do pacote); minúscula, privados ao pacote.

### Prática
```go
package main

import "fmt"

type Pinguim struct {
	Nome   string
	Peixes int
}

func (p *Pinguim) Comer(n int) {
	p.Peixes += n
}

func (p Pinguim) Resumo() string {
	return fmt.Sprintf("%s comeu %d peixes", p.Nome, p.Peixes)
}

func main() {
	teco := Pinguim{Nome: "Teco"}
	teco.Comer(3)
	teco.Comer(2)
	fmt.Println(teco.Resumo()) // Teco comeu 5 peixes
}
```

### Questão
Por que `Comer` usa receptor ponteiro `(p *Pinguim)`?
- [ ] Porque é obrigatório
- [x] Para poder modificar o struct original
- [ ] Para ser mais lento
- [ ] Para tornar o método privado
> Com receptor valor, a alteração ficaria numa cópia.

### Questão
Como tornar um campo de struct visível para outros pacotes?
- [ ] Usando public
- [x] Começando o nome com letra maiúscula
- [ ] Usando export
- [ ] Colocando em main
> Em Go, a visibilidade é definida pela inicial do identificador.

### Questão
Go possui classes?
- [ ] Sim, com a palavra class
- [x] Não; usa structs com métodos e interfaces
- [ ] Só em pacotes externos
- [ ] Só com generics
> Composição e interfaces substituem a herança de classes.

## Interfaces e erros
### Teoria
Uma **interface** define métodos. Em Go, a implementação é **implícita**: qualquer tipo que tenha os métodos satisfaz a interface, sem declarar `implements`.

```
type Descritor interface { Descrever() string }
```

Interfaces pequenas são o estilo da linguagem (`io.Reader`, `fmt.Stringer`, `error`).

**Erros** são valores que implementam a interface `error`. Boas práticas:
- Sempre verifique `if err != nil`.
- Embrulhe com contexto: `fmt.Errorf("lendo config: %w", err)`.
- Compare com `errors.Is` e `errors.As`.

### Prática
```go
package main

import (
	"errors"
	"fmt"
)

var ErrSemPeixe = errors.New("sem peixe no estoque")

type Animal interface{ Som() string }

type Pinguim struct{}
type Foca struct{}

func (Pinguim) Som() string { return "quá" }
func (Foca) Som() string    { return "arf" }

func pescar(estoque int) error {
	if estoque == 0 {
		return fmt.Errorf("pescando: %w", ErrSemPeixe)
	}
	return nil
}

func main() {
	for _, a := range []Animal{Pinguim{}, Foca{}} {
		fmt.Println(a.Som())
	}
	if err := pescar(0); errors.Is(err, ErrSemPeixe) {
		fmt.Println("erro:", err)
	}
}
```

### Questão
Como um tipo passa a implementar uma interface em Go?
- [ ] Declarando implements
- [x] Simplesmente possuindo todos os métodos da interface
- [ ] Herdando de uma classe base
- [ ] Registrando num arquivo de configuração
> A satisfação de interfaces é implícita.

### Questão
O que `%w` faz em `fmt.Errorf`?
- [ ] Imprime em largura fixa
- [x] Embrulha o erro original, permitindo identificá-lo com errors.Is
- [ ] Converte o erro em warning
- [ ] Ignora o erro
> Erros embrulhados mantêm a cadeia de causas.

### Questão
Qual a forma idiomática de tratar erros em Go?
- [ ] try/catch
- [x] Verificar explicitamente if err != nil após cada chamada
- [ ] Ignorar e usar panic sempre
- [ ] Usar exceções checadas
> Erros são valores e são tratados explicitamente.

# Go concorrente e na web
> Goroutines, channels, APIs HTTP e testes.

## Projeto
**Verificador de sites:** receba uma lista de URLs, verifique todas em paralelo com goroutines e um `sync.WaitGroup`, colete os resultados (status e tempo) por um channel e exponha um endpoint `/status` em JSON com `net/http`. Escreva testes com `testing` e `httptest`.

## Goroutines e channels
### Teoria
Uma **goroutine** é uma função executando de forma concorrente, iniciada com `go funcao()`. Ela é muito leve: é possível ter milhares.

**Channels** comunicam goroutines com segurança: `ch := make(chan int)`. Enviar: `ch <- 5`; receber: `v := <-ch`. O lema de Go: *"não comunique compartilhando memória; compartilhe memória comunicando"*.

`sync.WaitGroup` espera um grupo de goroutines terminar. `select` aguarda o primeiro de vários channels. Para dados compartilhados, use `sync.Mutex`.

### Prática
```go
package main

import (
	"fmt"
	"sync"
	"time"
)

func pescar(nome string, resultados chan<- string, wg *sync.WaitGroup) {
	defer wg.Done()
	time.Sleep(500 * time.Millisecond)
	resultados <- nome + " pescou um peixe"
}

func main() {
	resultados := make(chan string, 3)
	var wg sync.WaitGroup
	for _, nome := range []string{"Teco", "Pipo", "Ana"} {
		wg.Add(1)
		go pescar(nome, resultados, &wg)
	}
	wg.Wait()
	close(resultados)
	for r := range resultados {
		fmt.Println(r)
	}
}
```

### Questão
Como iniciar uma goroutine?
- [ ] thread funcao()
- [x] go funcao()
- [ ] async funcao()
- [ ] spawn funcao()
> A palavra go antes da chamada a executa concorrentemente.

### Questão
Para que serve um channel?
- [ ] Guardar arquivos
- [x] Comunicar e sincronizar goroutines com segurança
- [ ] Criar structs
- [ ] Importar pacotes
> Channels transportam valores entre goroutines.

### Questão
O que `wg.Wait()` faz?
- [ ] Pausa por um segundo
- [x] Bloqueia até todas as goroutines registradas chamarem Done
- [ ] Cancela as goroutines
- [ ] Fecha o channel
> WaitGroup conta tarefas pendentes; Wait espera chegar a zero.

## APIs HTTP com net/http
### Teoria
A biblioteca padrão de Go já tem um servidor HTTP de produção. Desde o Go 1.22, o roteador aceita **método e parâmetros** no padrão: `"GET /iglus/{id}"`.

- O handler recebe `http.ResponseWriter` (para escrever a resposta) e `*http.Request`.
- `encoding/json` converte structs em JSON e vice-versa. **Tags** de struct (como `json:"nome"`, escritas entre crases após o campo) definem os nomes no JSON.
- `r.PathValue("id")` lê o parâmetro da rota.

Cada requisição roda em sua própria goroutine automaticamente.

### Prática
```go
package main

import (
	"encoding/json"
	"log"
	"net/http"
)

type Iglu struct {
	ID   string `json:"id"`
	Nome string `json:"nome"`
}

var iglus = map[string]Iglu{"1": {ID: "1", Nome: "Pinguins do Go"}}

func main() {
	http.HandleFunc("GET /iglus/{id}", func(w http.ResponseWriter, r *http.Request) {
		iglu, ok := iglus[r.PathValue("id")]
		if !ok {
			http.Error(w, "iglu não encontrado", http.StatusNotFound)
			return
		}
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(iglu)
	})
	log.Fatal(http.ListenAndServe(":8080", nil))
}
```

### Questão
Qual pacote converte structs para JSON?
- [ ] net/json
- [x] encoding/json
- [ ] fmt/json
- [ ] json/go
> encoding/json oferece Marshal, Unmarshal, Encoder e Decoder.

### Questão
Para que servem as tags de struct como `json:"nome"` nos campos?
- [ ] Documentar o código
- [x] Definir o nome do campo no JSON
- [ ] Validar tipos
- [ ] Tornar o campo privado
> Assim o campo Nome vira "nome" no JSON.

### Questão
Como cada requisição HTTP é processada pelo servidor padrão?
- [ ] Uma por vez, em fila
- [x] Cada uma em sua própria goroutine
- [ ] Num processo separado
- [ ] Apenas no main
> O net/http é concorrente por padrão.

## Testes e ferramentas
### Teoria
Go traz testes na própria ferramenta:
- Arquivos `_test.go` com funções `func TestXxx(t *testing.T)`.
- Rode com `go test ./...`.
- **Table-driven tests**: uma tabela de casos percorrida num laço, o estilo mais comum.
- `httptest` testa handlers HTTP sem subir servidor.
- Benchmarks com `func BenchmarkXxx(b *testing.B)`.

Outras ferramentas: `go vet` (encontra erros suspeitos), `gofmt`, e `go test -race` (detecta condições de corrida).

### Prática
```go
// soma.go
package mat

func Soma(a, b int) int { return a + b }

// soma_test.go
package mat

import "testing"

func TestSoma(t *testing.T) {
	casos := []struct {
		a, b, esperado int
	}{
		{1, 2, 3},
		{-1, 1, 0},
		{0, 0, 0},
	}
	for _, c := range casos {
		if got := Soma(c.a, c.b); got != c.esperado {
			t.Errorf("Soma(%d, %d) = %d; esperado %d", c.a, c.b, got, c.esperado)
		}
	}
}
```

### Questão
Como devem terminar os nomes dos arquivos de teste em Go?
- [ ] .test.go
- [x] _test.go
- [ ] Test.go
- [ ] .spec.go
> A ferramenta go test procura arquivos _test.go.

### Questão
O que são table-driven tests?
- [ ] Testes de banco de dados
- [x] Testes que percorrem uma tabela de casos de entrada e saída esperada
- [ ] Testes visuais
- [ ] Testes de desempenho
> Facilita adicionar novos casos sem repetir código.

### Questão
Qual flag detecta condições de corrida?
- [ ] go test -fast
- [x] go test -race
- [ ] go vet -lock
- [ ] go run -safe
> O detector de corrida aponta acessos concorrentes inseguros.
