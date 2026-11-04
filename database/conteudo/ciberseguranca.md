# Fundamentos de segurança
> Pilares, ameaças comuns e proteção pessoal.

## Projeto
**Auditoria de segurança pessoal:** revise suas contas principais (email, redes sociais, jogos): ative MFA, troque senhas repetidas usando um gerenciador de senhas, verifique em haveibeenpwned.com se seus emails vazaram e revise as permissões de apps no celular. Escreva um checklist para sua família.

## Os pilares da segurança
### Teoria
**Cibersegurança** é a proteção de sistemas, redes e dados contra ataques, danos e acessos não autorizados.

O modelo clássico é a **tríade CIA**:
- **Confidencialidade:** só quem tem permissão acessa a informação.
- **Integridade:** a informação não é alterada indevidamente.
- **Disponibilidade:** a informação e os sistemas estão acessíveis quando necessário.

Outros conceitos: **autenticidade** (garantir quem é quem) e **não repúdio** (não poder negar uma ação realizada).

Vocabulário: **ativo** (o que protegemos), **vulnerabilidade** (fraqueza), **ameaça** (o que pode explorar a fraqueza) e **risco** (probabilidade × impacto).

### Prática
Classifique cada incidente pelo pilar afetado:

```
Vazamento das notas dos alunos         → Confidencialidade
Alguém altera uma nota no sistema      → Integridade
Site da escola fora do ar por ataque   → Disponibilidade
```

### Questão
Um ataque que derruba um site afeta principalmente qual pilar?
- [ ] Confidencialidade
- [ ] Integridade
- [x] Disponibilidade
- [ ] Autenticidade
> O serviço deixa de estar acessível.

### Questão
Alterar indevidamente o valor de uma transferência afeta qual pilar?
- [ ] Disponibilidade
- [x] Integridade
- [ ] Confidencialidade
- [ ] Nenhum
> A informação foi modificada sem autorização.

### Questão
O que é uma vulnerabilidade?
- [ ] Um antivírus
- [x] Uma fraqueza que pode ser explorada por uma ameaça
- [ ] Um tipo de backup
- [ ] Um usuário administrador
> Risco surge quando uma ameaça pode explorar uma vulnerabilidade.

## Ameaças comuns
### Teoria
- **Phishing:** mensagens falsas (email, SMS, WhatsApp) que imitam empresas ou pessoas para roubar senhas ou dados. Variações: spear phishing (direcionado) e smishing (SMS).
- **Malware:** software malicioso: **vírus**, **worms** (se espalham sozinhos), **trojans** (disfarçados de programa legítimo), **spyware** e **ransomware** (sequestra arquivos e pede resgate).
- **Engenharia social:** manipular pessoas (urgência, autoridade, medo) em vez de quebrar sistemas.
- **Ataques a senhas:** força bruta, dicionário e **credential stuffing** (testar senhas vazadas em outros sites).
- **Golpes com IA:** vozes e vídeos falsos (deepfakes) imitando conhecidos.

### Prática
Sinais de alerta numa mensagem:

```
"URGENTE: sua conta será bloqueada em 1 hora! Clique: bit.ly/conta-segura"
 ✗ urgência artificial
 ✗ link encurtado/estranho em vez do domínio oficial
 ✗ ameaça de perda
 ✗ pedido de senha ou código
```

Na dúvida, não clique: acesse o site oficial digitando o endereço, ou ligue para a pessoa.

### Questão
O que é phishing?
- [ ] Um tipo de firewall
- [x] Uma mensagem falsa que tenta enganar a vítima para roubar dados
- [ ] Um backup automático
- [ ] Uma linguagem de programação
> Phishing explora a confiança e a pressa das pessoas.

### Questão
Qual malware criptografa arquivos e pede resgate?
- [ ] Spyware
- [ ] Adware
- [x] Ransomware
- [ ] Worm
> Backups offline são uma das melhores defesas.

