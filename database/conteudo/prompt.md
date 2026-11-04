# Fundamentos de IA generativa e prompts
> Como os modelos de linguagem funcionam e o que torna um prompt bom.

## Projeto
**Antes e depois:** escolha três tarefas reais (explicar um conceito, revisar um texto, criar um plano de estudos). Escreva um prompt simples e um prompt aprimorado para cada uma, compare as respostas e registre o que mudou e por quê numa tabela.

## Como funcionam os modelos de linguagem
### Teoria
Os **grandes modelos de linguagem (LLMs)**, como Claude, ChatGPT e Gemini, foram treinados com enormes quantidades de texto e aprenderam padrões da linguagem. Ao receber um texto (o **prompt**), eles geram a resposta **um pedaço de cada vez** (os **tokens**), escolhendo continuações prováveis dado o contexto.

Consequências práticas:
- O modelo **não lê sua mente**: só sabe o que está no prompt e no que aprendeu.
- Ele pode **"alucinar"**: produzir informações falsas com confiança. Confira fatos importantes.
- A **janela de contexto** é limitada: conversas muito longas podem perder detalhes.
- Os modelos têm uma **data de corte** de conhecimento, a menos que usem ferramentas como busca.

**Engenharia de prompt** é a habilidade de escrever instruções que levam o modelo a respostas úteis, precisas e no formato certo.

### Prática
Compare:

```
Prompt vago:
  "Fale sobre Python."

Prompt claro:
  "Sou estudante do 1º ano do ensino médio e nunca programei.
   Explique em até 5 parágrafos curtos o que é Python, para que é usado
   e mostre um exemplo de código de 3 linhas com comentários."
```

O segundo diz **quem você é**, **o que quer**, **o tamanho** e **o formato**.

### Questão
O que é uma "alucinação" de um modelo de linguagem?
- [ ] Um erro de digitação do usuário
- [x] Uma resposta falsa ou inventada apresentada com confiança
- [ ] Uma imagem gerada
- [ ] Uma pausa na resposta
> Por isso informações importantes devem ser verificadas.

### Questão
Por que o prompt claro do exemplo funciona melhor?
- [ ] Porque é mais curto
- [x] Porque informa contexto, objetivo, tamanho e formato esperados
- [ ] Porque usa palavras difíceis
- [ ] Porque está em inglês
> Quanto menos o modelo precisar adivinhar, melhor a resposta.

### Questão
O que são tokens?
- [ ] Senhas de acesso
- [x] Pedaços de texto que o modelo processa e gera
- [ ] Moedas virtuais
- [ ] Imagens
> Uma palavra pode ser um ou vários tokens.

## Anatomia de um bom prompt
### Teoria
Um prompt eficaz costuma ter:
- **Papel ou contexto:** quem é você, para quem é a resposta, qual a situação.
- **Tarefa clara:** um verbo de ação direto (explique, liste, compare, reescreva).
- **Restrições:** tamanho, tom, nível, o que evitar.
- **Formato de saída:** lista, tabela, JSON, código com comentários.
- **Exemplos** do que você espera, quando possível.
- **Critérios de qualidade:** "explique o porquê de cada passo".

Use **delimitadores** (aspas triplas, tags como `<texto>...</texto>`) para separar instruções do material a ser processado. E diga **o que fazer**, não só o que não fazer.

### Prática
```
Contexto: Sou professor de Lógica de Programação para alunos de 14 anos.
Tarefa: Crie 5 questões de múltipla escolha sobre laços de repetição.
Restrições: linguagem simples, sem pegadinhas, uma resposta correta por questão.
Formato: para cada questão, enunciado, 4 alternativas (A–D), a correta e
uma explicação de uma frase.
Material de referência:
<conteudo>
Laços repetem um bloco de código. O "para" repete um número conhecido de
vezes; o "enquanto" repete enquanto uma condição for verdadeira.
</conteudo>
```

### Questão
Para que servem delimitadores como `<texto>...</texto>` num prompt?
- [ ] Deixar o prompt bonito
- [x] Separar claramente as instruções do conteúdo a ser processado
- [ ] Traduzir o texto
- [ ] Reduzir o custo
> Evita que o modelo confunda o material com as instruções.

