# Fundamentos de redes
> O que é uma rede, tipos, equipamentos e modelos em camadas.

## Projeto
**Raio-X da sua rede de casa:** descubra o IP do seu computador, o gateway, o DNS e o IP público. Desenhe a topologia (roteador, dispositivos, Wi-Fi) e use `ping`, `tracert` e `nslookup` para investigar o caminho até três sites. Registre tudo num relatório.

## O que é uma rede de computadores
### Teoria
Uma **rede** é um conjunto de dispositivos conectados para **trocar dados e compartilhar recursos** (internet, impressoras, arquivos).

Classificação por alcance:
- **PAN:** pessoal (fone Bluetooth com o celular).
- **LAN:** local (casa, escola, escritório).
- **MAN:** metropolitana (uma cidade).
- **WAN:** longa distância (a internet é a maior delas).

**Topologias** descrevem como os dispositivos se ligam: estrela (todos num switch, a mais comum), barramento, anel e malha.

Modelos de comunicação: **cliente-servidor** (seu navegador pede, o servidor responde) e **peer-to-peer** (todos são clientes e servidores, como no BitTorrent).

### Prática
No Windows, abra o terminal e descubra informações da sua rede:

```
ipconfig            mostra seu IP, máscara e gateway
ipconfig /all       inclui o endereço MAC e os servidores DNS
ping 8.8.8.8        testa se você alcança a internet
```

Repare: seu computador tem um IP local (como `192.168.0.15`) e o roteador é o gateway (como `192.168.0.1`).

### Questão
A rede da sua casa é um exemplo de:
- [ ] WAN
- [x] LAN
- [ ] MAN
- [ ] PAN
> Uma rede local cobre um espaço limitado.

### Questão
Qual topologia é a mais comum em redes locais atuais?
- [ ] Anel
- [ ] Barramento
- [x] Estrela
- [ ] Linha
> Os dispositivos se conectam a um switch ou roteador central.

### Questão
No modelo cliente-servidor, quem responde às requisições?
- [ ] O cliente
- [x] O servidor
- [ ] O cabo de rede
- [ ] O monitor
> O cliente pede; o servidor atende.

## Equipamentos de rede
### Teoria
Os principais equipamentos:
- **Placa de rede (NIC):** conecta o dispositivo à rede; tem um **endereço MAC** único.
- **Switch:** liga dispositivos numa LAN e entrega cada quadro só para o destinatário certo (usa endereços MAC).
- **Roteador:** liga **redes diferentes** e escolhe o caminho dos pacotes (usa endereços IP). O "roteador" de casa costuma juntar roteador, switch, ponto de acesso Wi-Fi e firewall.
- **Ponto de acesso (AP):** cria a rede sem fio.
- **Modem/ONT:** converte o sinal da operadora (cabo, fibra).
- **Firewall:** filtra o tráfego com base em regras.

Meios físicos: cabo de par trançado (Cat5e/Cat6), fibra óptica e ondas de rádio (Wi-Fi).

### Prática
O caminho de um vídeo até seu celular, de trás para frente:

```
Celular  ← Wi-Fi (ponto de acesso)
         ← roteador doméstico (NAT + firewall)
         ← ONT/modem (fibra da operadora)
         ← roteadores da operadora e da internet
         ← servidor do serviço de vídeo
```

### Questão
Qual equipamento conecta redes diferentes e escolhe rotas usando IP?
- [ ] Switch
- [x] Roteador
- [ ] Hub
- [ ] Placa de vídeo
> Roteadores trabalham com endereços IP entre redes.

### Questão
Qual endereço identifica fisicamente a placa de rede?
- [ ] IP
- [x] MAC
- [ ] URL
- [ ] DNS
> O MAC vem gravado na placa (48 bits).

### Questão
Qual meio físico oferece maior alcance e velocidade?
- [ ] Par trançado Cat5
- [x] Fibra óptica
- [ ] Bluetooth
- [ ] Infravermelho
> A fibra transmite luz por quilômetros com altas taxas.

## Modelos OSI e TCP/IP
### Teoria
Para organizar a comunicação, as redes são divididas em **camadas**, cada uma com uma função.

O **modelo OSI** tem 7 camadas (de baixo para cima): Física, Enlace, Rede, Transporte, Sessão, Apresentação e Aplicação.

O **modelo TCP/IP**, usado na prática, tem 4:
- **Acesso à rede:** física e enlace (Ethernet, Wi-Fi, MAC).
- **Internet:** endereçamento e roteamento (IP).
- **Transporte:** entrega entre aplicações (TCP, UDP, portas).
- **Aplicação:** serviços (HTTP, DNS, SMTP).

