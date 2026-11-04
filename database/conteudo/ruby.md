# Fundamentos de Ruby
> Sintaxe, tipos, símbolos e controle de fluxo.

## Projeto
**Diário de pesca:** crie um script que pergunta quantos peixes você pescou em cada dia da semana (`gets.chomp.to_i`), guarda num hash `{ segunda: 3, ... }`, mostra o total, a média e o melhor dia, e diz "Semana de imperador!" se passar de 20.

## Olá, Ruby
### Teoria
Ruby foi criada no Japão por Yukihiro "Matz" Matsumoto para ser **agradável de escrever**. É interpretada, dinâmica e **tudo é objeto**, até números: `5.times { }`.

Rode com `ruby arquivo.rb` ou experimente no console interativo `irb`.

- `puts` imprime com quebra de linha; `print` sem quebra.
- Interpolação com aspas duplas: `"Olá, #{nome}"`.
- Parênteses em chamadas de método são opcionais.
- Comentários começam com `#`.

### Prática
```ruby
nome = "Teco"
peixes = 3
puts "Olá, #{nome}!"
puts "#{nome} tem #{peixes * 2} peixes depois do almoço"
puts nome.upcase.reverse
3.times { |i| puts "Volta #{i + 1}" }
puts 10.even?
```

### Questão
Qual a sintaxe de interpolação de strings em Ruby?
- [ ] "${nome}"
- [x] "#{nome}"
- [ ] "{nome}"
- [ ] "%nome%"
> Ruby usa #{} dentro de aspas duplas.

### Questão
O que `5.times { puts "oi" }` mostra sobre Ruby?
- [ ] Que números são primitivos
- [x] Que números são objetos com métodos
- [ ] Que times é palavra reservada
- [ ] Nada, é um erro
> Em Ruby, tudo é objeto, inclusive inteiros.

### Questão
Qual a diferença entre `puts` e `print`?
- [ ] Nenhuma
- [x] puts adiciona uma quebra de linha no final; print não
- [ ] print só imprime números
- [ ] puts só funciona no irb
> puts = put string com nova linha.

## Tipos, símbolos e coleções
### Teoria
Tipos comuns: `Integer`, `Float`, `String`, `Symbol`, `Array`, `Hash`, `true`/`false` e `nil` (ausência de valor).

**Símbolos** (`:nome`) são identificadores imutáveis e únicos, muito usados como chaves de hash.

**Arrays**: `[1, 2, 3]`, com métodos como `push` (ou `<<`), `first`, `last`, `include?`, `sort`.

**Hashes**: `{ nome: "Ana", nota: 9 }` (chaves símbolo). Acesse com `aluno[:nome]`; `fetch` lança erro se a chave não existir.

Por convenção, métodos que retornam booleano terminam com `?` e métodos que alteram o objeto terminam com `!`.

### Prática
```ruby
linguagens = ["Ruby", "Python"]
linguagens << "Go"
puts linguagens.include?("Ruby")    # true
puts linguagens.sort.first          # Go

aluno = { nome: "Ana", nota: 9.5 }
aluno[:turma] = "B"
puts aluno[:nome]
aluno.each { |chave, valor| puts "#{chave}: #{valor}" }

texto = "gelo"
texto.upcase!                        # altera o próprio texto
puts texto                           # GELO
```

### Questão
O que é `:nome` em Ruby?
- [ ] Uma string
- [x] Um símbolo
- [ ] Um comentário
- [ ] Uma variável global
> Símbolos são identificadores imutáveis, ótimos como chaves.

### Questão
O que indica um método terminado em `!`, como `upcase!`?
- [ ] Que ele é privado
- [x] Que ele modifica o próprio objeto (ou é "perigoso")
- [ ] Que retorna booleano
- [ ] Que lança exceção sempre
> upcase devolve uma nova string; upcase! altera a original.

### Questão
Qual valor representa "nada" em Ruby?
- [ ] null
- [ ] None
- [x] nil
- [ ] undefined
> nil é o objeto que representa ausência de valor.

## Controle de fluxo e blocos
### Teoria
Ruby tem `if / elsif / else`, `unless` (o contrário do if) e `case / when`. Condições podem vir **no fim da linha**: `puts "frio" if temperatura < 0`.

Em Ruby, só `false` e `nil` são falsos: `0` e `""` são verdadeiros!

