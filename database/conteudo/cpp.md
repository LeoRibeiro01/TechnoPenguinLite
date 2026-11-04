# Fundamentos de C++
> Sintaxe, tipos, entrada e saída, controle de fluxo e funções.

## Projeto
**Jogo da forca no terminal:** sorteie uma palavra de um `std::vector<std::string>`, mostre as letras descobertas com `_`, leia palpites com `std::cin` e conte os erros. Separe a lógica em funções (`mostrarPalavra`, `atualizar`, `venceu`).

## Olá, C++
### Teoria
C++ nasceu como "C com classes" e hoje é uma linguagem multiparadigma usada em jogos, motores gráficos, navegadores, sistemas financeiros e embarcados. É **compilada** para código nativo e oferece alto desempenho com abstrações modernas.

Compile com `g++ -std=c++20 -Wall main.cpp -o main`.

A biblioteca padrão fica no namespace `std`: `std::cout` para saída, `std::cin` para entrada e `std::string` para textos (muito mais seguro que `char[]`).

### Prática
```cpp
#include <iostream>
#include <string>

int main() {
    std::string nome;
    std::cout << "Seu nome: ";
    std::getline(std::cin, nome);
    std::cout << "Olá, " << nome << "! Seu nome tem "
              << nome.size() << " letras.\n";
    return 0;
}
```

### Questão
Qual objeto escreve na saída padrão em C++?
- [ ] printf
- [x] std::cout
- [ ] std::print
- [ ] System.out
> std::cout com o operador << envia dados para a saída.

### Questão
Qual a vantagem de `std::string` sobre `char[]`?
- [ ] Ocupa menos memória sempre
- [x] Gerencia o tamanho e a memória automaticamente
- [ ] É mais antiga
- [ ] Não tem vantagem
> std::string cresce sozinha e evita estouros de buffer comuns em C.

### Questão
O que `std::` indica em `std::cout`?
- [ ] Um ponteiro
- [x] Que cout pertence ao namespace std da biblioteca padrão
- [ ] Uma classe abstrata
- [ ] Um comentário
> Namespaces evitam conflitos de nomes.

## Tipos, auto e referências
### Teoria
C++ herda os tipos de C (`int`, `double`, `char`, `bool`) e adiciona:

- `auto`: o compilador deduz o tipo a partir do valor.
- **Referências** (`int& r = x;`): um apelido para outra variável. Alterar `r` altera `x`.
- `const`: valores que não podem mudar. Use `const T&` para passar objetos grandes sem copiar e sem permitir alteração.

**Inicialização com chaves** (`int x{5};`) evita conversões perigosas, pois o compilador recusa perda de dados.

### Prática
```cpp
#include <iostream>
#include <string>

void dobrar(int& n) { n *= 2; }

void mostrar(const std::string& texto) {
    std::cout << texto << "\n";
}

int main() {
    auto peixes = 4;          // int
    auto nome = std::string{"Teco"};
    dobrar(peixes);
    mostrar(nome + " tem " + std::to_string(peixes) + " peixes");
    // int x{3.7};            // erro: conversão com perda
}
```

### Questão
O que `void dobrar(int& n)` permite?
- [ ] Receber uma cópia de n
- [x] Alterar a variável original passada como argumento
- [ ] Receber apenas constantes
- [ ] Retornar dois valores
> Parâmetro por referência é um apelido da variável original.

### Questão
Qual o tipo deduzido em `auto x = 3.5;`?
- [ ] int
- [ ] float
- [x] double
- [ ] auto
> Literais decimais são double por padrão.

### Questão
Por que usar `const std::string&` como parâmetro?
- [x] Para evitar cópia e impedir alterações
- [ ] Para alterar a string original
- [ ] Porque strings não podem ser copiadas
- [ ] Para converter em char*
> Passar por referência constante é eficiente e seguro.

## Controle de fluxo e funções
### Teoria
Decisões e laços são como em C: `if`, `switch`, `for`, `while`, `do-while`. C++ adiciona o **range-based for**: `for (const auto& item : colecao)`.

Funções podem ter **sobrecarga** (mesmo nome, parâmetros diferentes) e **parâmetros com valor padrão**.

Declare antes de usar: ou defina a função acima de `main`, ou escreva o **protótipo** antes.

### Prática
```cpp
#include <iostream>
#include <vector>

double area(double raio) { return 3.14159 * raio * raio; }
double area(double base, double altura) { return base * altura; }

void saudar(const std::string& nome = "pinguim") {
    std::cout << "Oi, " << nome << "\n";
}

int main() {
    std::vector<int> notas{8, 6, 9};
    int soma = 0;
    for (int n : notas) soma += n;
    std::cout << area(2) << " " << area(2, 3) << " " << soma << "\n";
    saudar();
}
```

