# Fundamentos de TypeScript
> Por que tipos, anotações básicas e o compilador.

## Projeto
**Carrinho de compras tipado:** modele `Produto` e `ItemCarrinho` com interfaces e escreva funções para adicionar, remover e calcular o total com desconto. Ative `"strict": true` no `tsconfig.json` e corrija todos os erros apontados pelo compilador.

## Por que TypeScript
### Teoria
TypeScript é **JavaScript com tipos**. Você escreve `.ts`, o compilador (`tsc`) verifica os tipos e gera JavaScript comum. Os tipos somem na execução: servem para pegar erros **antes** de rodar e melhorar o autocompletar do editor.

Instalação e uso:

```
npm install -D typescript
npx tsc --init
npx tsc
```

O `tsconfig.json` configura o compilador. Ligue sempre o modo `"strict": true`.

### Prática
```typescript
function area(largura: number, altura: number): number {
  return largura * altura;
}

area(3, 4);       // ok
// area("3", 4);  // erro: string não é atribuível a number
```

Em JavaScript puro, `area("3", 4)` rodaria e daria 12 por coerção, escondendo um bug.

### Questão
O que acontece com os tipos do TypeScript em tempo de execução?
- [ ] São verificados a cada chamada
- [x] São removidos; o resultado é JavaScript comum
- [ ] Viram comentários
- [ ] Deixam o código mais lento
> A checagem acontece na compilação. O JavaScript gerado não tem tipos.

### Questão
Qual comando compila um projeto TypeScript?
- [ ] node build
- [x] npx tsc
- [ ] npm ts
- [ ] tsrun
> tsc é o compilador oficial.

### Questão
Qual a principal vantagem do TypeScript?
- [ ] Rodar sem navegador
- [x] Encontrar erros de tipo antes de executar o código
- [ ] Substituir o HTML
- [ ] Dispensar testes
> Os tipos detectam muitos bugs ainda no editor.

## Tipos básicos e inferência
### Teoria
Tipos primitivos: `string`, `number`, `boolean`, `null`, `undefined`. Arrays: `number[]` ou `Array<number>`. Tuplas: `[string, number]`.

O TypeScript **infere** tipos: `let nome = "Teco"` já é `string`, sem anotar. Anote parâmetros de funções e onde a inferência não basta.

- `any` desliga a checagem: evite.
- `unknown` é a alternativa segura: obriga a verificar o tipo antes de usar.
- **Tipos literais** restringem valores: `let direcao: "norte" | "sul"`.

### Prática
```typescript
let peixes = 3;              // inferido: number
const nomes: string[] = ["Teco", "Pipo"];
const posicao: [number, number] = [10, 20];
let status: "ativo" | "inativo" = "ativo";

function tamanho(valor: unknown): number {
  if (typeof valor === "string") return valor.length;
  return 0;
}
// peixes = "muitos"; // erro: string não é atribuível a number
```

### Questão
Qual o tipo inferido em `let x = 42`?
- [ ] any
- [x] number
- [ ] unknown
- [ ] 42
> Com let, o TypeScript infere o tipo amplo number.

### Questão
Qual a diferença entre `any` e `unknown`?
- [x] unknown exige verificar o tipo antes de usar; any aceita tudo sem checar
- [ ] São iguais
- [ ] any é mais seguro
- [ ] unknown só aceita números
> unknown mantém a segurança: você precisa estreitar o tipo antes de usá-lo.

### Questão
Como declarar um array de strings?
- [ ] let a: array<string>
- [x] let a: string[]
- [ ] let a: [string]
- [ ] let a: strings
> string[] (ou Array<string>) é um array de strings; [string] é uma tupla de um elemento.

## Funções tipadas
### Teoria
Anote parâmetros e, quando útil, o retorno. Parâmetros **opcionais** usam `?` e podem ter **valor padrão**.

Funções que não retornam nada têm tipo `void`. Você pode descrever o **tipo de uma função**: `(a: number, b: number) => number`, útil para callbacks.

**Overloads** e **union types** permitem aceitar mais de um formato de entrada.

