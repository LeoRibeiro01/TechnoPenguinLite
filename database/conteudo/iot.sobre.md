@historia
A ideia de objetos conectados é antiga: em 1982, estudantes da Carnegie Mellon ligaram uma máquina de refrigerante à rede para saber, à distância, se havia latas geladas. O termo **"Internet das Coisas"** foi cunhado em **1999** por **Kevin Ashton**, que trabalhava com etiquetas RFID na cadeia de suprimentos da Procter & Gamble.

O conceito de **sistemas ciberfísicos** (CPS) foi formalizado por volta de 2006 pela National Science Foundation dos EUA, para descrever sistemas em que computação e processos físicos se controlam mutuamente. A popularização veio com hardware barato: o **Arduino** (2005, Itália), o **Raspberry Pi** (2012, Reino Unido) e o **ESP8266/ESP32** (2014/2016, China), além de protocolos leves como o **MQTT**, criado em 1999 pela IBM para monitorar oleodutos via satélite.

@hoje
Há dezenas de bilhões de dispositivos conectados no mundo. A IoT está em casas inteligentes (assistentes de voz, lâmpadas, fechaduras com o padrão Matter), **agricultura de precisão** (forte no Brasil), **Indústria 4.0**, cidades inteligentes (iluminação, trânsito, coleta de lixo), saúde (monitores cardíacos, bombas de insulina), logística e veículos conectados. Os sistemas ciberfísicos estão em carros autônomos, aviões, redes elétricas e robôs industriais.

@iniciante
- Entender o que são IoT e sistemas ciberfísicos
- Conhecer a arquitetura em camadas, da percepção à aplicação
- Diferenciar sensores, atuadores e microcontroladores
- Ler sensores e controlar atuadores num Arduino ou ESP32

@intermediario
- Escolher a rede certa: Wi-Fi, BLE, Zigbee, LoRaWAN ou celular
- Publicar e assinar dados com MQTT
- Usar HTTP, CoAP e formatos como JSON e CBOR
- Guardar leituras em bancos de séries temporais

@avancado
- Usar plataformas IoT, atualização OTA e gêmeos digitais
- Proteger dispositivos e redes IoT
- Respeitar privacidade e a LGPD nos dados coletados
- Conhecer tendências como TinyML, 5G e Matter

@referencias
**Livros**
- *Internet of Things: A Hands-On Approach* — Arshdeep Bahga e Vijay Madisetti: conceitos e projetos práticos.
- *Getting Started with Arduino* — Massimo Banzi: escrito por um dos criadores do Arduino.
- *Introduction to Embedded Systems: A Cyber-Physical Systems Approach* — Edward Lee e Sanjit Seshia ([gratuito online](https://ptolemy.berkeley.edu/books/leeseshia/)).

**Na internet**
- [Wokwi](https://wokwi.com) — simulador de Arduino e ESP32 no navegador.
- [Documentação do ESP-IDF e Arduino-ESP32](https://docs.espressif.com)
- [MQTT.org](https://mqtt.org) — especificação e ferramentas.

@modulo 1
**Um sistema ciberfísico é um laço de controle.** Lee e Seshia mostram que o desafio não é só programar, mas lidar com o **tempo** e o mundo físico: sensores têm ruído, atuadores têm atraso e as decisões precisam acontecer dentro de prazos. Um controlador simples liga e desliga (termostato com histerese, para não ficar ligando e desligando sem parar); controladores mais sofisticados, como o **PID**, ajustam a força da ação conforme o erro, sua soma e sua variação.

Leituras analógicas passam por um **ADC** com resolução limitada (10 bits no Arduino Uno: valores de 0 a 1023). Filtros simples, como a média móvel, reduzem o ruído. Microcontroladores têm pouca memória e devem economizar energia: muitos passam a maior parte do tempo em *deep sleep*, acordando só para medir e transmitir.

@fontes 1
- Lee e Seshia, *Introduction to Embedded Systems*, capítulos 1 e 2 (modelagem de sistemas contínuos)
- Banzi, *Getting Started with Arduino*, capítulos 4 e 5
- [Arduino Docs: Analog Input](https://docs.arduino.cc/learn/microcontrollers/analog-input/)

@modulo 2
**Na IoT, energia e banda são o recurso mais caro.** Cada byte transmitido gasta bateria, por isso protocolos IoT são enxutos. O MQTT tem cabeçalho mínimo de 2 bytes, mantém a conexão aberta e permite mensagens retidas e o **último desejo** (*last will*): o broker avisa os assinantes quando um dispositivo cai sem se despedir.

Em redes de longo alcance e baixa potência (LPWAN), como **LoRaWAN**, um dispositivo envia poucas mensagens por hora com dezenas de bytes, e a bateria dura anos. Isso muda a forma de programar: em vez de mandar tudo, o dispositivo processa localmente e envia só eventos e resumos.

Bahga e Madisetti propõem pensar a IoT em níveis de implantação, de um único nó com processamento local até sistemas distribuídos com análise na nuvem.

@fontes 2
- Bahga e Madisetti, *Internet of Things: A Hands-On Approach*, capítulos 1 e 4
- [HiveMQ: MQTT Essentials](https://www.hivemq.com/mqtt/mqtt-essentials/)
- [The Things Network: LoRaWAN](https://www.thethingsnetwork.org/docs/lorawan/)

@modulo 3
**Segurança em IoT começa na fábrica.** A lei californiana SB-327 (2020) e o regulamento europeu Cyber Resilience Act proíbem senhas padrão iguais em todos os dispositivos, e o NIST publicou orientações básicas (NISTIR 8259) para fabricantes: identidade única por dispositivo, atualização segura de firmware, proteção de dados e mecanismos para relatar vulnerabilidades. A lista **OWASP IoT Top 10** resume os principais riscos.

Gêmeos digitais e manutenção preditiva dependem de dados de qualidade: sensores calibrados, carimbo de tempo confiável e contexto (onde e em que máquina a leitura foi feita). Já a ética pede minimização: coletar só o necessário e, sempre que possível, processar na borda, sem enviar dados pessoais à nuvem.

@fontes 3
- [OWASP Internet of Things Top 10](https://owasp.org/www-project-internet-of-things/)
- NIST, *IoT Device Cybersecurity Capability Core Baseline* (NISTIR 8259A)
- Lei Geral de Proteção de Dados (Lei 13.709/2018), artigos 6º e 46
