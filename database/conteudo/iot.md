# Fundamentos de IoT e sistemas ciberfísicos
> Conceitos, arquitetura e componentes.

## Projeto
**Mapa de um iglu inteligente:** escolha um ambiente (casa, sala de aula, estufa) e desenhe um sistema IoT para ele: quais sensores e atuadores, que microcontrolador, como os dados chegam à nuvem e que decisões são automáticas. Apresente o diagrama em camadas (dispositivo, rede, plataforma, aplicação).

## O que são IoT e sistemas ciberfísicos
### Teoria
A **Internet das Coisas (IoT)** é a rede de objetos físicos com sensores, processamento e conectividade, que trocam dados pela internet: lâmpadas, relógios, carros, máquinas industriais.

Um **sistema ciberfísico (CPS)** integra **computação** e **processos físicos** em ciclo fechado: sensores medem o mundo, o software decide e atuadores agem sobre ele. Exemplos: piloto automático, marcapasso, linha de produção robotizada, rede elétrica inteligente.

A IoT costuma fornecer a **conectividade** que liga sistemas ciberfísicos entre si e à nuvem. Juntos, formam a base da **Indústria 4.0**, das **cidades inteligentes** e da **agricultura de precisão**.

### Prática
Exemplo de ciclo ciberfísico num termostato inteligente:

```
1. Sensor mede 27 °C
2. Controlador compara com a meta (23 °C)
3. Decide ligar o ar-condicionado
4. Atuador (relé) liga o aparelho
5. Sensor mede de novo... (ciclo fechado)
6. Dados vão para a nuvem e aparecem no app
```

### Questão
O que caracteriza um sistema ciberfísico?
- [ ] Apenas um site na internet
- [x] A integração entre computação e processos físicos, com sensores e atuadores
- [ ] Um computador sem conexão
- [ ] Um jogo de realidade virtual
> O software interage continuamente com o mundo físico.

### Questão
Num termostato inteligente, qual componente liga o ar-condicionado?
- [ ] O sensor
- [x] O atuador
- [ ] A nuvem
- [ ] O app
> Sensores medem; atuadores agem.

### Questão
Qual termo descreve a aplicação de IoT e CPS na indústria?
- [ ] Web 1.0
- [x] Indústria 4.0
- [ ] Era do vapor
- [ ] Computação quântica
> Fábricas conectadas e automatizadas com dados em tempo real.

## Arquitetura em camadas
### Teoria
Uma solução IoT costuma ser organizada em camadas:

- **Percepção (dispositivos):** sensores, atuadores e microcontroladores.
- **Rede (conectividade):** Wi-Fi, Bluetooth, LoRaWAN, celular (4G/5G, NB-IoT).
- **Borda (edge):** gateways que filtram e processam dados perto da origem, reduzindo latência e tráfego.
- **Plataforma (nuvem):** armazenamento, processamento, regras e gestão dos dispositivos.
- **Aplicação:** painéis, apps e integrações para os usuários.

Processar na **borda** é importante quando a decisão precisa ser rápida (um freio) ou a conexão é instável.

### Prática
Monitoramento de uma estufa:

```
Percepção:   sensores de umidade do solo e temperatura + ESP32
Rede:        Wi-Fi até o roteador
Borda:       o próprio ESP32 liga a irrigação se o solo secar (sem esperar a nuvem)
Plataforma:  servidor MQTT + banco de séries temporais
Aplicação:   painel com gráficos e alertas no celular
```

### Questão
Qual camada contém sensores e microcontroladores?
- [x] Percepção
- [ ] Aplicação
- [ ] Plataforma
- [ ] Rede
> É a camada que toca o mundo físico.

### Questão
Por que processar dados na borda (edge)?
- [ ] Para gastar mais internet
- [x] Para decidir rápido, perto da origem, e reduzir dependência da nuvem
- [ ] Porque a nuvem não guarda dados
- [ ] Para desligar os sensores
> Decisões críticas não podem esperar a ida e volta à nuvem.

### Questão
Qual tecnologia de rede é indicada para longas distâncias com baixo consumo?
- [ ] Bluetooth
- [x] LoRaWAN
- [ ] USB
- [ ] HDMI
> LoRa alcança quilômetros com baterias que duram anos.

## Sensores, atuadores e microcontroladores
### Teoria
**Sensores** convertem grandezas físicas em sinais elétricos: temperatura (DHT22), distância (ultrassônico), luz (LDR), movimento (PIR), umidade do solo, acelerômetro.

**Atuadores** agem no ambiente: LEDs, relés, motores, servos, buzzers.

Sinais podem ser **digitais** (0 ou 1, como um botão) ou **analógicos** (faixa contínua, lidos por um conversor ADC).