Em vez de laços clássicos, Ruby usa **blocos** com iteradores: `each`, `each_with_index`, `times`, `upto`. Um bloco é o código entre `{ }` ou `do ... end` passado a um método, com parâmetros entre `| |`.

### Prática
```ruby
nota = 7.5

situacao = case nota
           when 9..10 then "excelente"
           when 7...9 then "aprovado"
           else "recuperação"
           end
puts situacao

puts "Bora estudar!" unless nota >= 10

%w[Teco Pipo Ana].each_with_index do |nome, i|
  puts "#{i + 1}. #{nome}"
end

1.upto(3) { |n| print n, " " }
```

### Questão
Em Ruby, o número `0` é verdadeiro ou falso numa condição?
- [x] Verdadeiro
- [ ] Falso
- [ ] Gera erro
- [ ] Depende do contexto
> Somente false e nil são falsos em Ruby.

### Questão
O que `unless` faz?
- [ ] Repete o bloco
- [x] Executa o bloco quando a condição é falsa
- [ ] Executa sempre
- [ ] Encerra o programa
> unless condicao é o mesmo que if !condicao.

### Questão
Num bloco `do |nome, i| ... end`, o que são `nome` e `i`?
- [ ] Variáveis globais
- [x] Parâmetros do bloco
- [ ] Símbolos
- [ ] Constantes
> Os valores passados pelo iterador chegam pelos parâmetros entre barras.

# Orientação a objetos e coleções avançadas
> Classes, módulos, Enumerable e tratamento de erros.

## Projeto
**Gerenciador de tarefas em Ruby:** crie as classes `Tarefa` e `ListaDeTarefas` (incluindo `Enumerable` e implementando `each`), com métodos para adicionar, concluir e filtrar. Salve e carregue a lista em JSON (`require "json"`) e trate erros de leitura com `begin/rescue`.

## Classes e objetos
### Teoria
Classes são definidas com `class` e o construtor se chama `initialize`. Variáveis de instância começam com `@`.

`attr_reader`, `attr_writer` e `attr_accessor` criam getters e setters automaticamente.

Herança com `<`: `class Imperador < Pinguim`. `super` chama o método da classe mãe. Métodos depois de `private` só podem ser usados dentro da classe.

O método `to_s` define como o objeto aparece em `puts`.

### Prática
```ruby
class Pinguim
  attr_reader :nome, :peixes

  def initialize(nome)
    @nome = nome
    @peixes = 0
  end

  def comer(quantidade)
    @peixes += quantidade
    self
  end

  def to_s
    "#{nome} (#{peixes} peixes)"
  end
end

class Imperador < Pinguim
  def comer(quantidade)
    super(quantidade * 2)
  end
end

puts Pinguim.new("Teco").comer(2).comer(1)   # Teco (3 peixes)
puts Imperador.new("Rei").comer(2)           # Rei (4 peixes)
```

### Questão
Qual método funciona como construtor em Ruby?
- [ ] new
- [x] initialize
- [ ] constructor
- [ ] init
> Pinguim.new chama initialize para preparar o objeto.

### Questão
O que `attr_reader :nome` cria?
- [ ] Um setter
- [x] Um método de leitura para @nome
- [ ] Uma constante
- [ ] Uma variável global
> attr_reader gera o getter; attr_accessor gera getter e setter.

### Questão
Como indicar herança em Ruby?
- [ ] class Imperador extends Pinguim
- [x] class Imperador < Pinguim
- [ ] class Imperador : Pinguim
- [ ] class Imperador(Pinguim)
> O símbolo < indica a classe mãe.

## Módulos e Enumerable
### Teoria
**Módulos** agrupam métodos e constantes. Servem como namespaces e como **mixins**: com `include`, uma classe ganha os métodos do módulo (reuso sem herança múltipla).

O mixin mais famoso é o **Enumerable**: implemente `each` e inclua `Enumerable` para ganhar dezenas de métodos: `map`, `select`, `reject`, `find`, `sort_by`, `sum`, `group_by`, `count`...

Os mesmos métodos já existem em Array e Hash, e encadeá-los é o estilo Ruby.

