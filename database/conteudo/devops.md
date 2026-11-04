# Cultura DevOps e Git
> O que é DevOps, versionamento com Git e colaboração.

## Projeto
**Repositório colaborativo do iglu:** crie um repositório no GitHub para um projeto do seu iglu, proteja a branch `main`, defina um padrão de commits e trabalhe com branches e pull requests. Cada membro deve abrir pelo menos um PR revisado por outro.

## O que é DevOps
### Teoria
**DevOps** é uma cultura e um conjunto de práticas que aproxima **desenvolvimento (Dev)** e **operações (Ops)** para entregar software com mais **frequência**, **qualidade** e **segurança**.

Antes, desenvolvedores "jogavam o código por cima do muro" para a equipe de operações, gerando conflitos e entregas lentas. O DevOps propõe responsabilidade compartilhada e automação de ponta a ponta.

Pilares (modelo **CALMS**): **C**ultura, **A**utomação, **L**ean (eliminar desperdícios), **M**edição e compartilhamento (**S**haring).

O ciclo DevOps é contínuo: planejar → codificar → construir → testar → lançar → implantar → operar → monitorar → (planejar de novo).

Métricas **DORA** medem o desempenho: frequência de deploy, tempo de entrega, taxa de falha de mudanças e tempo de recuperação.

### Prática
Compare dois times:

```
Time A (sem DevOps): deploy a cada 3 meses, manual, numa sexta à noite,
                     com planilha de passos e muita tensão.
Time B (com DevOps): vários deploys por dia, automáticos após os testes
                     passarem, com rollback em 1 clique e monitoramento.
```

O time B erra menos porque faz mudanças **pequenas e frequentes**.

### Questão
Qual o objetivo principal do DevOps?
- [ ] Separar desenvolvedores e operações
- [x] Entregar software com mais frequência, qualidade e segurança, unindo Dev e Ops
- [ ] Eliminar os testes
- [ ] Fazer deploys só uma vez por ano
> Colaboração e automação reduzem atritos e riscos.

### Questão
Por que mudanças pequenas e frequentes são mais seguras?
- [ ] Porque não precisam de testes
- [x] Porque são mais fáceis de testar, revisar e reverter
- [ ] Porque o servidor fica mais leve
- [ ] Não são mais seguras
> Menos coisas mudam de cada vez, então problemas são localizados rápido.

### Questão
Qual destas é uma métrica DORA?
- [ ] Número de linhas de código
- [x] Frequência de deploy
- [ ] Quantidade de reuniões
- [ ] Cor do logotipo
> As quatro métricas DORA medem velocidade e estabilidade.

## Git: versionamento essencial
### Teoria
**Git** é um sistema de **controle de versão distribuído**: registra o histórico de mudanças do código, permite voltar no tempo e trabalhar em equipe.

Conceitos:
- **Repositório:** o projeto com todo o histórico.
- **Commit:** um "retrato" das mudanças, com mensagem descritiva.
- **Área de preparação (staging):** onde você escolhe o que vai no próximo commit.
- **Branch:** linha de desenvolvimento paralela.
- **Remoto:** cópia do repositório num servidor (GitHub, GitLab).

Escreva mensagens de commit claras, no imperativo: "Adiciona tela de iglus", não "mudanças".

### Prática
```
git init                          cria um repositório
git status                        mostra o que mudou
git add index.html                prepara um arquivo
git commit -m "Cria página inicial"
git log --oneline                 histórico resumido
git switch -c feature/vestuario   cria e muda para uma branch
git push -u origin feature/vestuario
```

O arquivo `.gitignore` lista o que não deve ser versionado (como `.env`, `vendor/`, `node_modules/`).

### Questão
O que é um commit no Git?
- [ ] Um servidor
- [x] Um registro das mudanças num momento, com uma mensagem
- [ ] Um tipo de branch
- [ ] Um arquivo de configuração
> Commits formam o histórico do projeto.

