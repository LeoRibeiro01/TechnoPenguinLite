# Fundamentos de sistemas operacionais
> O papel do SO, kernel, chamadas de sistema e linha de comando.

## Projeto
**Explorador do sistema:** escreva um relatório comparando Windows e Linux (pode usar uma máquina virtual ou o WSL): estrutura de pastas, gerenciador de pacotes, permissões, processos em execução e consumo de memória, com prints dos comandos usados em cada um.

## O que faz um sistema operacional
### Teoria
O **sistema operacional (SO)** é o software que gerencia o hardware e oferece serviços aos programas. Sem ele, cada aplicativo teria de saber controlar diretamente o disco, a memória e a placa de rede.

Funções principais:
- Gerenciar **processos** (programas em execução) e a **CPU**.
- Gerenciar a **memória**.
- Gerenciar **arquivos** e armazenamento.
- Controlar **dispositivos de entrada e saída**.
- Garantir **segurança** e isolamento entre usuários e programas.
- Oferecer uma **interface** (gráfica ou de linha de comando).

Exemplos: Windows, Linux (Ubuntu, Fedora), macOS, Android (baseado em Linux), iOS.

### Prática
Veja o SO trabalhando agora:

```
Windows: Ctrl + Shift + Esc abre o Gerenciador de Tarefas
         (aba Processos: CPU, memória, disco e rede por programa)
Linux:   top   ou   htop
macOS:   Monitor de Atividade
```

Abra e feche um programa observando a memória: o SO aloca e libera recursos o tempo todo.

### Questão
Qual a função principal de um sistema operacional?
- [ ] Editar textos
- [x] Gerenciar o hardware e oferecer serviços aos programas
- [ ] Navegar na internet
- [ ] Criar planilhas
> Ele é o intermediário entre aplicativos e hardware.

### Questão
O Android é baseado em qual kernel?
- [ ] Windows NT
- [x] Linux
- [ ] macOS
- [ ] DOS
> O Android usa o kernel Linux com camadas próprias.

### Questão
Qual destes NÃO é uma função típica do SO?
- [ ] Gerenciar memória
- [ ] Gerenciar processos
- [x] Escrever trabalhos escolares
- [ ] Controlar dispositivos
> Isso é tarefa de aplicativos, como editores de texto.

## Kernel e chamadas de sistema
### Teoria
O **kernel** (núcleo) é a parte central do SO, que roda com privilégios totais sobre o hardware.

O processador tem dois modos:
- **Modo kernel:** acesso total ao hardware.
- **Modo usuário:** onde rodam os aplicativos, com acesso restrito.

Quando um programa precisa de algo protegido (abrir um arquivo, enviar dados pela rede), faz uma **chamada de sistema (system call)** e o kernel executa por ele. Isso protege o sistema de programas com erros ou maliciosos.

Arquiteturas de kernel:
- **Monolítico:** tudo no kernel (Linux): rápido.
- **Microkernel:** só o essencial no kernel, o resto em processos separados: mais isolado.
- **Híbrido:** mistura (Windows NT, macOS XNU).

### Prática
Quando você roda este programa, várias chamadas de sistema acontecem por baixo:

```python
with open("notas.txt", "w") as arquivo:   # open() → syscall open/CreateFile
    arquivo.write("Teco: 10\n")          # write() → syscall write/WriteFile
                                          # ao sair → syscall close
```

No Linux, `strace python3 programa.py` mostra todas as chamadas de sistema feitas.

### Questão
O que é uma chamada de sistema?
- [ ] Uma ligação telefônica
- [x] Um pedido de um programa para o kernel executar uma operação protegida
- [ ] Uma função de matemática
- [ ] Um erro do sistema
> É a porta de entrada controlada para os serviços do kernel.

### Questão
Por que aplicativos rodam em modo usuário?
- [ ] Para serem mais rápidos
- [x] Para que erros ou programas maliciosos não comprometam todo o sistema
- [ ] Porque não precisam de memória
- [ ] Por causa da interface gráfica
> O acesso direto ao hardware fica restrito ao kernel.

### Questão
O Linux usa qual tipo de kernel?
- [x] Monolítico
- [ ] Microkernel puro
- [ ] Sem kernel
- [ ] Exokernel
> Drivers e serviços principais rodam dentro do kernel (com módulos carregáveis).

## A linha de comando
### Teoria
O **shell** é o programa que interpreta comandos de texto. No Windows: **PowerShell** e Prompt de Comando; no Linux/macOS: **Bash** ou **Zsh**.

Por que aprender? É mais rápido para muitas tarefas, permite **automatizar** com scripts e é essencial em servidores, que muitas vezes nem têm interface gráfica.

