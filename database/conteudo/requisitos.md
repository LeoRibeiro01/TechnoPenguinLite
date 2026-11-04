# O que são requisitos
> Conceitos, tipos de requisitos e partes interessadas.

## Projeto
**Requisitos de um app de cantina:** entreviste duas pessoas da sua escola (um aluno e um funcionário da cantina), liste pelo menos 10 requisitos funcionais e 5 não funcionais para um app de pedidos, e identifique todas as partes interessadas.

## Por que requisitos importam
### Teoria
**Requisitos** descrevem **o que** um sistema deve fazer e **sob quais condições**. A **Engenharia de Requisitos** é o processo de descobrir, documentar, validar e gerenciar esses requisitos.

Estudos mostram que boa parte dos projetos de software que fracassam tem problemas de requisitos: o sistema funciona, mas **não resolve o problema certo**. Corrigir um requisito errado depois que o software está pronto custa muito mais do que no início.

Etapas principais:
- **Elicitação:** descobrir as necessidades.
- **Análise:** organizar, priorizar e resolver conflitos.
- **Especificação:** documentar.
- **Validação:** confirmar com os interessados.
- **Gerenciamento:** controlar mudanças ao longo do projeto.

### Prática
A famosa história do balanço na árvore ilustra o problema: o cliente pediu algo, o analista entendeu outra coisa, o programador fez uma terceira, e o que o cliente **realmente precisava** era um simples pneu pendurado na árvore.

Exercício: escreva em uma frase o problema que um app de cantina resolve, **antes** de pensar em funcionalidades. Ex.: *"Alunos perdem o intervalo na fila da cantina."*

### Questão
O que a Engenharia de Requisitos busca definir?
- [ ] A linguagem de programação
- [x] O que o sistema deve fazer e sob quais condições
- [ ] A cor dos botões
- [ ] O servidor de hospedagem
> Requisitos focam no problema e nas necessidades, não na tecnologia.

### Questão
Quando corrigir um requisito errado é mais barato?
- [x] No início do projeto
- [ ] Depois da entrega
- [ ] Durante os testes finais
- [ ] Nunca há diferença
> O custo de correção cresce a cada fase do projeto.

### Questão
Qual etapa confirma com os interessados que os requisitos estão corretos?
- [ ] Elicitação
- [ ] Implementação
- [x] Validação
- [ ] Implantação
> Validar evita construir o sistema errado.

## Requisitos funcionais e não funcionais
### Teoria
- **Requisitos funcionais (RF):** o que o sistema **faz**. Ex.: "O sistema deve permitir que o aluno faça um pedido."
- **Requisitos não funcionais (RNF):** **como** o sistema deve ser: qualidades e restrições. Ex.: desempenho, segurança, usabilidade, disponibilidade, compatibilidade, acessibilidade.

Há também **regras de negócio** (políticas da organização: "um aluno só pode ter 2 pedidos em aberto") e **restrições** (prazos, orçamento, tecnologia obrigatória, leis como a LGPD).

Bons RNFs são **mensuráveis**: em vez de "o sistema deve ser rápido", escreva "a lista de produtos deve carregar em até 2 segundos em 4G".

### Prática
App de cantina:

```
RF01 O aluno deve poder ver o cardápio do dia.
RF02 O aluno deve poder pagar o pedido pelo app.
RF03 A cantina deve receber um aviso a cada novo pedido.
RNF01 O app deve funcionar em Android 10 ou superior e iOS 15 ou superior.
RNF02 O pagamento deve usar conexão criptografada (HTTPS).
RNF03 95% dos pedidos devem ser confirmados em até 3 segundos.
RN01 Pedidos só podem ser feitos até 10 minutos antes do intervalo.
```

### Questão
"O sistema deve permitir emitir o boletim do aluno" é um requisito:
- [x] Funcional
- [ ] Não funcional
- [ ] De hardware
- [ ] Regra de negócio obrigatoriamente
> Descreve algo que o sistema faz.

### Questão
"O sistema deve suportar 1.000 usuários simultâneos" é um requisito:
- [ ] Funcional
- [x] Não funcional (desempenho/escalabilidade)
- [ ] De interface
- [ ] Inválido
> Descreve uma qualidade, não uma funcionalidade.

### Questão
Qual RNF está mais bem escrito?
- [ ] O sistema deve ser rápido
- [ ] O sistema deve ser bonito
- [x] A tela inicial deve carregar em até 2 segundos numa conexão 4G
- [ ] O sistema deve ser fácil
> Requisitos mensuráveis podem ser testados.

## Partes interessadas
### Teoria
**Partes interessadas (stakeholders)** são todas as pessoas ou grupos afetados pelo sistema ou que influenciam o projeto: usuários finais, clientes que pagam, gestores, equipe técnica, suporte, órgãos reguladores, e até quem é afetado indiretamente.