### Questão
O que é sobrecarga de funções?
- [ ] Uma função muito longa
- [x] Várias funções com o mesmo nome e parâmetros diferentes
- [ ] Chamar uma função muitas vezes
- [ ] Uma função recursiva
> O compilador escolhe a versão de acordo com os argumentos.

### Questão
O que `for (int n : notas)` faz?
- [ ] Percorre os índices
- [x] Percorre cada valor do vector
- [ ] Ordena o vector
- [ ] Soma os valores automaticamente
> É o for baseado em intervalo.

### Questão
Em `saudar()`, sem argumentos, qual nome é usado?
- [ ] Nenhum, dá erro
- [x] "pinguim"
- [ ] ""
- [ ] nullptr
> O valor padrão é usado quando o argumento é omitido.

# Orientação a objetos e STL
> Classes, RAII, herança e a biblioteca padrão de contêineres e algoritmos.

## Projeto
**Inventário de jogo:** crie a classe `Item` (nome, peso, valor) e a classe `Inventario` com limite de peso, usando `std::vector` e `std::map` para contar itens por tipo. Ordene por valor com `std::sort` e encontre itens com `std::find_if`.

## Classes e construtores
### Teoria
Uma **classe** junta dados e funções. Membros são `private` por padrão em `class` e `public` em `struct`.

- **Construtor:** inicializa o objeto; prefira a **lista de inicialização** (`: nome(n)`).
- **Destrutor** (`~Classe()`): roda quando o objeto é destruído.
- **Métodos const** (`int idade() const`) prometem não alterar o objeto.

Esse ciclo construtor/destrutor é a base do **RAII**: o recurso é adquirido no construtor e liberado no destrutor, automaticamente.

### Prática
```cpp
#include <iostream>
#include <string>

class Pinguim {
public:
    Pinguim(std::string nome, int idade) : nome_(std::move(nome)), idade_(idade) {}
    ~Pinguim() { std::cout << nome_ << " foi dormir\n"; }

    void aniversario() { ++idade_; }
    int idade() const { return idade_; }
    const std::string& nome() const { return nome_; }

private:
    std::string nome_;
    int idade_;
};

int main() {
    Pinguim teco{"Teco", 3};
    teco.aniversario();
    std::cout << teco.nome() << " tem " << teco.idade() << "\n";
}   // destrutor chamado aqui
```

### Questão
Quando o destrutor de `teco` é chamado?
- [ ] Nunca
- [x] Quando o objeto sai de escopo, no fim de main
- [ ] Quando o programa compila
- [ ] Só se chamarmos delete
> Objetos na pilha são destruídos automaticamente ao sair do escopo.

### Questão
O que um método marcado com `const` garante?
- [ ] Que retorna uma constante
- [x] Que não altera o estado do objeto
- [ ] Que é estático
- [ ] Que é mais rápido
> Métodos const podem ser chamados em objetos constantes.

### Questão
O que é RAII?
- [ ] Uma biblioteca gráfica
- [x] Ligar a vida de um recurso à vida de um objeto: adquire no construtor, libera no destrutor
- [ ] Um tipo de ponteiro
- [ ] Um padrão de nomes
> RAII é a técnica central de gerenciamento de recursos em C++.

## Herança e polimorfismo
### Teoria
**Herança:** `class Imperador : public Pinguim`. Para polimorfismo, o método da base deve ser `virtual` e o da derivada marcado com `override`.

Use ponteiros ou referências para a base: `Animal& a = pinguim; a.som();` chama a versão do objeto real.

Uma função **virtual pura** (`= 0`) torna a classe **abstrata**. Classes base polimórficas devem ter **destrutor virtual**.

### Prática
```cpp
#include <iostream>
#include <memory>
#include <vector>

class Animal {
public:
    virtual ~Animal() = default;
    virtual std::string som() const = 0;
};

class Pinguim : public Animal {
public:
    std::string som() const override { return "quá"; }
};

class Foca : public Animal {
public:
    std::string som() const override { return "arf"; }
};

int main() {
    std::vector<std::unique_ptr<Animal>> zoo;
    zoo.push_back(std::make_unique<Pinguim>());
    zoo.push_back(std::make_unique<Foca>());
    for (const auto& a : zoo) std::cout << a->som() << "\n";
}
```

### Questão
O que torna uma classe abstrata em C++?
- [ ] A palavra abstract
- [x] Ter pelo menos uma função virtual pura (= 0)
- [ ] Não ter construtor
- [ ] Ter atributos privados
> Classes com funções virtuais puras não podem ser instanciadas.