### Questão
Para que serve o `.gitignore`?
- [ ] Apagar o repositório
- [x] Listar arquivos e pastas que o Git não deve versionar
- [ ] Ignorar erros de compilação
- [ ] Desativar branches
> Senhas (.env) e dependências instaladas não devem ir para o repositório.

### Questão
Qual comando prepara um arquivo para o próximo commit?
- [ ] git push
- [x] git add
- [ ] git clone
- [ ] git log
> git add coloca as mudanças na área de preparação.

## Branches, pull requests e revisão
### Teoria
Em equipe, cada funcionalidade ou correção é feita numa **branch** própria. Quando pronta, abre-se um **pull request (PR)** para integrá-la à `main`.

No PR acontecem:
- **Revisão de código** por colegas (encontra bugs e espalha conhecimento).
- **Verificações automáticas** (testes, lint) da integração contínua.
- Discussão e ajustes antes do **merge**.

Estratégias de branches: **trunk-based development** (branches curtas e integração frequente na main, preferido em DevOps) e **Git Flow** (branches de longa duração para develop/release).

**Conflitos** acontecem quando duas branches mudam as mesmas linhas: o Git pede que você escolha o resultado final.

### Prática
```
git switch main
git pull
git switch -c fix/gelo-derretendo
# ... edita e testa ...
git add .
git commit -m "Corrige cálculo de dias sem lição do gelo"
git push -u origin fix/gelo-derretendo
# abre o PR no GitHub, pede revisão, aguarda os checks ficarem verdes
```

Resolvendo um conflito: o Git marca o trecho com `<<<<<<<`, `=======` e `>>>>>>>`. Edite, mantenha o código correto, remova as marcas e faça um novo commit.

### Questão
O que é um pull request?
- [ ] Um comando para baixar o repositório
- [x] Uma proposta de integrar as mudanças de uma branch, com revisão e verificações
- [ ] Um tipo de servidor
- [ ] Um backup
> PRs são o centro da colaboração em código.

### Questão
Qual estratégia de branches é mais alinhada ao DevOps?
- [ ] Branches que duram meses
- [x] Trunk-based: branches curtas e integração frequente
- [ ] Nunca usar branches
- [ ] Uma branch por desenvolvedor para sempre
> Integrar cedo reduz conflitos e acelera a entrega.

### Questão
Quando ocorre um conflito de merge?
- [ ] Quando o repositório está vazio
- [x] Quando duas branches alteram as mesmas linhas de forma diferente
- [ ] Sempre que se faz commit
- [ ] Quando a internet cai
> O Git não sabe qual versão manter e pede ajuda.

# Integração e entrega contínuas
> Pipelines de CI/CD, testes automatizados e contêineres.

## Projeto
**Pipeline completo no GitHub Actions:** para um projeto seu, crie um workflow que, a cada push e PR, instala dependências, roda lint e testes, gera uma imagem Docker e, na `main`, publica automaticamente (GitHub Pages, Render ou outro serviço). Adicione o selo (badge) de status no README.

## Integração contínua (CI)
### Teoria
**Integração Contínua (CI)** é integrar o código de todos na branch principal **várias vezes ao dia**, com um **pipeline automático** que verifica cada mudança:

1. Baixa o código.
2. Instala dependências.
3. Roda **lint** (estilo e erros comuns) e **testes automatizados**.
4. Gera o build (artefato).

Se algo falha, o time é avisado imediatamente e corrige enquanto a mudança é pequena. Regra de ouro: **a main deve estar sempre verde** (funcionando).

Ferramentas: **GitHub Actions**, GitLab CI, Jenkins, CircleCI, Azure Pipelines.

### Prática
`.github/workflows/ci.yml` para um projeto Node:

```yaml
name: CI
on: [push, pull_request]

jobs:
  testes:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 20
      - run: npm ci
      - run: npm run lint
      - run: npm test
```

