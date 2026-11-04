# Fundamentos de Java
> Estrutura de um programa, tipos, operadores e controle de fluxo.

## Projeto
**Caixa eletrônico de terminal:** use `Scanner` para um menu com saldo, depósito, saque e sair. Não permita saque maior que o saldo nem valores negativos. Use `switch`, `while` e `printf` para mostrar o saldo com duas casas decimais.

## Seu primeiro programa
### Teoria
Java é uma linguagem **compilada para bytecode**, que roda na **JVM** (Java Virtual Machine). Por isso o lema "escreva uma vez, rode em qualquer lugar".

Todo código fica dentro de uma **classe**. O ponto de entrada é o método `main`:

```
public static void main(String[] args)
```

Java é **fortemente tipado**: toda variável tem tipo declarado. Instruções terminam com `;` e blocos usam `{ }`. Para compilar e executar: `javac Ola.java` e depois `java Ola`. Desde o Java 11, `java Ola.java` faz os dois.

### Prática
```java
public class Ola {
    public static void main(String[] args) {
        String nome = "Teco";
        int idade = 3;
        System.out.println("Olá, " + nome + "!");
        System.out.printf("%s tem %d anos%n", nome, idade);
    }
}
```

O nome do arquivo deve ser igual ao da classe pública: `Ola.java`.

### Questão
O que é a JVM?
- [ ] Um editor de código
- [x] A máquina virtual que executa o bytecode Java
- [ ] Um banco de dados
- [ ] Um framework web
> O compilador gera bytecode, que a JVM executa em qualquer sistema.

### Questão
Qual a assinatura do método de entrada de um programa Java?
- [ ] void main()
- [x] public static void main(String[] args)
- [ ] function main(args)
- [ ] static int start()
> A JVM procura exatamente esse método para iniciar o programa.

### Questão
Se a classe pública se chama `Calculadora`, qual deve ser o nome do arquivo?
- [ ] calc.java
- [ ] Main.java
- [x] Calculadora.java
- [ ] Calculadora.class
> O arquivo fonte deve ter o mesmo nome da classe pública.

## Tipos e operadores
### Teoria
Tipos **primitivos**: `int` e `long` (inteiros), `double` e `float` (decimais), `boolean`, `char` (um caractere, entre aspas simples). `String` não é primitivo, é uma classe, mas é usada o tempo todo.

Cuidado com a **divisão inteira**: `7 / 2` dá `3`. Para obter `3.5`, um dos lados precisa ser decimal: `7 / 2.0`.

**Casting** converte tipos: `(int) 3.9` dá `3`. Para converter texto em número: `Integer.parseInt("42")`.

Para comparar Strings use `.equals()`, nunca `==` (que compara referências).

### Prática
```java
int a = 7, b = 2;
System.out.println(a / b);          // 3
System.out.println(a / (double) b); // 3.5
System.out.println(a % b);          // 1

String s1 = "gelo";
String s2 = new String("gelo");
System.out.println(s1 == s2);       // false
System.out.println(s1.equals(s2));  // true

int numero = Integer.parseInt("42");
```

### Questão
Quanto vale `7 / 2` em Java com dois `int`?
- [ ] 3.5
- [x] 3
- [ ] 4
- [ ] Erro
> Divisão entre inteiros descarta a parte decimal.

### Questão
Como comparar o conteúdo de duas Strings?
- [ ] s1 == s2
- [x] s1.equals(s2)
- [ ] s1 = s2
- [ ] compare(s1, s2)
> == compara se são o mesmo objeto; equals compara o texto.

### Questão
Qual tipo guarda um único caractere?
- [ ] String
- [x] char
- [ ] byte
- [ ] text
> char guarda um caractere, escrito entre aspas simples: 'A'.

## Controle de fluxo
### Teoria
Decisões: `if / else if / else` e `switch`. O **switch moderno** (Java 14+) usa `->` e pode retornar valores, sem precisar de `break`.

Laços: `for`, `while`, `do-while` (executa pelo menos uma vez) e o **for-each** para percorrer arrays e coleções: `for (String s : lista)`.

Para ler do teclado, use `Scanner`:

```
Scanner entrada = new Scanner(System.in);
int n = entrada.nextInt();
```