**Microcontroladores** são pequenos computadores num chip, com pinos de entrada e saída (GPIO):
- **Arduino:** simples, ótimo para começar.
- **ESP32:** barato, com Wi-Fi e Bluetooth integrados.
- **Raspberry Pi:** na verdade um computador completo com Linux, útil como gateway.

### Prática
Lendo um sensor analógico de luz e acendendo um LED (Arduino):

```cpp
const int PINO_LDR = A0;
const int PINO_LED = 13;

void setup() {
  pinMode(PINO_LED, OUTPUT);
  Serial.begin(9600);
}

void loop() {
  int luz = analogRead(PINO_LDR);   // 0 a 1023
  Serial.println(luz);
  digitalWrite(PINO_LED, luz < 300 ? HIGH : LOW);  // escuro: acende
  delay(500);
}
```

### Questão
Qual a diferença entre sinal digital e analógico?
- [ ] Nenhuma
- [x] Digital tem dois estados (0/1); analógico varia numa faixa contínua
- [ ] Analógico só existe em rádios
- [ ] Digital é sempre sem fio
> Leituras analógicas passam por um conversor ADC.

### Questão
Qual placa tem Wi-Fi e Bluetooth integrados e é muito usada em IoT?
- [ ] Arduino Uno
- [x] ESP32
- [ ] Placa de vídeo
- [ ] HD externo
> O ESP32 conecta-se diretamente à rede sem módulos extras.

### Questão
Um relé que liga uma bomba d'água é um:
- [ ] Sensor
- [x] Atuador
- [ ] Protocolo
- [ ] Gateway
> Ele age sobre o ambiente.

# Conectividade e protocolos
> Como os dispositivos conversam: MQTT, HTTP, CoAP e redes sem fio.

## Projeto
**Estação meteorológica conectada:** com um ESP32 e um sensor DHT22 (ou um simulador como o Wokwi), publique temperatura e umidade a cada minuto via MQTT. Assine os tópicos num cliente (MQTT Explorer) e crie um alerta quando a temperatura passar de um limite.

## Redes sem fio para IoT
### Teoria
A escolha da rede depende de **alcance**, **consumo de energia**, **taxa de dados** e **custo**:

- **Wi-Fi:** alta taxa, alcance curto, consumo alto. Bom para dispositivos ligados na tomada.
- **Bluetooth Low Energy (BLE):** curto alcance, consumo muito baixo. Wearables, sensores pessoais.
- **Zigbee / Thread:** redes em **malha** (mesh) de baixo consumo; automação residencial (Matter usa Thread).
- **LoRaWAN:** quilômetros de alcance, pouquíssimos dados, bateria por anos. Agricultura, cidades.
- **Celular (NB-IoT, LTE-M, 5G):** cobertura ampla, gerenciada por operadoras.

Em redes **mesh**, cada dispositivo repassa mensagens dos vizinhos, ampliando o alcance.

### Prática
Escolhendo a rede certa:

```
Pulseira fitness            → BLE (bateria pequena, celular por perto)
Lâmpadas da casa            → Zigbee/Thread (mesh, baixo consumo)
Câmera de segurança         → Wi-Fi (vídeo exige alta taxa)
Sensor de umidade numa fazenda → LoRaWAN (longe, poucos dados)
Rastreador de caminhão      → NB-IoT/LTE-M (cobertura nacional)
```

### Questão
Qual rede é mais indicada para uma câmera que transmite vídeo?
- [ ] LoRaWAN
- [x] Wi-Fi
- [ ] Zigbee
- [ ] NB-IoT
> Vídeo exige alta taxa de dados.

### Questão
O que caracteriza uma rede mesh?
- [ ] Todos os dispositivos falam só com a nuvem
- [x] Os dispositivos repassam mensagens uns dos outros, ampliando o alcance
- [ ] Usa apenas cabos
- [ ] Só funciona com dois dispositivos
> Zigbee e Thread formam redes em malha.

### Questão
Qual é o principal ponto forte do BLE?
- [ ] Alcance de quilômetros
- [x] Consumo de energia muito baixo
- [ ] Transmitir vídeo em 4K
- [ ] Não precisar de antena
> Ideal para dispositivos com baterias pequenas.

## MQTT: publicar e assinar
### Teoria
**MQTT** é o protocolo mais popular da IoT: leve, feito para redes instáveis e dispositivos simples.

Ele usa o modelo **publish/subscribe**:
- Um **broker** (servidor, como o Mosquitto) recebe e distribui as mensagens.
- **Publicadores** enviam mensagens para **tópicos** (`iglu/sala/temperatura`).
- **Assinantes** recebem as mensagens dos tópicos que assinaram. Curingas: `+` (um nível) e `#` (vários níveis).

Níveis de **QoS**: 0 (no máximo uma vez), 1 (pelo menos uma vez) e 2 (exatamente uma vez). Mensagens **retidas** guardam o último valor para quem assinar depois.