Cada camada adiciona seu cabeçalho aos dados: é o **encapsulamento**.

### Prática
Quando você envia uma mensagem num chat:

```
Aplicação:  "Oi, iglu!"  (HTTPS)
Transporte: + cabeçalho TCP (porta de origem e destino 443)  → segmento
Internet:   + cabeçalho IP (IP de origem e destino)          → pacote
Enlace:     + cabeçalho Ethernet/Wi-Fi (endereços MAC)       → quadro
Física:     bits viajando como sinais elétricos, luz ou rádio
```

No destino, o processo é desfeito de baixo para cima.

### Questão
Quantas camadas tem o modelo OSI?
- [ ] 4
- [ ] 5
- [x] 7
- [ ] 9
> Física, Enlace, Rede, Transporte, Sessão, Apresentação e Aplicação.

### Questão
Em qual camada do TCP/IP o protocolo IP atua?
- [ ] Aplicação
- [ ] Transporte
- [x] Internet
- [ ] Acesso à rede
> A camada de internet cuida de endereçamento e roteamento.

### Questão
O que é encapsulamento?
- [ ] Criptografar os dados
- [x] Cada camada adicionar seu cabeçalho aos dados ao descer na pilha
- [ ] Compactar arquivos
- [ ] Desligar a rede
> No destino, os cabeçalhos são removidos na ordem inversa.

# Endereçamento e protocolos
> IPv4, sub-redes, IPv6, TCP, UDP, DNS e DHCP.

## Projeto
**Plano de endereçamento da escola:** divida a rede `192.168.10.0/24` em sub-redes para laboratório (60 hosts), administração (25 hosts) e Wi-Fi de visitantes (100 hosts). Informe endereço de rede, máscara, faixa de hosts e broadcast de cada uma, e justifique as escolhas.

## Endereços IPv4 e máscaras
### Teoria
Um endereço **IPv4** tem 32 bits, escrito em 4 números de 0 a 255: `192.168.0.15`.

A **máscara de sub-rede** separa a parte da **rede** da parte do **host**. Em notação CIDR, `/24` significa que os 24 primeiros bits são da rede (máscara `255.255.255.0`), sobrando 8 bits para hosts: 256 endereços, dos quais **254 utilizáveis** (o primeiro identifica a rede e o último é o broadcast).

**Endereços privados** (usados dentro de casas e empresas, não roteados na internet):
- `10.0.0.0/8`
- `172.16.0.0/12`
- `192.168.0.0/16`

O **NAT** do roteador troca os IPs privados pelo IP público ao sair para a internet.

### Prática
Analisando `192.168.1.77/26`:

```
/26 → 26 bits de rede, 6 de host → 2^6 = 64 endereços por sub-rede
Máscara: 255.255.255.192
Sub-redes: .0, .64, .128, .192
77 está na sub-rede 192.168.1.64
Rede: 192.168.1.64   Broadcast: 192.168.1.127
Hosts utilizáveis: .65 a .126 (62 hosts)
```

### Questão
Quantos hosts utilizáveis existem numa rede /24?
- [ ] 256
- [x] 254
- [ ] 255
- [ ] 24
> 256 endereços menos o de rede e o de broadcast.

### Questão
Qual destes é um endereço IP privado?
- [ ] 8.8.8.8
- [x] 192.168.0.10
- [ ] 200.160.2.3
- [ ] 1.1.1.1
> 192.168.0.0/16 é uma faixa reservada para redes privadas.

### Questão
Para que serve o NAT?
- [ ] Atribuir nomes aos sites
- [x] Traduzir IPs privados para o IP público ao acessar a internet
- [ ] Criptografar o Wi-Fi
- [ ] Bloquear vírus
> Vários dispositivos compartilham um único IP público.

## IPv6, TCP e UDP
### Teoria
Os cerca de 4,3 bilhões de endereços IPv4 se esgotaram. O **IPv6** usa **128 bits**, escritos em hexadecimal (`2001:db8::1`), com endereços praticamente ilimitados e configuração automática.

Na camada de transporte, dois protocolos principais:
- **TCP:** orientado a conexão (aperto de mão em 3 vias: SYN, SYN-ACK, ACK), **confiável**, garante ordem e reenvia perdas. Usado em web, email, arquivos.
- **UDP:** sem conexão, sem garantias, porém **mais rápido** e leve. Usado em jogos online, chamadas de voz/vídeo, DNS e streaming ao vivo.

**Portas** identificam a aplicação: 80 (HTTP), 443 (HTTPS), 22 (SSH), 53 (DNS), 3306 (MySQL).