### Questão
Qual destas instruções é mais eficaz?
- [ ] "Não seja longo."
- [x] "Responda em no máximo 3 frases."
- [ ] "Seja bom."
- [ ] "Faça direito."
> Instruções positivas e mensuráveis são mais fáceis de seguir.

### Questão
Qual elemento ajuda o modelo a entregar a resposta no formato desejado?
- [ ] Pedir "por favor" várias vezes
- [x] Especificar o formato de saída (lista, tabela, JSON...)
- [ ] Escrever tudo em maiúsculas
- [ ] Enviar o prompt duas vezes
> Diga explicitamente como quer receber a resposta.

## Iteração e refinamento
### Teoria
Raramente o primeiro prompt é o melhor. Trate a conversa como um **ciclo**:
1. Escreva o prompt.
2. Avalie a resposta: está correta? completa? no formato?
3. Refine: adicione contexto que faltou, corrija o rumo, peça ajustes específicos.

Técnicas úteis:
- **Peça para o modelo perguntar:** "Antes de responder, faça perguntas sobre o que estiver faltando."
- **Divida tarefas grandes** em etapas menores.
- **Peça revisão:** "Revise sua resposta procurando erros e corrija."
- **Dê feedback específico:** "O exemplo 2 está avançado demais; troque por algo do dia a dia."

### Prática
```
Você: Crie um plano de estudos de Python.
IA:   (plano genérico de 12 semanas)
Você: Tenho 20 minutos por dia e quero criar um bot para o Discord do meu
      iglu em 1 mês. Refaça o plano com metas semanais e um mini-projeto
      por semana. Antes, me pergunte o que precisar saber.
IA:   Você já programou antes? Usa Windows ou Linux?...
```

### Questão
O que fazer quando a primeira resposta não é boa?
- [ ] Desistir da IA
- [x] Refinar o prompt com mais contexto e feedback específico
- [ ] Repetir exatamente o mesmo prompt
- [ ] Escrever mais rápido
> Iterar é parte normal do processo.

### Questão
Qual a vantagem de pedir para o modelo fazer perguntas antes de responder?
- [ ] Deixar a conversa mais longa
- [x] Descobrir informações que faltam e evitar suposições erradas
- [ ] Testar a internet
- [ ] Reduzir a qualidade
> O modelo aponta lacunas que você nem percebeu.

### Questão
Como lidar com uma tarefa grande e complexa?
- [ ] Pedir tudo num único prompt gigante sempre
- [x] Dividir em etapas menores e encadeadas
- [ ] Evitar dar contexto
- [ ] Usar só uma palavra
> Etapas menores são mais fáceis de verificar e corrigir.

# Técnicas avançadas de prompt
> Exemplos, raciocínio passo a passo, papéis e saídas estruturadas.

## Projeto
**Biblioteca de prompts do iglu:** crie, com seu iglu, uma coleção de 10 prompts reutilizáveis (com variáveis entre colchetes) para tarefas de estudo: explicar conceitos, gerar exercícios, revisar código, criar resumos. Teste cada um com pelo menos dois temas e documente os resultados.

## Few-shot: ensinando com exemplos
### Teoria
- **Zero-shot:** você só descreve a tarefa.
- **Few-shot:** você mostra **alguns exemplos** de entrada e saída antes do caso real. O modelo imita o padrão: formato, tom, nível de detalhe.

Few-shot é muito útil para classificações, transformações de texto e formatos específicos. Dicas:
- Use exemplos **variados** e **representativos**.
- Mantenha o **mesmo formato** em todos.
- Coloque o caso real no final, seguindo o padrão.

### Prática
```
Classifique o sentimento do comentário sobre a aula como POSITIVO, NEGATIVO ou NEUTRO.

Comentário: "Amei a explicação de laços, finalmente entendi!"
Sentimento: POSITIVO

Comentário: "A lição tinha muitos erros no código."
Sentimento: NEGATIVO

Comentário: "A aula foi sobre variáveis."
Sentimento: NEUTRO

Comentário: "O projeto do módulo foi difícil, mas aprendi muito."
Sentimento:
```

