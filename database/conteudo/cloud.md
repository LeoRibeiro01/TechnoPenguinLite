# Fundamentos da nuvem
> O que é computação em nuvem, modelos de serviço e de implantação.

## Projeto
**Comparativo de provedores:** escolha um pequeno app (um site com banco de dados) e compare como hospedá-lo na AWS, no Azure e no Google Cloud: serviços equivalentes, custo estimado mensal (use as calculadoras oficiais) e camadas gratuitas. Apresente uma recomendação justificada.

## O que é computação em nuvem
### Teoria
**Computação em nuvem** é o fornecimento de recursos de TI (servidores, armazenamento, bancos, redes, software) **sob demanda pela internet**, pagando pelo uso, em vez de comprar e manter hardware próprio.

Características essenciais (definição do NIST):
- **Autoatendimento sob demanda:** você cria recursos sozinho, em minutos.
- **Amplo acesso pela rede.**
- **Pool de recursos** compartilhado entre clientes.
- **Elasticidade:** aumentar ou diminuir conforme a carga.
- **Serviço medido:** paga pelo que usa.

Benefícios: começar rápido, trocar gasto de capital (CAPEX) por gasto operacional (OPEX), alcance global e alta disponibilidade. Principais provedores: **AWS**, **Microsoft Azure** e **Google Cloud**.

### Prática
Antes e depois da nuvem para lançar um site de eventos:

```
Sem nuvem:  comprar servidor → esperar entrega → instalar → configurar rede
            → pagar o servidor mesmo quando ninguém acessa
Com nuvem:  criar uma instância em minutos → aumentar na semana do evento
            → reduzir depois → pagar por hora usada
```

### Questão
Qual característica permite aumentar recursos na semana de pico e reduzir depois?
- [ ] Pool de recursos
- [x] Elasticidade
- [ ] Amplo acesso
- [ ] Virtualização de desktop
> A capacidade acompanha a demanda.

### Questão
O que significa "serviço medido" na nuvem?
- [ ] Os servidores são pesados
- [x] O uso é medido e você paga pelo que consumir
- [ ] O preço é fixo para sempre
- [ ] Só se paga na compra
> Modelo pague-pelo-uso.

### Questão
Qual destes NÃO é um dos grandes provedores de nuvem pública?
- [ ] AWS
- [ ] Microsoft Azure
- [ ] Google Cloud
- [x] Microsoft Excel
> Excel é uma planilha, não um provedor de nuvem.

## IaaS, PaaS e SaaS
### Teoria
Os **modelos de serviço** dizem quanto o provedor gerencia por você:

- **IaaS (Infraestrutura):** você recebe máquinas virtuais, redes e discos, e cuida do SO e da aplicação. Ex.: AWS EC2, Azure VMs.
- **PaaS (Plataforma):** você envia o código e o provedor cuida do servidor, SO e escala. Ex.: Heroku, Google App Engine, Azure App Service.
- **SaaS (Software):** você só usa o software pronto pelo navegador. Ex.: Gmail, Google Docs, Microsoft 365.

Há também **FaaS / serverless** (funções executadas sob demanda, como AWS Lambda) e **BaaS** (back-end pronto, como Firebase).

Quanto mais "alto" o modelo, menos controle e menos trabalho de manutenção.

### Prática
A analogia da pizza:

```
Fazer em casa  → tudo é seu (on-premises)
IaaS           → compra a massa pronta; o resto é com você
PaaS           → pede delivery; só põe a mesa
SaaS           → come na pizzaria; só aproveita
```

### Questão
Num serviço IaaS, quem cuida do sistema operacional?
- [ ] O provedor
- [x] O cliente
- [ ] Ninguém
- [ ] O navegador
> No IaaS, o provedor entrega a infraestrutura; SO e aplicação são seus.