### Prática
Veja as conexões abertas no seu computador:

```
netstat -an | findstr ESTABLISHED
```

Cada linha mostra `IP:porta` local e remoto. Abra um site e rode de novo: aparecerão conexões para a porta 443.

### Questão
Quantos bits tem um endereço IPv6?
- [ ] 32
- [ ] 64
- [x] 128
- [ ] 256
> O espaço de endereços do IPv6 é gigantesco.

### Questão
Qual protocolo é mais indicado para uma chamada de vídeo em tempo real?
- [ ] TCP, pois reenvia tudo
- [x] UDP, pois prioriza velocidade e tolera pequenas perdas
- [ ] FTP
- [ ] SMTP
> Reenviar um quadro atrasado não ajuda numa conversa ao vivo.

### Questão
Qual porta é usada pelo HTTPS?
- [ ] 21
- [ ] 80
- [x] 443
- [ ] 3306
> HTTP usa 80; HTTPS usa 443.

## DNS, DHCP e HTTP
### Teoria
Protocolos de aplicação que você usa o tempo todo:

- **DHCP:** distribui automaticamente IP, máscara, gateway e DNS aos dispositivos quando eles entram na rede.
- **DNS:** traduz nomes (`technopenguin.com`) em endereços IP. Funciona em hierarquia: servidores raiz, domínios de topo (`.com`, `.br`) e servidores autoritativos. Registros comuns: `A` (IPv4), `AAAA` (IPv6), `CNAME` (apelido), `MX` (email).
- **HTTP/HTTPS:** pedidos (GET, POST...) e respostas com códigos de status (200 OK, 301 redirecionado, 404 não encontrado, 500 erro do servidor). O HTTPS adiciona criptografia com **TLS**.

### Prática
Investigue com comandos:

```
nslookup google.com         descobre o IP pelo DNS
nslookup -type=mx gmail.com mostra os servidores de email
curl -I https://example.com mostra os cabeçalhos e o status HTTP
ipconfig /renew             pede um novo IP ao servidor DHCP
```

### Questão
Qual protocolo traduz nomes de domínio em endereços IP?
- [ ] DHCP
- [x] DNS
- [ ] FTP
- [ ] ARP
> O DNS é a "agenda telefônica" da internet.

### Questão
O que o DHCP faz?
- [ ] Criptografa páginas
- [x] Atribui automaticamente configurações de rede (como IP) aos dispositivos
- [ ] Envia emails
- [ ] Bloqueia sites
> Sem DHCP, cada IP teria de ser configurado à mão.

### Questão
O que significa o código HTTP 404?
- [ ] Sucesso
- [ ] Erro interno do servidor
- [x] Recurso não encontrado
- [ ] Redirecionamento
> 2xx sucesso, 3xx redirecionamento, 4xx erro do cliente, 5xx erro do servidor.

# Redes na prática
> Roteamento, Wi-Fi, segurança e diagnóstico.

## Projeto
**Rede simulada no Cisco Packet Tracer:** monte duas LANs ligadas por roteadores, configure IPs, DHCP e rotas estáticas, crie uma VLAN para visitantes e teste a comunicação com `ping` e `tracert`. Documente a topologia e as configurações.

## Roteamento e Wi-Fi
### Teoria
**Roteamento** é escolher o caminho dos pacotes entre redes. Cada roteador consulta sua **tabela de rotas**:
- **Rotas estáticas:** configuradas à mão.
- **Rotas dinâmicas:** aprendidas por protocolos como OSPF (dentro de uma organização) e **BGP** (entre os provedores que formam a internet).
- **Rota padrão (default gateway):** para onde vai tudo que não tem rota específica.

**Wi-Fi** (padrões IEEE 802.11): Wi-Fi 5 (802.11ac), Wi-Fi 6/6E (802.11ax) e Wi-Fi 7. As faixas de **2,4 GHz** têm mais alcance e mais interferência; **5 GHz e 6 GHz** são mais rápidas e com menor alcance. Use criptografia **WPA3** (ou WPA2) com senha forte.

**VLANs** dividem logicamente um switch em redes separadas (ex.: alunos, professores, visitantes).

### Prática
Veja a tabela de rotas e o caminho até um site:

```
route print            tabela de rotas do Windows
tracert technopenguin.com   cada linha é um roteador no caminho
```

No `tracert`, o primeiro salto costuma ser o seu roteador (gateway padrão).

### Questão
Qual protocolo de roteamento interliga os provedores da internet?
- [ ] DHCP
- [ ] OSPF
- [x] BGP
- [ ] HTTP
> BGP troca rotas entre sistemas autônomos.

