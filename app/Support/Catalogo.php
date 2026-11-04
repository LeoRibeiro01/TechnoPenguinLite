<?php
declare(strict_types=1);

namespace TechnoPenguin\Support;

/** Opções fixas da interface: trilhas, respostas do questionário inicial e paletas do avatar. */
final class Catalogo
{
    /** Trilha obrigatória para quem ainda não sabe nada de programação. */
    public const TRILHA_INICIAL = 'logica';

    /** slug => [título, tipo, sigla do ícone, cor do ícone, descrição]. tipo: base, linguagem ou assunto. */
    public const TRILHAS = [
        'logica' => ['Lógica de Programação', 'base', '{ }', '#4fb3a9', 'O começo de tudo: algoritmos, variáveis, decisões e repetições, sem depender de linguagem.'],

        'python' => ['Python', 'linguagem', 'Py', '#3776ab', 'Sintaxe simples e muito versátil: automação, dados, IA e back-end.'],
        'javascript' => ['JavaScript', 'linguagem', 'JS', '#c9a400', 'A linguagem da web: interatividade no navegador e servidores com Node.js.'],
        'typescript' => ['TypeScript', 'linguagem', 'TS', '#3178c6', 'JavaScript com tipos: menos bugs e mais segurança em projetos grandes.'],
        'html-css' => ['HTML & CSS', 'linguagem', '</>', '#e44d26', 'Estrutura e estilo das páginas web, do layout à responsividade.'],
        'java' => ['Java', 'linguagem', 'Jv', '#b07219', 'Orientação a objetos, sistemas corporativos e apps Android.'],
        'c' => ['C', 'linguagem', 'C', '#5c6bc0', 'A base dos sistemas: memória, ponteiros e alto desempenho.'],
        'cpp' => ['C++', 'linguagem', 'C++', '#00599c', 'Desempenho com abstração: jogos, motores gráficos e sistemas.'],
        'csharp' => ['C#', 'linguagem', 'C#', '#68217a', 'Ecossistema .NET: aplicações web, desktop e jogos com Unity.'],
        'kotlin' => ['Kotlin', 'linguagem', 'Kt', '#7f52ff', 'Moderna e concisa, a linguagem oficial do Android.'],
        'php' => ['PHP', 'linguagem', 'php', '#777bb4', 'Back-end web: formulários, sessões e bancos de dados.'],
        'go' => ['Go', 'linguagem', 'Go', '#00add8', 'Simples e rápida, feita para serviços em nuvem e concorrência.'],
        'ruby' => ['Ruby', 'linguagem', 'Rb', '#cc342d', 'Expressiva e produtiva, famosa pelo framework Rails.'],
        'rust' => ['Rust', 'linguagem', 'Rs', '#ce422b', 'Segurança de memória sem coletor de lixo e desempenho de C.'],
        'swift' => ['Swift', 'linguagem', 'Sw', '#f05138', 'Apps para iPhone, iPad e Mac no ecossistema Apple.'],
        'mysql' => ['MySQL', 'linguagem', 'SQL', '#00758f', 'Consultas SQL no banco relacional mais usado da web.'],
        'mariadb' => ['MariaDB', 'linguagem', 'MDB', '#a2775b', 'O fork livre do MySQL: SQL, motores de armazenamento e recursos próprios.'],

        'modelagem-bd' => ['Modelagem de Banco de Dados', 'assunto', 'DER', '#2e7d6b', 'Entidades, relacionamentos e normalização para bancos bem projetados.'],
        'historia' => ['A história da programação', 'assunto', '1843', '#8d6e63', 'De Ada Lovelace à inteligência artificial: como chegamos até aqui.'],
        'iot' => ['Sistemas Ciberfísicos e IoT', 'assunto', 'IoT', '#00897b', 'Sensores, atuadores e a conectividade entre o mundo físico e o digital.'],
        'redes' => ['Redes de Computadores', 'assunto', 'NET', '#1e88e5', 'Modelos OSI e TCP/IP, endereçamento, roteamento e protocolos.'],
        'requisitos' => ['Engenharia de Requisitos', 'assunto', 'REQ', '#6d4c41', 'Descobrir, escrever e validar o que um sistema precisa fazer.'],
        'sistemas-operacionais' => ['Sistemas Operacionais', 'assunto', 'SO', '#546e7a', 'Processos, memória, arquivos e o que acontece por trás do sistema.'],
        'cloud' => ['Computação em nuvem', 'assunto', '☁', '#039be5', 'IaaS, PaaS, SaaS, escalabilidade e serviços dos grandes provedores.'],
        'devops' => ['DevOps e CI/CD', 'assunto', 'CI', '#43a047', 'Git, pipelines, contêineres e entrega contínua de software.'],
        'ciberseguranca' => ['Cibersegurança', 'assunto', 'SEC', '#c62828', 'Ameaças, criptografia, autenticação e desenvolvimento seguro.'],
        'prompt' => ['Engenharia de Prompt e vibe coding', 'assunto', 'AI', '#8e24aa', 'Como melhorar seus projetos através de prompts melhores.'],
        'estruturas-dados' => ['Estruturas de Dados e Algoritmos', 'assunto', 'O(n)', '#ef6c00', 'Listas, pilhas, filas, árvores, grafos e como medir a eficiência de um algoritmo.'],
        'testes' => ['Testes de Software', 'assunto', 'QA', '#7cb342', 'Testes unitários, de integração e ponta a ponta, TDD e qualidade de código.'],
        'ingles-entrevista' => ['Inglês para entrevista de programação', 'assunto', 'EN', '#3949ab', 'Vocabulário técnico, como se apresentar, explicar seu código e encarar entrevistas em inglês.'],
    ];

