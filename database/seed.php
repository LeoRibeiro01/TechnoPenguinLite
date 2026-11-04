<?php
declare(strict_types=1);

require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/Conteudo.php';
require_once dirname(__DIR__) . '/app/bootstrap.php';

use TechnoPenguin\Support\Catalogo;

/**
 * Seeder de desenvolvimento, idempotente. Carrega as trilhas de database/conteudo/*.md
 * (módulos, lições e questões) e cria usuários de teste com amizades e um iglu.
 */

function garantirRegistro(PDO $pdo, string $sqlBuscar, array $parametros, string $sqlInserir, array $dados): int
{
    $buscar = $pdo->prepare($sqlBuscar);
    $buscar->execute($parametros);
    $id = $buscar->fetchColumn();
    if ($id !== false) {
        return (int) $id;
    }
    $inserir = $pdo->prepare($sqlInserir);
    $inserir->execute($dados);
    return (int) $pdo->lastInsertId();
}

/**
 * Embaralha as alternativas para a correta não cair sempre na mesma letra. A semente vem do
 * enunciado, então rodar o seeder de novo mantém a mesma ordem.
 */
function embaralharAlternativas(array $questao): array
{
    $ordem = array_keys($questao['alternativas']);
    mt_srand(crc32($questao['enunciado']));
    shuffle($ordem);
    mt_srand();
    return [
        array_map(static fn (int $i): string => $questao['alternativas'][$i], $ordem),
        array_search($questao['correta'], $ordem, true),
    ];
}