### Questão
Qual faixa de Wi-Fi tem maior alcance, porém mais interferência?
- [x] 2,4 GHz
- [ ] 5 GHz
- [ ] 6 GHz
- [ ] 60 GHz
> Frequências mais baixas atravessam melhor paredes, mas são mais congestionadas.

### Questão
Para que servem as VLANs?
- [ ] Aumentar a velocidade da internet
- [x] Separar logicamente redes num mesmo switch
- [ ] Substituir o roteador
- [ ] Criptografar o Wi-Fi
> Visitantes e administração ficam isolados mesmo no mesmo equipamento.

## Segurança de redes
### Teoria
Ameaças comuns: **sniffing** (captura de tráfego), **man-in-the-middle**, **phishing**, **ataques de negação de serviço (DDoS)** e redes Wi-Fi falsas.

Defesas:
- **Firewall:** permite ou bloqueia tráfego por IP, porta e protocolo.
- **Criptografia:** HTTPS/TLS, WPA3, **VPN** (túnel criptografado sobre a internet).
- **Segmentação:** VLANs e redes de convidados.
- **IDS/IPS:** detectam e bloqueiam padrões de ataque.
- **Atualizações** de roteadores e dispositivos, e senhas fortes.
- **Zero Trust:** não confiar automaticamente em nada dentro da rede; verificar sempre.

### Prática
Boas práticas no roteador de casa:

```
[ ] Trocar a senha do painel de administração
[ ] Usar WPA3 (ou WPA2-AES) com senha longa
[ ] Desativar WPS e acesso remoto ao painel
[ ] Atualizar o firmware
[ ] Criar rede de convidados para visitas e dispositivos IoT
```

### Questão
O que uma VPN faz?
- [ ] Aumenta a velocidade da conexão
- [x] Cria um túnel criptografado entre o dispositivo e outra rede
- [ ] Substitui o DNS
- [ ] Remove vírus
> O tráfego dentro do túnel fica protegido de bisbilhoteiros no caminho.

### Questão
O que é um ataque DDoS?
- [ ] Roubo de senha por email
- [x] Sobrecarregar um serviço com tráfego de muitas origens para derrubá-lo
- [ ] Uma falha no cabo
- [ ] Um tipo de criptografia
> Distributed Denial of Service.

### Questão
O que a abordagem Zero Trust propõe?
- [ ] Confiar em tudo dentro da rede interna
- [x] Verificar sempre identidade e permissões, mesmo dentro da rede
- [ ] Desligar o firewall
- [ ] Não usar senhas
> Estar "dentro" da rede não dá acesso automático a nada.

## Diagnóstico de problemas
### Teoria
Quando "a internet não funciona", siga as camadas de baixo para cima:

1. **Física:** cabo conectado? Wi-Fi ligado? Luzes do roteador?
2. **Enlace/rede:** o dispositivo recebeu IP? (`ipconfig`). Se começar com `169.254`, o DHCP falhou.
3. **Gateway:** `ping` no roteador responde?
4. **Internet:** `ping 8.8.8.8` responde? Se sim, a conexão funciona.
5. **DNS:** `ping google.com` falha mas o IP funciona? O problema é o DNS.
6. **Aplicação:** o site está fora do ar? O firewall bloqueia?

Ferramentas: `ping`, `tracert`, `nslookup`, `ipconfig`, `netstat` e o **Wireshark** para analisar pacotes.

### Prática
Roteiro de diagnóstico:

```
ipconfig                 → tenho IP válido? (não 169.254.x.x)
ping 192.168.0.1         → alcanço o roteador?
ping 8.8.8.8             → alcanço a internet?
nslookup technopenguin.com → o DNS resolve?
ipconfig /flushdns       → limpa o cache de DNS
```

### Questão
O que indica um IP começando com 169.254?
- [ ] Conexão perfeita
- [x] O dispositivo não conseguiu obter IP do servidor DHCP
- [ ] IP público
- [ ] Rede IPv6
> É um endereço automático de fallback (APIPA).

### Questão
`ping 8.8.8.8` funciona, mas `ping google.com` não. Qual o provável problema?
- [ ] Cabo desconectado
- [x] DNS
- [ ] Placa de rede queimada
- [ ] Falta de energia
> Há conexão com a internet, mas os nomes não estão sendo resolvidos.

### Questão
Qual ferramenta captura e analisa pacotes em detalhes?
- [ ] Paint
- [x] Wireshark
- [ ] Excel
- [ ] Bloco de notas
> O Wireshark mostra cada pacote e seus cabeçalhos camada por camada.