### Questão
O que a integração contínua propõe?
- [ ] Integrar o código uma vez por mês
- [x] Integrar frequentemente, com verificação automática a cada mudança
- [ ] Testar só em produção
- [ ] Eliminar o controle de versão
> Problemas são encontrados cedo, quando são baratos de corrigir.

### Questão
O que deve acontecer quando o pipeline de CI falha?
- [ ] Ignorar e continuar
- [x] Corrigir rapidamente para a main voltar a ficar verde
- [ ] Desligar o pipeline
- [ ] Apagar os testes
> Um pipeline vermelho bloqueia a entrega segura.

### Questão
Onde ficam os workflows do GitHub Actions?
- [ ] Na pasta node_modules
- [x] Em .github/workflows/ no repositório
- [ ] No servidor de produção
- [ ] No arquivo README
> São arquivos YAML versionados junto com o código.

## Entrega e implantação contínuas (CD)
### Teoria
- **Entrega Contínua (Continuous Delivery):** todo código aprovado no pipeline fica **pronto para ir à produção**; o deploy é acionado com um clique.
- **Implantação Contínua (Continuous Deployment):** vai além: todo código aprovado é **implantado automaticamente**.

Estratégias de deploy seguras:
- **Blue-green:** duas versões lado a lado; o tráfego troca de uma vez e o rollback é instantâneo.
- **Canary:** a nova versão recebe primeiro uma pequena parte dos usuários.
- **Rolling:** substitui as instâncias aos poucos.
- **Feature flags:** liga e desliga funcionalidades sem novo deploy.

Use **ambientes** separados (desenvolvimento, homologação, produção) e guarde segredos no cofre da ferramenta, nunca no código.

### Prática
Job de deploy que só roda na `main` depois dos testes:

```yaml
  deploy:
    needs: testes
    if: github.ref == 'refs/heads/main'
    runs-on: ubuntu-latest
    environment: producao
    steps:
      - uses: actions/checkout@v4
      - run: ./scripts/deploy.sh
        env:
          TOKEN_DEPLOY: ${{ secrets.TOKEN_DEPLOY }}
```

### Questão
Qual a diferença entre entrega contínua e implantação contínua?
- [ ] Nenhuma
- [x] Na entrega, o deploy em produção é acionado manualmente; na implantação, é automático
- [ ] Implantação contínua não tem testes
- [ ] Entrega contínua não usa Git
> Ambas exigem um pipeline confiável.

### Questão
O que é um deploy canary?
- [ ] Um deploy feito à noite
- [x] Liberar a nova versão primeiro para uma pequena parte dos usuários
- [ ] Apagar a versão antiga antes de subir a nova
- [ ] Um deploy sem testes
> Se algo der errado, poucos usuários são afetados.

### Questão
Onde guardar o token de deploy?
- [ ] No código-fonte
- [ ] No README
- [x] Nos segredos (secrets) da ferramenta de CI/CD
- [ ] Num comentário do workflow
> Segredos ficam criptografados e fora do repositório.

## Contêineres com Docker
### Teoria
Contêineres empacotam a aplicação **com tudo o que ela precisa** (runtime, bibliotecas, configurações), garantindo que rode igual no notebook, no CI e na produção.

- **Dockerfile:** receita para construir a imagem.
- **Imagem:** pacote imutável gerado pelo build.
- **Contêiner:** uma imagem em execução.
- **Registro:** onde as imagens são publicadas (Docker Hub, GitHub Container Registry).
- **Docker Compose:** sobe vários contêineres juntos (app + banco + cache) com um arquivo YAML.

Boas práticas: imagens pequenas (bases `slim`/`alpine`), não rodar como root, uma responsabilidade por contêiner.

### Prática
```dockerfile
FROM php:8.2-apache
COPY . /var/www/html/
RUN docker-php-ext-install pdo_mysql
EXPOSE 80
```

