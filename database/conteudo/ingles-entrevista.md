# Base do inglês técnico
> Vocabulário de programação, leitura de documentação e mensagens de erro.

## Projeto
**Glossário pessoal:** monte um glossário com 60 termos técnicos em inglês (com pronúncia, tradução e uma frase de exemplo sua), dividido em categorias: código, ferramentas, equipe e entrevista. Leia o README de um projeto open source que você usa e escreva, em inglês, um resumo de 5 frases do que ele faz.

## Vocabulário essencial de programação
### Teoria
Programação é escrita em inglês: palavras-chave, documentação, mensagens de erro e a maioria das conversas técnicas. Comece pelos termos que você já usa:

- **Code:** *function*, *variable*, *loop*, *array*, *string*, *object*, *class*, *method*, *parameter* / *argument*, *return value*.
- **Ferramentas:** *repository* (repo), *branch*, *commit*, *pull request*, *merge*, *deploy*, *build*, *pipeline*.
- **Problemas:** *bug*, *issue*, *error*, *exception*, *crash*, *fix*, *workaround*, *edge case*.
- **Qualidade:** *test*, *coverage*, *refactor*, *code review*, *readable*, *maintainable*.

Cuidado com **falsos cognatos**: *actually* = "na verdade" (não "atualmente", que é *currently*); *eventually* = "no fim das contas"; *library* = biblioteca (não livraria); *push* = empurrar/enviar.

### Prática
Leia e traduza mentalmente:

```
"I refactored the function to remove a nested loop.
 Actually, the bug was in an edge case: an empty array."
```

Tradução: "Eu refatorei a função para remover um laço aninhado. Na verdade, o bug estava num caso extremo: um array vazio."

Pronúncia de termos comuns: *cache* ("késh"), *queue* ("kiú"), *char* ("tchár"), *GUI* ("gúi"), *SQL* ("ess-kiu-él" ou "síquel").

### Questão
O que significa "actually" em inglês?
- [ ] Atualmente
- [x] Na verdade
- [ ] Rapidamente
- [ ] Atualizar
> "Atualmente" em inglês é "currently" ou "nowadays".

### Questão
Como se diz "caso extremo" (um caso de borda num teste) em inglês?
- [ ] Extreme house
- [x] Edge case
- [ ] Last case
- [ ] Hard test
> "Edge case" é um dos termos mais usados em entrevistas e revisões de código.

### Questão
Qual a pronúncia mais próxima de "queue"?
- [ ] "kê-u-ê"
- [x] "kiú"
- [ ] "quêuê"
- [ ] "cuí"
> As letras "ueue" são mudas: queue soa igual à letra Q.

## Lendo documentação e mensagens de erro
### Teoria
Saber ler inglês técnico é mais urgente do que falar: documentação, Stack Overflow, issues no GitHub e mensagens de erro estão em inglês. Estratégias:

- **Leia por estrutura:** documentação segue padrões como *Overview*, *Getting started*, *Installation*, *Usage*, *Parameters*, *Returns*, *Examples*, *Deprecated*.
- **Palavras de alerta:** *must* (obrigatório), *should* (recomendado), *may* (opcional), *deprecated* (obsoleto, vai sair), *breaking change* (mudança que quebra compatibilidade).
- **Mensagens de erro** costumam dizer o tipo do erro, o que aconteceu e onde: `TypeError: Cannot read properties of undefined (reading 'name') at line 12`.

Use o tradutor como apoio, mas tente primeiro: o vocabulário técnico se repete muito, e em poucas semanas você lê sem traduzir.

### Prática
Decifrando mensagens comuns:

```
ModuleNotFoundError: No module named 'requests'
→ O módulo 'requests' não foi encontrado (falta instalar: pip install requests)

SyntaxError: unexpected token '}'
→ Erro de sintaxe: uma chave '}' apareceu onde não era esperada

This function is deprecated and will be removed in v3.0. Use fetchData() instead.
→ Esta função está obsoleta e será removida na versão 3.0. Use fetchData() no lugar.
```

