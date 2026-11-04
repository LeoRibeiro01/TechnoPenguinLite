@historia
Java nasceu em 1991 na **Sun Microsystems**, num projeto chamado *Green*, liderado por **James Gosling**. A ideia era programar aparelhos eletrônicos como TVs a cabo. A linguagem se chamou Oak (carvalho) e depois **Java**, nome de um café da Indonésia, daí o logo da xícara.

O grande trunfo era a **JVM**: o código compilado vira *bytecode*, que roda em qualquer sistema com uma máquina virtual Java. O slogan *"Write once, run anywhere"* fez sucesso quando o Java 1.0 saiu em 1996, junto com a explosão da internet. Em 2010, a **Oracle** comprou a Sun. Desde 2017, o Java ganhou versões a cada seis meses, com recursos modernos como `var`, records, switch expressions, pattern matching e virtual threads (Java 21).

@hoje
Java é uma das linguagens mais usadas em **sistemas corporativos**: bancos, seguradoras, governo, e-commerce e telecomunicações. O framework **Spring Boot** domina o back-end Java. A plataforma Android foi construída sobre Java (hoje dividindo espaço com Kotlin), e ferramentas de big data como **Hadoop**, **Kafka** e **Elasticsearch** rodam na JVM. O jogo **Minecraft** (edição Java) também é feito em Java. Há muitas vagas no Brasil e no exterior.

@iniciante
- Escrever, compilar e executar programas Java
- Usar tipos primitivos, Strings, operadores e conversões
- Controlar o fluxo com if, switch moderno e laços
- Ler dados com Scanner

@intermediario
- Criar classes com encapsulamento, construtores e métodos
- Aplicar herança, classes abstratas, interfaces e polimorfismo
- Usar List, Set, Map e generics
- Processar coleções com a Stream API

@avancado
- Tratar exceções checked e unchecked e usar try-with-resources
- Gerenciar projetos com Maven e escrever testes com JUnit 5
- Criar APIs REST com Spring Boot e Spring Data JPA
- Aplicar injeção de dependência e boas práticas de projeto

@referencias
**Livros**
- *Use a Cabeça! Java* — Kathy Sierra e Bert Bates: o jeito mais divertido de aprender Java e orientação a objetos.
- *Java Efetivo* — Joshua Bloch: 90 itens de boas práticas, escrito por um dos criadores da plataforma.
- *Código Limpo* — Robert C. Martin: exemplos em Java sobre como escrever código legível.
- *Spring Boot in Action* — Craig Walls.

**Na internet**
- [Dev.java](https://dev.java/learn) — o site oficial de aprendizado da Oracle.
- [Documentação do Java SE](https://docs.oracle.com/en/java/javase/)
- [Guias do Spring](https://spring.io/guides)

@modulo 1
**Java é estaticamente e fortemente tipado, e a JVM faz muito trabalho por você.** O compilador `javac` gera bytecode; a JVM interpreta esse código e, com o compilador **JIT** (*just-in-time*), transforma os trechos mais usados em código de máquina otimizado durante a execução. Por isso programas Java ficam mais rápidos depois de "aquecer".

A memória é gerenciada pelo **coletor de lixo** (garbage collector): objetos sem referências são removidos automaticamente. Variáveis de tipos primitivos guardam o valor; variáveis de objetos guardam uma **referência**. Comparar Strings com `==` compara referências, não o texto.

Desde o Java 10, `var` permite inferência de tipo em variáveis locais (`var lista = new ArrayList<String>();`), sem deixar de ser estaticamente tipado. E desde o Java 21, um programa simples pode ter só `void main()` (recurso em evolução para facilitar o ensino).

@fontes 1
- Sierra e Bates, *Use a Cabeça! Java*, capítulos 1 a 3
- [Dev.java: Getting Started](https://dev.java/learn/getting-started/)
- [JEP 445/463: métodos main simplificados](https://openjdk.org/jeps/463)

@modulo 2
**Prefira composição a herança.** Este é um dos conselhos mais famosos de *Java Efetivo* (item 18): herança cria acoplamento forte, e mudanças na classe mãe podem quebrar as filhas. Use herança quando houver uma relação "é um" verdadeira; nos outros casos, guarde um objeto dentro de outro e delegue.

Outros itens essenciais do livro: **minimize a mutabilidade** (classes imutáveis são seguras para compartilhar, e os `record` do Java 16 facilitam isso), sobrescreva `equals` e `hashCode` juntos, e programe para **interfaces** (`List<String> nomes = new ArrayList<>()`).

Os princípios **SOLID**, popularizados por Robert C. Martin, guiam bons projetos orientados a objetos: responsabilidade única, aberto/fechado, substituição de Liskov, segregação de interfaces e inversão de dependência.

@fontes 2
- Bloch, *Java Efetivo*, itens 17 (minimizar mutabilidade), 18 (composição) e 64 (interfaces)
- Martin, *Arquitetura Limpa*, parte III (princípios SOLID)
- [Dev.java: Records](https://dev.java/learn/records/)

@modulo 3
**Exceções são para situações excepcionais.** *Java Efetivo* recomenda usar checked exceptions só quando o chamador pode se recuperar, preferir exceções padrão (`IllegalArgumentException`, `IllegalStateException`) e nunca ignorar uma exceção com um `catch` vazio.

No Spring, a **injeção de dependência** inverte o controle: em vez de cada classe criar suas dependências com `new`, o contêiner do Spring as cria e entrega. Isso facilita testar (trocando a dependência por uma falsa) e trocar implementações. Prefira injeção pelo **construtor**, com campos `final`.

Testes formam uma pirâmide: muitos testes de unidade rápidos, alguns de integração (com `@SpringBootTest` e bancos em contêiner via Testcontainers) e poucos de ponta a ponta. E com as **virtual threads** do Java 21, servidores conseguem atender milhões de requisições concorrentes com código simples e bloqueante.

@fontes 3
- Bloch, *Java Efetivo*, capítulo 10 (exceções)
- [Spring Boot Reference Documentation](https://docs.spring.io/spring-boot/)
- [JEP 444: Virtual Threads](https://openjdk.org/jeps/444)
