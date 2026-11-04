@historia
Por décadas, quem desenvolvia software e quem operava os servidores trabalhavam separados, com metas opostas: desenvolvedores queriam mudar rápido; operações queriam estabilidade. Em 2008, **Patrick Debois** e **Andrew Shafer** discutiram "infraestrutura ágil" numa conferência. Em 2009, a palestra "**10+ Deploys per Day**", de John Allspaw e Paul Hammond (Flickr), mostrou que dava para fazer as duas coisas. Debois organizou o primeiro **DevOpsDays** na Bélgica, e o nome pegou.

As raízes são mais antigas: o **Sistema Toyota de Produção** e o *Lean*, a integração contínua do Extreme Programming (Kent Beck, anos 1990), o livro *Continuous Delivery* (2010) e o **Git**, criado por Linus Torvalds em 2005 para o kernel do Linux. Docker (2013), Kubernetes (2014) e o GitHub Actions (2019) tornaram as práticas acessíveis a qualquer equipe.

@hoje
Equipes de alto desempenho fazem deploys várias vezes por dia com segurança, graças a pipelines de CI/CD, testes automatizados, contêineres e monitoramento. O relatório anual **State of DevOps** (DORA, do Google) mede isso com quatro métricas. Os cargos de DevOps, SRE e engenharia de plataforma estão entre os mais bem pagos da área, e saber Git, CI e Docker é esperado de qualquer pessoa desenvolvedora.

@iniciante
- Entender a cultura DevOps, o ciclo e as métricas DORA
- Versionar código com Git: commits, branches e .gitignore
- Colaborar com pull requests e revisão de código
- Resolver conflitos de merge

@intermediario
- Criar pipelines de integração contínua com GitHub Actions
- Entender entrega e implantação contínuas e estratégias de deploy
- Guardar segredos com segurança no CI/CD
- Empacotar aplicações com Docker e Docker Compose

@avancado
- Descrever infraestrutura como código e conhecer o Kubernetes
- Implementar observabilidade com logs, métricas e traces
- Definir SLIs, SLOs e error budgets
- Responder a incidentes e escrever post-mortems sem culpados

@referencias
**Livros**
- *O Projeto Fênix* — Gene Kim, Kevin Behr e George Spafford: um romance sobre a transformação DevOps de uma empresa.
- *Manual de DevOps* — Gene Kim, Jez Humble, Patrick Debois e John Willis: as práticas em detalhe.
- *Accelerate* — Nicole Forsgren, Jez Humble e Gene Kim: a pesquisa científica por trás das métricas DORA.
- *Site Reliability Engineering* — equipe do Google ([gratuito online](https://sre.google/books/)).

**Na internet**
- [Pro Git (em português)](https://git-scm.com/book/pt-br/v2) — o livro oficial do Git, gratuito.
- [GitHub Docs: GitHub Actions](https://docs.github.com/pt/actions)
- [DORA — pesquisa e métricas](https://dora.dev)

@modulo 1
**As "Três Maneiras" do DevOps**, descritas em *O Projeto Fênix* e no *Manual de DevOps*, são: **fluxo** (acelerar a entrega do código até o cliente, com lotes pequenos), **feedback** (perceber problemas cedo, com testes e monitoramento) e **aprendizado contínuo** (experimentar e aprender com falhas, sem procurar culpados).

No Git, cada commit é um retrato completo do projeto, identificado por um hash calculado a partir do conteúdo; por isso o histórico não pode ser alterado sem que se perceba. Branches são só ponteiros leves para um commit, e criar uma é instantâneo. O livro *Pro Git* explica esses detalhes internos e por que o Git é distribuído: cada clone tem o histórico inteiro.

Boas mensagens de commit explicam **por que** a mudança foi feita, não só o quê.

@fontes 1
- Kim, Humble, Debois e Willis, *Manual de DevOps*, parte 1 (as Três Maneiras)
- Chacon e Straub, *Pro Git*, capítulos 2, 3 e 10 (Git por dentro)
- [Conventional Commits (em português)](https://www.conventionalcommits.org/pt-br/v1.0.0/)

@modulo 2
**O que é difícil, faça com mais frequência.** Esse é o lema de *Continuous Delivery*, de Jez Humble e David Farley: integrar e implantar com frequência transforma eventos raros e assustadores em rotinas automáticas e chatas. A pesquisa do livro *Accelerate* mostrou que equipes com deploys mais frequentes também têm **menos falhas** e se recuperam mais rápido, derrubando o mito de que velocidade e estabilidade se opõem.

Um bom pipeline é rápido (feedback em minutos), confiável (testes que não falham aleatoriamente) e reprodutível (o mesmo artefato vai do teste à produção). **Feature flags** separam o deploy (código no servidor) do lançamento (funcionalidade visível para o usuário).

@fontes 2
- Humble e Farley, *Continuous Delivery*, capítulos 1, 3 e 5
- Forsgren, Humble e Kim, *Accelerate*, capítulos 2 a 4
- [Docker Docs: melhores práticas para Dockerfile](https://docs.docker.com/build/building/best-practices/)

@modulo 3
**Confiabilidade é um recurso, e tem custo.** O livro *Site Reliability Engineering*, do Google, mostra que 100% de disponibilidade é o alvo errado: o usuário não percebe a diferença entre 99,99% e 100%, mas o custo para chegar lá é enorme. O SLO define o "bom o suficiente", e o **error budget** transforma confiabilidade em decisão de negócio.

O Google também limita o trabalho manual e repetitivo (*toil*) a menos de 50% do tempo do SRE, para sobrar tempo de automatizar. E a observabilidade moderna se padroniza em torno do **OpenTelemetry**, para que logs, métricas e traces funcionem com qualquer ferramenta.

Post-mortems sem culpados seguem a ideia de que sistemas complexos falham por combinações de fatores, e não por "erro humano" isolado.

@fontes 3
- Beyer et al., *Site Reliability Engineering*, capítulos 3 (abraçando o risco), 4 (SLOs), 5 (toil) e 15 (post-mortems)
- [OpenTelemetry — documentação](https://opentelemetry.io/docs/)
- Richard Cook, "How Complex Systems Fail" (1998)