### Prática
```python
# pip install paho-mqtt
import json, time
import paho.mqtt.client as mqtt

cliente = mqtt.Client()
cliente.connect("test.mosquitto.org", 1883)

for leitura in range(3):
    dados = {"temperatura": 21.5 + leitura, "umidade": 60}
    cliente.publish("technopenguin/iglu/sala", json.dumps(dados), qos=1)
    time.sleep(2)
```

Para ver as mensagens: `mosquitto_sub -h test.mosquitto.org -t "technopenguin/iglu/#"`.

### Questão
Qual o papel do broker no MQTT?
- [ ] Medir a temperatura
- [x] Receber as mensagens publicadas e entregá-las aos assinantes
- [ ] Criptografar o Wi-Fi
- [ ] Armazenar o firmware
> Publicadores e assinantes não se conhecem; o broker intermedia.

### Questão
Qual tópico com curinga recebe tudo abaixo de `iglu/`?
- [ ] iglu/+
- [x] iglu/#
- [ ] iglu/*
- [ ] iglu/all
> # cobre vários níveis; + cobre apenas um.

### Questão
Qual QoS garante entrega exatamente uma vez?
- [ ] 0
- [ ] 1
- [x] 2
- [ ] 3
> QoS 2 usa um handshake de quatro etapas.

## HTTP, CoAP e formatos de dados
### Teoria
Nem tudo é MQTT:
- **HTTP/REST:** universal e simples de integrar com APIs web, mas mais pesado. Bom para dispositivos com energia e boa conexão.
- **CoAP:** um "HTTP leve" sobre UDP, para dispositivos muito limitados.
- **WebSockets:** comunicação bidirecional em tempo real com navegadores.

Formatos de dados:
- **JSON:** legível e comum.
- **CBOR / Protocol Buffers:** binários e compactos, economizam bateria e banda.

Dados de sensores são **séries temporais** (valor + horário) e costumam ir para bancos especializados como InfluxDB ou TimescaleDB.

### Prática
Enviando uma leitura por HTTP a partir de um ESP32 (MicroPython):

```python
import urequests

leitura = {"dispositivo": "estufa-01", "umidade_solo": 37.2}
resposta = urequests.post(
    "https://api.exemplo.com/leituras",
    json=leitura,
    headers={"Authorization": "Bearer TOKEN_DO_DISPOSITIVO"},
)
print(resposta.status_code)
resposta.close()
```

### Questão
Qual protocolo é um "HTTP leve" sobre UDP para dispositivos limitados?
- [ ] FTP
- [x] CoAP
- [ ] SMTP
- [ ] SSH
> CoAP mantém o estilo REST com muito menos sobrecarga.

### Questão
Por que usar formatos binários como CBOR em IoT?
- [ ] São mais fáceis de ler
- [x] São mais compactos, economizando energia e banda
- [ ] São obrigatórios no Wi-Fi
- [ ] Não precisam de rede
> Menos bytes transmitidos significam menos bateria gasta.

### Questão
Que tipo de banco é indicado para leituras de sensores ao longo do tempo?
- [ ] Banco de grafos
- [x] Banco de séries temporais
- [ ] Planilha impressa
- [ ] Banco de imagens
> Séries temporais são otimizadas para dados com carimbo de tempo.

# IoT na prática: dados, segurança e futuro
> Plataformas, gêmeos digitais, segurança e ética.

## Projeto
**Painel de monitoramento seguro:** receba os dados da estação meteorológica num servidor, guarde num banco, mostre gráficos num painel (Grafana ou uma página com Chart.js) e aplique segurança: TLS no MQTT, usuário/senha por dispositivo e atualização de firmware documentada.

## Plataformas e gêmeos digitais
### Teoria
**Plataformas IoT** gerenciam dispositivos em escala: cadastro, autenticação, recepção de dados, regras, atualizações remotas (**OTA**) e painéis. Exemplos: AWS IoT Core, Azure IoT Hub, ThingsBoard, Node-RED (para fluxos visuais).

Um **gêmeo digital** (digital twin) é uma réplica virtual de um objeto ou processo físico, alimentada por dados reais. Com ele dá para monitorar, simular cenários e prever falhas (**manutenção preditiva**) sem mexer na máquina real.

Exemplos: turbinas eólicas, fábricas inteiras, prédios e até cidades.

### Prática
Regra simples num fluxo do Node-RED (pseudoconfiguração):

```
[MQTT in: fabrica/motor1/vibracao]
   → [function: se vibracao > 7.5 mm/s, marca alerta]
   → [switch: alerta?]
        → sim: [email para manutenção] + [dashboard: luz vermelha]
        → não: [banco de séries temporais]
```

Com o histórico, um modelo pode prever quando o motor vai falhar.

### Questão
O que é um gêmeo digital?
- [ ] Um backup do banco
- [x] Uma réplica virtual de algo físico, alimentada por dados reais
- [ ] Um segundo microcontrolador
- [ ] Um tipo de sensor
> Permite monitorar e simular sem interferir no objeto real.

### Questão
O que significa atualização OTA?
- [ ] Atualização feita com cabo
- [x] Atualização de firmware enviada remotamente pela rede
- [ ] Troca do hardware
- [ ] Reinício manual
> Over-The-Air: essencial para corrigir falhas em dispositivos espalhados.

### Questão
O que é manutenção preditiva?
- [ ] Consertar só quando quebrar
- [x] Usar dados para prever falhas e agir antes que aconteçam
- [ ] Trocar peças todo dia
- [ ] Desligar máquinas aos finais de semana
> Sensores e modelos indicam o momento certo da manutenção.

## Segurança em IoT
### Teoria
Dispositivos IoT são alvos frequentes: muitos têm **senhas padrão**, firmware desatualizado e ficam expostos na internet. A botnet **Mirai** (2016) usou milhares de câmeras e roteadores invadidos para derrubar grandes sites.

Boas práticas:
- Nada de **senha padrão**: credenciais únicas por dispositivo.
- **Criptografia** em trânsito (TLS no MQTT/HTTP) e, quando possível, em repouso.
- **Atualizações** de firmware assinadas e frequentes.
- **Segmentação de rede:** dispositivos IoT numa rede separada.
- **Mínimo privilégio:** cada dispositivo só publica/assina seus próprios tópicos.
- Desativar serviços e portas que não são usados.

### Prática
Checklist antes de instalar uma câmera IP em casa:

```
[ ] Trocar a senha padrão por uma senha forte e única
[ ] Atualizar o firmware
[ ] Desativar acesso remoto/UPnP se não for necessário
[ ] Colocar na rede de convidados ou numa VLAN separada
[ ] Conferir se o app usa conexão criptografada
```

### Questão
O que a botnet Mirai explorou?
- [ ] Falhas em supercomputadores
- [x] Dispositivos IoT com senhas padrão
- [ ] Bancos de dados SQL
- [ ] Satélites
> Ela testava listas de senhas de fábrica em câmeras e roteadores.

### Questão
Por que colocar dispositivos IoT numa rede separada?
- [ ] Para deixar o Wi-Fi mais rápido
- [x] Para que um dispositivo invadido não alcance facilmente os computadores e celulares
- [ ] Porque eles não funcionam na rede principal
- [ ] Para economizar energia
> A segmentação limita o movimento de um atacante.

### Questão
Qual medida protege os dados enviados pelos sensores?
- [ ] Usar senha "admin"
- [x] Criptografia em trânsito com TLS
- [ ] Desligar o firewall
- [ ] Publicar em tópicos públicos
> TLS impede que terceiros leiam ou alterem as mensagens.

## Privacidade, ética e o futuro
### Teoria
Sensores coletam dados muito pessoais: localização, rotina, voz, saúde. Por isso é preciso:
- **Coletar só o necessário** (minimização) e informar com clareza.
- Respeitar leis como a **LGPD** (Brasil) e a GDPR (Europa).
- Pensar em **ética**: quem tem acesso aos dados? Por quanto tempo ficam guardados?

Tendências: **5G** e redes privadas industriais, **TinyML** (IA rodando em microcontroladores), **Matter** (padrão unificado para casa inteligente), cidades inteligentes e **sustentabilidade**: monitorar energia, água e emissões.

Sistemas ciberfísicos críticos (carros autônomos, saúde) exigem também **segurança funcional**: falhas podem causar danos físicos.

### Prática
Avalie uma assistente de voz com perguntas de privacidade:

```
Que dados ela coleta? (áudio, horários, comandos)
Onde são processados? (no aparelho ou na nuvem)
Por quanto tempo ficam guardados? Posso apagar?
Quem mais acessa? (empresa, parceiros)
Existe botão físico para desligar o microfone?
```

### Questão
O que é o princípio da minimização de dados?
- [ ] Guardar tudo para sempre
- [x] Coletar apenas os dados necessários para a finalidade
- [ ] Apagar o banco todo dia
- [ ] Comprimir os arquivos
> Menos dados coletados, menos riscos de exposição.

### Questão
Qual lei brasileira regula o tratamento de dados pessoais?
- [ ] Marco Civil das Telecomunicações
- [x] LGPD
- [ ] CLT
- [ ] Lei de Moore
> Lei Geral de Proteção de Dados (Lei 13.709/2018).

### Questão
O que é TinyML?
- [ ] Um protocolo de rede
- [x] Aprendizado de máquina rodando em microcontroladores de baixo consumo
- [ ] Um banco de dados
- [ ] Uma linguagem de marcação
> Permite reconhecer padrões direto no dispositivo, sem nuvem.