### Prática
```java
import java.util.Scanner;

public class Dias {
    public static void main(String[] args) {
        Scanner entrada = new Scanner(System.in);
        System.out.print("Dia (1-7): ");
        int dia = entrada.nextInt();

        String tipo = switch (dia) {
            case 1, 7 -> "Fim de semana";
            case 2, 3, 4, 5, 6 -> "Dia útil";
            default -> "Inválido";
        };
        System.out.println(tipo);

        for (int i = 1; i <= 3; i++) {
            System.out.println("Volta " + i);
        }
    }
}
```

### Questão
Qual laço executa o bloco pelo menos uma vez?
- [ ] for
- [ ] while
- [x] do-while
- [ ] for-each
> do-while testa a condição só depois de executar o bloco.

### Questão
No switch com `->`, é preciso usar `break`?
- [ ] Sim, sempre
- [x] Não, cada caso já termina sozinho
- [ ] Só no default
- [ ] Só com Strings
> A sintaxe de seta não tem "fall-through", então break não é necessário.

### Questão
Qual classe lê dados digitados no terminal?
- [ ] Reader
- [x] Scanner
- [ ] Console.read
- [ ] Input
> Scanner(System.in) lê números e textos da entrada padrão.

# Orientação a objetos em Java
> Classes, encapsulamento, herança, interfaces e coleções.

## Projeto
**Sistema de zoológico:** crie a classe abstrata `Animal` (nome, idade, método abstrato `emitirSom`), as subclasses `Pinguim` e `Leao`, e uma interface `Nadador`. Guarde os animais num `ArrayList`, liste-os e mostre apenas os que nadam. Encapsule os atributos com getters e validação.

## Classes, objetos e encapsulamento
### Teoria
Uma **classe** define atributos e métodos; **objetos** são criados com `new`. O **construtor** tem o nome da classe e inicializa o objeto. `this` referencia o objeto atual.

**Encapsulamento:** atributos `private` e acesso por métodos públicos (**getters e setters**), que podem validar os dados.

Modificadores: `public` (todos), `private` (só a classe), `protected` (pacote e subclasses), padrão (só o pacote).

Membros `static` pertencem à classe, não aos objetos.

### Prática
```java
public class Conta {
    private final String titular;
    private double saldo;

    public Conta(String titular) {
        this.titular = titular;
    }

    public void depositar(double valor) {
        if (valor <= 0) throw new IllegalArgumentException("Valor inválido");
        saldo += valor;
    }

    public double getSaldo() { return saldo; }
    public String getTitular() { return titular; }
}

// uso
Conta c = new Conta("Teco");
c.depositar(50);
System.out.println(c.getSaldo()); // 50.0
```

### Questão
Por que deixar atributos `private`?
- [ ] Para deixar o código mais rápido
- [x] Para controlar o acesso e validar alterações (encapsulamento)
- [ ] Porque Java exige
- [ ] Para que não ocupem memória
> Com getters e setters, a classe protege seu estado de valores inválidos.

### Questão
O que `this.titular = titular` faz no construtor?
- [ ] Cria uma nova variável local
- [x] Atribui o parâmetro ao atributo do objeto
- [ ] Copia a classe
- [ ] Gera erro de compilação
> this.titular é o atributo; titular sozinho é o parâmetro.

### Questão
Um método `static` pertence a quê?
- [ ] A cada objeto
- [x] À classe
- [ ] Ao pacote
- [ ] À JVM
> É chamado pela classe, como Math.max(a, b), sem criar objeto.

## Herança, interfaces e polimorfismo
### Teoria
**Herança** com `extends`: a subclasse herda atributos e métodos da superclasse e pode **sobrescrever** métodos (anote com `@Override`). Java permite herdar de **uma única** classe.

**Classes abstratas** (`abstract`) não podem ser instanciadas e podem ter métodos sem corpo.

**Interfaces** (`interface`) definem contratos; uma classe pode **implementar várias** com `implements`.

**Polimorfismo:** uma variável do tipo da superclasse/interface pode guardar qualquer subtipo, e o método chamado é o do objeto real.

### Prática
```java
interface Nadador { String nadar(); }

abstract class Animal {
    protected String nome;
    Animal(String nome) { this.nome = nome; }
    abstract String emitirSom();
}

class Pinguim extends Animal implements Nadador {
    Pinguim(String nome) { super(nome); }
    @Override String emitirSom() { return nome + ": quá!"; }
    public String nadar() { return nome + " mergulhou"; }
}

Animal a = new Pinguim("Teco");
System.out.println(a.emitirSom());
if (a instanceof Nadador n) System.out.println(n.nadar());
```

