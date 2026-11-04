@historia
Em 1961, o cientista **John McCarthy** previu que a computação um dia seria vendida como serviço público, como água e luz. Os mainframes com tempo compartilhado chegaram perto disso, mas a ideia só se tornou realidade com a internet rápida e a virtualização.

Em 2006, a **Amazon Web Services** lançou o **S3** (armazenamento) e o **EC2** (servidores sob demanda): pela primeira vez, qualquer pessoa podia alugar servidores por hora com um cartão de crédito. Vieram o Google App Engine (2008), o **Microsoft Azure** (2010) e o Google Cloud. Em 2011, o **NIST** publicou a definição de computação em nuvem usada até hoje. Em 2014, o **AWS Lambda** popularizou o serverless, e em 2015 nasceu a CNCF, que cuida do Kubernetes e da "nuvem nativa".

@hoje
A maioria das empresas usa nuvem pública, privada ou híbrida. Startups lançam produtos sem comprar nenhum servidor; bancos, governos e varejistas migram sistemas inteiros. Serviços de streaming, jogos online e aplicativos de IA dependem da escala da nuvem, e treinar grandes modelos de IA exige data centers enormes. Profissionais de nuvem (arquitetos, engenheiros de plataforma, DevOps e FinOps) estão entre os mais procurados, com certificações como **AWS Cloud Practitioner**, **Azure Fundamentals (AZ-900)** e **Google Cloud Digital Leader**.

@iniciante
- Entender a definição de computação em nuvem e suas vantagens
- Diferenciar IaaS, PaaS, SaaS e serverless
- Conhecer nuvem pública, privada, híbrida e multicloud
- Entender regiões e zonas de disponibilidade

@intermediario
- Escolher entre máquinas virtuais, contêineres e funções serverless
- Usar armazenamento de objetos e bancos de dados gerenciados
- Montar redes com VPC, sub-redes, grupos de segurança e CDN
- Controlar acessos com IAM, menor privilégio e MFA

@avancado
- Projetar sistemas escaláveis e altamente disponíveis
- Planejar recuperação de desastres com RPO e RTO
- Controlar custos com práticas de FinOps
- Descrever infraestrutura como código com Terraform

@referencias
**Livros**
- *Cloud Computing: Concepts, Technology & Architecture* — Thomas Erl, Ricardo Puttini e Zaigham Mahmood: os conceitos sem depender de um provedor.
- *Designing Data-Intensive Applications* — Martin Kleppmann: como sistemas distribuídos guardam e processam dados.
- *Terraform: Up & Running* — Yevgeniy Brikman: infraestrutura como código na prática.

**Na internet**
- [AWS Well-Architected Framework](https://docs.aws.amazon.com/pt_br/wellarchitected/latest/framework/welcome.html)
- [Microsoft Learn: Azure Fundamentals](https://learn.microsoft.com/pt-br/training/paths/microsoft-azure-fundamentals-describe-cloud-concepts/)
- [NIST SP 800-145 — A definição de nuvem](https://csrc.nist.gov/pubs/sp/800/145/final)

@modulo 1
**A nuvem troca propriedade por elasticidade.** O documento do NIST (SP 800-145) define cinco características, três modelos de serviço e quatro modelos de implantação; quase toda discussão sobre nuvem usa esse vocabulário.

Uma ideia útil é **"pets vs. cattle"** (bichos de estimação contra gado): servidores tradicionais eram cuidados um a um, com nome e carinho; na nuvem, servidores são intercambiáveis, criados e destruídos automaticamente. Se um falha, outro igual sobe no lugar. Isso exige que aplicações não guardem estado importante no disco local.

A nuvem também tem limites: dependência de um provedor (*lock-in*), custos que crescem sem controle, latência e questões legais sobre onde os dados ficam guardados, importantes para a LGPD.

@fontes 1
- Erl, Puttini e Mahmood, *Cloud Computing: Concepts, Technology & Architecture*, capítulos 3 e 4
- [NIST SP 800-145](https://csrc.nist.gov/pubs/sp/800/145/final)
- [AWS: Visão geral da AWS (whitepaper)](https://docs.aws.amazon.com/pt_br/whitepapers/latest/aws-overview/introduction.html)

@modulo 2
**Escolha serviços gerenciados sempre que puder.** Cada servidor que você mesmo administra precisa de atualizações, backups, monitoramento e plantão. Bancos gerenciados, filas e armazenamento de objetos transferem esse trabalho ao provedor, e a equipe se concentra no produto.

O armazenamento de objetos como o S3 tem durabilidade projetada de "onze noves" (99,999999999%), conseguida guardando cópias em vários data centers. Já sistemas distribuídos enfrentam o **teorema CAP**: diante de uma falha de rede, é preciso escolher entre consistência e disponibilidade. Martin Kleppmann, em *Designing Data-Intensive Applications*, explica esses compromissos de forma clara.

Na segurança, a identidade é o novo perímetro: um papel IAM com permissões demais é tão perigoso quanto uma porta aberta.

@fontes 2
- Kleppmann, *Designing Data-Intensive Applications*, capítulos 5 (replicação) e 9 (consistência)
- [AWS: Melhores práticas de segurança no IAM](https://docs.aws.amazon.com/pt_br/IAM/latest/UserGuide/best-practices.html)
- [Amazon S3: durabilidade e disponibilidade](https://docs.aws.amazon.com/pt_br/AmazonS3/latest/userguide/DataDurability.html)

@modulo 3
**Projete para a falha.** O AWS Well-Architected Framework tem seis pilares: excelência operacional, segurança, confiabilidade, eficiência de desempenho, otimização de custos e sustentabilidade. Um princípio central é assumir que tudo falha um dia: servidores, discos, zonas inteiras. A Netflix chegou a criar o **Chaos Monkey**, que desliga servidores de propósito em produção para garantir que o sistema se recupere sozinho.

Os **12 fatores** (*The Twelve-Factor App*), publicados pelos criadores do Heroku, resumem como escrever aplicações que escalam na nuvem: configuração em variáveis de ambiente, processos sem estado, logs como fluxo de eventos e paridade entre desenvolvimento e produção.

Com infraestrutura como código, todo o ambiente pode ser recriado do zero, o que é a melhor forma de recuperação de desastres.

@fontes 3
- [AWS Well-Architected: os seis pilares](https://docs.aws.amazon.com/pt_br/wellarchitected/latest/framework/the-pillars-of-the-framework.html)
- [The Twelve-Factor App (em português)](https://12factor.net/pt_br/)
- Brikman, *Terraform: Up & Running*, capítulos 1 a 3