### Questão
O que caracteriza o few-shot prompting?
- [ ] Não dar nenhuma instrução
- [x] Mostrar alguns exemplos de entrada e saída antes do caso real
- [ ] Usar apenas uma palavra
- [ ] Repetir o prompt várias vezes
> Os exemplos ensinam o padrão esperado.

### Questão
Como devem ser os exemplos num prompt few-shot?
- [ ] Todos iguais
- [x] Variados, representativos e no mesmo formato
- [ ] Em idiomas diferentes
- [ ] O mais longos possível
> Exemplos variados evitam que o modelo copie um único caso.

### Questão
Quando o few-shot é especialmente útil?
- [ ] Para perguntas de sim ou não sem formato
- [x] Para classificações e saídas com formato específico
- [ ] Nunca é útil
- [ ] Só para gerar imagens
> Ele fixa o formato e o critério das respostas.

## Raciocínio passo a passo e papéis
### Teoria
Para problemas que exigem raciocínio (matemática, lógica, depuração), pedir que o modelo **pense passo a passo** antes da resposta final costuma melhorar a qualidade. Você pode pedir que ele separe o raciocínio da conclusão.

**Papéis (role prompting):** atribuir um papel ("Aja como um revisor de código experiente e exigente") ajusta vocabulário, foco e profundidade.

Outras técnicas:
- **Autoverificação:** "Depois de responder, confira cada passo e aponte possíveis erros."
- **Múltiplas perspectivas:** "Dê três abordagens diferentes e compare prós e contras."
- **Restrições de fonte:** "Responda apenas com base no texto fornecido; se não estiver lá, diga que não sabe."

### Prática
```
Aja como um professor de matemática paciente.
Resolva o problema abaixo pensando passo a passo e mostre cada etapa.
No final, escreva "Resposta:" seguido só do resultado.
Depois, confira se a resposta faz sentido com uma estimativa rápida.

Problema: Um iglu tem 4 membros. Cada um faz 3 lições por dia, e cada lição
vale 25 pontos. Quantos pontos o iglu soma em uma semana?
```

### Questão
Quando pedir raciocínio passo a passo é mais útil?
- [ ] Para perguntar a data de hoje
- [x] Em problemas de lógica, matemática e depuração
- [ ] Para traduzir uma palavra
- [ ] Nunca ajuda
> Etapas explícitas reduzem saltos e erros de raciocínio.

### Questão
O que o role prompting faz?
- [ ] Muda o idioma do modelo
- [x] Ajusta o foco e o estilo da resposta ao atribuir um papel
- [ ] Dá ao modelo acesso à internet
- [ ] Apaga o histórico
> "Aja como um revisor de segurança" muda o que o modelo observa.

### Questão
Como reduzir alucinações ao trabalhar com um texto fornecido?
- [ ] Pedir para inventar detalhes
- [x] Instruir a responder só com base no texto e dizer "não sei" quando faltar informação
- [ ] Não fornecer o texto
- [ ] Pedir respostas mais longas
> Limitar a fonte e permitir "não sei" diminui invenções.

## Saídas estruturadas e prompts reutilizáveis
### Teoria
Quando a resposta será usada por um **programa**, peça formatos estruturados como **JSON**, com o esquema bem definido: nomes dos campos, tipos e exemplos. Muitas APIs de IA têm modos de saída estruturada que garantem JSON válido.

Para tarefas repetidas, crie **templates de prompt** com variáveis:

```
Explique [CONCEITO] para um estudante de [NÍVEL] em até [N] parágrafos,
com um exemplo em [LINGUAGEM].
```

Em aplicações, o **prompt de sistema** define regras gerais (papel, tom, limites) que valem para toda a conversa.

### Prática
```
Gere 2 questões sobre arrays em JavaScript.
Responda APENAS com JSON válido, sem texto antes ou depois, neste formato:
[
  {
    "enunciado": "string",
    "alternativas": ["string", "string", "string", "string"],
    "correta": 0,
    "explicacao": "string"
  }
]
"correta" é o índice (0 a 3) da alternativa certa.
```