    /**
     * Níveis de dificuldade dos módulos: rótulo, horas estimadas por lição (teoria, prática,
     * questões e leitura de apoio) e horas estimadas para o projeto de cada módulo.
     */
    public const DIFICULDADES = [
        'iniciante' => ['Iniciante', 2, 6],
        'intermediario' => ['Intermediário', 3, 10],
        'avancado' => ['Avançado', 4, 16],
    ];

    /** Aproveitamento mínimo nas questões de um nível para emitir o certificado. */
    public const APROVEITAMENTO_CERTIFICADO = 0.7;

    /** Idade mínima para usar o chat dos iglus e para ser professor. */
    public const IDADE_ADULTA = 18;

    /** Abaixo desta idade a LGPD (art. 14) exige consentimento de um responsável. */
    public const IDADE_CRIANCA = 12;

    /** Respostas de "O quanto você sabe sobre programação?". */
    public const NIVEIS = [
        'zero' => 'Não sei nada sobre programação',
        'algumas' => 'Consigo programar em algumas linguagens',
        'varias' => 'Consigo programar em várias linguagens',
        'avancado' => 'Sei vários assuntos detalhados',
    ];

    public const OBJETIVOS = [
        'carreira' => ['Progredir na carreira', '💼'],
        'estudos' => ['Avançar nos estudos', '🎓'],
        'projetos' => ['Fazer projetos pessoais', '🛠️'],
        'tempo' => ['Usar melhor meu tempo', '⏳'],
    ];

    /** minutos => [rótulo, multiplicador de pontos]. Quanto maior a meta, mais pontos por acerto. */
    public const METAS = [
        5 => ['5 minutos por dia', 1.0],
        10 => ['10 minutos por dia', 1.25],
        15 => ['15 minutos por dia', 1.5],
        20 => ['20 minutos por dia', 1.75],
        30 => ['30 minutos por dia', 2.0],
        60 => ['+ de 1 hora por dia', 3.0],
    ];

    /** Tempo médio de uma lição, usado para medir a meta diária. */
    public const MINUTOS_POR_LICAO = 5;

    /** Cores dominantes da foto original do pinguim; com elas o avatar fica idêntico à foto. */
    public const AVATAR_PADRAO = [
        'corpo' => '#384048',
        'barriga' => '#ecf0f0',
        'detalhe' => '#ec9038',
        'fundo' => '#00bcd4',
    ];

    public const PALETAS_AVATAR = [
        'corpo' => [
            '#384048' => 'Clássico',
            '#1a2e46' => 'Azul-noite',
            '#5c7aa5' => 'Azul-gelo',
            '#5b4b8a' => 'Aurora',
            '#2f6b5a' => 'Floresta',
            '#c4687f' => 'Rosa',
            '#6b4a3a' => 'Chocolate',
            '#8a94a3' => 'Neblina',
        ],
        'barriga' => [
            '#ecf0f0' => 'Neve',
            '#f6ecd9' => 'Creme',
            '#d6ecfb' => 'Gelo',
            '#fde4ea' => 'Algodão-doce',
            '#fff3b0' => 'Baunilha',
        ],
        'detalhe' => [
            '#ec9038' => 'Laranja',
            '#f5c242' => 'Amarelo',
            '#e5533d' => 'Vermelho',
            '#ee7fa4' => 'Rosa',
            '#4fb3e8' => 'Azul',
        ],
        'fundo' => [
            '#00bcd4' => 'Mar',
            '#ffeb3b' => 'Sol',
            '#f44336' => 'Vulcão',
            '#4caf50' => 'Floresta',
            '#e91e63' => 'Chiclete',
            '#1a2e46' => 'Noite polar',
            '#b39ddb' => 'Aurora',
            '#ff9800' => 'Pôr do sol',
            '#9fe3ff' => 'Geleira',
            '#2e7d32' => 'Pinheiral',
            '#ffffff' => 'Neve fresca',
            '#37474f' => 'Tempestade',
        ],
    ];

    public static function trilha(string $slug): ?array
    {
        $dados = self::TRILHAS[$slug] ?? null;
        if ($dados === null) {
            return null;
        }
        [$titulo, $tipo, $sigla, $cor, $descricao] = $dados;
        return compact('slug', 'titulo', 'tipo', 'sigla', 'cor', 'descricao');
    }

    /** Trilhas filtradas por tipo (base, linguagem, assunto), no formato de trilha(). */
    public static function trilhasPorTipo(string ...$tipos): array
    {
        $resultado = [];
        foreach (array_keys(self::TRILHAS) as $slug) {
            $trilha = self::trilha($slug);
            if (in_array($trilha['tipo'], $tipos, true)) {
                $resultado[$slug] = $trilha;
            }
        }
        return $resultado;
    }

    public static function multiplicador(?int $metaMinutos): float
    {
        return self::METAS[$metaMinutos][1] ?? 1.0;
    }
}
