@historia
Em 1947, a equipe de **Grace Hopper** encontrou uma mariposa presa num relé do computador Mark II e a colou no caderno de registros com a anotação "primeiro caso real de bug encontrado". A palavra "bug" já existia na engenharia, mas a história a tornou famosa.

Por muito tempo, testar era só "depurar". Em 1979, **Glenford Myers** publicou *The Art of Software Testing*, que definiu teste como "executar um programa com a intenção de encontrar erros". Nos anos 1990, **Kent Beck** criou o SUnit (Smalltalk) e, com Erich Gamma, o **JUnit** (1997), que inspirou toda a família xUnit (pytest, NUnit, PHPUnit). Beck também popularizou o **TDD** com a Extreme Programming. Nos anos 2000 surgiram o BDD (Dan North), o Selenium e a integração contínua, e nos 2010 a pirâmide de testes, o Cypress e o Playwright.

@hoje
Testes automatizados são parte do trabalho de todo desenvolvedor profissional: projetos sérios não aceitam código novo sem testes, e pipelines de CI bloqueiam mudanças que quebram a suíte. A área de **qualidade de software (QA)** tem carreiras próprias, como analista de testes, engenheiro de automação e SDET, e certificações como o **CTFL do ISTQB**, muito procurada no Brasil. Testes de desempenho, segurança e acessibilidade completam a garantia de qualidade.

@iniciante
- Entender por que testar e os princípios básicos de teste
- Diferenciar erro, defeito e falha
- Conhecer a pirâmide de testes e os níveis de teste
- Escolher casos com partição de equivalência e valores limite

@intermediario
- Desenvolver com TDD no ciclo vermelho, verde e refatorar
- Isolar dependências com stubs, mocks e fakes
- Medir cobertura de linhas e de ramos
- Escrever testes de integração com banco e API

@avancado
- Automatizar jornadas com testes ponta a ponta no navegador
- Testar desempenho, segurança e acessibilidade
- Integrar a suíte de testes ao CI e bloquear regressões
- Avaliar a qualidade dos testes com teste de mutação

@referencias
**Livros**
- *TDD: Desenvolvimento Guiado por Testes* — Kent Beck: o livro que apresentou o TDD.
- *The Art of Software Testing* — Glenford Myers: o clássico sobre técnicas de teste.
- *Unit Testing: Principles, Practices, and Patterns* — Vladimir Khorikov: como escrever testes de unidade valiosos.
- *Agile Testing* — Lisa Crispin e Janet Gregory: testes em equipes ágeis.

**Na internet**
- [Syllabus CTFL do ISTQB em português (BSTQB)](https://bstqb.online)
- [Documentação do pytest](https://docs.pytest.org)
- [Playwright — documentação](https://playwright.dev/docs/intro)

@modulo 1
**Testar é uma atividade de investigação.** Glenford Myers mostrou que um bom caso de teste é aquele com alta probabilidade de encontrar um defeito ainda desconhecido. Por isso testes que só confirmam o "caminho feliz" encontram pouco: os defeitos moram nas bordas, nas combinações e nos dados inesperados.

O ISTQB lista **sete princípios** de teste, entre eles o **paradoxo do pesticida**: repetir sempre os mesmos testes faz com que eles deixem de encontrar defeitos novos, assim como insetos se acostumam a um pesticida. Por isso a suíte precisa evoluir junto com o sistema.

Vladimir Khorikov define quatro qualidades de um bom teste: protege contra regressões, resiste a refatorações (não quebra quando a implementação muda sem mudar o comportamento), dá feedback rápido e é fácil de manter.

@fontes 1
- Myers, *The Art of Software Testing*, capítulos 1 a 4
- ISTQB, *Certified Tester Foundation Level Syllabus*, capítulo 1 (os sete princípios)
- Khorikov, *Unit Testing: Principles, Practices, and Patterns*, capítulo 4

@modulo 2
**O TDD é uma técnica de design, não só de teste.** Kent Beck descreve o TDD como uma forma de controlar o medo durante a programação: passos pequenos, feedback constante. Como o teste é escrito antes, o código nasce com dependências explícitas e interfaces fáceis de usar.

Khorikov distingue duas escolas: a **clássica** (de Detroit), que só usa dublês para dependências realmente externas (rede, email, banco), e a **de Londres** (mockista), que isola cada classe. A recomendação moderna é testar **comportamentos observáveis**, e não detalhes de implementação, usando mocks com moderação.

Martin Fowler, no artigo "Mocks Aren't Stubs", explica as diferenças entre os tipos de dublês e as consequências de cada escolha.

@fontes 2
- Beck, *TDD: Desenvolvimento Guiado por Testes*, parte 1 (o exemplo do dinheiro)
- Khorikov, *Unit Testing*, capítulos 2 e 5 (as escolas e os mocks)
- Martin Fowler, "Mocks Aren't Stubs" (2007)

@modulo 3
**Testes caros devem valer o que custam.** O Google descreve em *Software Engineering at Google* que testes grandes (E2E) são executados com menos frequência e que a instabilidade é tratada como defeito: testes *flaky* são isolados e corrigidos, porque minam a confiança da equipe. A empresa classifica testes por tamanho (pequeno, médio, grande) conforme os recursos que usam.

Lisa Crispin e Janet Gregory propõem os **quadrantes de testes ágeis**: testes que apoiam a equipe (unidade, componentes), testes que verificam o negócio (exemplos, histórias), testes que criticam o produto (exploratório, usabilidade) e testes de tecnologia (desempenho, segurança). Uma estratégia equilibrada cobre os quatro.

Em produção, qualidade continua: monitoramento, lançamentos graduais e *feature flags* permitem detectar e reverter problemas antes que atinjam todos os usuários.

@fontes 3
- Winters, Manshreck e Wright, *Software Engineering at Google*, capítulos 11 a 14 (testes)
- Crispin e Gregory, *Agile Testing*, capítulo 6 (os quadrantes)
- [Playwright: Best Practices](https://playwright.dev/docs/best-practices)