Depois, o programa faz `JSON.parse(resposta)` e valida os campos antes de usar.

### Questão
Por que pedir saída em JSON com esquema definido?
- [ ] Para a resposta ficar mais bonita
- [x] Para que um programa consiga ler e usar a resposta automaticamente
- [ ] Porque o modelo não escreve texto
- [ ] Para reduzir o número de tokens a zero
> Formatos estruturados são fáceis de processar e validar.

### Questão
O que é um template de prompt?
- [ ] Um prompt que nunca muda
- [x] Um prompt reutilizável com variáveis a serem preenchidas
- [ ] Um tipo de imagem
- [ ] Um erro de formatação
> Templates padronizam tarefas recorrentes.

### Questão
Para que serve o prompt de sistema numa aplicação?
- [ ] Mostrar anúncios
- [x] Definir regras gerais, como papel, tom e limites, para toda a conversa
- [ ] Guardar senhas
- [ ] Substituir o banco de dados
> Ele orienta o comportamento do modelo em todas as mensagens.

# Vibe coding: programando com IA
> Usar assistentes de IA para criar projetos com qualidade e responsabilidade.

## Projeto
**Construa com IA, entenda tudo:** crie um mini-app (por exemplo, um rastreador de gelo/ofensiva) usando um assistente de código. Escreva primeiro uma especificação, gere o código em etapas, revise cada parte, escreva testes e registre num diário: o que a IA acertou, o que errou e o que você aprendeu. No final, explique o código inteiro para seu iglu sem consultar a IA.

## O que é vibe coding
### Teoria
**Vibe coding** é o estilo de programar descrevendo em linguagem natural o que você quer e deixando a IA gerar o código, iterando pelas conversas. Ferramentas: assistentes como Claude, ChatGPT e Copilot, editores com IA e agentes que editam projetos inteiros.

Vantagens: protótipos muito rápidos, menos código repetitivo, ajuda para aprender tecnologias novas e destravar problemas.

Riscos:
- Código que **parece** funcionar, mas tem bugs ou falhas de segurança.
- Dependências inventadas ou desatualizadas.
- Você não entender o próprio projeto e não conseguir mantê-lo.
- Expor dados sensíveis colando segredos no prompt.

A regra de ouro: **a IA é uma copiloto; a responsabilidade pelo código é sua**.

### Prática
Um bom primeiro pedido para começar um projeto:

```
Quero criar um app web simples para registrar minhas sessões de estudo.
Antes de escrever código:
1. Faça perguntas sobre o que estiver indefinido.
2. Proponha a estrutura de arquivos e as tecnologias (HTML, CSS, JS puro).
3. Liste as funcionalidades do MVP em ordem de implementação.
Só depois de eu aprovar, comece pelo passo 1.
```

### Questão
Qual a regra de ouro do vibe coding?
- [ ] Aceitar todo código gerado sem ler
- [x] A IA ajuda, mas a responsabilidade pelo código é de quem programa
- [ ] Nunca usar IA
- [ ] Pedir o projeto inteiro de uma vez
> Você precisa entender, testar e revisar o que vai para o projeto.

### Questão
Qual é um risco comum de código gerado por IA?
- [ ] Ser sempre lento demais para rodar
- [x] Ter bugs, falhas de segurança ou bibliotecas inexistentes
- [ ] Não poder ser salvo
- [ ] Funcionar só em inglês
> O código pode parecer correto e não estar.

### Questão
Por que pedir um plano antes do código?
- [ ] Para atrasar o projeto
- [x] Para alinhar a estrutura e as decisões antes de gerar muito código
- [ ] Porque a IA não sabe programar
- [ ] Para economizar luz
> Corrigir o plano é mais barato do que reescrever o código.

## Fluxo de trabalho com assistentes de código
### Teoria
Um fluxo eficiente:
1. **Especifique** o objetivo, as restrições e os critérios de pronto.
2. **Dê contexto:** arquivos relevantes, padrões do projeto, mensagens de erro completas, versões das ferramentas.
3. **Peça mudanças pequenas** e incrementais, uma por vez.
4. **Revise e rode** cada mudança; use controle de versão (commits frequentes) para poder voltar.
5. **Teste:** peça também os testes, ou escreva os seus.
6. **Depure com método:** cole o erro exato e o que você esperava acontecer.