Esquecer um stakeholder é uma das principais fontes de requisitos ausentes. Por exemplo: num app escolar, os **pais** e a **secretaria** também têm necessidades.

Ferramentas úteis:
- **Mapa de stakeholders** (poder × interesse).
- **Personas:** personagens fictícios que representam grupos de usuários, com objetivos e dificuldades.

### Prática
Persona para o app de cantina:

```
Nome: Júlia, 15 anos, 1º ano do ensino médio
Objetivo: comprar o lanche sem perder o intervalo
Frustrações: filas longas, não saber se o salgado acabou
Tecnologia: usa o celular o dia todo, mas tem pouco crédito de internet
Necessidade implícita: o app precisa ser leve e funcionar com Wi-Fi da escola
```

### Questão
Quem é um stakeholder de um sistema?
- [ ] Apenas o programador
- [ ] Apenas o cliente que paga
- [x] Qualquer pessoa ou grupo afetado pelo sistema ou que influencia o projeto
- [ ] Apenas os usuários com mais de 18 anos
> Inclui usuários, clientes, gestores, suporte, reguladores e outros.

### Questão
O que é uma persona?
- [ ] Um usuário real cadastrado
- [x] Um personagem fictício que representa um grupo de usuários
- [ ] Um tipo de requisito não funcional
- [ ] Um diagrama de banco de dados
> Personas ajudam a equipe a lembrar para quem está construindo.

### Questão
Qual o risco de esquecer um stakeholder?
- [ ] Nenhum
- [x] Deixar de levantar requisitos importantes
- [ ] O sistema ficar mais rápido
- [ ] Gastar menos
> Necessidades não descobertas aparecem tarde, quando custa mais mudar.

# Elicitação e especificação
> Técnicas para descobrir requisitos e formas de documentá-los.

## Projeto
**Backlog do TechnoPenguin:** escreva 10 histórias de usuário para funcionalidades da plataforma (iglus, gelo, vestuário...), cada uma com critérios de aceitação no formato Dado/Quando/Então, e organize-as num quadro (Trello, GitHub Projects) com prioridade MoSCoW.

## Técnicas de elicitação
### Teoria
**Elicitar** é descobrir requisitos junto às partes interessadas. Técnicas comuns:

- **Entrevistas:** conversas estruturadas ou abertas. Faça perguntas abertas ("Como você faz hoje?") e evite induzir respostas.
- **Questionários:** alcançam muitas pessoas, mas com menos profundidade.
- **Observação/etnografia:** ver o trabalho acontecendo revela o que as pessoas não dizem.
- **Workshops e brainstorming:** várias partes interessadas juntas.
- **Análise de documentos** e de sistemas existentes.
- **Prototipação:** mostrar algo concreto e colher reação.

Combine técnicas: o que as pessoas **dizem** que fazem nem sempre é o que **fazem**.

### Prática
Roteiro de entrevista com a funcionária da cantina:

```
1. Como funciona um dia típico na cantina?
2. Qual o momento mais difícil do dia? Por quê?
3. Como vocês sabem o que está acabando?
4. Já perderam vendas? Em que situações?
5. Se pudesse mudar uma coisa no processo, o que seria?
```

Note: nenhuma pergunta sugere a solução ("Você quer um app?").

### Questão
Qual técnica revela como o trabalho realmente acontece, não só como é descrito?
- [ ] Questionário
- [x] Observação
- [ ] Análise de custos
- [ ] Diagrama de classes
> Observar mostra atalhos, improvisos e problemas que ninguém menciona.

### Questão
Qual é um bom tipo de pergunta numa entrevista de elicitação?
- [ ] "Você quer um botão azul?"
- [x] "Como você realiza essa tarefa hoje?"
- [ ] "Concorda que o sistema atual é ruim?"
- [ ] "Qual linguagem devemos usar?"
> Perguntas abertas não induzem a resposta.

### Questão
Qual a vantagem de mostrar um protótipo?
- [ ] Dispensar entrevistas
- [x] Obter reações concretas e descobrir requisitos que não apareceram em conversa
- [ ] Entregar o sistema final mais cedo
- [ ] Evitar testes
> Ver algo concreto facilita dizer "não era bem isso".

## Histórias de usuário e critérios de aceitação
### Teoria
Em métodos ágeis, requisitos costumam ser escritos como **histórias de usuário**:

**Como** [tipo de usuário], **quero** [ação] **para** [benefício].

Boas histórias seguem o **INVEST**: Independentes, Negociáveis, Valiosas, Estimáveis, Pequenas (Small) e Testáveis.

Cada história precisa de **critérios de aceitação**, frequentemente no formato **Dado / Quando / Então** (Gherkin), que dizem quando ela está pronta e viram testes.