### Prática
```ruby
module Saudavel
  def saudar
    "Olá, eu sou #{nome}!"
  end
end

class Turma
  include Enumerable

  def initialize(*alunos) = @alunos = alunos
  def each(&bloco) = @alunos.each(&bloco)
end

Aluno = Struct.new(:nome, :nota) do
  include Saudavel
end

turma = Turma.new(Aluno.new("Ana", 9), Aluno.new("Beto", 5), Aluno.new("Caio", 7))
puts turma.select { |a| a.nota >= 7 }.map(&:nome).inspect   # ["Ana", "Caio"]
puts turma.sum(&:nota) / turma.count.to_f                   # 7.0
puts turma.max_by(&:nota).saudar
```

### Questão
O que `include Enumerable` exige da classe?
- [ ] Um método initialize
- [x] Um método each
- [ ] Herdar de Array
- [ ] Nada
> Enumerable constrói todos os métodos em cima de each.

### Questão
O que `map(&:nome)` faz?
- [ ] Ordena por nome
- [x] Chama o método nome em cada elemento e coleta os resultados
- [ ] Remove os nomes
- [ ] Cria um hash
> &:nome é um atalho para { |x| x.nome }.

### Questão
Para que servem módulos como mixins?
- [ ] Criar instâncias
- [x] Compartilhar métodos entre classes sem herança múltipla
- [ ] Definir variáveis globais
- [ ] Rodar código em paralelo
> include insere os métodos do módulo na classe.

## Tratamento de erros e arquivos
### Teoria
Exceções são tratadas com `begin / rescue / ensure` (em métodos, o `begin` é opcional):

- `rescue TipoDeErro => e` captura um tipo específico.
- `ensure` sempre roda.
- `raise` lança uma exceção.
- `retry` tenta de novo.

Arquivos: `File.read`, `File.write` e `File.open` com bloco, que fecha o arquivo automaticamente. A biblioteca `json` converte entre hashes e JSON.

### Prática
```ruby
require "json"

def carregar_tarefas(caminho)
  JSON.parse(File.read(caminho), symbolize_names: true)
rescue Errno::ENOENT
  []
rescue JSON::ParserError => e
  warn "Arquivo corrompido: #{e.message}"
  []
end

tarefas = carregar_tarefas("tarefas.json")
tarefas << { titulo: "Estudar Ruby", feita: false }
File.write("tarefas.json", JSON.pretty_generate(tarefas))

def dividir(a, b)
  raise ArgumentError, "divisor zero" if b.zero?
  a / b
end
```

### Questão
Qual palavra captura exceções em Ruby?
- [ ] catch
- [x] rescue
- [ ] except
- [ ] handle
> begin/rescue/ensure equivale a try/catch/finally.

### Questão
Qual bloco é sempre executado, com ou sem erro?
- [ ] rescue
- [ ] else
- [x] ensure
- [ ] retry
> ensure é o equivalente ao finally.

### Questão
Como lançar uma exceção em Ruby?
- [ ] throw new Error
- [x] raise ArgumentError, "mensagem"
- [ ] error("mensagem")
- [ ] panic("mensagem")
> raise lança a exceção com a classe e a mensagem.

# Ruby no mundo real
> Gems, testes e aplicações web com Ruby on Rails.

## Projeto
**Blog com Rails:** crie um app com `rails new blog`, gere o scaffold de `Post` (título, conteúdo), adicione validações, comentários associados (`has_many`) e testes de modelo. Publique numa plataforma como Render ou Fly.io.

## Gems e Bundler
### Teoria
Bibliotecas Ruby são **gems**, publicadas em rubygems.org. O **Bundler** gerencia as dependências de um projeto pelo arquivo `Gemfile`, e trava as versões no `Gemfile.lock`.

```
gem install bundler
bundle init
bundle add httparty
bundle exec ruby app.rb
```

Gems populares: **Rails** (web), **Sinatra** (web minimalista), **RSpec** (testes), **Sidekiq** (filas), **Devise** (autenticação), **RuboCop** (estilo).

### Prática
```ruby
# Gemfile
source "https://rubygems.org"
gem "httparty"
gem "rspec", group: :test
```

```ruby
# app.rb
require "httparty"

resposta = HTTParty.get("https://api.github.com/repos/rails/rails")
if resposta.success?
  puts "Rails tem #{resposta['stargazers_count']} estrelas"
else
  puts "Falhou: #{resposta.code}"
end
```

### Questão
Qual arquivo lista as gems de um projeto?
- [ ] package.json
- [x] Gemfile
- [ ] requirements.txt
- [ ] gems.yml
> O Bundler lê o Gemfile para instalar as dependências.