### Questão
O que é credential stuffing?
- [ ] Criar senhas fortes
- [x] Testar senhas vazadas de um site em outros sites
- [ ] Guardar senhas num gerenciador
- [ ] Trocar a senha todo dia
> Por isso nunca reutilize senhas.

## Senhas, MFA e higiene digital
### Teoria
Boas práticas pessoais:
- **Senhas longas e únicas** para cada serviço. Frases-senha são fortes e fáceis de lembrar: `pinguim-come-sardinha-no-iglu-azul`.
- **Gerenciador de senhas** (Bitwarden, KeePassXC, o do navegador) gera e guarda tudo.
- **MFA/2FA:** além da senha, um segundo fator: app autenticador, chave física ou **passkeys**. SMS é melhor que nada, mas é o fator mais fraco.
- **Atualizações** do sistema e dos apps corrigem falhas conhecidas.
- **Backups** seguindo a regra **3-2-1**: 3 cópias, 2 mídias diferentes, 1 fora de casa.
- Cuidado com Wi-Fi público e com o que você publica nas redes.

### Prática
Força bruta contra senhas (estimativa simplificada, 10 bilhões de tentativas por segundo):

```
"teco123"                     → instantâneo (está em listas vazadas)
"T3c0!"                       → segundos (curta)
"pinguim-come-sardinha-azul"  → séculos (longa)
```

Comprimento vence complexidade. E com MFA, mesmo uma senha vazada não basta para invadir.

### Questão
Qual é a melhor estratégia de senhas?
- [ ] Uma senha forte para todos os sites
- [x] Senhas longas e únicas, guardadas num gerenciador de senhas
- [ ] Anotar num papel colado no monitor
- [ ] Usar a data de nascimento
> Uma senha vazada não compromete as outras contas.

### Questão
Qual segundo fator é considerado o mais fraco?
- [ ] Chave física de segurança
- [ ] App autenticador
- [x] Código por SMS
- [ ] Passkey
> SMS pode ser interceptado ou desviado (troca de chip).

### Questão
O que diz a regra de backup 3-2-1?
- [ ] 3 senhas, 2 emails, 1 celular
- [x] 3 cópias, em 2 mídias diferentes, com 1 fora do local
- [ ] Backup a cada 3 dias, 2 vezes, durante 1 mês
- [ ] 3 antivírus e 2 firewalls
> Assim um incêndio ou ransomware não destrói todas as cópias.

# Criptografia e redes seguras
> Criptografia, hashes, certificados e segurança de redes.

## Projeto
**Cofre de mensagens:** escreva um programa que cifra e decifra mensagens com AES-GCM (biblioteca `cryptography` no Python ou Web Crypto no JavaScript), guarda senhas de usuários com bcrypt/argon2 e verifica a integridade de arquivos com SHA-256. Explique cada escolha num README.

## Criptografia simétrica e assimétrica
### Teoria
**Criptografia** transforma dados legíveis (texto claro) em dados ilegíveis (texto cifrado) usando uma **chave**.

- **Simétrica:** a mesma chave cifra e decifra. Rápida, ideal para grandes volumes. Padrão atual: **AES**. Desafio: compartilhar a chave com segurança.
- **Assimétrica:** um par de chaves: a **pública** (pode ser divulgada) cifra, e só a **privada** correspondente decifra. Exemplos: **RSA**, **curvas elípticas (ECC)**. Mais lenta.

Na prática, usamos as duas juntas: a assimétrica troca uma chave simétrica com segurança, e a simétrica cifra os dados. É o que o HTTPS faz.

A assimétrica também permite **assinaturas digitais**: cifrar com a chave privada prova a autoria.

### Prática
```python
# pip install cryptography
from cryptography.fernet import Fernet   # AES por baixo

chave = Fernet.generate_key()
cofre = Fernet(chave)

cifrado = cofre.encrypt(b"O iglu secreto fica no polo sul")
print(cifrado)                 # ilegível
print(cofre.decrypt(cifrado))  # b'O iglu secreto fica no polo sul'
```

Quem não tem a `chave` não consegue ler a mensagem.