### Prática
```typescript
function saudar(nome: string, titulo?: string): string {
  return titulo ? `${titulo} ${nome}` : nome;
}

const aplicar = (valores: number[], op: (n: number) => number): number[] =>
  valores.map(op);

function log(mensagem: string): void {
  console.log(`[log] ${mensagem}`);
}

console.log(saudar("Teco"), saudar("Teco", "Sr."));
console.log(aplicar([1, 2, 3], (n) => n * 10));
```

### Questão
O que `?` significa em `titulo?: string`?
- [ ] Que o tipo é desconhecido
- [x] Que o parâmetro é opcional
- [ ] Que o valor pode ser number
- [ ] Que a função é assíncrona
> Parâmetro opcional: pode ser omitido e, nesse caso, vale undefined.

### Questão
Qual o tipo de retorno de uma função que não devolve nada?
- [ ] null
- [ ] undefined[]
- [x] void
- [ ] never
> void indica ausência de retorno útil.

### Questão
Como tipar um callback que recebe e devolve um número?
- [ ] callback: number
- [x] callback: (n: number) => number
- [ ] callback: Function<number>
- [ ] callback: number => number
> A sintaxe de tipo de função usa parâmetros nomeados e =>.

# Modelando dados
> Interfaces, uniões, generics e narrowing.

## Projeto
**Sistema de biblioteca:** modele `Livro`, `Usuario` e `Emprestimo` com interfaces, use um union type para o status do empréstimo e crie uma classe genérica `Repositorio<T>` com `adicionar`, `buscarPorId` e `listar`. Garanta que não seja possível emprestar um livro já emprestado.

## Interfaces e type aliases
### Teoria
**Interfaces** descrevem o formato de objetos:

```
interface Pinguim {
  nome: string;
  idade?: number;
  readonly id: number;
}
```

`?` marca propriedades opcionais e `readonly` impede alterações. Interfaces podem **estender** outras (`interface Imperador extends Pinguim`).

**Type aliases** (`type`) dão nome a qualquer tipo, inclusive uniões e tuplas. Para objetos, interface e type são quase equivalentes; muitos times usam interface para objetos e type para o resto.

### Prática
```typescript
interface Produto {
  readonly id: number;
  nome: string;
  preco: number;
  tags?: string[];
}

interface ProdutoDigital extends Produto {
  urlDownload: string;
}

type Moeda = "BRL" | "USD";

const ebook: ProdutoDigital = { id: 1, nome: "TS na prática", preco: 39.9, urlDownload: "/ebook.pdf" };
// ebook.id = 2; // erro: id é somente leitura
```

### Questão
O que `readonly` faz numa propriedade?
- [ ] Torna a propriedade opcional
- [x] Impede que ela seja alterada depois de criada
- [ ] Esconde a propriedade
- [ ] Converte em string
> Propriedades readonly só podem ser definidas na criação.

### Questão
Como uma interface herda de outra?
- [ ] interface B inherits A
- [x] interface B extends A
- [ ] interface B : A
- [ ] interface B implements A
> extends adiciona as propriedades da interface base.

### Questão
Qual recurso é ideal para nomear a união `"BRL" | "USD"`?
- [ ] interface
- [x] type
- [ ] enum obrigatório
- [ ] class
> type aliases nomeiam uniões, tuplas e outros tipos compostos.

## Uniões e narrowing
### Teoria
Um **union type** aceita um entre vários tipos: `string | number`. Para usar com segurança, **estreite** o tipo (narrowing):

- `typeof` para primitivos.
- `in` para checar propriedades.
- `instanceof` para classes.
- **Uniões discriminadas**: todos os tipos têm um campo comum (ex.: `tipo`) que identifica qual é qual. Com `switch`, o compilador sabe exatamente o tipo em cada caso.

O tipo `never` ajuda a garantir que todos os casos foram tratados.

### Prática
```typescript
type Forma =
  | { tipo: "circulo"; raio: number }
  | { tipo: "retangulo"; largura: number; altura: number };

function area(f: Forma): number {
  switch (f.tipo) {
    case "circulo":
      return Math.PI * f.raio ** 2;
    case "retangulo":
      return f.largura * f.altura;
    default:
      const impossivel: never = f;
      return impossivel;
  }
}
```

### Questão
O que é narrowing?
- [ ] Converter tipos à força
- [x] Refinar um tipo amplo para um mais específico com verificações
- [ ] Diminuir o tamanho do arquivo
- [ ] Remover propriedades
> Verificações como typeof fazem o compilador entender o tipo exato naquele trecho.

