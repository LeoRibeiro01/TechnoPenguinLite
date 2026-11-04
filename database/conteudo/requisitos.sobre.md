@historia
Nos anos 1960, projetos de software grandes atrasavam, estouravam orçamentos e entregavam sistemas que não serviam. Em **1968**, a conferência da OTAN em Garmisch, na Alemanha, deu nome à "**crise do software**" e propôs a **engenharia de software**. Logo ficou claro que muitos fracassos vinham de entender errado o problema, e não de programar mal.

Nos anos 1970 e 1980 surgiram os documentos de especificação formais e o padrão **IEEE 830** (1984). **Ivar Jacobson** criou os **casos de uso** em 1987, depois incorporados à UML. Em 2001, o **Manifesto Ágil** propôs colaboração contínua com o cliente em vez de longos documentos, e as **histórias de usuário**, popularizadas por Kent Beck e Mike Cohn, tornaram-se a forma mais comum de registrar requisitos. O padrão **ISO/IEC/IEEE 29148** (2011) substituiu o IEEE 830.

@hoje
Toda equipe de software lida com requisitos, mesmo que não use esse nome: analistas de negócio, product owners, product managers, designers de UX e desenvolvedores conversam com usuários, escrevem histórias, priorizam backlogs e validam protótipos. Saber fazer boas perguntas e escrever critérios de aceitação claros é uma das habilidades mais valorizadas, e a certificação **CPRE** (do IREB) é reconhecida internacionalmente.

@iniciante
- Entender o que são requisitos e por que tantos projetos falham por causa deles
- Diferenciar requisitos funcionais, não funcionais, regras de negócio e restrições
- Escrever requisitos não funcionais mensuráveis
- Identificar stakeholders e criar personas

@intermediario
- Aplicar técnicas de elicitação: entrevistas, observação, workshops e protótipos
- Escrever histórias de usuário seguindo o INVEST
- Definir critérios de aceitação no formato Dado/Quando/Então
- Descrever casos de uso com fluxos principal e alternativos

@avancado
- Priorizar com MoSCoW, valor × esforço e modelo Kano e definir o MVP
- Validar requisitos com revisões, protótipos e testes de usabilidade
- Manter rastreabilidade e analisar o impacto de mudanças
- Controlar o escopo e evitar o scope creep

@referencias
**Livros**
- *Engenharia de Software* — Ian Sommerville: o capítulo 4 é uma excelente introdução à engenharia de requisitos.
- *Software Requirements* — Karl Wiegers e Joy Beatty: o guia mais completo sobre o tema.
- *User Stories Applied* — Mike Cohn: como escrever boas histórias de usuário.
- *Lean Inception* — Paulo Caroli: técnica brasileira para alinhar a equipe e definir o MVP.

**Na internet**
- [IREB — Glossário de engenharia de requisitos](https://www.ireb.org/en/cpre/glossary/)
- [Atlassian: histórias de usuário (em português)](https://www.atlassian.com/br/agile/project-management/user-stories)
- [Nielsen Norman Group — artigos de UX e pesquisa com usuários](https://www.nngroup.com/articles/)

@modulo 1
**Requisitos de qualidade são a parte mais esquecida.** Sommerville e a norma **ISO/IEC 25010** organizam a qualidade de software em características como adequação funcional, eficiência, compatibilidade, usabilidade, confiabilidade, segurança, manutenibilidade e portabilidade. Use essa lista como checklist para não esquecer requisitos não funcionais importantes.

Wiegers e Beatty separam três níveis: **requisitos de negócio** (por que o projeto existe), **requisitos de usuário** (o que os usuários precisam fazer) e **requisitos funcionais** (o que o sistema precisa fazer para isso). Um bom requisito de software sempre pode ser rastreado até um objetivo de negócio. Se não pode, talvez nem precise existir.

@fontes 1
- Sommerville, *Engenharia de Software*, capítulo 4 (engenharia de requisitos)
- Wiegers e Beatty, *Software Requirements*, capítulo 1 (níveis de requisitos)
- [ISO/IEC 25010 — modelo de qualidade de produto](https://iso25000.com/index.php/en/iso-25000-standards/iso-25010)

@modulo 2
**Uma história de usuário é um lembrete de conversa.** Ron Jeffries definiu os três "Cs": **Cartão** (a frase curta), **Conversa** (onde os detalhes são combinados) e **Confirmação** (os critérios de aceitação). A história não é a especificação completa: é o convite para conversar na hora certa.

Mike Cohn mostra como dividir histórias grandes (*épicos*) em fatias **verticais**, que entregam valor de ponta a ponta, em vez de fatias horizontais ("só o banco de dados", "só a tela"). O **mapeamento de histórias**, de Jeff Patton, organiza as histórias pela jornada do usuário e ajuda a recortar a primeira versão.

Critérios de aceitação no formato Gherkin podem virar testes automatizados com ferramentas de **BDD** (Behaviour-Driven Development), como o Cucumber.

@fontes 2
- Cohn, *User Stories Applied*, capítulos 1 a 3
- Jeff Patton, *User Story Mapping*
- Ron Jeffries, "Essential XP: Card, Conversation, Confirmation" (2001)

@modulo 3
**Requisitos mudam, e isso não é falha.** O *Cone da Incerteza*, descrito por Barry Boehm e popularizado por Steve McConnell, mostra que no início de um projeto as estimativas podem errar por um fator de 4 para mais ou para menos, e que a incerteza só diminui à medida que o produto é construído e testado. Por isso métodos ágeis entregam em ciclos curtos e validam cedo com usuários reais.

A **Lean Inception**, de Paulo Caroli, ajuda a equipe a definir em poucos dias a visão do produto, as personas, as jornadas e o MVP, com atividades colaborativas. Já a **matriz de rastreabilidade** continua essencial em sistemas regulados (saúde, financeiro), onde é preciso provar que cada requisito foi implementado e testado.

@fontes 3
- Steve McConnell, *Software Estimation*, capítulo 4 (o cone da incerteza)
- Caroli, *Lean Inception*
- Wiegers e Beatty, *Software Requirements*, capítulos 27 a 29 (gerência de requisitos)
