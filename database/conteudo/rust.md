# Fundamentos de Rust
> Cargo, variáveis, tipos, controle de fluxo e funções.

## Projeto
**Jogo de adivinhação:** com `cargo new adivinha`, adicione a crate `rand`, sorteie um número de 1 a 100, leia palpites com `std::io::stdin`, converta com `parse` tratando o `Result` e use `match` com `Ordering` para dizer "maior", "menor" ou "acertou".

## Olá, Rust
### Teoria
Rust é uma linguagem de sistemas com **desempenho de C/C++** e **segurança de memória garantida pelo compilador**, sem coletor de lixo. É usada em navegadores, sistemas operacionais, ferramentas de linha de comando, WebAssembly e infraestrutura de nuvem.

**Cargo** é o gerenciador de projetos e pacotes (crates):

```
cargo new meu_app
cargo run
cargo build --release
```

`println!` é uma **macro** (o `!` indica isso) que imprime com formatação: `{}` mostra valores e `{:?}` mostra a forma de depuração.

### Prática
```rust
fn main() {
    let nome = "Teco";
    let peixes = 3;
    println!("Olá, {nome}!");
    println!("{} tem {} peixes", nome, peixes);
    println!("Debug de um vetor: {:?}", vec![1, 2, 3]);
}
```

### Questão
O que o `!` em `println!` indica?
- [ ] Que a função é perigosa
- [x] Que é uma macro
- [ ] Que retorna booleano
- [ ] Que é assíncrona
> Macros geram código em tempo de compilação.

### Questão
Qual ferramenta cria, compila e executa projetos Rust?
- [ ] npm
- [x] cargo
- [ ] rustpm
- [ ] make
> cargo new, cargo run, cargo test e cargo build são o dia a dia.

### Questão
Como Rust garante segurança de memória?
- [ ] Com coletor de lixo
- [x] Com regras verificadas pelo compilador (ownership e borrowing)
- [ ] Rodando numa máquina virtual
- [ ] Não garante
> As regras de posse eliminam erros como uso após liberação, sem custo em execução.

## Variáveis, mutabilidade e tipos
### Teoria
Em Rust, variáveis são **imutáveis por padrão**: `let x = 5;`. Para permitir mudanças, use `let mut x = 5;`.

**Shadowing**: declarar de novo com `let` cria uma nova variável com o mesmo nome (pode até mudar o tipo).

Tipos escalares: inteiros (`i32`, `u8`, `i64`, `usize`...), `f64`, `bool`, `char`. Compostos: **tuplas** `(i32, f64)` e **arrays** de tamanho fixo `[i32; 3]`.

Não há conversão implícita: use `as` (`x as f64`) ou métodos como `parse`.

### Prática
```rust
fn main() {
    let mut peixes = 3;
    peixes += 2;

    let entrada = "42";
    let entrada: i32 = entrada.trim().parse().expect("não é número");  // shadowing

    let ponto: (i32, f64) = (10, 2.5);
    let (x, y) = ponto;
    let notas = [8, 9, 7];

    println!("{peixes} {entrada} {x} {y} {}", notas.len());
    println!("{}", peixes as f64 / 2.0);
}
```

### Questão
O que acontece em `let x = 5; x = 6;`?
- [ ] x passa a valer 6
- [x] Erro de compilação: x é imutável
- [ ] Aviso, mas compila
- [ ] x vira uma constante
> Para reatribuir, declare com let mut.

### Questão
O que é shadowing?
- [ ] Copiar uma variável para outra thread
- [x] Declarar uma nova variável com o mesmo nome, ocultando a anterior
- [ ] Apagar uma variável
- [ ] Tornar uma variável global
> let entrada: i32 = ... cria uma nova variável que substitui a anterior.

### Questão
Qual tipo representa um inteiro de 32 bits com sinal?
- [ ] u32
- [x] i32
- [ ] int
- [ ] f32
> i = com sinal, u = sem sinal, f = ponto flutuante.

## Controle de fluxo e funções
### Teoria
`if` é uma **expressão** e não usa parênteses: `let tipo = if n > 0 { "positivo" } else { "outro" };`.

Laços: `loop` (infinito, pode retornar valor com `break valor`), `while` e `for` sobre intervalos e coleções: `for i in 1..=5`.

Funções usam `fn`, tipos obrigatórios nos parâmetros e `-> Tipo` no retorno. A **última expressão sem ponto e vírgula** é o valor retornado.

`match` compara um valor com padrões e precisa cobrir **todos os casos**.