### Questão
Para que serve `override`?
- [ ] Criar um novo método
- [x] Indicar que o método sobrescreve um virtual da base, com checagem do compilador
- [ ] Tornar o método privado
- [ ] Impedir herança
> Se a assinatura não corresponder, o compilador acusa erro.

### Questão
Por que a base polimórfica deve ter destrutor virtual?
- [ ] Por estilo
- [x] Para destruir corretamente o objeto derivado através de um ponteiro para a base
- [ ] Para evitar herança
- [ ] Para o programa compilar mais rápido
> Sem destrutor virtual, só a parte da base seria destruída.

## STL: contêineres e algoritmos
### Teoria
A **Standard Template Library** traz estruturas e algoritmos genéricos:

- `std::vector`: array dinâmico (a escolha padrão).
- `std::map` / `std::unordered_map`: chave-valor (ordenado / por hash).
- `std::set`: elementos únicos.

Algoritmos em `<algorithm>` trabalham com **iteradores** (`begin()`, `end()`): `std::sort`, `std::find`, `std::find_if`, `std::count_if`, `std::accumulate` (em `<numeric>`). **Lambdas** (`[](int x) { return x > 5; }`) definem critérios na hora.

### Prática
```cpp
#include <algorithm>
#include <iostream>
#include <map>
#include <numeric>
#include <vector>

int main() {
    std::vector<int> xp{120, 40, 300, 75};
    std::sort(xp.begin(), xp.end(), std::greater<>());
    int total = std::accumulate(xp.begin(), xp.end(), 0);
    auto altos = std::count_if(xp.begin(), xp.end(), [](int v) { return v > 100; });

    std::map<std::string, int> estoque{{"sardinha", 10}};
    estoque["atum"] += 3;

    std::cout << xp.front() << " " << total << " " << altos << " " << estoque["atum"] << "\n";
}
```

### Questão
Qual contêiner é a escolha padrão para uma sequência de elementos?
- [ ] std::list
- [x] std::vector
- [ ] std::map
- [ ] std::array obrigatório
> vector tem memória contígua e ótimo desempenho na maioria dos casos.

### Questão
O que `[](int v) { return v > 100; }` é?
- [ ] Um array
- [x] Uma lambda (função anônima)
- [ ] Um template
- [ ] Uma macro
> Lambdas são funções definidas no local onde são usadas.

### Questão
Qual a diferença entre `std::map` e `std::unordered_map`?
- [ ] Nenhuma
- [x] map mantém as chaves ordenadas; unordered_map usa hash e não tem ordem
- [ ] unordered_map não aceita strings
- [ ] map não permite busca
> unordered_map costuma ser mais rápido para busca; map mantém a ordem.

# C++ moderno
> Smart pointers, templates, move semantics e build.

## Projeto
**Biblioteca de matrizes genérica:** crie `Matriz<T>` com template, operadores sobrecarregados (`+`, `*`, `<<`), construtor de movimento e testes simples com `assert`. Configure a compilação com CMake.

## Smart pointers
### Teoria
Em C++ moderno, evite `new` e `delete` manuais. Use **smart pointers** de `<memory>`, que liberam a memória automaticamente (RAII):

- `std::unique_ptr`: dono único; não pode ser copiado, só movido. Crie com `std::make_unique`.
- `std::shared_ptr`: posse compartilhada com contagem de referências. Crie com `std::make_shared`.
- `std::weak_ptr`: observa um shared_ptr sem aumentar a contagem (quebra ciclos).

Regra prática: comece com `unique_ptr`; use `shared_ptr` só quando a posse for realmente compartilhada.

### Prática
```cpp
#include <iostream>
#include <memory>

struct Iglu {
    Iglu() { std::cout << "Iglu construído\n"; }
    ~Iglu() { std::cout << "Iglu derreteu\n"; }
};

int main() {
    auto meu = std::make_unique<Iglu>();
    auto outro = std::move(meu);           // posse transferida
    std::cout << (meu == nullptr) << "\n"; // 1

    auto compartilhado = std::make_shared<Iglu>();
    {
        auto copia = compartilhado;
        std::cout << compartilhado.use_count() << "\n"; // 2
    }
}
```

### Questão
Qual smart pointer representa posse exclusiva?
- [x] std::unique_ptr
- [ ] std::shared_ptr
- [ ] std::weak_ptr
- [ ] std::auto_ptr
> unique_ptr tem um único dono e não pode ser copiado.