### Questão
Na criptografia simétrica:
- [ ] Usa-se um par de chaves pública e privada
- [x] A mesma chave cifra e decifra
- [ ] Não há chave
- [ ] Só é possível cifrar, nunca decifrar
> AES é o algoritmo simétrico mais usado.

### Questão
Na criptografia assimétrica, qual chave pode ser divulgada?
- [ ] A privada
- [x] A pública
- [ ] Ambas
- [ ] Nenhuma
> A privada nunca deve ser compartilhada.

### Questão
Por que o HTTPS usa criptografia simétrica e assimétrica juntas?
- [ ] Por acaso
- [x] A assimétrica troca a chave com segurança e a simétrica cifra os dados rapidamente
- [ ] Porque a simétrica não funciona sozinha em nenhum caso
- [ ] Para deixar o site mais lento
> Combina segurança na troca de chaves com desempenho.

## Hashes e armazenamento de senhas
### Teoria
Uma **função hash** transforma qualquer dado num resumo de tamanho fixo. Propriedades:
- **Determinística:** a mesma entrada gera sempre o mesmo hash.
- **Mão única:** não dá para voltar do hash à entrada.
- **Efeito avalanche:** uma pequena mudança altera totalmente o resultado.
- **Resistente a colisões:** é inviável achar duas entradas com o mesmo hash.

Usos: verificar integridade de arquivos (**SHA-256**) e armazenar senhas.

Para **senhas**, use algoritmos **lentos e com salt**: **bcrypt**, **argon2** ou scrypt. O **salt** (valor aleatório por usuário) impede ataques com tabelas pré-calculadas. **Nunca** use MD5 ou SHA-1 para senhas, e nunca guarde senhas em texto puro ou com criptografia reversível.

### Prática
```python
import hashlib
print(hashlib.sha256(b"gelo").hexdigest())
print(hashlib.sha256(b"Gelo").hexdigest())   # totalmente diferente

# senhas: pip install bcrypt
import bcrypt
hash_senha = bcrypt.hashpw(b"PenguinDev2026!", bcrypt.gensalt())
print(bcrypt.checkpw(b"PenguinDev2026!", hash_senha))  # True
print(bcrypt.checkpw(b"senha-errada", hash_senha))     # False
```

### Questão
Por que hashes são chamados de funções de mão única?
- [ ] Porque só funcionam uma vez
- [x] Porque não é viável recuperar a entrada a partir do hash
- [ ] Porque só aceitam números
- [ ] Porque são reversíveis com a chave certa
> Diferente da criptografia, hash não tem "decifrar".

### Questão
Qual algoritmo é adequado para guardar senhas?
- [ ] MD5
- [ ] SHA-1
- [x] bcrypt ou argon2
- [ ] Base64
> Algoritmos lentos e com salt dificultam ataques de força bruta.

### Questão
Para que serve o salt no hash de senhas?
- [ ] Deixar a senha mais curta
- [x] Tornar cada hash único, mesmo para senhas iguais, e impedir tabelas pré-calculadas
- [ ] Criptografar o banco inteiro
- [ ] Substituir o MFA
> Dois usuários com a senha "123456" terão hashes diferentes.

## TLS, certificados e segurança de redes
### Teoria
O **TLS** (o "S" do HTTPS) garante que a conexão é **cifrada**, **íntegra** e com o **servidor autêntico**.

A autenticidade vem dos **certificados digitais**, emitidos por **Autoridades Certificadoras (CAs)** confiáveis, que atestam que uma chave pública pertence a um domínio. O cadeado do navegador indica que o certificado é válido para aquele endereço (mas não garante que o site é honesto!). **Let's Encrypt** emite certificados gratuitos.

Defesas de rede:
- **Firewalls** e segmentação.
- **VPN** para conexões em redes não confiáveis.
- **IDS/IPS** para detectar e bloquear ataques.
- Desativar serviços e portas desnecessários.

### Prática
Inspecione um certificado:

```
Navegador: clique no cadeado → "A conexão é segura" → Certificado
Terminal:  openssl s_client -connect example.com:443 -servername example.com
```