### Prática
```rust
fn classificar(nota: u8) -> &'static str {
    match nota {
        9..=10 => "excelente",
        7..=8 => "aprovado",
        0..=6 => "recuperação",
        _ => "nota inválida",
    }
}

fn dobro(n: i32) -> i32 {
    n * 2          // sem ; é o retorno
}

fn main() {
    for i in 1..=3 {
        println!("{i}: {}", classificar(i * 3));
    }
    let mut tentativas = 0;
    let resultado = loop {
        tentativas += 1;
        if tentativas == 4 { break dobro(tentativas); }
    };
    println!("{resultado}"); // 8
}
```

### Questão
Como uma função Rust retorna um valor sem usar `return`?
- [ ] Não é possível
- [x] Deixando a última expressão do corpo sem ponto e vírgula
- [ ] Usando yield
- [ ] Imprimindo o valor
> A última expressão é o valor do bloco.

### Questão
Quais números `1..=3` produz?
- [ ] 1 e 2
- [x] 1, 2 e 3
- [ ] 0, 1, 2 e 3
- [ ] Só 3
> ..= inclui o limite superior; .. o exclui.

### Questão
O que o compilador exige de um `match`?
- [ ] Que tenha no máximo 3 casos
- [x] Que cubra todos os valores possíveis
- [ ] Que use só números
- [ ] Nada
> O _ cobre "todo o resto" quando necessário.

# Ownership e tipos
> Posse, empréstimos, structs, enums e tratamento de erros.

## Projeto
**Biblioteca de livros:** crie `struct Livro` e `enum Status { Disponivel, Emprestado(String) }`, uma `Vec<Livro>` e funções que recebem referências (`&` e `&mut`) para emprestar e devolver. Retorne `Result<(), String>` para erros como "livro já emprestado" e trate-os com `match` e `?`.

## Ownership
### Teoria
O **ownership** (posse) é o coração de Rust. Três regras:
- Cada valor tem **um único dono**.
- Só pode haver um dono por vez.
- Quando o dono sai de escopo, o valor é **liberado** automaticamente.

Atribuir ou passar um `String` para uma função **move** a posse: a variável original deixa de ser válida. Tipos simples como inteiros implementam `Copy` e são copiados.

Para duplicar dados de verdade, use `.clone()` (explícito e com custo).

### Prática
```rust
fn mostrar(texto: String) {
    println!("{texto}");
}   // texto é liberado aqui

fn main() {
    let a = String::from("gelo");
    let b = a;              // posse movida para b
    // println!("{a}");     // erro: a não é mais válido
    mostrar(b.clone());     // passa uma cópia
    mostrar(b);             // move b para a função
    // println!("{b}");     // erro: b foi movido

    let x = 5;
    let y = x;              // i32 é Copy
    println!("{x} {y}");    // ok
}
```

### Questão
O que acontece com `a` após `let b = a;` quando `a` é um `String`?
- [ ] a e b apontam para o mesmo texto e ambos são válidos
- [x] A posse é movida para b e a deixa de ser válida
- [ ] O texto é copiado automaticamente
- [ ] Erro de compilação na atribuição
> String não é Copy, então a atribuição move.

### Questão
Quando a memória de um valor é liberada em Rust?
- [ ] Quando o coletor de lixo decide
- [x] Quando o dono sai de escopo
- [ ] Só ao chamar free()
- [ ] Ao fim do programa
> A liberação é determinística e automática.

### Questão
Por que `let y = x;` não invalida `x` quando é um `i32`?
- [ ] Por ser constante
- [x] Porque i32 implementa Copy e é copiado
- [ ] Porque é global
- [ ] Porque está na heap
> Tipos pequenos de tamanho fixo são copiados.

## Borrowing e referências
### Teoria
Em vez de mover, podemos **emprestar** com referências:
- `&valor`: referência imutável (só leitura).
- `&mut valor`: referência mutável.

Regras do **borrow checker**:
- Várias referências imutáveis **ou** uma única mutável, nunca as duas ao mesmo tempo.
- Referências nunca podem viver mais que o valor (sem ponteiros pendurados).

Essas regras evitam **condições de corrida** e bugs de memória em tempo de compilação. Para textos, prefira receber `&str` (fatia de string) em vez de `&String`.

### Prática
```rust
fn tamanho(texto: &str) -> usize {
    texto.len()
}

fn adicionar_emoji(texto: &mut String) {
    texto.push_str(" 🐧");
}

fn main() {
    let mut nome = String::from("Teco");
    let t = tamanho(&nome);          // empresta para leitura
    adicionar_emoji(&mut nome);      // empresta para alteração
    println!("{nome} ({t} letras)");

    let r1 = &nome;
    let r2 = &nome;                  // várias leituras: ok
    println!("{r1} {r2}");
}
```

### Questão
Quantas referências mutáveis a um mesmo valor podem existir ao mesmo tempo?
- [x] Uma
- [ ] Duas
- [ ] Ilimitadas
- [ ] Nenhuma, Rust não permite mutação
> Uma &mut exclusiva ou várias & imutáveis, nunca misturadas.