### Questão
O que acontece com `meu` após `auto outro = std::move(meu);`?
- [ ] Continua apontando para o Iglu
- [x] Fica vazio (nullptr), pois a posse foi transferida
- [ ] O Iglu é destruído
- [ ] Dá erro de compilação
> Mover transfere a posse; o unique_ptr de origem fica nulo.

### Questão
Para que serve `std::weak_ptr`?
- [ ] Deixar ponteiros mais rápidos
- [x] Observar um objeto de um shared_ptr sem aumentar a contagem, evitando ciclos
- [ ] Substituir referências
- [ ] Criar objetos na pilha
> Ciclos de shared_ptr nunca liberam memória; weak_ptr resolve isso.

## Templates
### Teoria
**Templates** escrevem código genérico que o compilador especializa para cada tipo usado:

```
template <typename T>
T maior(T a, T b) { return a > b ? a : b; }
```

Funcionam para funções e classes (a própria STL é feita de templates). Em C++20, **concepts** restringem os tipos aceitos com mensagens de erro claras: `template <std::integral T>`.

O código de templates normalmente fica em arquivos de cabeçalho, pois o compilador precisa vê-lo para gerar cada versão.

### Prática
```cpp
#include <concepts>
#include <iostream>
#include <string>

template <typename T>
class Caixa {
public:
    explicit Caixa(T valor) : valor_(std::move(valor)) {}
    const T& valor() const { return valor_; }
private:
    T valor_;
};

template <std::integral T>
T somarAte(T n) { return n * (n + 1) / 2; }

int main() {
    Caixa<std::string> c{"peixe"};
    Caixa<int> d{42};
    std::cout << c.valor() << " " << d.valor() << " " << somarAte(10) << "\n";
    // somarAte(2.5); // erro: double não é integral
}
```

### Questão
O que um template permite?
- [ ] Executar código em paralelo
- [x] Escrever código genérico que funciona com vários tipos
- [ ] Criar interfaces gráficas
- [ ] Gerenciar memória automaticamente
> O compilador gera uma versão para cada tipo utilizado.

### Questão
Para que servem os concepts do C++20?
- [ ] Criar classes abstratas
- [x] Restringir os tipos aceitos por um template
- [ ] Substituir namespaces
- [ ] Definir macros
> Concepts deixam claros os requisitos e melhoram as mensagens de erro.

### Questão
Por que templates costumam ficar em arquivos de cabeçalho?
- [ ] Por convenção estética
- [x] Porque o compilador precisa ver a definição para gerar cada especialização
- [ ] Porque .cpp não aceita templates
- [ ] Para compilar mais rápido
> A instanciação acontece onde o template é usado.

## Move semantics e build com CMake
### Teoria
**Move semantics** (C++11) evita cópias caras: em vez de copiar um vetor enorme, o objeto "rouba" os recursos de um temporário. `std::move` converte um objeto em algo que pode ser movido. Contêineres da STL e smart pointers já sabem mover.

**Regra do zero:** se sua classe só usa membros que se gerenciam sozinhos (string, vector, unique_ptr), não escreva destrutor, cópia nem movimento: os padrões gerados já fazem o certo.

Para compilar projetos reais, use o **CMake**, que gera os arquivos de build para qualquer plataforma.

### Prática
```cpp
#include <iostream>
#include <string>
#include <vector>

int main() {
    std::vector<std::string> nomes;
    std::string grande(1000, 'x');
    nomes.push_back(std::move(grande));   // move, não copia
    std::cout << nomes[0].size() << " " << grande.size() << "\n"; // 1000 0 (normalmente)
}
```

```
# CMakeLists.txt
cmake_minimum_required(VERSION 3.20)
project(Pinguins CXX)
set(CMAKE_CXX_STANDARD 20)
add_executable(app main.cpp)
```

Compilando: `cmake -B build` e depois `cmake --build build`.

### Questão
Qual o objetivo das move semantics?
- [ ] Mover arquivos no disco
- [x] Transferir recursos de objetos temporários em vez de copiá-los
- [ ] Ordenar vetores
- [ ] Mover o cursor do terminal
> Mover é muito mais barato que copiar dados grandes.

### Questão
O que diz a "regra do zero"?
- [ ] Inicialize tudo com zero
- [x] Se os membros se gerenciam sozinhos, não escreva destrutor nem operações de cópia/movimento
- [ ] Nunca use ponteiros
- [ ] Toda classe precisa de cinco construtores
> Delegar o gerenciamento a tipos RAII evita bugs.

### Questão
Para que serve o CMake?
- [ ] É um compilador
- [x] Gerar a configuração de build do projeto para diferentes plataformas
- [ ] Testar o código
- [ ] Formatar o código
> CMake descreve o projeto; o build real é feito por make, Ninja ou Visual Studio.