```yaml
# compose.yaml
services:
  app:
    build: .
    ports: ["8080:80"]
    depends_on: [banco]
  banco:
    image: mariadb:11
    environment:
      MARIADB_ROOT_PASSWORD: segredo_local
      MARIADB_DATABASE: technopenguin
```

Suba tudo com `docker compose up -d`.

### Questão
Qual arquivo descreve como construir uma imagem Docker?
- [ ] compose.yaml
- [x] Dockerfile
- [ ] package.json
- [ ] .gitignore
> O Dockerfile tem as instruções do build.

### Questão
Qual a diferença entre imagem e contêiner?
- [ ] São a mesma coisa
- [x] A imagem é o pacote imutável; o contêiner é a imagem em execução
- [ ] O contêiner é o código-fonte
- [ ] A imagem é um banco de dados
> De uma imagem podem nascer vários contêineres.

### Questão
Para que serve o Docker Compose?
- [ ] Compor músicas
- [x] Definir e subir vários contêineres relacionados de uma vez
- [ ] Compilar código C
- [ ] Substituir o Git
> App, banco e cache sobem juntos com um comando.

# Operação e confiabilidade
> Infraestrutura como código, orquestração, observabilidade e SRE.

## Projeto
**Observando sua aplicação:** rode sua aplicação com Docker Compose junto com Prometheus e Grafana, exponha métricas (requisições, erros, latência), crie um painel e um alerta para taxa de erros acima de 5%. Escreva um post-mortem fictício de um incidente.

## Infraestrutura como código e Kubernetes
### Teoria
**Infraestrutura como Código (IaC)**: servidores, redes e serviços descritos em arquivos versionados, revisados em PRs e aplicados automaticamente. Ferramentas: **Terraform**, Ansible (configuração), CloudFormation, Pulumi.

**Kubernetes (K8s)** orquestra contêineres em escala:
- **Pod:** a menor unidade, com um ou mais contêineres.
- **Deployment:** mantém o número desejado de réplicas e faz atualizações graduais.
- **Service:** endereço estável para acessar os pods.
- **Ingress:** expõe serviços HTTP para fora do cluster.

O Kubernetes é **declarativo**: você descreve o estado desejado e ele trabalha continuamente para alcançá-lo (se um pod morre, outro é criado).

### Prática
```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: technopenguin
spec:
  replicas: 3
  selector:
    matchLabels: { app: technopenguin }
  template:
    metadata:
      labels: { app: technopenguin }
    spec:
      containers:
        - name: web
          image: ghcr.io/iglu/technopenguin:1.4.0
          ports: [{ containerPort: 80 }]
```

`kubectl apply -f deployment.yaml` aplica; `kubectl get pods` mostra as 3 réplicas.

### Questão
O que significa o Kubernetes ser declarativo?
- [ ] Que é preciso declarar variáveis
- [x] Você descreve o estado desejado e ele mantém o sistema nesse estado
- [ ] Que só aceita comandos manuais
- [ ] Que não usa contêineres
> Se uma réplica cai, o controlador cria outra automaticamente.

### Questão
Qual é a menor unidade implantável no Kubernetes?
- [ ] Cluster
- [ ] Deployment
- [x] Pod
- [ ] Ingress
> Um pod agrupa um ou mais contêineres.

### Questão
Qual a vantagem de versionar a infraestrutura como código?
- [ ] Deixar os servidores mais caros
- [x] Ambientes reproduzíveis, revisados e com histórico de mudanças
- [ ] Eliminar a necessidade de servidores
- [ ] Impedir mudanças
> Criar um ambiente novo vira rodar um comando.

## Observabilidade
### Teoria
**Observabilidade** é a capacidade de entender o que acontece dentro do sistema a partir dos dados que ele emite. Três pilares:

- **Logs:** registros de eventos (preferencialmente estruturados em JSON).
- **Métricas:** números ao longo do tempo (requisições/s, uso de CPU, erros).
- **Traces (rastreamento distribuído):** o caminho de uma requisição por vários serviços.

