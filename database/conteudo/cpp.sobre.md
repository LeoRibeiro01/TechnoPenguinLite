@historia
Em 1979, o dinamarquês **Bjarne Stroustrup**, nos Laboratórios Bell, começou a trabalhar em "C com Classes": queria a eficiência e o acesso ao hardware do C com a organização da orientação a objetos da linguagem Simula. Em 1983 a linguagem foi rebatizada de **C++** (o `++` é o operador de incremento do C: "um passo além do C").

O livro *The C++ Programming Language* (1985) divulgou a linguagem, padronizada pela ISO em **1998**. Depois de anos parado, o **C++11** foi uma revolução (auto, lambdas, smart pointers, move semantics), seguido por C++14, C++17, **C++20** (concepts, ranges, módulos, coroutines) e C++23. Hoje um comitê internacional lança uma versão a cada três anos.

@hoje
C++ é a linguagem dos **motores de jogos** (Unreal Engine) e de jogos AAA, dos **navegadores** (Chrome, Firefox), de sistemas de **alta frequência** no mercado financeiro, de compiladores (LLVM), bancos de dados (MySQL, MongoDB), softwares gráficos (Photoshop, Blender), robótica e sistemas embarcados. Bibliotecas de IA como PyTorch e TensorFlow têm o núcleo em C++. É escolhido quando desempenho e controle de recursos são críticos.

@iniciante
- Compilar programas com g++ e usar iostream e string
- Usar tipos, auto, referências e const
- Escrever funções com sobrecarga e parâmetros padrão
- Percorrer coleções com range-based for

@intermediario
- Criar classes com construtores, destrutores e RAII
- Aplicar herança, funções virtuais e polimorfismo
- Usar vector, map, set e os algoritmos da STL
- Escrever lambdas e trabalhar com iteradores

@avancado
- Gerenciar memória com unique_ptr e shared_ptr
- Escrever templates e restringi-los com concepts
- Entender move semantics e a regra do zero
- Configurar projetos com CMake

@referencias
**Livros**
- *Programming: Principles and Practice Using C++* — Bjarne Stroustrup: o criador ensinando programação do zero.
- *A Tour of C++* — Bjarne Stroustrup: visão geral do C++ moderno em poucas páginas.
- *Effective Modern C++* — Scott Meyers: 42 itens sobre C++11 e C++14.
- *C++ Primer* — Lippman, Lajoie e Moo: a referência didática completa.

**Na internet**
- [cppreference](https://pt.cppreference.com) — a referência da linguagem e da biblioteca padrão.
- [C++ Core Guidelines](https://isocpp.github.io/CppCoreGuidelines/) — boas práticas de Stroustrup e Herb Sutter.
- [learncpp.com](https://www.learncpp.com) — tutorial gratuito e atualizado.

@modulo 1
**C++ moderno não é "C com classes".** Stroustrup insiste: ensine `std::string`, `std::vector` e referências antes de ponteiros e arrays crus. As **C++ Core Guidelines** resumem: prefira tipos da biblioteca padrão, evite `new`/`delete` diretos e torne erros visíveis em tempo de compilação.

A **inicialização uniforme** com chaves (`int x{3};`) impede conversões que perdem dados (*narrowing*). `const` deve ser usado sempre que possível: em variáveis, parâmetros por referência e métodos. Isso documenta a intenção e deixa o compilador ajudar.

C++ separa **declaração** de **definição**: o compilador precisa conhecer uma função antes de usá-la, e cada arquivo `.cpp` é compilado separadamente antes do *linker* juntar tudo. Entender esse modelo ajuda a decifrar erros de "undefined reference".

@fontes 1
- Stroustrup, *A Tour of C++*, capítulos 1 e 2
- [C++ Core Guidelines: Philosophy (P.1 a P.13)](https://isocpp.github.io/CppCoreGuidelines/CppCoreGuidelines#S-philosophy)
- [learncpp: Chapter 1 e 2](https://www.learncpp.com)

@modulo 2
**RAII é a ideia mais importante do C++.** "Resource Acquisition Is Initialization": um recurso (memória, arquivo, conexão, trava) é adquirido no construtor e liberado no destrutor. Como destrutores rodam automaticamente ao sair do escopo, até em caso de exceção, os recursos nunca vazam. `std::vector`, `std::fstream`, `std::lock_guard` e os smart pointers são todos RAII.

Na STL, o trio **contêineres + iteradores + algoritmos** foi projetado por Alexander Stepanov para que qualquer algoritmo funcione com qualquer contêiner. Antes de escrever um laço, procure um algoritmo pronto (`std::find_if`, `std::count_if`, `std::accumulate`, `std::ranges::sort`): o código fica mais claro e, muitas vezes, mais rápido. Escolha `std::vector` por padrão: memória contígua aproveita bem o cache do processador.

@fontes 2
- Stroustrup, *A Tour of C++*, capítulos 5 (classes), 6 (operações essenciais) e 12 (algoritmos)
- Meyers, *Effective STL*, itens sobre escolha de contêineres e algoritmos
- [C++ Core Guidelines: R.1 (RAII)](https://isocpp.github.io/CppCoreGuidelines/CppCoreGuidelines#Rr-raii)

@modulo 3
**Valor, referência e movimento.** Scott Meyers dedica boa parte de *Effective Modern C++* a entender **rvalues**, `std::move` e `std::forward`. Resumo prático: `std::move` não move nada, só permite que o objeto seja movido; depois de movido, o objeto original fica válido, mas com valor não especificado.

Para gerenciar recursos, siga a **regra do zero** (não escreva as operações especiais se os membros já se gerenciam) ou, se precisar de um destrutor, a **regra dos cinco**. Prefira `std::make_unique` e `std::make_shared`.

Templates são resolvidos em tempo de compilação: o código é gerado para cada tipo, sem custo em execução, mas com erros longos. Os **concepts** do C++20 deixam os requisitos explícitos. Para projetos grandes, use CMake, ative avisos, rode sanitizers e ferramentas como clang-tidy, e escreva testes com GoogleTest ou Catch2.

@fontes 3
- Meyers, *Effective Modern C++*, capítulos 4 (smart pointers) e 5 (rvalues e move semantics)
- Stroustrup, *A Tour of C++*, capítulos 7 e 8 (templates e concepts)
- [CMake Tutorial oficial](https://cmake.org/cmake/help/latest/guide/tutorial/)