### Questão
Na documentação, o que significa "deprecated"?
- [ ] Muito rápido
- [x] Obsoleto: ainda funciona, mas será removido no futuro
- [ ] Obrigatório
- [ ] Gratuito
> Recursos "deprecated" devem ser substituídos pela alternativa indicada.

### Questão
Qual a diferença entre "must" e "should" numa especificação?
- [ ] Nenhuma
- [x] "Must" é obrigatório; "should" é recomendado
- [ ] "Should" é obrigatório; "must" é opcional
- [ ] Os dois significam "pode"
> Essas palavras têm significado preciso em padrões técnicos (RFC 2119).

### Questão
O que a mensagem "No module named 'requests'" indica?
- [ ] O código tem erro de lógica
- [x] O módulo requests não está instalado ou não foi encontrado
- [ ] O servidor está fora do ar
- [ ] A internet caiu
> Normalmente se resolve instalando o pacote no ambiente correto.

## Escrevendo em inglês no dia a dia
### Teoria
Em equipes internacionais, você vai escrever em inglês todos os dias: mensagens de commit, descrições de pull request, comentários, issues e mensagens no chat. Regras práticas:

- **Commits** no imperativo e curtos: *"Add login validation"*, *"Fix crash on empty cart"*, *"Update README"*.
- **Issues** com estrutura: *Steps to reproduce*, *Expected behavior*, *Actual behavior*, *Environment*.
- **Code review** educado: *"Could we...?"*, *"What do you think about...?"*, *"Nit:"* (detalhe pequeno), *"LGTM"* (*looks good to me*).
- Frases curtas e diretas valem mais do que vocabulário sofisticado.

Siglas comuns: **ASAP** (o quanto antes), **FYI** (para sua informação), **WIP** (trabalho em andamento), **PTAL** (*please take another look*), **TL;DR** (resumo).

### Prática
Um relato de bug bem escrito:

```
Title: Cart total is wrong when a coupon is applied twice

Steps to reproduce:
1. Add any product to the cart
2. Apply the coupon "GELO10"
3. Apply the same coupon again

Expected behavior: the discount is applied only once.
Actual behavior: the discount is applied twice (20% off).
Environment: Chrome 130, Windows 11
```

### Questão
Qual mensagem de commit segue a convenção em inglês?
- [ ] "Fixed the bug of the cart that was wrong"
- [x] "Fix discount applied twice in cart"
- [ ] "changes"
- [ ] "Fixing stuff ASAP"
> Imperativo, curto e específico.

### Questão
O que significa "LGTM" numa revisão de código?
- [ ] Let's go to the meeting
- [x] Looks good to me (por mim está aprovado)
- [ ] Large git transfer mode
- [ ] Log the message
> É a aprovação informal mais comum em pull requests.

### Questão
Num relato de bug, o que vai em "Actual behavior"?
- [ ] O comportamento esperado
- [x] O que realmente acontece, o comportamento errado observado
- [ ] A versão do sistema
- [ ] O nome do programador
> "Expected" é o esperado; "actual" é o que de fato aconteceu.

# Conversação para entrevistas [intermediario]
> Apresentação pessoal, perguntas comportamentais e o método STAR.

## Projeto
**Pitch gravado:** escreva e grave (áudio ou vídeo) sua apresentação de 1 minuto em inglês e três respostas no método STAR para perguntas comportamentais. Ouça, anote palavras que travaram e regrave. Peça a um colega do seu iglu para fazer as perguntas e dar feedback.

## Apresentando-se: "Tell me about yourself"
### Teoria
Quase toda entrevista começa com **"Tell me about yourself"** ou **"Walk me through your background"**. Não é para contar a vida inteira: é um resumo profissional de 1 a 2 minutos. Uma estrutura simples é **presente, passado e futuro**:

- **Presente:** quem você é hoje. *"I'm a computer science student and a junior web developer."*
- **Passado:** experiências e projetos relevantes. *"Last year I built a study platform with PHP and MySQL."*
- **Futuro:** por que essa vaga. *"Now I'm looking for an internship where I can grow as a back-end developer."*