### Prática
```
História: Como membro de um iglu, quero ser avisado quando o gelo do iglu
estiver para derreter, para que eu possa estudar e salvar a ofensiva.

Critérios de aceitação:
Dado que ninguém do iglu fez lição há 3 dias
Quando eu abrir o painel
Então devo ver um alerta "O gelo do iglu vai derreter amanhã!"

Dado que o gelo do iglu derreteu
Quando eu abrir o app
Então devo receber uma notificação informando o derretimento
```

### Questão
Qual o formato clássico de uma história de usuário?
- [ ] Se... então... senão
- [x] Como... quero... para...
- [ ] Início... fim
- [ ] Entrada... saída
> Quem, o quê e por quê.

### Questão
Para que servem os critérios de aceitação?
- [ ] Definir o salário da equipe
- [x] Definir quando a história está pronta, de forma testável
- [ ] Escolher a linguagem
- [ ] Desenhar o logotipo
> Eles orientam o desenvolvimento e os testes.

### Questão
No INVEST, o que significa o "T"?
- [ ] Técnica
- [x] Testável
- [ ] Temporária
- [ ] Transparente
> Se não dá para testar, não dá para saber se está pronta.

## Casos de uso e documentação
### Teoria
**Casos de uso** descrevem interações entre um **ator** (usuário ou outro sistema) e o sistema para atingir um objetivo. São representados no **diagrama de casos de uso** da UML (bonecos e elipses) e detalhados em texto:

- Nome, ator principal, pré-condições.
- **Fluxo principal** (o caminho feliz).
- **Fluxos alternativos e de exceção**.
- Pós-condições.

Documentos mais formais, como o **Documento de Requisitos** ou a SRS (IEEE 830/ISO 29148), reúnem visão geral, escopo, requisitos e glossário. O nível de formalidade depende do projeto: sistemas críticos (saúde, aviação) exigem mais rigor.

### Prática
```
Caso de uso: Entrar num iglu por convite
Ator: Usuário convidado
Pré-condição: o usuário recebeu um convite pendente

Fluxo principal:
1. O usuário abre o convite.
2. O sistema mostra as linguagens e assuntos obrigatórios do iglu.
3. O usuário aceita.
4. O sistema adiciona o usuário ao iglu e as trilhas obrigatórias ao seu perfil.

Fluxo alternativo:
3a. O usuário recusa → o convite é marcado como recusado.

Pós-condição: o usuário é membro do iglu.
```

### Questão
Num caso de uso, o que é o ator?
- [ ] Um banco de dados
- [x] Quem interage com o sistema (pessoa ou outro sistema)
- [ ] O programador
- [ ] Um botão da tela
> Atores ficam fora do sistema e iniciam ou participam das interações.

### Questão
O que descreve o fluxo principal?
- [ ] Os erros possíveis
- [x] O caminho mais comum, em que tudo dá certo
- [ ] A lista de atores
- [ ] O banco de dados
> Desvios e erros ficam nos fluxos alternativos e de exceção.

### Questão
Quando a documentação de requisitos deve ser mais formal e detalhada?
- [ ] Sempre no mesmo nível
- [x] Em sistemas críticos, como saúde e aviação
- [ ] Nunca
- [ ] Só em jogos
> A formalidade acompanha o risco do sistema.

# Validação e gestão de requisitos
> Priorização, protótipos, rastreabilidade e mudanças.

## Projeto
**Matriz de rastreabilidade:** para 8 requisitos do app de cantina, crie uma matriz ligando cada requisito à sua origem (quem pediu), às telas do protótipo e aos testes que o verificam. Simule uma mudança de requisito e mostre o impacto usando a matriz.

## Priorização
### Teoria
Nunca há tempo para tudo: é preciso **priorizar**. Técnicas comuns:

- **MoSCoW:** **M**ust have (obrigatório), **S**hould have (importante), **C**ould have (desejável), **W**on't have now (fica para depois).
- **Valor × esforço:** primeiro o que dá muito valor com pouco esforço.
- **Modelo Kano:** separa requisitos básicos (esperados), de desempenho (quanto mais, melhor) e encantadores (surpreendem).

O conjunto mínimo que já entrega valor e permite aprender com usuários reais é o **MVP** (Produto Mínimo Viável).

### Prática
Priorizando o app de cantina com MoSCoW:

```
Must:   ver cardápio, fazer pedido, avisar a cantina
Should: pagar pelo app, histórico de pedidos
Could:  avaliar lanches, programa de pontos
Won't:  entrega na sala de aula (fica para uma versão futura)
```

O MVP é formado pelos itens "Must".

### Questão
No MoSCoW, o que significa "Won't have"?
- [ ] Nunca será feito
- [x] Não entra agora; pode ficar para uma versão futura
- [ ] É obrigatório
- [ ] Foi cancelado por erro
> Explicitar o que fica de fora também alinha expectativas.