Observe: para quem foi emitido, quem emitiu (a CA), a validade e o algoritmo de chave.

### Questão
O que um certificado digital comprova?
- [ ] Que o site é honesto
- [x] Que a chave pública pertence ao domínio, atestado por uma autoridade certificadora
- [ ] Que o site não tem bugs
- [ ] Que o site é gratuito
> Golpistas também podem ter certificados válidos para seus próprios domínios.

### Questão
O que o cadeado no navegador garante?
- [x] Que a conexão com aquele domínio é cifrada e autenticada
- [ ] Que o site é confiável e não é golpe
- [ ] Que o site não coleta dados
- [ ] Que o computador está sem vírus
> Sempre confira se o domínio é mesmo o que você esperava.

### Questão
Qual serviço emite certificados TLS gratuitos?
- [ ] Wireshark
- [x] Let's Encrypt
- [ ] Docker Hub
- [ ] Google Docs
> Ele popularizou o HTTPS em toda a web.

# Desenvolvimento seguro e defesa
> OWASP Top 10, autenticação, LGPD e resposta a incidentes.

## Projeto
**Caça às vulnerabilidades:** use um laboratório intencionalmente vulnerável (OWASP Juice Shop ou DVWA, localmente) para encontrar e explicar três falhas (SQL injection, XSS e controle de acesso). Depois, mostre como corrigi-las no código. Pratique apenas em ambientes próprios e autorizados.

## OWASP Top 10: injeção e XSS
### Teoria
O **OWASP Top 10** lista os riscos mais críticos em aplicações web. Dois clássicos:

**Injeção (SQL injection):** a entrada do usuário é concatenada num comando SQL e passa a fazer parte dele. Um atacante pode ler ou apagar dados. **Defesa:** consultas parametrizadas (prepared statements), nunca concatenação.

**XSS (Cross-Site Scripting):** o site exibe texto do usuário sem tratamento, e um script malicioso roda no navegador de outras pessoas (roubando sessões, por exemplo). **Defesa:** escapar a saída (`htmlspecialchars`, `textContent`), usar frameworks que escapam por padrão e uma **Content Security Policy (CSP)**.

Regra geral: **nunca confie na entrada do usuário**: valide na entrada e escape na saída.

### Prática
```php
// ❌ Vulnerável: o login ' OR '1'='1 entra sem senha
$sql = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";

// ✅ Seguro: valores enviados separados do comando
$consulta = $pdo->prepare('SELECT * FROM usuario WHERE email = ?');
$consulta->execute([$email]);

// ❌ XSS: echo $_GET['nome'];   com nome=<script>roubar()</script>
// ✅ Seguro:
echo htmlspecialchars($_GET['nome'] ?? '', ENT_QUOTES, 'UTF-8');
```

### Questão
Qual a principal defesa contra SQL injection?
- [ ] Esconder o nome das tabelas
- [x] Consultas parametrizadas (prepared statements)
- [ ] Usar senhas longas no banco
- [ ] Desligar o banco à noite
> Os valores nunca são interpretados como parte do SQL.

### Questão
O que é XSS?
- [ ] Um tipo de banco de dados
- [x] Injetar scripts que rodam no navegador de outros usuários
- [ ] Um ataque físico ao servidor
- [ ] Uma forma de compressão
> Ocorre quando a saída não é escapada.

### Questão
Qual o princípio geral para dados vindos do usuário?
- [ ] Confiar se vierem de formulários
- [x] Nunca confiar: validar na entrada e escapar na saída
- [ ] Validar apenas no JavaScript do navegador
- [ ] Guardar sem verificar
> Validação no navegador pode ser contornada; o servidor deve validar sempre.

## Autenticação, sessões e controle de acesso
### Teoria
Falhas de **controle de acesso** lideram o OWASP Top 10: usuários acessando dados ou ações que não deveriam. Exemplo: trocar `?id=5` por `?id=6` na URL e ver o perfil de outra pessoa (**IDOR**). **Defesa:** verificar no servidor, a cada requisição, se aquele usuário pode acessar aquele recurso.