### Questão
O Gmail é um exemplo de:
- [ ] IaaS
- [ ] PaaS
- [x] SaaS
- [ ] Hardware
> Você só usa o software pronto.

### Questão
O que caracteriza o serverless (FaaS)?
- [ ] Não existem servidores físicos
- [x] Você envia funções que rodam sob demanda, sem gerenciar servidores
- [ ] Funciona sem internet
- [ ] É sempre gratuito
> Os servidores existem, mas são totalmente abstraídos.

## Modelos de implantação
### Teoria
Onde a nuvem está e quem a usa:

- **Nuvem pública:** infraestrutura do provedor compartilhada por muitos clientes. Escalável e sem investimento inicial.
- **Nuvem privada:** dedicada a uma organização, no data center dela ou de um terceiro. Mais controle, mais custo.
- **Nuvem híbrida:** combina pública e privada, integradas (ex.: dados sensíveis no privado, picos de acesso no público).
- **Multicloud:** uso de vários provedores públicos, evitando dependência de um só (**lock-in**).

A nuvem é organizada em **regiões** (áreas geográficas) e **zonas de disponibilidade** (data centers independentes dentro de uma região). Distribuir a aplicação em várias zonas aumenta a resiliência.

### Prática
Um hospital decide:

```
Prontuários (dados sensíveis, regras rígidas) → nuvem privada
Site institucional e agendamento online         → nuvem pública
Integração entre os dois                        → nuvem híbrida
Backup em outro provedor                        → estratégia multicloud
```

### Questão
O que é uma nuvem híbrida?
- [ ] Uma nuvem só de servidores físicos
- [x] A combinação integrada de nuvem pública e privada
- [ ] Uma nuvem que funciona a gasolina e eletricidade
- [ ] Um único provedor público
> Permite colocar cada carga onde faz mais sentido.

### Questão
Por que distribuir uma aplicação em várias zonas de disponibilidade?
- [ ] Para pagar menos sempre
- [x] Para continuar funcionando se um data center falhar
- [ ] Para deixar o código mais curto
- [ ] Porque é obrigatório
> Zonas são isoladas umas das outras.

### Questão
O que é vendor lock-in?
- [ ] Um tipo de senha
- [x] Ficar dependente de um provedor, dificultando a migração
- [ ] Bloquear o acesso de clientes
- [ ] Um firewall
> Usar serviços muito específicos aumenta o custo de trocar de provedor.

# Serviços essenciais
> Computação, armazenamento, bancos de dados e redes na nuvem.

## Projeto
**Site estático + API na nuvem (camada gratuita):** publique um site estático num bucket de armazenamento (ou GitHub Pages/Netlify), crie uma função serverless que responde com dados em JSON e conecte as duas. Documente a arquitetura num diagrama e acompanhe os custos.

## Computação: VMs, contêineres e serverless
### Teoria
Opções para rodar código na nuvem, da mais controlada à mais gerenciada:

- **Máquinas virtuais:** EC2 (AWS), Compute Engine (GCP), Azure VMs. Você escolhe CPU, memória e SO.
- **Contêineres:** serviços como ECS, Cloud Run e Azure Container Apps rodam imagens Docker; **Kubernetes** gerenciado (EKS, GKE, AKS) orquestra muitos contêineres.
- **Serverless:** Lambda, Cloud Functions, Azure Functions executam funções por evento (requisição HTTP, arquivo enviado, mensagem numa fila) e cobram por execução.

**Autoescalonamento** adiciona ou remove instâncias conforme métricas (uso de CPU, requisições), e um **balanceador de carga** distribui o tráfego entre elas.

### Prática
Uma função serverless em Python (estilo AWS Lambda):

```python
import json

def handler(evento, contexto):
    nome = (evento.get("queryStringParameters") or {}).get("nome", "pinguim")
    return {
        "statusCode": 200,
        "headers": {"Content-Type": "application/json"},
        "body": json.dumps({"mensagem": f"Olá, {nome}! 🐧"}),
    }
```