Bons pedidos de depuração dizem: o que você fez, o que esperava, o que aconteceu (com a mensagem de erro) e o que já tentou.

### Prática
```
Contexto: projeto PHP 8.2 sem framework, PDO com MySQL, arquivo licao.php.
Problema: ao enviar as respostas, aparece
  "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry"
Esperado: refazer uma lição já concluída deve apenas mostrar o resultado.
Já tentei: conferir que o id da lição está correto.
Pedido: explique a causa provável e sugira a menor correção possível,
mostrando só o trecho alterado.
```

### Questão
O que torna um pedido de depuração eficaz?
- [ ] Dizer apenas "não funciona"
- [x] Informar contexto, erro exato, comportamento esperado e o que já foi tentado
- [ ] Enviar o projeto inteiro sem explicação
- [ ] Pedir para reescrever tudo do zero
> Detalhes concretos levam a diagnósticos precisos.

### Questão
Por que pedir mudanças pequenas e incrementais?
- [ ] Porque a IA não gera textos longos
- [x] Porque são mais fáceis de revisar, testar e reverter
- [ ] Para gastar mais tempo
- [ ] Para evitar usar Git
> Mudanças grandes misturam problemas e dificultam a revisão.

### Questão
Qual prática ajuda a voltar atrás se a IA quebrar algo?
- [ ] Nunca salvar o projeto
- [x] Fazer commits frequentes no controle de versão
- [ ] Apagar os testes
- [ ] Trabalhar direto no servidor de produção
> Com Git, qualquer estado anterior pode ser recuperado.

## Qualidade, segurança e aprendizado
### Teoria
Antes de aceitar código gerado, revise:
- **Entendo cada linha?** Se não, peça explicação.
- **Segurança:** entradas validadas? Saída escapada? Consultas parametrizadas? Nenhum segredo no código?
- **Dependências:** o pacote existe, é mantido e confiável?
- **Testes:** os casos normais, limites e erros foram cobertos?
- **Licenças e direitos:** o código ou conteúdo pode ser usado?

Nunca cole no prompt **senhas, chaves de API ou dados pessoais** de outras pessoas.

Para **aprender** (e não só entregar), use a IA como tutora: peça dicas em vez de respostas prontas, tente primeiro sozinho, peça explicações do "porquê" e refaça o código sem ajuda. Em trabalhos escolares, siga as regras da sua escola sobre o uso de IA e seja transparente.

### Prática
Prompt de revisão de código:

```
Aja como um revisor de código focado em segurança e clareza.
Revise o código abaixo e liste, por ordem de gravidade:
1. Bugs e falhas de segurança (com a linha e a correção sugerida)
2. Problemas de legibilidade
3. Casos de teste que faltam
Não reescreva o código inteiro; aponte só os trechos.

<codigo>
(cole aqui o código, sem senhas ou chaves)
</codigo>
```

E para estudar: *"Não me dê a resposta. Me dê uma dica sobre por onde começar a resolver este exercício."*

### Questão
O que nunca deve ser colado num prompt?
- [ ] Mensagens de erro
- [x] Senhas, chaves de API e dados pessoais de terceiros
- [ ] Trechos de código do seu projeto
- [ ] A descrição do problema
> Dados sensíveis podem ficar expostos fora do seu controle.

### Questão
Como usar a IA para aprender de verdade?
- [ ] Copiar todas as respostas prontas
- [x] Pedir dicas e explicações, tentar sozinho e refazer sem ajuda
- [ ] Nunca ler as respostas
- [ ] Usar só para jogos
> O esforço de resolver é o que fixa o conhecimento.

### Questão
Antes de instalar um pacote sugerido pela IA, o que conferir?
- [ ] Nada, a IA sempre acerta
- [x] Se o pacote existe, é mantido e confiável
- [ ] Se o nome é bonito
- [ ] Se tem mais de 10 letras
> Pacotes inventados ou maliciosos são um risco real.