Use verbos de ação: *built*, *developed*, *improved*, *led*, *learned*, *designed*. Treine em voz alta: a fluência vem da repetição, não de decorar palavra por palavra.

### Prática
```
"Hi, I'm Ana. I'm currently in my second year of Computer Science,
 and I work part-time as a junior front-end developer.
 In my last project, I built a dashboard with React and TypeScript
 that reduced the time our team spent on reports by about 30%.
 I'm really interested in this role because I want to work
 with accessibility and design systems, which is a big focus here."
```

Expressões úteis: *"I'm passionate about..."*, *"I'm currently..."*, *"I've been working with... for two years"*, *"What I enjoy the most is..."*.

### Questão
Qual a melhor estrutura para responder "Tell me about yourself"?
- [ ] Contar a história desde a infância
- [x] Presente, passado e futuro, focando na vida profissional
- [ ] Falar só dos hobbies
- [ ] Repetir o currículo palavra por palavra
> Uma resposta de 1 a 2 minutos, objetiva e ligada à vaga.

### Questão
Como se diz "estou cursando o segundo ano de Ciência da Computação"?
- [ ] I am doing the second year of Computer Science course in the moment
- [x] I'm currently in my second year of Computer Science
- [ ] I make the second year of Computer Science
- [ ] I am in the second class of Computer Science
> "Currently" deixa claro que é a situação atual.

### Questão
Qual verbo de ação descreve bem que você criou um projeto?
- [ ] Did
- [ ] Made a thing
- [x] Built
- [ ] Was
> Verbos como built, developed e designed passam mais iniciativa.

## Perguntas comportamentais e o método STAR
### Teoria
Perguntas comportamentais pedem exemplos reais: **"Tell me about a time when..."** (fale de uma vez em que...). As mais comuns:
- *"...you faced a difficult bug."*
- *"...you disagreed with a teammate."*
- *"...you made a mistake."*
- *"...you had to learn something quickly."*

Responda com o **método STAR**:
- **S**ituation: o contexto.
- **T**ask: qual era o seu desafio ou responsabilidade.
- **A**ction: o que **você** fez (use *I*, não só *we*).
- **R**esult: o resultado, de preferência com números, e o que aprendeu.

Fale de erros com honestidade e mostre aprendizado: *"Looking back, I would..."*.

### Prática
```
Q: Tell me about a time you solved a difficult bug.

S: "In my school project, our app crashed randomly for some users."
T: "I was responsible for finding the cause before our presentation."
A: "I added logs, reproduced the problem and found that it happened
    only when the cart was empty. I wrote a test for that case and fixed it."
R: "The crashes stopped, and since then I always test edge cases first."
```

Conectores úteis: *first*, *then*, *after that*, *as a result*, *in the end*, *because of that*.

### Questão
No método STAR, o que vai na parte "Action"?
- [ ] O contexto do problema
- [x] O que você fez para resolver
- [ ] O nome da empresa
- [ ] A pergunta do entrevistador
> Destaque suas ações, usando "I" para mostrar sua contribuição.

### Questão
Como começa uma pergunta comportamental típica?
- [ ] "What is a variable?"
- [x] "Tell me about a time when..."
- [ ] "How old are you?"
- [ ] "Where is the office?"
> Elas pedem exemplos reais do seu passado.

### Questão
Por que incluir números no "Result"?
- [ ] Para parecer mais velho
- [x] Porque resultados mensuráveis deixam o impacto concreto e convincente
- [ ] Porque é obrigatório em inglês
- [ ] Para confundir o entrevistador
> "Reduced load time by 40%" diz muito mais que "made it faster".

## Fazendo perguntas e lidando com o que não entendeu
### Teoria
Não entender uma pergunta é normal, inclusive para nativos. O erro é fingir que entendeu. Frases que salvam:

- *"Could you repeat that, please?"*
- *"Sorry, could you speak a bit more slowly?"*
- *"Just to make sure I understood: you're asking about..., right?"*
- *"Let me think about that for a second."*
- *"I'm not sure, but my guess would be..."*
- *"I haven't worked with that yet, but I'd approach it by..."*