### Questão
Quantas classes uma classe Java pode estender diretamente?
- [x] Uma
- [ ] Duas
- [ ] Ilimitadas
- [ ] Nenhuma
> Herança de classe é simples; para múltiplos contratos usamos interfaces.

### Questão
Para que serve `@Override`?
- [ ] Criar um novo método
- [x] Indicar que o método sobrescreve um da superclasse, e o compilador confere
- [ ] Tornar o método estático
- [ ] Ocultar o método
> Se a assinatura não bater com a da superclasse, o compilador avisa.

### Questão
Em `Animal a = new Pinguim("Teco"); a.emitirSom();`, qual implementação é executada?
- [ ] A de Animal
- [x] A de Pinguim
- [ ] Nenhuma, dá erro
- [ ] Ambas
> Polimorfismo: vale o método do objeto real.

## Coleções
### Teoria
O pacote `java.util` traz estruturas prontas, com **generics** para definir o tipo dos elementos:

- `List` (`ArrayList`): lista ordenada, aceita repetidos.
- `Set` (`HashSet`): sem repetidos.
- `Map` (`HashMap`): pares chave-valor.

Coleções guardam objetos: para números use os **wrappers** (`Integer`, `Double`).

A **Stream API** processa coleções de forma declarativa: `filter`, `map`, `sorted`, `collect`.

### Prática
```java
import java.util.*;

List<String> linguagens = new ArrayList<>(List.of("Java", "Python", "Go"));
linguagens.add("Kotlin");

Map<String, Integer> xp = new HashMap<>();
xp.put("teco", 120);
xp.merge("teco", 30, Integer::sum);   // 150

List<String> curtas = linguagens.stream()
    .filter(l -> l.length() <= 4)
    .map(String::toUpperCase)
    .sorted()
    .toList();
System.out.println(curtas); // [GO, JAVA]
```

### Questão
Qual coleção não permite elementos repetidos?
- [ ] ArrayList
- [x] HashSet
- [ ] LinkedList
- [ ] Vector
> Sets garantem unicidade.

### Questão
Por que usamos `List<Integer>` e não `List<int>`?
- [x] Generics só aceitam objetos, então usamos o wrapper Integer
- [ ] int não existe em Java
- [ ] Integer é mais rápido
- [ ] É só uma preferência
> Tipos primitivos não podem ser parâmetros de tipo.

### Questão
Na Stream API, o que `filter` faz?
- [ ] Ordena os elementos
- [x] Mantém apenas os elementos que satisfazem a condição
- [ ] Transforma cada elemento
- [ ] Soma os elementos
> filter seleciona; map transforma; sorted ordena.

# Java profissional
> Exceções, Maven, testes e APIs com Spring Boot.

## Projeto
**API de tarefas com Spring Boot:** crie um projeto no start.spring.io com Spring Web, Data JPA e H2. Implemente CRUD de tarefas (`GET`, `POST`, `PUT`, `DELETE`), valide os dados com Bean Validation e escreva testes com JUnit para o serviço.

## Exceções
### Teoria
Exceções sinalizam erros. Trate-as com `try / catch / finally`.

Java tem dois grupos:
- **Checked** (ex.: `IOException`): o compilador obriga a tratar ou declarar com `throws`.
- **Unchecked** (`RuntimeException` e filhas, como `NullPointerException`, `IllegalArgumentException`): não são obrigatórias, geralmente indicam erro de programação.

**try-with-resources** fecha automaticamente recursos como arquivos e conexões.

Crie exceções próprias estendendo `Exception` ou `RuntimeException`.

### Prática
```java
import java.nio.file.*;
import java.io.IOException;

class SaldoInsuficienteException extends RuntimeException {
    SaldoInsuficienteException(double falta) {
        super("Faltam R$ " + falta);
    }
}

try (var linhas = Files.lines(Path.of("dados.txt"))) {
    linhas.forEach(System.out::println);
} catch (IOException e) {
    System.err.println("Não consegui ler: " + e.getMessage());
} finally {
    System.out.println("Fim da leitura");
}
```