/** Grava (ou atualiza) o curso de uma trilha; as questões de cada lição são substituídas. */
function semearTrilha(PDO $pdo, string $slug, array $modulos, array $apresentacao): array
{
    $trilha = Catalogo::trilha($slug);
    $idCurso = garantirRegistro(
        $pdo,
        'SELECT id_curso FROM curso WHERE linguagem_curso = ?',
        [$slug],
        'INSERT INTO curso (titulo_curso, descricao_curso, nivel_dificuldade_curso, linguagem_curso) VALUES (?, ?, ?, ?)',
        [$trilha['titulo'], $trilha['descricao'], 'Iniciante ao avançado', $slug]
    );
    $pdo->prepare(
        'UPDATE curso SET titulo_curso = ?, descricao_curso = ?, tipo_curso = ?, sigla_curso = ?, cor_curso = ?,
            historia_curso = ?, uso_atual_curso = ?, objetivos_curso = ?, referencias_curso = ?
         WHERE id_curso = ?'
    )->execute([
        $trilha['titulo'], $trilha['descricao'], $trilha['tipo'], $trilha['sigla'], $trilha['cor'],
        $apresentacao['historia'] ?? null, $apresentacao['hoje'] ?? null,
        ($apresentacao['objetivos'] ?? []) === [] ? null : json_encode($apresentacao['objetivos'], JSON_UNESCAPED_UNICODE),
        $apresentacao['referencias'] ?? null, $idCurso,
    ]);

    $atualizarModulo = $pdo->prepare(
        'UPDATE modulo SET titulo_modulo = ?, descricao_modulo = ?, projeto_modulo = ?, dificuldade_modulo = ?,
            aprofundamento_modulo = ?, referencias_modulo = ? WHERE id_modulo = ?'
    );
    $dificuldades = array_keys(Catalogo::DIFICULDADES);
    $atualizarLicao = $pdo->prepare('UPDATE licao SET titulo_licao = ?, pontos_licao = ?, teoria_licao = ?, pratica_licao = ? WHERE id_licao = ?');
    $apagarQuestoes = $pdo->prepare('DELETE FROM questao WHERE id_licao = ?');
    $inserirQuestao = $pdo->prepare(
        'INSERT INTO questao (enunciado_questao, tipo_questao, gabarito_questao, id_licao, alternativas_questao, explicacao_questao, sequencia_questao)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $totais = [0, 0];
    foreach ($modulos as $i => $modulo) {
        $sequenciaModulo = $i + 1;
        $idModulo = garantirRegistro(
            $pdo,
            'SELECT id_modulo FROM modulo WHERE id_curso = ? AND sequencia_modulo = ?',
            [$idCurso, $sequenciaModulo],
            'INSERT INTO modulo (titulo_modulo, sequencia_modulo, id_curso) VALUES (?, ?, ?)',
            [$modulo['titulo'], $sequenciaModulo, $idCurso]
        );
        $extra = $apresentacao['modulos'][$sequenciaModulo] ?? [];
        $atualizarModulo->execute([
            $modulo['titulo'], $modulo['descricao'] ?: null, $modulo['projeto'] ?: null,
            $modulo['dificuldade'] ?? $dificuldades[min($i, count($dificuldades) - 1)],
            $extra['aprofundamento'] ?? null, $extra['referencias'] ?? null, $idModulo,
        ]);

        foreach ($modulo['licoes'] as $j => $licao) {
            $pontos = 10 + 5 * $i;
            $idLicao = garantirRegistro(
                $pdo,
                'SELECT id_licao FROM licao WHERE id_modulo = ? AND sequencia_licao = ?',
                [$idModulo, $j + 1],
                'INSERT INTO licao (titulo_licao, pontos_licao, sequencia_licao, id_modulo) VALUES (?, ?, ?, ?)',
                [$licao['titulo'], $pontos, $j + 1, $idModulo]
            );
            $atualizarLicao->execute([$licao['titulo'], $pontos, $licao['teoria'], $licao['pratica'], $idLicao]);
            $apagarQuestoes->execute([$idLicao]);
            foreach ($licao['questoes'] as $k => $questao) {
                [$alternativas, $correta] = embaralharAlternativas($questao);
                $inserirQuestao->execute([
                    $questao['enunciado'], 'multipla_escolha', (string) $correta, $idLicao,
                    json_encode($alternativas, JSON_UNESCAPED_UNICODE), $questao['explicacao'] ?: null, $k + 1,
                ]);
            }
            $totais[1] += count($licao['questoes']);
        }
        $totais[0] += count($modulo['licoes']);
    }
    return $totais;
}

/** Usuário de teste com o questionário inicial já respondido. */
function semearUsuario(PDO $pdo, array $dados, array $trilhas): int
{
    $id = garantirRegistro(
        $pdo,
        'SELECT id_usuario FROM usuario WHERE email = ?',
        [$dados['email']],
        'INSERT INTO usuario (nome, arroba, email, senha) VALUES (?, ?, ?, ?)',
        [$dados['nome'], $dados['arroba'], $dados['email'], password_hash('PenguinDev2026!', PASSWORD_BCRYPT)]
    );
    $pdo->prepare(
        "UPDATE usuario SET arroba = ?, data_nascimento = COALESCE(data_nascimento, ?),
            aceite_termos_em = COALESCE(aceite_termos_em, NOW()), papel_usuario = ?,
            nivel_conhecimento_usuario = IF(nivel_conhecimento_usuario IN ('zero', 'algumas', 'varias', 'avancado'), nivel_conhecimento_usuario, ?),
            objetivo_usuario = COALESCE(objetivo_usuario, ?), meta_diaria_usuario = COALESCE(meta_diaria_usuario, ?),
            cor_corpo_avatar = COALESCE(cor_corpo_avatar, ?), cor_barriga_avatar = COALESCE(cor_barriga_avatar, ?),
            cor_detalhe_avatar = COALESCE(cor_detalhe_avatar, ?), cor_fundo_avatar = COALESCE(cor_fundo_avatar, ?),
            chapeu_avatar = COALESCE(chapeu_avatar, ?), roupa_avatar = COALESCE(roupa_avatar, ?)
         WHERE id_usuario = ?"
    )->execute([
        $dados['arroba'], $dados['nascimento'], $dados['papel'] ?? 'aluno', $dados['nivel'], $dados['objetivo'], $dados['meta'],
        $dados['cores'][0], $dados['cores'][1], $dados['cores'][2], $dados['cores'][3],
        $dados['chapeu'], $dados['roupa'], $id,
    ]);
    $pdo->prepare('INSERT IGNORE INTO sequencia (id_usuario) VALUES (?)')->execute([$id]);
    $inserir = $pdo->prepare('INSERT IGNORE INTO usuario_linguagem (id_usuario, linguagem) VALUES (?, ?)');
    foreach ($trilhas as $trilha) {
        $inserir->execute([$id, $trilha]);
    }
    return $id;
}

/** Curso de exemplo da comunidade, criado pela professora de teste, com uma pergunta respondida. */
function semearCursoComunidade(PDO $pdo, int $idProfessora, int $idAluno): void
{
    $idCurso = garantirRegistro(
        $pdo,
        'SELECT id_curso FROM curso WHERE id_professor = ? AND titulo_curso = ?',
        [$idProfessora, 'Lua para jogos'],
        "INSERT INTO curso (titulo_curso, descricao_curso, nivel_dificuldade_curso, tipo_curso, sigla_curso, cor_curso,
            publicado_curso, id_professor, historia_curso, uso_atual_curso, objetivos_curso, referencias_curso)
         VALUES (?, ?, 'Iniciante ao avançado', 'linguagem', 'Lua', '#000080', 1, ?, ?, ?, ?, ?)",
        [
            'Lua para jogos',
            'A linguagem leve usada em Roblox, Defold e LÖVE para programar a lógica de jogos.',
            $idProfessora,
            'Lua nasceu em 1993 na PUC-Rio, criada por Roberto Ierusalimschy, Luiz Henrique de Figueiredo e Waldemar Celes. É uma das poucas linguagens brasileiras com uso mundial: "lua" é mesmo a palavra em português.',
            'Hoje Lua aparece embutida em jogos e motores (Roblox usa uma variante chamada Luau), em editores como o Neovim e em servidores como o Nginx (OpenResty).',
            json_encode(['iniciante' => ['Variáveis, tipos e tabelas', 'Funções e laços'], 'intermediario' => ['Programar a lógica de um jogo 2D']], JSON_UNESCAPED_UNICODE),
            "- Programming in Lua — Roberto Ierusalimschy (o livro oficial)\n- [Manual de referência de Lua](https://www.lua.org/manual/5.4/)",
        ]
    );
    $pdo->prepare("UPDATE curso SET linguagem_curso = CONCAT('comunidade-', id_curso) WHERE id_curso = ?")->execute([$idCurso]);
    $modulos = [
        ['Primeiros passos em Lua', 'iniciante', [
            ['Variáveis e tabelas', "Em Lua, variáveis são globais por padrão: use **local** para criar variáveis locais.\n\nA única estrutura de dados é a **tabela**, que funciona como lista e como dicionário ao mesmo tempo.", "```lua\nlocal vidas = 3\nlocal jogador = { nome = \"Teco\", pontos = 0 }\njogador.pontos = jogador.pontos + 10\nprint(jogador.nome, jogador.pontos)\n```", [
                ['Qual palavra cria uma variável local em Lua?', ['var', 'local', 'let', 'my'], 1, 'Sem local, a variável é global.'],
                ['Qual é a estrutura de dados central de Lua?', ['Array', 'Lista', 'Tabela (table)', 'Struct'], 2, 'Tabelas fazem o papel de listas, dicionários e objetos.'],
            ]],
            ['Funções e laços', "Funções são declaradas com **function** e terminam com **end**. Os índices de listas começam em **1**.", "```lua\nlocal function dano(vida, golpe)\n  return math.max(0, vida - golpe)\nend\nfor i = 1, 3 do\n  print(i, dano(10, i * 3))\nend\n```", [
                ['Em Lua, o primeiro índice de uma lista é:', ['0', '1', '-1', 'Depende'], 1, 'Por convenção, listas em Lua começam em 1.'],
            ]],
        ]],
    ];
    $proximo = $pdo->prepare('SELECT id_modulo FROM modulo WHERE id_curso = ? AND sequencia_modulo = ?');
    foreach ($modulos as $m => [$titulo, $dificuldade, $licoes]) {
        $proximo->execute([$idCurso, $m + 1]);
        $idModulo = $proximo->fetchColumn();
        if ($idModulo === false) {
            $pdo->prepare('INSERT INTO modulo (titulo_modulo, sequencia_modulo, id_curso, dificuldade_modulo, projeto_modulo) VALUES (?, ?, ?, ?, ?)')
                ->execute([$titulo, $m + 1, $idCurso, $dificuldade, 'Crie um mini jogo de texto: o jogador tem 3 vidas e escolhe portas até achar o peixe dourado.']);
            $idModulo = (int) $pdo->lastInsertId();
        }
        foreach ($licoes as $l => [$tituloLicao, $teoria, $pratica, $questoes]) {
            $idLicao = garantirRegistro(
                $pdo,
                'SELECT id_licao FROM licao WHERE id_modulo = ? AND sequencia_licao = ?',
                [$idModulo, $l + 1],
                'INSERT INTO licao (titulo_licao, pontos_licao, sequencia_licao, id_modulo, teoria_licao, pratica_licao) VALUES (?, 10, ?, ?, ?, ?)',
                [$tituloLicao, $l + 1, $idModulo, $teoria, $pratica]
            );
            $pdo->prepare('DELETE FROM questao WHERE id_licao = ?')->execute([$idLicao]);
            foreach ($questoes as $q => [$enunciado, $alternativas, $correta, $explicacao]) {
                $pdo->prepare("INSERT INTO questao (enunciado_questao, tipo_questao, gabarito_questao, id_licao, alternativas_questao, explicacao_questao, sequencia_questao)
                    VALUES (?, 'multipla_escolha', ?, ?, ?, ?, ?)")
                    ->execute([$enunciado, (string) $correta, $idLicao, json_encode($alternativas, JSON_UNESCAPED_UNICODE), $explicacao, $q + 1]);
            }
        }
    }
    $pdo->prepare('INSERT IGNORE INTO usuario_linguagem (id_usuario, linguagem) SELECT ?, linguagem_curso FROM curso WHERE id_curso = ?')
        ->execute([$idAluno, $idCurso]);
    garantirRegistro(
        $pdo,
        'SELECT id_pergunta FROM pergunta WHERE id_curso = ? AND id_aluno = ?',
        [$idCurso, $idAluno],
        'INSERT INTO pergunta (id_curso, id_aluno, texto_pergunta, publica_pergunta, resposta_pergunta, respondida_em) VALUES (?, ?, ?, 1, ?, NOW())',
        [$idCurso, $idAluno, 'Por que as listas em Lua começam no 1 e não no 0?', 'Boa pergunta! Lua foi pensada para não programadores (engenheiros da Petrobras), e contar a partir de 1 era mais natural para eles. Você pode até usar o índice 0, mas as funções da biblioteca padrão, como ipairs e #, assumem que a lista começa em 1.']
    );
}

try {
    carregarAmbiente(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
    if (strtolower(getenv('APP_ENV') ?: 'development') === 'production') {
        throw new RuntimeException('O seeder é bloqueado quando APP_ENV=production.');
    }
    $pdo = criarConexao();
    $pdo->beginTransaction();

    $resumo = [];
    foreach (array_keys(Catalogo::TRILHAS) as $slug) {
        $arquivo = __DIR__ . "/conteudo/{$slug}.md";
        if (!is_file($arquivo)) {
            fwrite(STDERR, "Aviso: sem conteúdo para a trilha {$slug} ({$arquivo}).\n");
            continue;
        }
        try {
            $sobre = __DIR__ . "/conteudo/{$slug}.sobre.md";
            [$licoes, $questoes] = semearTrilha(
                $pdo,
                $slug,
                lerConteudoTrilha($arquivo),
                is_file($sobre) ? lerApresentacaoTrilha($sobre) : []
            );
        } catch (RuntimeException $e) {
            throw new RuntimeException("{$slug}.md: {$e->getMessage()}", 0, $e);
        }
        $resumo[] = "{$slug} ({$licoes} lições, {$questoes} questões)";
    }

    $teste = semearUsuario($pdo, [
        'nome' => 'Usuário Teste', 'arroba' => 'usuario_teste', 'email' => 'teste@technopenguin.local',
        'nivel' => 'algumas', 'objetivo' => 'carreira', 'meta' => 15, 'nascimento' => '1998-05-10',
        'cores' => ['#384048', '#ecf0f0', '#ec9038', '#00bcd4'], 'chapeu' => 'bone', 'roupa' => 'moletom',
    ], ['python', 'javascript', 'html-css']);
    $ana = semearUsuario($pdo, [
        'nome' => 'Ana Pinguim', 'arroba' => 'ana.pinguim', 'email' => 'ana@technopenguin.local',
        'nivel' => 'varias', 'objetivo' => 'projetos', 'meta' => 30, 'nascimento' => '1995-03-02',
        'cores' => ['#5b4b8a', '#fde4ea', '#ee7fa4', '#e91e63'], 'chapeu' => 'chapeu-festa', 'roupa' => 'camisa-polo',
    ], ['python', 'modelagem-bd', 'mysql']);
    $beto = semearUsuario($pdo, [
        'nome' => 'Beto Dev', 'arroba' => 'beto_dev', 'email' => 'beto@technopenguin.local',
        'nivel' => 'zero', 'objetivo' => 'estudos', 'meta' => 10, 'nascimento' => '2010-08-20',
        'cores' => ['#2f6b5a', '#f6ecd9', '#f5c242', '#4caf50'], 'chapeu' => 'cartola', 'roupa' => 'terno',
    ], ['logica']);

    $professora = semearUsuario($pdo, [
        'nome' => 'Profa. Glácia', 'arroba' => 'prof.glacia', 'email' => 'glacia@technopenguin.local',
        'nivel' => 'avancado', 'objetivo' => 'carreira', 'meta' => 30, 'nascimento' => '1985-11-15', 'papel' => 'professor',
        'cores' => ['#5c7aa5', '#ecf0f0', '#ec9038', '#1a2e46'], 'chapeu' => 'chapeu-bruxo', 'roupa' => 'terno',
    ], ['python', 'estruturas-dados']);
    semearCursoComunidade($pdo, $professora, $teste);

    $amizade = $pdo->prepare('INSERT IGNORE INTO amizade (id_solicitante, id_destinatario, status_amizade) VALUES (?, ?, ?)');
    $amizade->execute([$ana, $teste, 'aceita']);
    $amizade->execute([$beto, $teste, 'pendente']);

    $iglu = garantirRegistro(
        $pdo,
        'SELECT id_iglu FROM iglu WHERE nome_iglu = ?',
        ['Pinguins do Python'],
        'INSERT INTO iglu (nome_iglu, descricao_iglu, id_criador) VALUES (?, ?, ?)',
        ['Pinguins do Python', 'Grupo para aprender Python do básico aos projetos, com um pouco de banco de dados.', $ana]
    );
    $membro = $pdo->prepare('INSERT IGNORE INTO iglu_membro (id_iglu, id_usuario, papel_membro) VALUES (?, ?, ?)');
    $membro->execute([$iglu, $ana, 'dono']);
    $pdo->prepare("UPDATE iglu SET icone_iglu = '🐍', cor_iglu = '#3776ab' WHERE id_iglu = ?")->execute([$iglu]);
    $trilhaIglu = $pdo->prepare('INSERT IGNORE INTO iglu_trilha (id_iglu, trilha, obrigatoria) VALUES (?, ?, ?)');
    $trilhaIglu->execute([$iglu, 'python', 1]);
    $trilhaIglu->execute([$iglu, 'modelagem-bd', 0]);
    garantirRegistro(
        $pdo,
        'SELECT id_convite FROM iglu_convite WHERE id_iglu = ? AND id_usuario = ?',
        [$iglu, $teste],
        "INSERT INTO iglu_convite (id_iglu, id_usuario, id_remetente, tipo_convite) VALUES (?, ?, ?, 'convite')",
        [$iglu, $teste, $ana]
    );

    (new TechnoPenguin\Controllers\ConquistaController($pdo))->sincronizarCatalogo();
    foreach ([['Complete uma lição nesta semana', 20], ['Pratique programação em três dias', 50]] as [$descricao, $recompensa]) {
        garantirRegistro(
            $pdo,
            'SELECT id_desafio FROM desafio WHERE descricao_desafio = ?',
            [$descricao],
            'INSERT INTO desafio (descricao_desafio, recompensa_xp_desafio, data_expiracao_desafio) VALUES (?, ?, ?)',
            [$descricao, $recompensa, (new DateTimeImmutable('+30 days'))->format('Y-m-d H:i:s')]
        );
    }

    $pdo->commit();
    fwrite(STDOUT, "Trilhas carregadas:\n  " . implode("\n  ", $resumo) . "\n");
    fwrite(STDOUT, "Usuários de teste (senha PenguinDev2026!): @usuario_teste, @ana.pinguim, @beto_dev (16 anos) e @prof.glacia (professora).\n");
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "Erro no seeder: {$e->getMessage()}\n");
    exit(1);
}
