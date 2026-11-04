@historia
A segurança da informação é tão antiga quanto os segredos: a **cifra de César** já trocava letras para esconder mensagens militares. No século XX, quebrar a máquina **Enigma** em Bletchley Park ajudou a encurtar a Segunda Guerra. Em 1976, **Whitfield Diffie** e **Martin Hellman** publicaram a criptografia de chave pública, e em 1977 surgiu o **RSA**, base da segurança da internet.

Em 1988, o **Worm de Morris** infectou cerca de 10% dos computadores da internet da época, e foi criado o primeiro CERT, um centro de resposta a incidentes. Vieram vírus de email (ILOVEYOU, 2000), o **Stuxnet** (2010, que sabotou centrífugas no Irã), o ransomware **WannaCry** (2017) e grandes vazamentos de dados. No Brasil, a **LGPD** entrou em vigor em 2020 e a ANPD passou a fiscalizar o tratamento de dados pessoais.

@hoje
Ataques de ransomware atingem hospitais, prefeituras e empresas; golpes de phishing e engenharia social (agora com vozes e vídeos falsos gerados por IA) atingem pessoas comuns. A demanda por profissionais de segurança é muito maior que a oferta: analistas de SOC, pentesters, engenheiros de segurança de aplicações (AppSec) e especialistas em privacidade. E todo desenvolvedor é responsável por escrever código seguro, seguindo referências como o **OWASP Top 10**.

@iniciante
- Entender os pilares confidencialidade, integridade e disponibilidade
- Reconhecer phishing, malware, ransomware e engenharia social
- Proteger contas com senhas únicas, gerenciador de senhas e MFA
- Fazer backups seguindo a regra 3-2-1

@intermediario
- Diferenciar criptografia simétrica e assimétrica
- Usar hashes para integridade e bcrypt/argon2 para senhas
- Entender TLS, certificados digitais e autoridades certificadoras
- Conhecer firewalls, VPN, IDS/IPS e segmentação

@avancado
- Prevenir as falhas do OWASP Top 10, como injeção e XSS
- Implementar autenticação, sessões e controle de acesso seguros
- Aplicar a LGPD e o privacy by design
- Planejar resposta a incidentes e agir com ética e autorização

@referencias
**Livros**
- *Security Engineering* — Ross Anderson: a obra de referência sobre engenharia de segurança ([edições anteriores gratuitas online](https://www.cl.cam.ac.uk/~rja14/book.html)).
- *The Web Application Hacker's Handbook* — Dafydd Stuttard e Marcus Pinto: as falhas de aplicações web em detalhe.
- *A Arte de Enganar* — Kevin Mitnick: engenharia social contada por um dos hackers mais famosos.
- *Serious Cryptography* — Jean-Philippe Aumasson: criptografia moderna sem complicação excessiva.

**Na internet**
- [OWASP Top 10](https://owasp.org/Top10/pt_BR/) — os riscos mais críticos em aplicações web.
- [OWASP Juice Shop](https://owasp.org/www-project-juice-shop/) — aplicação vulnerável para treinar com segurança.
- [Cartilha de Segurança para Internet (CERT.br)](https://cartilha.cert.br)

@modulo 1
**Segurança é gerenciar riscos, não eliminá-los.** Ross Anderson, em *Security Engineering*, lembra que todo sistema seguro equilibra quatro coisas: a **política** (o que se quer proteger), os **mecanismos** (criptografia, controles de acesso), a **garantia** (quanto podemos confiar nos mecanismos) e o **incentivo** (quem ganha e quem perde). Muitas falhas acontecem não por falta de tecnologia, mas porque o sistema ignorou as pessoas.

Kevin Mitnick mostra em *A Arte de Enganar* que o elo mais fraco costuma ser humano: atacantes exploram urgência, autoridade, reciprocidade e medo. Por isso treinamento e processos (confirmar pedidos por outro canal, por exemplo) importam tanto quanto antivírus.

O conceito de **defesa em profundidade** usa várias camadas de proteção, para que a falha de uma não comprometa tudo.

@fontes 1
- Anderson, *Security Engineering*, capítulos 1 (o que é engenharia de segurança) e 3 (psicologia e usabilidade)
- Mitnick, *A Arte de Enganar*, parte 1
- [CERT.br: Cartilha de Segurança — golpes na internet](https://cartilha.cert.br/golpes/)

@modulo 2
**Nunca invente sua própria criptografia.** O **princípio de Kerckhoffs** (1883) diz que um sistema deve continuar seguro mesmo que o inimigo conheça tudo sobre ele, exceto a chave. Por isso usamos algoritmos públicos, estudados por anos (AES, ChaCha20, curvas elípticas), e bibliotecas confiáveis, em vez de esquemas caseiros.

Em *Serious Cryptography*, Jean-Philippe Aumasson explica que a maioria das falhas reais não está nos algoritmos, mas no **uso**: chaves fracas, números aleatórios previsíveis, reutilização de nonces, modos de operação errados (como o ECB, que deixa padrões visíveis) e certificados não verificados.

A computação quântica ameaça o RSA e as curvas elípticas atuais; por isso o NIST padronizou em 2024 algoritmos de **criptografia pós-quântica**, como o ML-KEM, que já começam a ser usados no TLS.

@fontes 2
- Aumasson, *Serious Cryptography*, capítulos 1 (cifragem), 4 (cifras de bloco) e 13 (TLS)
- [NIST: padrões de criptografia pós-quântica (2024)](https://csrc.nist.gov/projects/post-quantum-cryptography)
- [OWASP Cheat Sheet: Password Storage](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

@modulo 3
**Segurança precisa entrar no ciclo de desenvolvimento desde o começo** (*shift left*). Práticas como **modelagem de ameaças** (a técnica STRIDE pergunta sobre falsificação, adulteração, repúdio, vazamento de informação, negação de serviço e elevação de privilégio), revisão de código focada em segurança, análise estática (SAST), análise de dependências e testes dinâmicos (DAST) no pipeline encontram falhas antes de chegarem à produção.

*The Web Application Hacker's Handbook* mostra que a raiz de quase todas as falhas web é a mesma: confiar em dados controlados pelo usuário. O **OWASP ASVS** traz uma lista detalhada de requisitos de segurança para verificar aplicações.

Na LGPD, privacidade desde a concepção significa coletar menos, guardar por menos tempo, anonimizar quando possível e dar ao titular controle real sobre seus dados.

@fontes 3
- Stuttard e Pinto, *The Web Application Hacker's Handbook*, capítulos 1 e 2
- [OWASP Application Security Verification Standard (ASVS)](https://owasp.org/www-project-application-security-verification-standard/)
- [ANPD — Guias orientativos sobre a LGPD](https://www.gov.br/anpd/pt-br/centrais-de-conteudo/materiais-educativos-e-publicacoes)