### Questão
O que é um MVP?
- [ ] O jogador mais valioso
- [x] A menor versão do produto que já entrega valor e permite aprender
- [ ] A versão final completa
- [ ] Um documento de requisitos
> Produto Mínimo Viável.

### Questão
No modelo Kano, requisitos que surpreendem positivamente o usuário são chamados de:
- [ ] Básicos
- [ ] Obrigatórios
- [x] Encantadores (ou atrativos)
- [ ] Inválidos
> A ausência deles não incomoda, mas a presença encanta.

## Validação e prototipação
### Teoria
**Validar** é garantir que os requisitos representam o que os interessados precisam (estamos construindo o **produto certo**?). Diferente de **verificar**, que confere se o produto atende à especificação (estamos construindo o **produto do jeito certo**?).

Técnicas de validação:
- **Revisões e inspeções** dos documentos com os interessados.
- **Protótipos:** de baixa fidelidade (papel, wireframes) a alta fidelidade (Figma clicável).
- **Testes de aceitação** baseados nos critérios.
- **Testes de usabilidade** com usuários reais.

Bons requisitos são: corretos, completos, consistentes (sem contradições), não ambíguos, verificáveis e rastreáveis.

### Prática
Encontre os problemas nestes requisitos:

```
"O sistema deve ser amigável."           → ambíguo, não verificável
"O app deve aceitar pagamento."          → incompleto (quais formas?)
"Pedidos até 10h" e "Pedidos até 10h30"  → inconsistente
"O sistema deve responder rápido."       → não mensurável
```

Reescreva: *"Um novo usuário deve conseguir fazer o primeiro pedido em até 2 minutos, sem ajuda, em 90% dos testes de usabilidade."*

### Questão
Qual a diferença entre validação e verificação?
- [ ] São sinônimos
- [x] Validação: produto certo para o usuário; verificação: produto conforme a especificação
- [ ] Validação é só para hardware
- [ ] Verificação acontece antes da elicitação
> "Construímos a coisa certa?" versus "construímos a coisa do jeito certo?"

### Questão
"O sistema deve ser amigável" tem qual problema?
- [ ] É muito específico
- [x] É ambíguo e não pode ser verificado
- [ ] É um requisito funcional perfeito
- [ ] Tem números demais
> Sem critério mensurável, não há como testar.

### Questão
Qual a vantagem de um protótipo de baixa fidelidade?
- [ ] Ser idêntico ao produto final
- [x] Ser rápido e barato de fazer e mudar, ideal para validar ideias cedo
- [ ] Não precisar de usuários
- [ ] Substituir o código
> Papel e wireframes permitem errar barato.

## Rastreabilidade e mudanças
### Teoria
Requisitos **mudam**: o mercado muda, os usuários aprendem, as leis mudam. O **gerenciamento de requisitos** controla essas mudanças sem perder o rumo.

- **Rastreabilidade:** ligar cada requisito à sua origem, ao design, ao código e aos testes. Assim, ao mudar um requisito, sabemos o que é afetado (**análise de impacto**).
- **Versionamento:** cada requisito tem identificador e histórico.
- **Controle de mudanças:** pedidos de mudança são avaliados (custo, prazo, risco) antes de aprovados.

Em métodos ágeis, o **backlog do produto**, gerenciado pelo Product Owner, é repriorizado continuamente, e a mudança é vista como natural.

O perigo é o **scope creep**: o escopo cresce aos poucos, sem controle, até estourar prazos.

### Prática
Trecho de matriz de rastreabilidade:

```
Requisito | Origem              | Tela/Protótipo      | Teste
RF01      | Entrevista aluno    | Tela Cardápio       | TA-01, TA-02
RF02      | Entrevista cantina  | Tela Pagamento      | TA-05
RNF02     | LGPD / segurança    | (todas)             | TS-01 (HTTPS)
```

Se o RF02 mudar (ex.: aceitar Pix), a matriz mostra que a tela de pagamento e o teste TA-05 precisam ser revistos.

### Questão
O que é rastreabilidade de requisitos?
- [ ] Rastrear a localização do usuário
- [x] Ligar cada requisito à origem, ao design, ao código e aos testes
- [ ] Registrar os erros do servidor
- [ ] Contar linhas de código
> Permite saber o impacto de qualquer mudança.

### Questão
O que é scope creep?
- [ ] Uma ferramenta de testes
- [x] O crescimento descontrolado do escopo ao longo do projeto
- [ ] A redução de requisitos
- [ ] Um tipo de diagrama
> Pequenos acréscimos sem análise acabam estourando prazo e orçamento.

### Questão
Em métodos ágeis, onde os requisitos são mantidos e repriorizados?
- [ ] No contrato assinado
- [x] No backlog do produto
- [ ] No código-fonte
- [ ] No servidor de produção
> O Product Owner ordena o backlog conforme o valor.