Conceitos: **diretório atual**, **caminhos** absolutos (`/home/teco`, `C:\Users\Teco`) e relativos (`../docs`), **redirecionamento** (`>` para arquivo) e **pipes** (`|`), que ligam a saída de um comando à entrada de outro.

### Prática
Comandos equivalentes:

```
Ação                 Linux/macOS          Windows (PowerShell)
Onde estou           pwd                  Get-Location (pwd)
Listar arquivos      ls -l                Get-ChildItem (ls)
Mudar de pasta       cd pasta             cd pasta
Criar pasta          mkdir projetos       mkdir projetos
Ver arquivo          cat notas.txt        Get-Content notas.txt
Procurar texto       grep gelo *.txt      Select-String gelo *.txt
Pipe                 ps aux | grep php    Get-Process | Where-Object Name -like "php*"
```

### Questão
O que faz o pipe `|` na linha de comando?
- [ ] Apaga arquivos
- [x] Envia a saída de um comando como entrada para outro
- [ ] Cria uma pasta
- [ ] Encerra o terminal
> Pipes permitem combinar comandos simples em tarefas complexas.

### Questão
Qual comando mostra o diretório atual no Linux?
- [ ] cd
- [x] pwd
- [ ] ls
- [ ] dir /p
> print working directory.

### Questão
Por que a linha de comando é essencial em servidores?
- [ ] Porque é mais colorida
- [x] Porque muitos servidores não têm interface gráfica e tarefas são automatizadas por scripts
- [ ] Porque é a única forma de navegar na web
- [ ] Porque substitui o kernel
> Administração remota e automação dependem do shell.

# Processos e memória
> Processos, threads, escalonamento, concorrência e memória virtual.

## Projeto
**Simulador de escalonamento:** escreva um programa (em qualquer linguagem) que recebe uma lista de processos com tempo de chegada e duração, simula os algoritmos FCFS, SJF e Round Robin e calcula o tempo médio de espera de cada um. Compare os resultados num gráfico.

## Processos e threads
### Teoria
Um **processo** é um programa em execução, com seu próprio espaço de memória, arquivos abertos e estado. O SO guarda as informações de cada processo num **PCB** (bloco de controle de processo) e o identifica por um **PID**.

Estados de um processo: **novo → pronto → executando → bloqueado (esperando E/S) → pronto ... → terminado**.

Uma **thread** é uma linha de execução **dentro** de um processo. Threads do mesmo processo **compartilham a memória**, o que torna a comunicação fácil e rápida, mas exige cuidado com acessos simultâneos.

Trocar a CPU de um processo para outro é a **troca de contexto**, que tem um custo.

### Prática
```
Linux:
  ps aux                  lista os processos com PID, CPU e memória
  kill 1234               pede para o processo 1234 terminar
  top -H -p 1234          mostra as threads do processo

Windows (PowerShell):
  Get-Process | Sort-Object CPU -Descending | Select-Object -First 5
  Stop-Process -Id 1234
```

Um navegador moderno usa vários processos (um por aba ou site) para que uma aba travada não derrube as outras.

### Questão
O que diferencia threads de processos?
- [ ] Threads não executam código
- [x] Threads do mesmo processo compartilham a memória; processos têm memória separada
- [ ] Processos são sempre mais leves
- [ ] Não há diferença
> Por isso threads se comunicam facilmente, mas precisam de sincronização.

### Questão
Em qual estado fica um processo que espera a leitura do disco?
- [ ] Executando
- [ ] Pronto
- [x] Bloqueado (esperando)
- [ ] Terminado
> Ele só volta a "pronto" quando a E/S termina.

### Questão
O que é o PID?
- [ ] Um tipo de memória
- [x] O número que identifica um processo
- [ ] Um protocolo de rede
- [ ] Um arquivo de configuração
> Process IDentifier.

## Escalonamento e concorrência
### Teoria
Com mais processos prontos do que núcleos de CPU, o **escalonador** decide quem executa. Algoritmos clássicos:

- **FCFS** (primeiro a chegar, primeiro a ser servido): simples, mas um processo longo atrasa todos.
- **SJF** (menor tarefa primeiro): ótimo tempo médio de espera, mas exige saber a duração.
- **Round Robin:** cada processo recebe uma fatia de tempo (**quantum**) em revezamento: justo e responsivo.
- **Prioridades:** processos importantes primeiro (cuidado com a **inanição** dos de baixa prioridade).

Na **concorrência**, threads acessando os mesmos dados podem gerar **condições de corrida**. A solução é a **exclusão mútua** (mutex, semáforos). Mas cuidado com o **deadlock**: dois processos esperando um pelo outro para sempre.

