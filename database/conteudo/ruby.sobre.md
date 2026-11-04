@historia
Ruby foi criada no Japão por **Yukihiro "Matz" Matsumoto**, que começou o projeto em 1993 e lançou a primeira versão em 1995. Matz queria uma linguagem mais orientada a objetos que Python e mais poderosa que Perl, mas acima de tudo **agradável para o programador**: "Ruby foi feita para deixar os programadores felizes". O nome, uma pedra preciosa, foi uma brincadeira com o Perl (pérola).

Ruby ficou famosa no mundo todo em 2004, quando o dinamarquês **David Heinemeier Hansson** extraiu do software Basecamp o framework **Ruby on Rails**. Rails popularizou ideias como convenção sobre configuração, migrations e scaffolding, copiadas por frameworks de várias linguagens. O Ruby 3 (2020) prometeu e entregou ser três vezes mais rápido que o Ruby 2, com o compilador JIT YJIT, desenvolvido pela Shopify.

@hoje
Ruby on Rails sustenta grandes plataformas como **GitHub**, **Shopify**, **Airbnb** (no início), Basecamp, Zendesk e GitLab. Muitas startups começam com Rails pela produtividade. Ruby também é usado em automação de infraestrutura (Chef, Vagrant), ferramentas como o **Homebrew** e o **CocoaPods**, e geradores de sites estáticos (Jekyll, que gera o GitHub Pages).

@iniciante
- Rodar scripts e usar o irb
- Usar strings, interpolação, símbolos, arrays e hashes
- Controlar o fluxo com if, unless, case e modificadores de linha
- Percorrer coleções com blocos e iteradores

@intermediario
- Criar classes com initialize, attr_accessor e herança
- Compartilhar comportamento com módulos e mixins
- Dominar Enumerable: map, select, reduce, group_by
- Tratar erros com begin/rescue/ensure e ler arquivos JSON

@avancado
- Gerenciar gems com Bundler
- Escrever testes com RSpec e praticar TDD
- Criar aplicações web com Ruby on Rails
- Usar Active Record, validações e associações

@referencias
**Livros**
- *Programming Ruby* — Dave Thomas, Andy Hunt e Chad Fowler: o "Pickaxe", a referência clássica.
- *Eloquent Ruby* — Russ Olsen: como escrever Ruby idiomático.
- *Practical Object-Oriented Design* — Sandi Metz: um dos melhores livros de orientação a objetos, com exemplos em Ruby.
- *Agile Web Development with Rails* — Sam Ruby e equipe.

**Na internet**
- [Documentação oficial do Ruby](https://www.ruby-lang.org/pt/documentation/)
- [Rails Guides](https://guides.rubyonrails.org)
- [Ruby Koans](https://www.rubykoans.com) — aprenda Ruby corrigindo testes.

@modulo 1
**Em Ruby, até os números recebem mensagens.** Inspirada em Smalltalk, a linguagem trata tudo como objeto e toda operação como envio de mensagem: `1 + 2` é, na verdade, `1.+(2)`. Por isso dá para escrever `5.times`, `"teco".upcase` ou `nil.to_a`. Matz chama isso de *princípio da menor surpresa*: a linguagem deve se comportar como o programador espera.

Ruby segue convenções fortes: métodos que perguntam terminam em `?`, métodos que modificam o objeto ou são "perigosos" terminam em `!`, constantes começam com maiúscula e variáveis em `snake_case`. A comunidade usa o **RuboCop** para padronizar o estilo.

Strings são mutáveis por padrão; adicionar `# frozen_string_literal: true` no topo do arquivo congela os literais, o que evita bugs e melhora o desempenho.

@fontes 1
- Thomas, Hunt e Fowler, *Programming Ruby*, capítulos 2 a 4
- Olsen, *Eloquent Ruby*, capítulos 1 a 4
- [Ruby Style Guide](https://rubystyle.guide)

@modulo 2
**Dependa de comportamentos, não de tipos.** Sandi Metz, em *Practical Object-Oriented Design*, mostra como o **duck typing** ("se anda como pato e grasna como pato, é um pato") torna o código flexível: um método não precisa saber a classe do objeto, só que ele responde à mensagem certa (`respond_to?`).

Suas regras práticas para classes saudáveis: classes pequenas com uma única responsabilidade, métodos curtos, dependências injetadas e poucas suposições sobre outros objetos. Prefira **composição** e módulos a cadeias longas de herança.

Blocos, `yield`, `Proc` e lambdas fazem do Ruby uma linguagem muito funcional: `Enumerable` foi construído só sobre o método `each`, um exemplo clássico de como um pequeno contrato gera muito comportamento.

@fontes 2
- Metz, *Practical Object-Oriented Design*, capítulos 2 (responsabilidade única), 5 (duck typing) e 8 (composição)
- Olsen, *Eloquent Ruby*, capítulos sobre blocos e módulos
- [Documentação de Enumerable](https://docs.ruby-lang.org/en/master/Enumerable.html)

@modulo 3
**Convenção sobre configuração** é a ideia que fez o Rails famoso: se o modelo se chama `Post`, a tabela é `posts`, o controller é `PostsController` e as views ficam em `app/views/posts`. Seguindo os nomes, quase nada precisa ser configurado. A outra ideia é o **DRY** (*Don't Repeat Yourself*), do livro *O Programador Pragmático*.

O Active Record implementa o padrão de mesmo nome descrito por Martin Fowler em *Patterns of Enterprise Application Architecture*: cada objeto representa uma linha e sabe se salvar. Atenção às consultas **N+1**: use `includes` para carregar associações de uma vez (a gem Bullet avisa quando o problema acontece).

O ciclo do **TDD** (vermelho, verde, refatorar), popularizado por Kent Beck, é muito forte na comunidade Ruby, com RSpec e Minitest.

@fontes 3
- [Rails Guides: Active Record Basics](https://guides.rubyonrails.org/active_record_basics.html)
- Fowler, *Patterns of Enterprise Application Architecture* (padrão Active Record)
- Kent Beck, *TDD: Desenvolvimento Guiado por Testes*