Ela só roda (e só cobra) quando alguém faz uma requisição.

### Questão
O que faz um balanceador de carga?
- [ ] Mede o peso dos servidores
- [x] Distribui o tráfego entre várias instâncias
- [ ] Faz backup do banco
- [ ] Compila o código
> Assim nenhuma instância fica sobrecarregada e falhas são contornadas.

### Questão
Qual serviço orquestra muitos contêineres em produção?
- [ ] Excel
- [x] Kubernetes
- [ ] FTP
- [ ] DNS
> EKS, GKE e AKS são versões gerenciadas do Kubernetes.

### Questão
Quando uma função serverless é cobrada?
- [ ] O mês inteiro, ligada ou não
- [x] Principalmente quando é executada
- [ ] Nunca
- [ ] Só na criação
> O custo acompanha o número e a duração das execuções.

## Armazenamento e bancos de dados
### Teoria
Tipos de armazenamento:
- **Objetos:** arquivos acessados por URL/API, com durabilidade altíssima e custo baixo. Ex.: **Amazon S3**, Google Cloud Storage, Azure Blob. Ideal para imagens, vídeos, backups e sites estáticos.
- **Blocos:** discos conectados às VMs (EBS).
- **Arquivos:** sistemas de arquivos compartilhados (EFS, Azure Files).

**Classes de armazenamento** mais baratas (frio, arquivamento) servem para dados pouco acessados.

Bancos gerenciados tiram de você o trabalho de backups, atualizações e réplicas:
- Relacionais: Amazon RDS, Cloud SQL, Azure SQL (MySQL, PostgreSQL...).
- NoSQL: DynamoDB, Firestore, Cosmos DB.

### Prática
Com a CLI da AWS:

```
aws s3 mb s3://teco-fotos-2026                 cria um bucket
aws s3 cp pinguim.png s3://teco-fotos-2026/    envia um arquivo
aws s3 ls s3://teco-fotos-2026/                lista os objetos
```

Boas práticas: buckets **privados por padrão**, criptografia ativada e regras de ciclo de vida para mover arquivos antigos para classes mais baratas.

### Questão
Qual tipo de armazenamento é ideal para fotos, vídeos e backups?
- [ ] Blocos
- [x] Objetos (ex.: S3)
- [ ] Memória RAM
- [ ] Registrador da CPU
> Armazenamento de objetos é durável, escalável e barato.

### Questão
Qual a vantagem de um banco de dados gerenciado?
- [ ] Ser sempre gratuito
- [x] O provedor cuida de backups, atualizações e réplicas
- [ ] Não precisar de SQL
- [ ] Funcionar sem rede
> A equipe foca nos dados e na aplicação.

### Questão
Por que buckets devem ser privados por padrão?
- [ ] Para economizar espaço
- [x] Para evitar vazamento de dados expostos publicamente
- [ ] Porque públicos são mais lentos
- [ ] Não há motivo
> Buckets públicos mal configurados já causaram grandes vazamentos.

## Redes e identidade na nuvem
### Teoria
Na nuvem você monta redes virtuais:
- **VPC (Virtual Private Cloud):** sua rede isolada, com **sub-redes públicas** (acessíveis da internet) e **privadas** (como o banco de dados).
- **Grupos de segurança** e ACLs: firewalls que definem quais portas e origens são permitidas.
- **CDN** (CloudFront, Cloud CDN): copia conteúdo para servidores perto dos usuários, reduzindo latência.
- **DNS gerenciado** (Route 53).

**IAM (Identity and Access Management)** controla quem pode fazer o quê: usuários, grupos, **papéis** e políticas. Regras de ouro: **menor privilégio**, **MFA** e nunca usar a conta raiz no dia a dia.

### Prática
Arquitetura típica de uma aplicação web:

```
Usuários → CDN → Balanceador de carga (sub-rede pública)
                 → Servidores da aplicação (sub-rede privada)
                    → Banco de dados gerenciado (sub-rede privada, porta 3306
                      liberada só para os servidores da aplicação)
```

Exemplo de política IAM que só permite ler um bucket:

```json
{
  "Effect": "Allow",
  "Action": ["s3:GetObject"],
  "Resource": "arn:aws:s3:::teco-fotos-2026/*"
}
```

### Questão
Onde deve ficar o banco de dados numa VPC bem desenhada?
- [ ] Numa sub-rede pública
- [x] Numa sub-rede privada, acessível só pela aplicação
- [ ] Fora da VPC
- [ ] No navegador
> O banco não deve ser alcançável diretamente pela internet.

### Questão
Para que serve uma CDN?
- [ ] Criar bancos de dados
- [x] Entregar conteúdo a partir de servidores próximos aos usuários
- [ ] Gerenciar senhas
- [ ] Compilar código
> Menos distância significa menos latência.

### Questão
Qual boa prática de IAM é correta?
- [ ] Usar a conta raiz para tudo
- [x] Aplicar o menor privilégio e ativar MFA
- [ ] Compartilhar a senha com a equipe
- [ ] Dar permissão total a todos
> Cada identidade recebe só as permissões necessárias.

# Arquitetura e operação na nuvem
> Escalabilidade, custos, segurança e boas práticas.

## Projeto
**Arquitetura bem projetada:** desenhe a arquitetura de nuvem do TechnoPenguin para 100 mil usuários, aplicando os pilares do Well-Architected Framework (confiabilidade, segurança, desempenho, custo, excelência operacional e sustentabilidade). Inclua estimativa de custos e plano de recuperação de desastres.

## Escalabilidade e alta disponibilidade
### Teoria
- **Escala vertical:** aumentar a máquina (mais CPU/RAM). Simples, mas tem limite e geralmente exige reinício.
- **Escala horizontal:** adicionar mais máquinas. Exige aplicações **sem estado** (sessões guardadas fora, em cache ou banco), mas escala quase sem limite.

**Alta disponibilidade** vem de eliminar pontos únicos de falha: várias instâncias em várias zonas, bancos com réplicas e failover automático.

**Recuperação de desastres** define dois números: **RPO** (quanto dado posso perder) e **RTO** (quanto tempo posso ficar fora do ar). Backups em outra região e testes periódicos de restauração são indispensáveis.

### Prática
Preparando o app para escalar horizontalmente:

```
❌ Sessões salvas no disco do servidor  → usuário "desloga" ao trocar de servidor
✅ Sessões no Redis gerenciado           → qualquer servidor atende qualquer usuário
❌ Uploads salvos na pasta local         → arquivos somem com a instância
✅ Uploads no armazenamento de objetos   → disponíveis para todas as instâncias
```

### Questão
O que é escala horizontal?
- [ ] Aumentar a CPU de um servidor
- [x] Adicionar mais servidores para dividir a carga
- [ ] Deitar o servidor
- [ ] Reduzir a memória
> Exige que a aplicação não dependa do estado local de uma máquina.

### Questão
O que significa RPO?
- [ ] O tempo máximo fora do ar
- [x] A quantidade máxima de dados que se aceita perder
- [ ] O número de servidores
- [ ] O preço por hora
> Recovery Point Objective; RTO é o tempo de recuperação.

### Questão
Por que guardar sessões num cache externo como o Redis?
- [ ] Para deixar o login mais lento
- [x] Para que qualquer instância consiga atender o usuário
- [ ] Porque o PHP exige
- [ ] Para apagar as sessões
> Aplicações sem estado escalam horizontalmente.

## Custos na nuvem (FinOps)
### Teoria
A nuvem é barata quando bem usada e muito cara quando esquecida. **FinOps** é a prática de gerenciar custos de nuvem com colaboração entre engenharia e finanças.