### Prática
Três processos chegam juntos: A (8 ms), B (4 ms), C (2 ms).

```
FCFS (A, B, C):  esperas 0, 8, 12  → média 6,67 ms
SJF  (C, B, A):  esperas 0, 2, 6   → média 2,67 ms
Round Robin (quantum 2): A B C A B A A A
```

O SJF minimiza a espera média, mas o Round Robin evita que alguém espere demais para começar.

### Questão
Qual algoritmo usa fatias de tempo em revezamento?
- [ ] FCFS
- [ ] SJF
- [x] Round Robin
- [ ] FIFO estrito
> Cada processo usa a CPU por um quantum e volta para a fila.

### Questão
O que é um deadlock?
- [ ] Um processo muito rápido
- [x] Processos bloqueados para sempre, cada um esperando um recurso que o outro segura
- [ ] Um tipo de memória
- [ ] O desligamento do computador
> Exemplo clássico: A segura X e espera Y; B segura Y e espera X.

### Questão
Como evitar condições de corrida em dados compartilhados?
- [ ] Usando mais threads
- [x] Com exclusão mútua (mutex, semáforos)
- [ ] Desligando o escalonador
- [ ] Aumentando a memória
> Só uma thread por vez acessa a região crítica.

## Memória virtual
### Teoria
Cada processo enxerga um **espaço de endereços virtual** próprio, como se tivesse a memória toda para si. O SO, com ajuda do hardware (**MMU**), traduz endereços virtuais em físicos.

A memória é dividida em **páginas** (geralmente 4 KB). Páginas pouco usadas podem ir para o disco (**swap** ou **arquivo de paginação**), liberando RAM. Quando o programa acessa uma página que não está na RAM, ocorre uma **falta de página** e o SO a carrega.

Vantagens: **isolamento** entre processos (um não lê a memória do outro), uso de programas maiores que a RAM e compartilhamento eficiente de bibliotecas.

Se a RAM falta demais, o sistema passa mais tempo trocando páginas do que trabalhando: é o **thrashing**, quando o computador fica muito lento.

### Prática
```
Windows: Gerenciador de Tarefas → Desempenho → Memória
         ("Confirmada" inclui o arquivo de paginação)
Linux:   free -h          memória e swap em uso
         vmstat 1         acompanha trocas de página a cada segundo
```

Abra muitas abas no navegador e observe a memória usada e o swap crescerem.

### Questão
O que é memória virtual?
- [ ] Memória na nuvem
- [x] Uma abstração em que cada processo tem seu espaço de endereços, traduzido pelo SO para a RAM física
- [ ] Um pendrive
- [ ] A memória da placa de vídeo
> A tradução é feita pela MMU com tabelas de páginas.

### Questão
O que acontece numa falta de página?
- [ ] O computador desliga
- [x] O SO carrega a página que estava no disco para a RAM
- [ ] O programa é apagado
- [ ] A página é perdida
> É um evento normal; muitas faltas seguidas indicam falta de RAM.

### Questão
Qual o principal benefício de segurança da memória virtual?
- [ ] Criptografar arquivos
- [x] Isolar a memória de cada processo dos demais
- [ ] Bloquear vírus de email
- [ ] Acelerar a internet
> Um processo não pode ler diretamente a memória de outro.

# Arquivos, segurança e virtualização
> Sistemas de arquivos, permissões, virtualização e contêineres.

## Projeto
**Servidor Linux na sua máquina:** instale o Ubuntu numa máquina virtual (VirtualBox) ou no WSL, crie usuários e grupos, configure permissões numa pasta compartilhada, instale o Apache com o gerenciador de pacotes e publique uma página. Depois, rode a mesma página num contêiner Docker e compare.

## Sistemas de arquivos
### Teoria
O **sistema de arquivos** organiza como os dados são guardados no disco: nomes, pastas, permissões, datas e onde ficam os blocos de cada arquivo.

Exemplos: **NTFS** (Windows), **ext4** e **Btrfs** (Linux), **APFS** (macOS), **FAT32/exFAT** (pendrives, compatíveis com tudo).

No Linux, tudo parte da raiz `/` e "tudo é arquivo", inclusive dispositivos. Pastas importantes: `/home` (usuários), `/etc` (configurações), `/var` (logs e dados variáveis), `/usr` (programas).

Recursos modernos: **journaling** (registra operações para evitar corrupção em quedas de energia), snapshots, compressão e criptografia.

### Prática
```
Linux:
  df -h            espaço livre em cada sistema de arquivos
  lsblk -f         discos, partições e tipo de sistema de arquivos
  ls -la /etc      arquivos de configuração (com ocultos)

Windows (PowerShell):
  Get-Volume       volumes, tipo (NTFS) e espaço livre
```