No fim da entrevista, quase sempre vem **"Do you have any questions for us?"**. Tenha perguntas prontas: mostra interesse.
- *"What does a typical day look like for this role?"*
- *"How does the team handle code reviews?"*
- *"What would success look like in the first three months?"*

### Prática
Diálogo de exemplo:

```
Interviewer: "How would you handle technical debt in a legacy codebase?"
You: "Just to make sure I understood: you mean old code that's hard
      to maintain, right?"
Interviewer: "Exactly."
You: "Let me think for a second... First, I'd add tests around the
      parts we need to change. Then I'd refactor in small steps..."
```

### Questão
Qual é a melhor atitude ao não entender uma pergunta?
- [ ] Responder qualquer coisa
- [x] Pedir para repetir ou confirmar o que entendeu
- [ ] Ficar em silêncio
- [ ] Mudar de assunto
> Confirmar mostra comunicação clara, uma habilidade valorizada.

### Questão
Como pedir para o entrevistador falar mais devagar?
- [ ] Speak slow you
- [x] Could you speak a bit more slowly, please?
- [ ] Less fast talking please
- [ ] You talk very rapid
> "Could you..., please?" é a forma educada de fazer pedidos.

### Questão
O que responder a "Do you have any questions for us?"?
- [ ] "No."
- [x] Fazer perguntas sobre a rotina, a equipe e as expectativas da vaga
- [ ] Perguntar quando é a próxima folga
- [ ] Perguntar o resultado na hora
> Boas perguntas mostram interesse genuíno pela vaga.

# Entrevista técnica em inglês [avancado]
> Pensar em voz alta, explicar código e system design.

## Projeto
**Simulado completo:** com um colega do seu iglu, faça uma entrevista simulada de 45 minutos em inglês: 5 minutos de apresentação, 25 de um problema de algoritmo resolvido em voz alta e 15 de perguntas. Gravem, troquem os papéis e escrevam um feedback em inglês usando as frases aprendidas.

## Pensando em voz alta
### Teoria
Em entrevistas de código, o entrevistador avalia **como você pensa**, não só a resposta. Por isso é preciso **pensar em voz alta** (*think out loud*). Um roteiro em inglês:

1. **Clarify:** *"Can I assume the input is always sorted?"*, *"What should I return if the list is empty?"*
2. **Examples:** *"Let me walk through a quick example."*
3. **Brute force:** *"A naive approach would be... It would be O(n²)."*
4. **Optimize:** *"We could improve this by using a hash map..."*
5. **Code:** narre o que escreve: *"Now I'm iterating over the array..."*
6. **Test:** *"Let me test it with an edge case: an empty array."*

Se travar, diga o que está pensando: *"I'm stuck on how to handle duplicates. One option could be..."*. O silêncio total é o pior cenário.

### Prática
```
"So, if I understood correctly, I need to find two numbers that add up
 to the target. Can I assume there's always exactly one solution?
 ...Great. The brute force approach would check every pair, which is
 O(n squared). A better approach is to use a hash map: for each number,
 I check if the complement is already in the map. That makes it O(n)
 in time and O(n) in space. Let me code that."
```

Leia complexidades em voz alta: O(n²) = *"O of n squared"*; O(log n) = *"O of log n"*; O(n log n) = *"n log n"*.

### Questão
Por que pensar em voz alta numa entrevista técnica?
- [ ] Para preencher o tempo
- [x] Porque o entrevistador avalia seu raciocínio, não só a resposta final
- [ ] Porque é proibido escrever código
- [ ] Para mostrar que você sabe muitas palavras
> Mesmo sem terminar, um raciocínio claro conta muitos pontos.

### Questão
Como se lê O(n²) em inglês?
- [ ] O of n two
- [x] O of n squared
- [ ] O n double
- [ ] Zero n square
> "Squared" é ao quadrado; "cubed", ao cubo.

### Questão
Qual frase serve para esclarecer o problema antes de codar?
- [ ] "I know the answer."
- [x] "Can I assume the input is always sorted?"
- [ ] "This is easy."
- [ ] "Let me code it now."
> Esclarecer suposições evita resolver o problema errado.