### Questão
O que caracteriza uma exceção checked?
- [ ] Ela nunca acontece
- [x] O compilador obriga a tratá-la ou declará-la com throws
- [ ] Ela é sempre RuntimeException
- [ ] Só ocorre em testes
> Checked exceptions fazem parte da assinatura do método.

### Questão
Qual a vantagem do try-with-resources?
- [ ] Ignora exceções
- [x] Fecha automaticamente os recursos abertos
- [ ] Deixa o código paralelo
- [ ] Converte exceções em avisos
> Recursos que implementam AutoCloseable são fechados ao fim do bloco.

### Questão
`NullPointerException` é de qual grupo?
- [ ] Checked
- [x] Unchecked (RuntimeException)
- [ ] Error de hardware
- [ ] Não é exceção
> Ela estende RuntimeException e costuma indicar um bug.

## Maven e testes com JUnit
### Teoria
**Maven** (ou Gradle) gerencia dependências e o build. O arquivo `pom.xml` descreve o projeto e as bibliotecas. Comandos: `mvn compile`, `mvn test`, `mvn package`.

Estrutura padrão:

```
src/main/java   código
src/test/java   testes
```

**JUnit 5** é o padrão para testes: métodos anotados com `@Test` e verificações como `assertEquals(esperado, atual)` e `assertThrows`. Testes automatizados dão confiança para mudar o código.

### Prática
```java
import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class ContaTest {
    @Test
    void depositoAumentaSaldo() {
        Conta c = new Conta("Teco");
        c.depositar(30);
        assertEquals(30, c.getSaldo());
    }

    @Test
    void depositoNegativoFalha() {
        Conta c = new Conta("Teco");
        assertThrows(IllegalArgumentException.class, () -> c.depositar(-5));
    }
}
```

### Questão
Qual arquivo descreve um projeto Maven?
- [ ] build.gradle
- [x] pom.xml
- [ ] package.json
- [ ] project.java
> O POM (Project Object Model) lista dependências e configurações.

### Questão
Qual anotação marca um método de teste no JUnit 5?
- [ ] @Run
- [x] @Test
- [ ] @Check
- [ ] @Main
> Métodos com @Test são executados pelo runner de testes.

### Questão
Onde ficam os testes num projeto Maven?
- [ ] src/main/java
- [x] src/test/java
- [ ] test/
- [ ] resources/
> O Maven separa código de produção e de teste.

## APIs REST com Spring Boot
### Teoria
**Spring Boot** é o framework mais usado para back-end em Java. Ele configura quase tudo sozinho e sobe um servidor embutido.

Peças principais:
- `@RestController`: classe que responde a requisições HTTP.
- `@GetMapping`, `@PostMapping`...: mapeiam rotas.
- `@RequestBody`: converte o JSON recebido em objeto.
- **Injeção de dependência:** o Spring cria e entrega os objetos (como serviços e repositórios) pelo construtor.
- **Spring Data JPA:** repositórios que geram as consultas ao banco.

### Prática
```java
@RestController
@RequestMapping("/tarefas")
public class TarefaController {
    private final TarefaRepository repositorio;

    public TarefaController(TarefaRepository repositorio) {
        this.repositorio = repositorio;
    }

    @GetMapping
    public List<Tarefa> listar() {
        return repositorio.findAll();
    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public Tarefa criar(@RequestBody Tarefa tarefa) {
        return repositorio.save(tarefa);
    }
}

interface TarefaRepository extends JpaRepository<Tarefa, Long> {}
```

### Questão
O que `@RestController` indica?
- [ ] Uma classe de testes
- [x] Uma classe que trata requisições HTTP e devolve dados (JSON)
- [ ] Uma entidade do banco
- [ ] Um arquivo de configuração
> Os métodos de um RestController retornam o corpo da resposta.

### Questão
Como o controller recebe o `TarefaRepository`?
- [ ] Com new TarefaRepository()
- [x] Por injeção de dependência no construtor
- [ ] Por uma variável global
- [ ] Lendo de um arquivo
> O Spring cria o repositório e o injeta automaticamente.

### Questão
Para que serve `@RequestBody`?
- [ ] Ler parâmetros da URL
- [x] Converter o corpo JSON da requisição num objeto Java
- [ ] Definir o status da resposta
- [ ] Criar a tabela no banco
> O JSON enviado pelo cliente vira um objeto Tarefa.