### Questão
Qual sistema de arquivos é o padrão do Windows?
- [ ] ext4
- [x] NTFS
- [ ] APFS
- [ ] Btrfs
> NTFS suporta permissões, journaling e arquivos grandes.

### Questão
Para que serve o journaling?
- [ ] Escrever um diário do usuário
- [x] Registrar operações para manter a consistência do disco após falhas
- [ ] Acelerar a internet
- [ ] Compactar fotos
> Após uma queda de energia, o sistema se recupera usando o registro.

### Questão
No Linux, onde costumam ficar os arquivos de configuração do sistema?
- [ ] /home
- [x] /etc
- [ ] /tmp
- [ ] /dev
> /etc concentra configurações de serviços e do sistema.

## Usuários e permissões
### Teoria
SOs multiusuário controlam **quem pode fazer o quê**. No Linux, cada arquivo tem um **dono**, um **grupo** e permissões para três classes (dono, grupo, outros):

- **r** (read, 4), **w** (write, 2), **x** (execute, 1).
- `rwxr-x---` = dono lê/escreve/executa, grupo lê/executa, outros nada = **750**.

O superusuário **root** pode tudo; use `sudo` para executar comandos administrativos só quando necessário (**princípio do menor privilégio**).

No Windows, as permissões são **ACLs** (listas de controle de acesso) e o **UAC** pede confirmação para ações administrativas.

### Prática
```
ls -l relatorio.txt
-rw-r--r-- 1 teco alunos 120 out 30 relatorio.txt

chmod 640 relatorio.txt      dono rw-, grupo r--, outros ---
chown teco:professores relatorio.txt
sudo adduser pipo            cria usuário (precisa de privilégio)
chmod +x script.sh           torna um script executável
```

### Questão
O que significa a permissão 755 num arquivo Linux?
- [ ] Todos podem tudo
- [x] Dono rwx; grupo e outros r-x
- [ ] Só o dono lê
- [ ] Ninguém executa
> 7 = 4+2+1 (rwx); 5 = 4+1 (r-x).

### Questão
Para que serve o `sudo`?
- [ ] Desligar o computador
- [x] Executar um comando com privilégios administrativos
- [ ] Criar arquivos ocultos
- [ ] Listar processos
> Permite usar privilégios elevados só quando necessário.

### Questão
O que diz o princípio do menor privilégio?
- [ ] Todos devem ser administradores
- [x] Cada usuário ou programa deve ter apenas as permissões necessárias
- [ ] Senhas são desnecessárias
- [ ] Root deve ser usado sempre
> Assim, um erro ou invasão causa menos estrago.

## Virtualização e contêineres
### Teoria
**Virtualização** permite rodar vários sistemas operacionais completos numa mesma máquina física. O **hipervisor** cria **máquinas virtuais (VMs)** com hardware virtual próprio:
- Tipo 1 (bare-metal): roda direto no hardware (VMware ESXi, Hyper-V, KVM). Usado em data centers e na nuvem.
- Tipo 2: roda sobre um SO (VirtualBox, VMware Workstation).

**Contêineres** (Docker) são mais leves: compartilham o **kernel do host** e isolam apenas processos, arquivos e rede, usando recursos do kernel Linux (namespaces e cgroups). Iniciam em segundos e empacotam a aplicação com suas dependências: "funciona na minha máquina" passa a funcionar em qualquer lugar.

O **WSL** permite rodar Linux dentro do Windows.

### Prática
```
docker run -d -p 8080:80 --name meu-site nginx
docker ps                   lista os contêineres em execução
docker exec -it meu-site sh entra no contêiner
docker stop meu-site
```

Abra `http://localhost:8080`: um servidor web completo rodando isolado, sem instalar nada no seu sistema além do Docker.

### Questão
Qual a principal diferença entre VMs e contêineres?
- [ ] Contêineres têm um SO completo cada
- [x] VMs têm kernel e SO próprios; contêineres compartilham o kernel do host
- [ ] VMs são sempre mais leves
- [ ] Não há diferença
> Por isso contêineres iniciam mais rápido e usam menos recursos.

### Questão
O que é um hipervisor?
- [ ] Um antivírus
- [x] O software que cria e gerencia máquinas virtuais
- [ ] Um navegador
- [ ] Um tipo de contêiner
> Ele distribui o hardware real entre as VMs.

### Questão
Qual ferramenta é a mais conhecida para contêineres?
- [ ] VirtualBox
- [x] Docker
- [ ] Excel
- [ ] Wireshark
> Docker popularizou o empacotamento de aplicações em contêineres.