### Questão
O que `&mut nome` permite à função?
- [ ] Tomar posse de nome
- [x] Alterar nome sem tomar sua posse
- [ ] Apenas ler nome
- [ ] Copiar nome
> É um empréstimo mutável: o dono continua sendo o main.

### Questão
Qual tipo de parâmetro é mais flexível para receber texto somente leitura?
- [ ] String
- [ ] &String
- [x] &str
- [ ] char
> &str aceita tanto literais quanto referências a String.

## Structs, enums e Result
### Teoria
**Structs** agrupam dados; métodos ficam em blocos `impl`. `&self` lê, `&mut self` altera.

**Enums** em Rust podem carregar dados em cada variante, e são usados com `match`.

Dois enums essenciais:
- `Option<T>`: `Some(valor)` ou `None` (Rust não tem null).
- `Result<T, E>`: `Ok(valor)` ou `Err(erro)` para operações que podem falhar.

O operador `?` propaga o erro automaticamente: se for `Err`, a função retorna esse erro.

### Prática
```rust
#[derive(Debug)]
enum Status { Disponivel, Emprestado(String) }

struct Livro { titulo: String, status: Status }

impl Livro {
    fn emprestar(&mut self, para: &str) -> Result<(), String> {
        match &self.status {
            Status::Disponivel => {
                self.status = Status::Emprestado(para.to_string());
                Ok(())
            }
            Status::Emprestado(quem) => Err(format!("já está com {quem}")),
        }
    }
}

fn ler_numero(texto: &str) -> Result<i32, std::num::ParseIntError> {
    let n = texto.trim().parse::<i32>()?;   // ? propaga o erro
    Ok(n * 2)
}

fn main() {
    let mut livro = Livro { titulo: "Rust Book".into(), status: Status::Disponivel };
    println!("{:?}", livro.emprestar("Teco"));
    println!("{:?}", livro.emprestar("Pipo"));
    println!("{} -> {:?} {:?}", livro.titulo, ler_numero("21"), livro.status);
}
```

### Questão
Como Rust representa a ausência de valor?
- [ ] null
- [ ] nil
- [x] Option com a variante None
- [ ] undefined
> Option obriga a tratar o caso sem valor.

### Questão
O que o operador `?` faz numa expressão que retorna `Result`?
- [ ] Ignora o erro
- [x] Se for Err, retorna o erro da função atual; se for Ok, extrai o valor
- [ ] Converte em Option
- [ ] Encerra o programa
> É a forma concisa de propagar erros.

### Questão
Num método, o que `&mut self` indica?
- [ ] Que o método é estático
- [x] Que o método pode alterar o objeto
- [ ] Que o método toma posse do objeto
- [ ] Que é um construtor
> &self lê; &mut self altera; self consome.

# Rust avançado
> Traits, generics, coleções, iteradores e concorrência.

## Projeto
**CLI de tarefas:** crie uma ferramenta de linha de comando com a crate `clap` (`tarefas add "estudar"`, `tarefas list`, `tarefas done 1`), salvando em JSON com `serde`. Defina um trait `Armazenamento` com implementações em arquivo e em memória (para testes), e escreva testes com `cargo test`.

## Traits e generics
### Teoria
**Traits** definem comportamentos compartilhados, como interfaces: `trait Som { fn som(&self) -> String; }`. Implemente com `impl Som for Pinguim`.

Traits podem ter métodos com implementação padrão. Muitos traits comuns podem ser derivados automaticamente: `#[derive(Debug, Clone, PartialEq)]`.

**Generics** com **trait bounds** escrevem código para qualquer tipo que implemente um trait: `fn falar<T: Som>(a: &T)` ou `fn falar(a: &impl Som)`.

Para coleções de tipos diferentes, use **trait objects**: `Vec<Box<dyn Som>>`.

### Prática
```rust
trait Som {
    fn som(&self) -> String;
    fn cumprimentar(&self) -> String {
        format!("Olá! {}", self.som())
    }
}

struct Pinguim;
struct Foca;

impl Som for Pinguim { fn som(&self) -> String { "quá".into() } }
impl Som for Foca { fn som(&self) -> String { "arf".into() } }

fn falar_duas_vezes(animal: &impl Som) -> String {
    format!("{0} {0}", animal.som())
}

fn main() {
    let zoo: Vec<Box<dyn Som>> = vec![Box::new(Pinguim), Box::new(Foca)];
    for animal in &zoo {
        println!("{}", animal.cumprimentar());
    }
    println!("{}", falar_duas_vezes(&Pinguim));
}
```