Boas práticas de autenticação e sessão:
- Hash de senhas com bcrypt/argon2; limite de tentativas e MFA.
- Regenerar o id de sessão no login; cookies `HttpOnly`, `Secure` e `SameSite`.
- **Tokens CSRF** em formulários que alteram dados.
- Mensagens de erro genéricas ("email ou senha incorretos").
- **Menor privilégio** e negar por padrão.

### Prática
```php
// ❌ Confia no id que veio da URL
$iglu = $iglus->find((int) $_GET['id']);
$iglus->delete($iglu['id_iglu']);

// ✅ Verifica no servidor se o usuário logado pode apagar
$papel = $iglus->papel((int) $_GET['id'], $usuarioLogado['id_usuario']);
if ($papel !== 'dono') {
    http_response_code(403);
    exit('Só o dono pode apagar o iglu.');
}
```

### Questão
O que é uma falha IDOR?
- [ ] Senha fraca
- [x] Acessar recursos de outros usuários trocando um identificador, sem checagem de permissão
- [ ] Um vírus de email
- [ ] Um erro de digitação no HTML
> Insecure Direct Object Reference: falta de controle de acesso.

### Questão
Onde o controle de acesso deve ser verificado?
- [ ] Apenas escondendo botões na interface
- [x] No servidor, a cada requisição
- [ ] Apenas no JavaScript
- [ ] No CSS
> Esconder um botão não impede alguém de chamar a URL diretamente.

### Questão
Qual atributo de cookie impede que JavaScript leia o cookie de sessão?
- [ ] Secure
- [x] HttpOnly
- [ ] Domain
- [ ] Path
> HttpOnly reduz o impacto de ataques XSS.

## Privacidade, LGPD e resposta a incidentes
### Teoria
A **LGPD** (Lei Geral de Proteção de Dados) regula o uso de dados pessoais no Brasil. Princípios: **finalidade**, **necessidade** (coletar o mínimo), **transparência**, **segurança** e **responsabilização**. Titulares têm direito de acessar, corrigir e pedir a exclusão de seus dados. Incidentes relevantes devem ser comunicados à **ANPD** e aos afetados.

**Privacy by design:** pensar em privacidade desde o início do projeto.

Resposta a incidentes (ciclo do NIST):
1. **Preparação** (planos, contatos, backups, logs).
2. **Detecção e análise.**
3. **Contenção, erradicação e recuperação.**
4. **Lições aprendidas.**

E lembre-se da ética: testar a segurança de sistemas **só com autorização**. Fora disso, é crime (Lei 12.737/2012). Programas de **bug bounty** permitem que pesquisadores reportem falhas legalmente.

### Prática
Plano de resposta simplificado para "senhas de usuários vazaram":

```
Detecção:    alerta de acessos estranhos + aviso de pesquisador
Contenção:   invalidar sessões, forçar troca de senhas, fechar a falha
Erradicação: corrigir a vulnerabilidade e revisar outras parecidas
Recuperação: monitorar, restaurar serviços com segurança
Comunicação: avisar usuários e a ANPD com clareza
Lições:      post-mortem, testes automatizados para a falha
```

### Questão
Qual princípio da LGPD diz para coletar apenas os dados necessários?
- [ ] Transparência
- [x] Necessidade
- [ ] Livre acesso
- [ ] Qualidade
> Menos dados guardados significa menos risco em caso de vazamento.

### Questão
Qual é a primeira fase do ciclo de resposta a incidentes?
- [ ] Recuperação
- [x] Preparação
- [ ] Lições aprendidas
- [ ] Erradicação
> Planos e ferramentas precisam existir antes do incidente.

### Questão
Testar a segurança de um site sem autorização é:
- [ ] Permitido se você não roubar nada
- [x] Ilegal; testes só devem ser feitos com autorização ou em programas de bug bounty
- [ ] Obrigatório para estudantes
- [ ] Permitido em sites do governo
> A ética e a lei exigem autorização explícita.