### Questão
Para que serve o Gemfile.lock?
- [ ] Bloquear o projeto
- [x] Registrar as versões exatas instaladas para todos usarem as mesmas
- [ ] Guardar senhas
- [ ] Listar testes
> Ele garante instalações reproduzíveis.

### Questão
Qual gem é o framework web mais conhecido de Ruby?
- [ ] RSpec
- [x] Rails
- [ ] Bundler
- [ ] RuboCop
> Ruby on Rails popularizou convenções como MVC e migrations.

## Testes com RSpec
### Teoria
**RSpec** escreve testes que se leem como especificações:

- `describe` agrupa testes de uma classe ou método.
- `it` descreve um comportamento.
- `expect(valor).to eq(esperado)` faz a verificação. Outros matchers: `be_truthy`, `include`, `raise_error`, `change`.
- `let` define dados reutilizáveis entre os testes.

Rode com `bundle exec rspec`. Escrever o teste antes do código é o **TDD**.

### Prática
```ruby
# spec/pinguim_spec.rb
require_relative "../pinguim"

RSpec.describe Pinguim do
  let(:teco) { Pinguim.new("Teco") }

  it "começa sem peixes" do
    expect(teco.peixes).to eq(0)
  end

  it "acumula peixes ao comer" do
    expect { teco.comer(3) }.to change { teco.peixes }.from(0).to(3)
  end

  it "mostra o nome no to_s" do
    expect(teco.to_s).to include("Teco")
  end
end
```

### Questão
O que `it` define no RSpec?
- [ ] Uma classe
- [x] Um exemplo (teste) de comportamento
- [ ] Uma variável
- [ ] Um módulo
> Cada it descreve e verifica um comportamento.

### Questão
Qual a função de `let(:teco)`?
- [ ] Criar uma constante global
- [x] Definir um valor reutilizável, criado sob demanda em cada teste
- [ ] Importar um arquivo
- [ ] Pular um teste
> let é avaliado de forma preguiçosa e isolado por exemplo.

### Questão
O que é TDD?
- [ ] Um framework
- [x] Desenvolvimento guiado por testes: escrever o teste antes do código
- [ ] Um banco de dados
- [ ] Um tipo de gem
> Test-Driven Development: vermelho, verde, refatorar.

## Ruby on Rails
### Teoria
**Rails** segue dois princípios: **convenção sobre configuração** e **DRY** (não se repita). Seguindo os nomes padrão, quase nada precisa ser configurado.

Estrutura MVC:
- **Models** com **Active Record**: classes ligadas a tabelas, com validações e associações (`has_many`, `belongs_to`).
- **Controllers** com ações (`index`, `show`, `create`...).
- **Views** em ERB.

Comandos úteis: `rails new`, `rails generate scaffold`, `rails db:migrate`, `rails server`, `rails console`.

### Prática
```ruby
# app/models/post.rb
class Post < ApplicationRecord
  has_many :comentarios, dependent: :destroy
  validates :titulo, presence: true, length: { maximum: 100 }
end

# app/controllers/posts_controller.rb
class PostsController < ApplicationController
  def index
    @posts = Post.order(created_at: :desc)
  end

  def create
    @post = Post.new(params.require(:post).permit(:titulo, :conteudo))
    if @post.save
      redirect_to @post, notice: "Post criado!"
    else
      render :new, status: :unprocessable_entity
    end
  end
end
```

### Questão
O que significa "convenção sobre configuração"?
- [ ] Que tudo deve ser configurado manualmente
- [x] Que, seguindo os padrões de nomes, o framework deduz as configurações
- [ ] Que não há banco de dados
- [ ] Que o código não pode ser alterado
> Um model Post é ligado automaticamente à tabela posts, por exemplo.

### Questão
O que o Active Record faz?
- [ ] Grava vídeos
- [x] Liga classes Ruby a tabelas do banco (ORM)
- [ ] Gerencia gems
- [ ] Renderiza HTML
> Cada instância de Post representa uma linha da tabela posts.

### Questão
Qual comando aplica as migrations ao banco no Rails?
- [ ] rails migrate
- [x] rails db:migrate
- [ ] bundle migrate
- [ ] rails run migrations
> db:migrate executa as migrations pendentes.