### Questão
Numa união discriminada, para que serve o campo `tipo`?
- [ ] Para documentação
- [x] Para identificar qual variante da união está em uso
- [ ] Para converter em JSON
- [ ] Para criar classes
> Com um campo literal comum, o switch estreita o tipo automaticamente.

### Questão
Qual verificação estreita `valor: string | number` para string?
- [x] typeof valor === "string"
- [ ] valor instanceof String
- [ ] valor is string
- [ ] valor.type == "string"
> typeof é o jeito padrão para primitivos.

## Generics
### Teoria
**Generics** criam código reutilizável que funciona com vários tipos sem perder a checagem. O parâmetro de tipo, normalmente `T`, é definido entre `< >`.

Restrinja com `extends`: `<T extends { id: number }>` aceita qualquer tipo que tenha `id`.

**Utility types** prontos ajudam muito: `Partial<T>` (tudo opcional), `Pick<T, "a" | "b">`, `Omit<T, "senha">`, `Record<K, V>`.

### Prática
```typescript
function primeiro<T>(lista: T[]): T | undefined {
  return lista[0];
}

class Repositorio<T extends { id: number }> {
  private itens: T[] = [];
  adicionar(item: T): void { this.itens.push(item); }
  buscar(id: number): T | undefined { return this.itens.find((i) => i.id === id); }
}

interface Usuario { id: number; nome: string; senha: string }
type UsuarioPublico = Omit<Usuario, "senha">;
const atualizacao: Partial<Usuario> = { nome: "Teco" };
```

### Questão
O que `primeiro<T>(lista: T[]): T | undefined` garante?
- [ ] Que a lista tem só números
- [x] Que o retorno tem o mesmo tipo dos itens da lista (ou undefined)
- [ ] Que a lista nunca está vazia
- [ ] Nada, é igual a any
> O tipo T é ligado à entrada e à saída, mantendo a checagem.

### Questão
O que `Omit<Usuario, "senha">` produz?
- [ ] Um Usuario só com senha
- [x] O tipo Usuario sem a propriedade senha
- [ ] Um erro de compilação
- [ ] Uma string
> Omit remove as propriedades listadas.

### Questão
Para que serve `<T extends { id: number }>`?
- [ ] Para herdar de uma classe
- [x] Para restringir T a tipos que tenham a propriedade id numérica
- [ ] Para criar um id automático
- [ ] Para converter T em number
> A restrição permite usar item.id com segurança dentro do código genérico.

# TypeScript no mundo real
> Classes, módulos, tipagem de APIs e integração com frameworks.

## Projeto
**Cliente de API tipado:** crie uma função genérica `buscar<T>(url)` que usa `fetch` e valida a resposta com um type guard. Consuma uma API pública (ex.: JSONPlaceholder) tipando `Post` e `Comentario`, e renderize os dados numa página com Vite + TypeScript.

## Classes e modificadores de acesso
### Teoria
TypeScript adiciona às classes JavaScript:
- **Modificadores:** `public` (padrão), `private` (só dentro da classe), `protected` (classe e filhas).
- **Parameter properties:** declarar e atribuir atributos direto no construtor.
- **implements:** obrigar a classe a seguir uma interface.
- **abstract:** classes que não podem ser instanciadas e definem métodos que as filhas devem implementar.

### Prática
```typescript
interface Nadador {
  nadar(): string;
}

abstract class Ave {
  constructor(protected nome: string) {}
  abstract som(): string;
}

class Pinguim extends Ave implements Nadador {
  private peixes = 0;
  som() { return `${this.nome}: quack?`; }
  nadar() { this.peixes++; return `${this.nome} pescou ${this.peixes}`; }
}

const teco = new Pinguim("Teco");
console.log(teco.som(), teco.nadar());
// teco.peixes; // erro: propriedade privada
```

### Questão
Quem pode acessar um membro `protected`?
- [ ] Qualquer código
- [x] A própria classe e suas subclasses
- [ ] Só a própria classe
- [ ] Ninguém
> protected é visível na classe e nas que herdam dela.