### Questão
O que é um trait em Rust?
- [ ] Um tipo de variável
- [x] Um conjunto de comportamentos (métodos) que tipos podem implementar
- [ ] Uma macro
- [ ] Um módulo de testes
> Traits são semelhantes a interfaces de outras linguagens.

### Questão
O que `#[derive(Debug)]` faz?
- [ ] Ativa o modo de depuração do compilador
- [x] Implementa automaticamente o trait Debug, permitindo imprimir com {:?}
- [ ] Remove o código em produção
- [ ] Cria testes
> derive gera implementações padrão de traits comuns.

### Questão
Para que serve `Box<dyn Som>`?
- [ ] Para copiar valores
- [x] Para guardar tipos diferentes que implementam Som numa mesma coleção
- [ ] Para criar threads
- [ ] Para converter em string
> Trait objects usam despacho dinâmico.

## Coleções e iteradores
### Teoria
Coleções da biblioteca padrão: `Vec<T>`, `HashMap<K, V>`, `HashSet<T>`, `String`.

**Iteradores** são preguiçosos e encadeáveis: `iter()`, `map`, `filter`, `enumerate`, `sum`, `count`, e `collect()` para materializar o resultado. São tão rápidos quanto laços escritos à mão (abstração de custo zero).

**Closures** são funções anônimas: `|x| x * 2`. Podem capturar variáveis do ambiente.

### Prática
```rust
use std::collections::HashMap;

fn main() {
    let notas = vec![9.5, 5.0, 7.0, 8.0];
    let aprovadas: Vec<f64> = notas.iter().copied().filter(|n| *n >= 7.0).collect();
    let media: f64 = notas.iter().sum::<f64>() / notas.len() as f64;
    println!("{aprovadas:?} média {media:.2}");

    let texto = "gelo peixe gelo iglu gelo";
    let mut contagem: HashMap<&str, usize> = HashMap::new();
    for palavra in texto.split_whitespace() {
        *contagem.entry(palavra).or_insert(0) += 1;
    }
    println!("{:?}", contagem.get("gelo")); // Some(3)
}
```

### Questão
O que `collect()` faz num iterador?
- [ ] Apaga os elementos
- [x] Consome o iterador e constrói uma coleção com os resultados
- [ ] Ordena os elementos
- [ ] Conta os elementos
> Iteradores são preguiçosos; collect os executa e materializa.

### Questão
O que `|x| x * 2` representa?
- [ ] Um valor absoluto
- [x] Uma closure (função anônima)
- [ ] Um operador bit a bit
- [ ] Um comentário
> Closures são definidas com parâmetros entre barras.

### Questão
O que `entry(palavra).or_insert(0)` faz num HashMap?
- [ ] Remove a palavra
- [x] Obtém o valor da chave ou insere 0 se ela não existir
- [ ] Ordena o mapa
- [ ] Gera erro se a chave não existir
> A API entry facilita contagens e atualizações.

## Concorrência sem medo
### Teoria
Rust promete **"concorrência sem medo"**: o compilador impede condições de corrida.

- `std::thread::spawn` cria threads; `join` espera o término.
- `move` em closures transfere a posse dos dados para a thread.
- Para compartilhar dados entre threads: `Arc<T>` (contador de referências atômico) e `Mutex<T>` (exclusão mútua).
- **Channels** (`std::sync::mpsc`) enviam mensagens entre threads.

Para I/O assíncrono em larga escala, o ecossistema usa `async/await` com o runtime **Tokio**.

### Prática
```rust
use std::sync::{Arc, Mutex};
use std::thread;

fn main() {
    let peixes = Arc::new(Mutex::new(0));
    let mut handles = vec![];

    for _ in 0..4 {
        let peixes = Arc::clone(&peixes);
        handles.push(thread::spawn(move || {
            let mut total = peixes.lock().unwrap();
            *total += 10;
        }));
    }
    for h in handles {
        h.join().unwrap();
    }
    println!("Total pescado: {}", *peixes.lock().unwrap()); // 40
}
```

### Questão
Para que serve `Arc<T>`?
- [ ] Desenhar arcos
- [x] Compartilhar a posse de um valor entre várias threads com segurança
- [ ] Tornar um valor mutável
- [ ] Criar channels
> Arc é um contador de referências atômico.

### Questão
Por que usamos `move` na closure passada a `thread::spawn`?
- [ ] Para deixar a thread mais rápida
- [x] Para transferir a posse dos dados capturados para a thread
- [ ] Para mover a thread para outro núcleo
- [ ] Não tem efeito
> A thread pode viver mais que o escopo atual, então precisa possuir seus dados.

### Questão
Qual runtime é o mais usado para async em Rust?
- [ ] Node
- [x] Tokio
- [ ] Gevent
- [ ] Netty
> Tokio fornece executor, I/O assíncrono e utilitários.
