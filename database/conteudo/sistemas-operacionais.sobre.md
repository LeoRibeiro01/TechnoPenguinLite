@historia
Os primeiros computadores não tinham sistema operacional: o programador reservava horário e operava a máquina sozinho. Nos anos 1950 surgiram os **monitores de lote**, que executavam uma fila de trabalhos. Nos anos 1960 veio o **tempo compartilhado**: vários usuários usando o mesmo computador ao mesmo tempo, como no CTSS do MIT e no ambicioso **Multics**.

Em 1969, **Ken Thompson** e **Dennis Ritchie** criaram o **Unix** nos Laboratórios Bell, simples e portável. Nos anos 1980 vieram o MS-DOS, o Macintosh e o Windows. Em 1991, o estudante finlandês **Linus Torvalds** anunciou o **Linux**, um kernel livre que, unido às ferramentas GNU, formou um sistema completo. O macOS herdou o Unix pelo NeXTSTEP, e o Android (2008) e o iOS (2007) levaram esses sistemas para o bolso.

@hoje
O Linux roda a imensa maioria dos servidores da internet, todos os 500 supercomputadores mais rápidos do mundo, roteadores, TVs e o Android, o sistema mais usado do planeta. O Windows domina os computadores pessoais e o macOS é forte entre criativos e desenvolvedores. Para quem programa, entender processos, memória, arquivos e permissões ajuda a depurar falhas, escrever código eficiente, trabalhar com servidores, contêineres e nuvem e passar em entrevistas técnicas.

@iniciante
- Entender as funções de um sistema operacional
- Conhecer o kernel, os modos usuário e kernel e as chamadas de sistema
- Usar a linha de comando no Linux e no Windows
- Combinar comandos com pipes e redirecionamento

@intermediario
- Entender processos, threads e seus estados
- Conhecer algoritmos de escalonamento e a troca de contexto
- Evitar condições de corrida e deadlocks com exclusão mútua
- Entender memória virtual, paginação e swap

@avancado
- Conhecer sistemas de arquivos e journaling
- Gerenciar usuários e permissões no Linux
- Diferenciar máquinas virtuais e contêineres
- Usar Docker e o WSL

@referencias
**Livros**
- *Sistemas Operacionais Modernos* — Andrew Tanenbaum e Herbert Bos: a referência mais usada nos cursos brasileiros.
- *Fundamentos de Sistemas Operacionais* — Silberschatz, Galvin e Gagne: o "livro do dinossauro".
- *Operating Systems: Three Easy Pieces* — Remzi e Andrea Arpaci-Dusseau: excelente e [gratuito online](https://pages.cs.wisc.edu/~remzi/OSTEP/).
- *The Linux Command Line* — William Shotts ([gratuito online](https://linuxcommand.org/tlcl.php)).

**Na internet**
- [Linux Journey](https://linuxjourney.com) — aprenda Linux passo a passo.
- [Documentação do WSL (Microsoft Learn)](https://learn.microsoft.com/pt-br/windows/wsl/)
- [Docker — Get started](https://docs.docker.com/get-started/)

@modulo 1
**O sistema operacional é uma ilusão bem feita.** O livro *Operating Systems: Three Easy Pieces* organiza o assunto em três ideias: **virtualização** (cada programa acha que tem a CPU e a memória só para ele), **concorrência** (várias coisas acontecendo ao mesmo tempo com segurança) e **persistência** (dados que sobrevivem ao desligamento).

As chamadas de sistema são a "API" do kernel. No Linux, o `fork()` cria uma cópia do processo e o `exec()` substitui o programa em execução; é assim que o shell roda cada comando que você digita. O Windows usa `CreateProcess`, que faz as duas coisas de uma vez.

A filosofia Unix, descrita por Doug McIlroy, é: escreva programas que façam uma coisa bem, que trabalhem juntos e que usem texto como interface universal. Os pipes são a expressão perfeita dessa ideia.

@fontes 1
- Arpaci-Dusseau, *Operating Systems: Three Easy Pieces*, capítulos 2, 4 e 5 (processos e a API de processos)
- Tanenbaum e Bos, *Sistemas Operacionais Modernos*, capítulo 1
- Shotts, *The Linux Command Line*, partes 1 e 2

@modulo 2
**O escalonador equilibra justiça, resposta e eficiência.** O Linux usa há anos o **CFS** (e, desde 2023, o EEVDF), que tenta dar a cada processo uma fatia justa da CPU. Sistemas reais combinam ideias: prioridades, filas com vários níveis (*MLFQ*, explicado em detalhe no OSTEP) e preferência por processos interativos, para que a interface responda rápido.

Para concorrência, as quatro **condições de Coffman** mostram quando um deadlock pode ocorrer: exclusão mútua, posse e espera, ausência de preempção e espera circular. Quebrar uma delas, por exemplo sempre adquirindo as travas na mesma ordem, evita o problema.

Na memória virtual, o **TLB** guarda traduções recentes de endereço; programas que acessam a memória de forma sequencial aproveitam melhor o TLB e o cache, e por isso costumam ser muito mais rápidos.

@fontes 2
- Arpaci-Dusseau, *Operating Systems: Three Easy Pieces*, capítulos 7 a 9 (escalonamento), 18 a 22 (paginação e TLB) e 32 (deadlock)
- Silberschatz, Galvin e Gagne, *Fundamentos de Sistemas Operacionais*, capítulos 5 a 9
- Tanenbaum e Bos, *Sistemas Operacionais Modernos*, capítulo 6 (impasses)

@modulo 3
**Contêineres são processos com óculos especiais.** No Linux, um contêiner é um processo comum isolado por **namespaces** (ele só enxerga seus próprios processos, rede, usuários e sistema de arquivos) e limitado por **cgroups** (quanto de CPU e memória pode usar). As imagens usam sistemas de arquivos em camadas (*overlay*), o que permite compartilhar partes comuns entre imagens. Por isso contêineres sobem em milissegundos.

Já a virtualização com hipervisor usa extensões do processador (Intel VT-x, AMD-V) para rodar sistemas completos com isolamento forte, que é o que a nuvem usa para separar clientes.

Em sistemas de arquivos, a **escrita em disco é a parte mais lenta e mais frágil**: o journaling, o *copy-on-write* (Btrfs, ZFS, APFS) e chamadas como `fsync` existem para garantir que uma queda de energia não deixe os dados corrompidos.

@fontes 3
- Arpaci-Dusseau, *Operating Systems: Three Easy Pieces*, capítulos 39 a 42 (sistemas de arquivos e journaling)
- [Docker overview — arquitetura](https://docs.docker.com/get-started/docker-overview/)
- Tanenbaum e Bos, *Sistemas Operacionais Modernos*, capítulo 7 (virtualização e a nuvem)
