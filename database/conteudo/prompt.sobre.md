@historia
A ideia de conversar com um computador em linguagem natural vem desde o **ELIZA** (1966), de Joseph Weizenbaum, no MIT, que simulava um terapeuta com regras simples. Por décadas, o processamento de linguagem natural avançou devagar, com regras escritas à mão e depois modelos estatísticos.

Em 2017, pesquisadores do Google publicaram o artigo "**Attention Is All You Need**", que apresentou a arquitetura **Transformer**. Ela permitiu treinar **grandes modelos de linguagem (LLMs)** com enormes quantidades de texto. Os modelos GPT mostraram que era possível ensinar tarefas só com exemplos no prompt (*few-shot*, 2020), e o lançamento do **ChatGPT** (novembro de 2022) levou a IA conversacional ao público. Seguiram-se o Claude, da Anthropic, o Gemini, do Google, e modelos abertos como o Llama. O termo **vibe coding** foi popularizado por **Andrej Karpathy** em fevereiro de 2025.

@hoje
Assistentes de IA são usados para escrever, estudar, programar, analisar dados e automatizar tarefas. Ferramentas como Claude Code, GitHub Copilot e Cursor geram e revisam código dentro do editor, e **agentes** já executam tarefas de várias etapas sozinhos. Saber escrever bons prompts, avaliar respostas criticamente e integrar modelos em aplicações virou uma habilidade básica de quem trabalha com tecnologia, e também exige atenção à ética, à privacidade e ao uso honesto em estudos e trabalhos.

@iniciante
- Entender como funcionam os grandes modelos de linguagem e seus limites
- Escrever prompts com contexto, tarefa, restrições e formato
- Usar delimitadores para separar instruções do conteúdo
- Refinar respostas em ciclos de iteração

@intermediario
- Ensinar padrões com exemplos (few-shot)
- Pedir raciocínio passo a passo e atribuir papéis
- Reduzir alucinações restringindo as fontes
- Criar prompts reutilizáveis e pedir saídas estruturadas em JSON

@avancado
- Programar com assistentes de IA de forma responsável (vibe coding)
- Especificar, gerar em etapas pequenas, testar e revisar o código gerado
- Escrever bons pedidos de depuração e de revisão de código
- Proteger dados sensíveis e usar a IA para aprender de verdade

@referencias
**Livros**
- *Prompt Engineering for LLMs* — John Berryman e Albert Ziegler (2024): escrito por engenheiros do GitHub Copilot.
- *AI Engineering* — Chip Huyen (2025): como construir aplicações com modelos de IA.
- *Inteligência Artificial: um guia para seres humanos pensantes* — Melanie Mitchell: os limites e o hype da IA explicados com clareza.

**Na internet**
- [Anthropic: guia de engenharia de prompt](https://docs.anthropic.com/en/docs/build-with-claude/prompt-engineering/overview)
- [OpenAI: Prompt engineering guide](https://platform.openai.com/docs/guides/prompt-engineering)
- [Learn Prompting](https://learnprompting.org/pt) — curso aberto e gratuito.

@modulo 1
**O modelo prevê texto; o prompt define o contexto da previsão.** Berryman e Ziegler explicam em *Prompt Engineering for LLMs* que um bom prompt é como um documento que o modelo "continua": quanto mais o início se parece com um texto em que a resposta ideal apareceria naturalmente, melhor. Por isso contexto, exemplos e formato funcionam tão bem.

A **janela de contexto** é medida em tokens e tem limite; informações importantes no meio de textos muito longos podem ser menos aproveitadas. Coloque instruções claras e o material bem delimitado.

Modelos **não consultam um banco de fatos**: eles geram texto plausível. Melanie Mitchell lembra que fluência não é compreensão. Por isso afirmações importantes (datas, números, citações, código de segurança) devem ser conferidas em fontes confiáveis.

@fontes 1
- Berryman e Ziegler, *Prompt Engineering for LLMs*, capítulos 2 (como os LLMs funcionam) e 4 (o prompt como documento)
- Mitchell, *Inteligência Artificial: um guia para seres humanos pensantes*, parte 4 (linguagem natural)
- Vaswani et al., "Attention Is All You Need" (2017)

@modulo 2
**Técnicas de prompt têm base em pesquisa.** O artigo "Language Models are Few-Shot Learners" (2020) mostrou que exemplos no prompt ensinam tarefas novas sem retreinar o modelo. O "Chain-of-Thought Prompting" (Wei e colegas, 2022) mostrou que pedir o raciocínio em etapas melhora problemas de lógica e matemática. Hoje, modelos com "raciocínio estendido" fazem isso internamente, mas as ideias continuam úteis para estruturar tarefas.

Em aplicações, a **avaliação** é tão importante quanto o prompt: monte um pequeno conjunto de casos de teste com respostas esperadas e meça cada mudança de prompt contra ele, como Chip Huyen recomenda em *AI Engineering*. Sem avaliação, melhorar um caso pode piorar outros sem ninguém perceber.

Cuidado com **injeção de prompt**: textos externos (páginas, emails, documentos) podem conter instruções maliciosas. Trate-os sempre como dados, nunca como ordens.

@fontes 2
- Brown et al., "Language Models are Few-Shot Learners" (2020)
- Wei et al., "Chain-of-Thought Prompting Elicits Reasoning in Large Language Models" (2022)
- Huyen, *AI Engineering*, capítulos 3 (avaliação) e 5 (engenharia de prompt)

@modulo 3
**IA acelera quem já sabe para onde vai.** Pesquisas sobre assistentes de código mostram ganhos de produtividade, mas também mais código duplicado e falhas de segurança quando o resultado não é revisado. O código gerado deve passar pelo mesmo crivo do código humano: testes, revisão e análise de segurança.

Um cuidado específico é o *slopsquatting*: modelos às vezes sugerem nomes de pacotes que não existem, e atacantes publicam pacotes maliciosos com esses nomes. Confira cada dependência antes de instalar.

Para aprender, pesquisas em educação mostram que usar a IA como tutora (pedir dicas, explicações e correções) ajuda, enquanto copiar respostas prontas prejudica o aprendizado. A pergunta que vale fazer sempre: eu conseguiria explicar e refazer esse código sozinho?

@fontes 3
- Huyen, *AI Engineering*, capítulos 6 (RAG e agentes) e 10 (arquitetura de aplicações de IA)
- [OWASP Top 10 for LLM Applications](https://genai.owasp.org/llm-top-10/)
- Andrej Karpathy, publicação sobre "vibe coding" (fevereiro de 2025)