## Explicando código e decisões técnicas
### Teoria
Você vai precisar explicar código que escreveu, defender escolhas e falar de trade-offs. Vocabulário útil:

- **Explicar:** *"This function takes... and returns..."*, *"Here we iterate over..."*, *"This handles the case where..."*
- **Trade-offs:** *"The trade-off is..."*, *"It's faster but uses more memory"*, *"It's simpler, although less flexible."*
- **Opinião com cuidado:** *"I'd prefer... because..."*, *"In my experience..."*, *"It depends on..."*
- **Concordar e discordar:** *"That's a good point."*, *"I see what you mean, but..."*, *"Have we considered...?"*

Projetos do seu portfólio são ótimo material: prepare uma explicação de 2 minutos em inglês do problema, das tecnologias, de uma decisão difícil e do que faria diferente.

### Prática
```
"In this project, I used MySQL instead of a NoSQL database because
 our data is very relational: users, courses and lessons are all
 connected. The trade-off is that the schema is less flexible,
 but we get foreign keys and transactions, which keep the data
 consistent. If I did it again, I'd add indexes earlier, because
 some queries got slow when we reached ten thousand users."
```

### Questão
Qual expressão apresenta as vantagens e desvantagens de uma escolha?
- [ ] "The problem is solved."
- [x] "The trade-off is..."
- [ ] "I don't know."
- [ ] "It's the best."
> Falar de trade-offs mostra maturidade técnica.

### Questão
Como discordar educadamente de uma sugestão?
- [ ] "You are wrong."
- [x] "I see what you mean, but have we considered...?"
- [ ] "No way."
- [ ] "That's stupid."
> Reconhecer o ponto do outro antes de propor alternativa mantém a conversa produtiva.

### Questão
Como começar a explicar o que uma função faz?
- [x] "This function takes a list of users and returns..."
- [ ] "This function is making things."
- [ ] "Function do the list."
- [ ] "I don't remember what it does."
> Diga entradas, processamento e saída.

## System design e negociação
### Teoria
Em vagas mais avançadas aparece a entrevista de **system design**: projetar um sistema como "um encurtador de URLs" ou "um chat". Vocabulário essencial:
- *scalability*, *availability*, *latency*, *throughput*, *bottleneck*
- *load balancer*, *cache*, *database*, *queue*, *replication*, *sharding*
- *read-heavy* / *write-heavy*, *single point of failure*

Roteiro: esclarecer requisitos (*"How many users do we expect?"*), estimar volume, desenhar a arquitetura em alto nível, aprofundar uma parte e discutir trade-offs.

No fim do processo pode vir a conversa sobre **oferta**: *salary expectations*, *benefits*, *remote work*, *start date*. Frases: *"Based on my research, I'm looking for a range of..."*, *"Is there any flexibility on...?"*, *"Thank you, I'd like some time to think about it."*

### Prática
```
"Before designing, I'd like to clarify the requirements.
 How many daily active users are we expecting?
 ...OK, 10 million. The system is read-heavy, so I'd put a cache
 in front of the database to reduce latency. To avoid a single
 point of failure, I'd run several app servers behind a load balancer
 and replicate the database. The bottleneck would probably be..."
```

### Questão
O que significa "single point of failure"?
- [ ] Um erro de digitação
- [x] Um componente cuja falha derruba o sistema inteiro
- [ ] Uma única linha de código
- [ ] Um servidor muito rápido
> Bons projetos eliminam pontos únicos de falha com redundância.

### Questão
Um sistema "read-heavy" é aquele que:
- [ ] Escreve muito mais do que lê
- [x] Recebe muito mais leituras do que escritas
- [ ] Tem muitos livros
- [ ] É pesado para instalar
> Sistemas assim se beneficiam muito de cache e réplicas de leitura.

### Questão
Qual frase é adequada para falar de pretensão salarial?
- [ ] "Give me a lot of money."
- [x] "Based on my research, I'm looking for a range of..."
- [ ] "I accept anything."
- [ ] "How much do you earn?"
> Mostra preparo e abre a negociação com respeito.