### Questão
O que significa `constructor(protected nome: string) {}`?
- [ ] Um construtor vazio
- [x] Declara e inicializa a propriedade nome automaticamente
- [ ] Um erro de sintaxe
- [ ] Um método estático
> São parameter properties: o modificador cria o atributo.

### Questão
Uma classe `abstract` pode ser instanciada com `new`?
- [ ] Sim
- [x] Não
- [ ] Só com generics
- [ ] Só uma vez
> Classes abstratas servem de base e só suas filhas concretas podem ser instanciadas.

## Tipando dados externos
### Teoria
Dados que vêm de fora (APIs, formulários, `JSON.parse`) **não têm tipo garantido**: o TypeScript confia no que você declarar. Para ter segurança de verdade, **valide em tempo de execução**.

- **Type guards** personalizados: funções que retornam `valor is Tipo`.
- Bibliotecas de schema como **Zod** validam e geram o tipo ao mesmo tempo.
- `as` (asserção de tipo) só diz ao compilador "confie em mim": use com cuidado.

### Prática
```typescript
interface Post { id: number; title: string }

function ehPost(x: unknown): x is Post {
  return typeof x === "object" && x !== null
    && typeof (x as Post).id === "number"
    && typeof (x as Post).title === "string";
}

async function buscarPost(id: number): Promise<Post> {
  const resposta = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`);
  const dados: unknown = await resposta.json();
  if (!ehPost(dados)) throw new Error("Formato inesperado");
  return dados;
}
```

### Questão
O que `x is Post` indica no retorno de uma função?
- [ ] Que a função converte x em Post
- [x] Que a função é um type guard: se retornar true, x é Post
- [ ] Que x sempre é Post
- [ ] Um erro de sintaxe
> Type guards estreitam o tipo no código que chama a função.

### Questão
Por que validar dados de uma API mesmo usando TypeScript?
- [ ] Porque o TypeScript não funciona com fetch
- [x] Porque os tipos não existem em execução e a API pode mandar outro formato
- [ ] Porque JSON é sempre inválido
- [ ] Não é necessário
> O compilador não vê a resposta real; só a validação em execução garante o formato.

### Questão
O que a asserção `dados as Post` faz?
- [ ] Valida os dados
- [x] Apenas manda o compilador tratar o valor como Post, sem verificar
- [ ] Converte números em strings
- [ ] Lança erro se não for Post
> as não verifica nada em execução: é uma promessa sua ao compilador.

## Configuração e ecossistema
### Teoria
Opções importantes do `tsconfig.json`:
- `strict`: liga todas as checagens rigorosas (inclusive `strictNullChecks`).
- `target`: versão do JavaScript gerado.
- `module` e `moduleResolution`: sistema de módulos.
- `outDir` e `rootDir`: onde fica o código gerado e o fonte.

No dia a dia, ferramentas como **Vite**, **Next.js**, **NestJS** e **Angular** já vêm com TypeScript configurado. Pacotes JavaScript ganham tipos pelos arquivos `.d.ts`, muitas vezes instalados via `@types/nome-do-pacote`.

### Prática
```json
{
  "compilerOptions": {
    "target": "ES2022",
    "module": "ESNext",
    "moduleResolution": "Bundler",
    "strict": true,
    "outDir": "dist",
    "rootDir": "src"
  },
  "include": ["src"]
}
```

Criando um projeto web com Vite: `npm create vite@latest meu-app -- --template vanilla-ts`.

### Questão
O que a opção `strict: true` faz?
- [ ] Proíbe o uso de classes
- [x] Liga um conjunto de verificações rigorosas, como checagem de null
- [ ] Minifica o código
- [ ] Desliga a inferência
> strict ativa várias flags que pegam mais bugs.

### Questão
Para que servem os arquivos `.d.ts`?
- [ ] Guardar dados do banco
- [x] Descrever os tipos de código JavaScript
- [ ] Rodar testes
- [ ] Configurar o servidor
> Arquivos de declaração dão tipos a bibliotecas escritas em JavaScript.

### Questão
Como obter os tipos de uma biblioteca JavaScript sem tipos próprios, como `express`?
- [ ] Reescrever a biblioteca
- [x] Instalar @types/express
- [ ] Usar any em tudo
- [ ] Não é possível
> O repositório DefinitelyTyped publica tipos no escopo @types.