Boas práticas:
- **Orçamentos e alertas** de gasto.
- **Etiquetas (tags)** para saber qual projeto gera cada custo.
- **Dimensionamento correto** (rightsizing): não usar máquinas maiores que o necessário.
- **Desligar ambientes** de teste fora do horário.
- **Instâncias reservadas / planos de economia** para cargas constantes; **instâncias spot** (até 90% mais baratas) para tarefas que toleram interrupções.
- Atenção à **transferência de dados de saída**, que costuma ser cobrada.

### Prática
Cálculo simples de economia:

```
Servidor de testes: R$ 0,50/hora
Ligado 24h x 30 dias        = R$ 360/mês
Ligado só 10h x 22 dias úteis = R$ 110/mês  → economia de ~70%
```

Configure um alerta de orçamento (por exemplo, R$ 50) logo que criar uma conta de estudos.

### Questão
O que são instâncias spot?
- [ ] Instâncias com desconto fixo de 1%
- [x] Capacidade ociosa vendida mais barata, que pode ser interrompida
- [ ] Servidores físicos dedicados
- [ ] Instâncias gratuitas para sempre
> Ótimas para processamento em lote tolerante a interrupções.

### Questão
Para que servem as tags nos recursos?
- [ ] Deixar os servidores mais rápidos
- [x] Identificar projetos, equipes e ambientes para rastrear custos
- [ ] Criptografar dados
- [ ] Criar usuários
> Sem tags, é difícil saber quem gasta o quê.

### Questão
Qual a primeira medida ao criar uma conta de nuvem para estudos?
- [ ] Criar máquinas grandes
- [x] Configurar alertas de orçamento
- [ ] Desativar o MFA
- [ ] Tornar todos os buckets públicos
> Alertas evitam surpresas na fatura.

## Segurança e infraestrutura como código
### Teoria
Na nuvem vale o **modelo de responsabilidade compartilhada**: o provedor protege a infraestrutura **da** nuvem (data centers, hardware, rede física); o cliente protege o que coloca **na** nuvem (dados, configurações, acessos, aplicações).

A maior parte dos incidentes vem de **configurações erradas**: buckets públicos, portas abertas, chaves de acesso vazadas no código.

**Infraestrutura como Código (IaC)** descreve a infraestrutura em arquivos versionados (**Terraform**, CloudFormation, Bicep, Pulumi). Benefícios: ambientes reproduzíveis, revisão por pares, histórico de mudanças e menos erros manuais.

### Prática
Um bucket privado e criptografado com Terraform:

```
resource "aws_s3_bucket" "fotos" {
  bucket = "teco-fotos-2026"
}

resource "aws_s3_bucket_public_access_block" "fotos" {
  bucket                  = aws_s3_bucket.fotos.id
  block_public_acls       = true
  block_public_policy     = true
  restrict_public_buckets = true
  ignore_public_acls      = true
}
```

Comandos: `terraform init`, `terraform plan` (mostra o que vai mudar) e `terraform apply`.

### Questão
No modelo de responsabilidade compartilhada, quem protege os dados que você coloca na nuvem?
- [ ] Só o provedor
- [x] O cliente
- [ ] Ninguém
- [ ] O governo
> O provedor protege a infraestrutura; dados e configurações são do cliente.

### Questão
Qual a causa mais comum de incidentes de segurança na nuvem?
- [ ] Falhas de energia
- [x] Configurações erradas, como recursos expostos publicamente
- [ ] Meteoros
- [ ] Excesso de criptografia
> Revisões, IaC e ferramentas de conformidade ajudam a evitá-las.

### Questão
O que `terraform plan` faz?
- [ ] Apaga a infraestrutura
- [x] Mostra as mudanças que serão aplicadas, sem executá-las
- [ ] Compila o código da aplicação
- [ ] Cria um bucket público
> Revisar o plano antes do apply evita surpresas.
