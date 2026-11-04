@historia
Em 1969, a **ARPANET**, financiada pelo Departamento de Defesa dos EUA, conectou quatro universidades usando **comutação de pacotes**, ideia desenvolvida por Paul Baran e Donald Davies: dividir mensagens em pedaços que seguem caminhos independentes. Em 1974, **Vint Cerf** e **Bob Kahn** publicaram o **TCP/IP**, que permitia interligar redes diferentes; em 1º de janeiro de 1983 a ARPANET migrou inteira para ele, data considerada o nascimento da internet.

Em 1973, **Bob Metcalfe** inventou a **Ethernet** no Xerox PARC. Nos anos 1980 surgiram o DNS e o modelo OSI. O **Wi-Fi** (IEEE 802.11) veio em 1997. No Brasil, a internet chegou às universidades em 1988 e ao público em 1995, com a criação do Comitê Gestor da Internet (CGI.br).

@hoje
Redes estão em tudo: casas, escolas, empresas, data centers e celulares. Todo desenvolvedor precisa entender o básico para depurar problemas (DNS, portas, firewalls, latência) e criar sistemas distribuídos. Profissionais de redes configuram roteadores, switches, Wi-Fi corporativo, VPNs e redes na nuvem. Certificações como **Cisco CCNA** e **CompTIA Network+** são muito procuradas, e a transição para **IPv6** e a chegada do **5G** e do **Wi-Fi 7** mantêm a área em movimento.

@iniciante
- Entender o que é uma rede, tipos (LAN, WAN) e topologias
- Conhecer equipamentos: placa de rede, switch, roteador e ponto de acesso
- Compreender os modelos OSI e TCP/IP e o encapsulamento
- Investigar sua própria rede com ipconfig e ping

@intermediario
- Calcular endereços IPv4, máscaras e sub-redes
- Entender IPv6, TCP, UDP e portas
- Explicar como funcionam DNS, DHCP e HTTP
- Usar nslookup, tracert e curl para investigar

@avancado
- Entender roteamento estático e dinâmico, Wi-Fi e VLANs
- Proteger redes com firewall, VPN, segmentação e Zero Trust
- Diagnosticar problemas camada por camada
- Analisar pacotes com o Wireshark

@referencias
**Livros**
- *Redes de Computadores e a Internet: uma abordagem top-down* — James Kurose e Keith Ross: o livro mais usado no mundo, começando pelas aplicações.
- *Redes de Computadores* — Andrew Tanenbaum e David Wetherall: a referência clássica, camada por camada.
- *TCP/IP Illustrated, vol. 1* — W. Richard Stevens: os protocolos vistos pacote a pacote.

**Na internet**
- [Cisco Networking Academy](https://www.netacad.com/pt) — cursos gratuitos e o simulador Packet Tracer.
- [Wireshark — documentação](https://www.wireshark.org/docs/)
- [Registro.br e NIC.br — cursos e materiais](https://nic.br)

@modulo 1
**Camadas existem para que cada parte possa mudar sem quebrar as outras.** Kurose e Ross explicam a internet como uma pilha de serviços: a aplicação não sabe se os bits viajam por fibra ou Wi-Fi, e o cabo não sabe se carrega um vídeo ou um email. Esse **princípio fim a fim** (*end-to-end*) põe a inteligência nas pontas (computadores) e deixa o núcleo da rede simples, só encaminhando pacotes.

O desempenho de uma rede tem quatro atrasos: **processamento** (no roteador), **fila** (esperando a vez), **transmissão** (colocar os bits no cabo) e **propagação** (a viagem física, limitada pela velocidade da luz). Por isso, mesmo com fibra rápida, um servidor do outro lado do mundo responde mais devagar: latência não se resolve só com mais banda.

@fontes 1
- Kurose e Ross, *Redes de Computadores e a Internet*, capítulo 1
- Tanenbaum e Wetherall, *Redes de Computadores*, capítulo 1 (modelos de referência)
- Saltzer, Reed e Clark, "End-to-End Arguments in System Design" (1984)

@modulo 2
**O TCP oferece confiabilidade sobre uma rede que não é confiável.** O IP só tenta entregar ("melhor esforço"): pacotes podem se perder, chegar fora de ordem ou duplicados. O TCP numera os bytes, confirma o recebimento, reenvia o que se perdeu e controla o ritmo com dois mecanismos: **controle de fluxo** (não afogar o receptor) e **controle de congestionamento** (não afogar a rede), que reduz a velocidade quando detecta perdas.

O **DNS** é um banco de dados distribuído e hierárquico, com cache em vários níveis (navegador, sistema, provedor), cada registro com um tempo de vida (TTL). Por isso uma mudança de DNS pode levar horas para "propagar".

Hoje, o HTTP/3 usa o protocolo **QUIC**, sobre UDP, que traz a confiabilidade para dentro da aplicação e reduz o tempo de conexão.

@fontes 2
- Kurose e Ross, *Redes de Computadores e a Internet*, capítulos 2 (DNS e HTTP) e 3 (TCP)
- Stevens, *TCP/IP Illustrated*, vol. 1, capítulos sobre conexão e retransmissão TCP
- [RFC 9000 — QUIC](https://www.rfc-editor.org/rfc/rfc9000)

@modulo 3
**Diagnóstico de redes é método científico.** Formule hipóteses começando pelas camadas de baixo e teste uma de cada vez. O **Wireshark** mostra a verdade sobre o que acontece no fio: dá para ver o aperto de mão do TCP, consultas DNS e até reenvios causados por perdas.

O roteamento entre redes de empresas e provedores usa o **BGP**, baseado em políticas e em confiança. Erros de configuração já derrubaram grandes serviços, como na queda global do Facebook em outubro de 2021, causada por um comando que retirou as rotas da empresa da internet.

Em segurança, o modelo **Zero Trust** (NIST SP 800-207) substitui o antigo "castelo e fosso": não basta estar dentro da rede, toda requisição precisa ser autenticada e autorizada.

@fontes 3
- Tanenbaum e Wetherall, *Redes de Computadores*, capítulo 5 (roteamento)
- NIST SP 800-207, *Zero Trust Architecture*
- [Wireshark User's Guide](https://www.wireshark.org/docs/wsug_html_chunked/)