Ferramentas: **Prometheus** + **Grafana** (métricas e painéis), ELK/Loki (logs), Jaeger/Tempo (traces), **OpenTelemetry** (padrão para coletar tudo).

Para monitorar serviços web, os **sinais de ouro**: latência, tráfego, erros e saturação. **Alertas** devem ser acionáveis: avisar só quando alguém precisa agir.

### Prática
Log estruturado em vez de texto solto:

```
Ruim: "erro ao salvar"
Bom:  {"nivel":"erro","evento":"salvar_licao","usuario":42,"licao":13,
       "duracao_ms":1830,"mensagem":"timeout no banco"}
```

Consulta PromQL para taxa de erros nos últimos 5 minutos:

```
sum(rate(http_requests_total{status=~"5.."}[5m]))
  / sum(rate(http_requests_total[5m]))
```

### Questão
Quais são os três pilares da observabilidade?
- [ ] HTML, CSS e JavaScript
- [x] Logs, métricas e traces
- [ ] Git, Docker e Kubernetes
- [ ] CPU, RAM e disco
> Juntos, explicam o que, quanto e onde algo aconteceu.

### Questão
Por que preferir logs estruturados?
- [ ] Ocupam menos espaço sempre
- [x] Podem ser filtrados e analisados automaticamente por campos
- [ ] São mais bonitos
- [ ] Não precisam de horário
> Buscar todos os erros do usuário 42 vira uma consulta simples.

### Questão
Qual destes NÃO é um dos sinais de ouro?
- [ ] Latência
- [ ] Erros
- [ ] Tráfego
- [x] Número de commits
> Os quatro sinais são latência, tráfego, erros e saturação.

## SRE e resposta a incidentes
### Teoria
**SRE (Site Reliability Engineering)**, criado no Google, aplica engenharia de software às operações. Conceitos-chave:

- **SLI:** indicador medido (ex.: % de requisições bem-sucedidas).
- **SLO:** meta para o indicador (ex.: 99,9% de sucesso no mês).
- **SLA:** compromisso contratual com o cliente, normalmente menos rigoroso que o SLO.
- **Error budget:** o quanto de falha o SLO permite (0,1% ≈ 43 minutos/mês). Se o orçamento acaba, a prioridade passa a ser confiabilidade, não novas funcionalidades.

Em incidentes: restabelecer o serviço primeiro, comunicar com clareza e depois escrever um **post-mortem sem culpados**, focado em causas e melhorias do sistema, não em punir pessoas.

### Prática
Estrutura de um post-mortem:

```
Resumo:      Lições não salvavam entre 14h05 e 14h40 (35 min).
Impacto:     ~1.200 usuários; gelo de 80 iglus em risco.
Causa raiz:  migration sem índice deixou a consulta de progresso lenta.
Detecção:    alerta de latência p95 > 2s às 14h12.
Resolução:   rollback da versão e criação do índice.
Ações:       testar migrations com volume real; alerta mais cedo; 
             estender o gelo dos afetados em 1 dia.
```

### Questão
O que é um SLO?
- [ ] Um contrato com multa
- [x] Uma meta interna de confiabilidade para um indicador
- [ ] Um tipo de servidor
- [ ] Um log de erro
> Ex.: 99,9% das requisições bem-sucedidas no mês.

### Questão
Para que serve o error budget?
- [ ] Pagar multas
- [x] Equilibrar inovação e confiabilidade: quanta falha ainda é aceitável
- [ ] Contar bugs no código
- [ ] Definir o salário da equipe
> Com orçamento sobrando, o time pode arriscar mais; sem ele, foca em estabilidade.

### Questão
Qual a característica de um bom post-mortem?
- [ ] Encontrar o culpado e puni-lo
- [x] Ser sem culpados, focado em causas e melhorias do sistema
- [ ] Ser secreto
- [ ] Ser escrito antes do incidente
> Ambientes sem culpa incentivam a transparência e o aprendizado.
