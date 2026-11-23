<?php

namespace Database\Seeders;

use App\Models\Conquista;
use App\Models\Linguagem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/*
 * SEEDER PRINCIPAL - preenche o banco com os dados iniciais:
 *   conteúdo oficial, conquistas, um usuário de cada tipo e um curso da comunidade de exemplo.
 * Rodar:  php artisan db:seed
 * Ou, apagando tudo e recriando as tabelas antes:  php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Linguagens, cursos, módulos, lições e exercícios.
        $this->call(ConteudoSeeder::class);

        // 2) Conquistas e medalhas (RF17).
        $conquistas = [
            ['Primeiros passos',  'Conclua sua primeira lição.', '🐣', 'licoes',    1],
            ['Pinguim estudioso', 'Conclua 10 lições.',          '📚', 'licoes',    10],
            ['Pinguim sábio',     'Conclua 30 lições.',          '🦉', 'licoes',    30],
            ['Gelo firme',        'Estude 3 dias seguidos.',     '🧊', 'sequencia', 3],
            ['Geleira',           'Estude 7 dias seguidos.',     '🏔️', 'sequencia', 7],
            ['Era do gelo',       'Estude 30 dias seguidos.',    '❄️', 'sequencia', 30],
            ['Medalha de bronze', 'Junte 100 pontos.',           '🥉', 'pontos',    100],
            ['Medalha de prata',  'Junte 500 pontos.',           '🥈', 'pontos',    500],
            ['Medalha de ouro',   'Junte 1000 pontos.',          '🥇', 'pontos',    1000],
        ];
        foreach ($conquistas as $c) {
            Conquista::create(['nome' => $c[0], 'descricao' => $c[1], 'icone' => $c[2], 'tipo' => $c[3], 'meta' => $c[4]]);
        }

        // 3) Um usuário de cada tipo. Senha de todos: Teste123
        User::create([
            'tipo'         => 'aluno',
            'nome'         => 'Usuário Teste',
            'nome_usuario' => 'teste',
            'email'        => 'teste@technopenguin.com',
            'password'     => Hash::make('Teste123'),
            'linguagem_id' => Linguagem::where('nome', 'Python')->value('id'),
            'nivel'        => 'iniciante',
            'meta_diaria'  => 2,
            'pontos'       => 150,          // para poder testar a compra de peças no vestuário
            'chapeu'       => 'bone',       // já começa de boné e moletom
            'roupa'        => 'moletom',
        ]);

        $professora = User::create([
            'tipo'         => 'professor',
            'nome'         => 'Professora Glácia',
            'nome_usuario' => 'prof.glacia',
            'email'        => 'professor@technopenguin.com',
            'password'     => Hash::make('Teste123'),
            'nivel'        => 'avancado',
            'cor_corpo'    => '#5b4b8a',      // pinguim roxo de óculos e gravata
            'cor_fundo'    => '#b39ddb',
            'oculos'       => 'oculos-leitura',
            'pescoco'      => 'gravata-borboleta',
        ]);

        User::create([
            'tipo'         => 'admin',
            'nome'         => 'Administrador',
            'nome_usuario' => 'admin',
            'email'        => 'admin@technopenguin.com',
            'password'     => Hash::make('Teste123'),
            'nivel'        => 'avancado',
        ]);

        // 4) Um curso da comunidade de exemplo, criado pela professora (texto simples, como no formulário).
        $curso = $professora->cursosCriados()->create([
            'titulo'    => 'Lua para jogos',
            'tipo'      => 'comunidade',
            'sigla'     => 'Lua',
            'cor'       => '#000080',
            'descricao' => 'A linguagem leve usada em Roblox, Defold e LÖVE para programar a lógica de jogos.',
            'publicado' => true,
        ]);

        $modulo = $curso->modulos()->create([
            'titulo'      => 'Primeiros passos em Lua',
            'descricao'   => 'Variáveis, tabelas, funções e laços.',
            'dificuldade' => 'iniciante',
            'ordem'       => 1,
        ]);

        $licao = $modulo->licoes()->create([
            'titulo'  => 'Variáveis e tabelas',
            'teoria'  => "Em Lua, variáveis são globais por padrão: use local para criar variáveis locais.\n\nA única estrutura de dados é a tabela, que funciona como lista e como dicionário ao mesmo tempo.",
            'pratica' => "local vidas = 3\nlocal jogador = { nome = \"Teco\", pontos = 0 }\njogador.pontos = jogador.pontos + 10\nprint(jogador.nome, jogador.pontos)",
            'ordem'   => 1,
        ]);
        $licao->exercicios()->create([
            'enunciado' => 'Qual palavra cria uma variável local em Lua?',
            'alternativa_a' => 'var', 'alternativa_b' => 'local', 'alternativa_c' => 'let', 'alternativa_d' => 'my',
            'resposta_correta' => 'b',
            'explicacao' => 'Sem local, a variável é global.',
        ]);
        $licao->exercicios()->create([
            'enunciado' => 'Qual é a estrutura de dados central de Lua?',
            'alternativa_a' => 'Array', 'alternativa_b' => 'Lista', 'alternativa_c' => 'Tabela (table)', 'alternativa_d' => 'Struct',
            'resposta_correta' => 'c',
            'explicacao' => 'Tabelas fazem o papel de listas, dicionários e objetos.',
        ]);

        $licao = $modulo->licoes()->create([
            'titulo'  => 'Funções e laços',
            'teoria'  => "Funções são declaradas com function e terminam com end.\n\nOs índices de listas começam em 1.",
            'pratica' => "local function dano(vida, golpe)\n  return math.max(0, vida - golpe)\nend\nfor i = 1, 3 do\n  print(i, dano(10, i * 3))\nend",
            'ordem'   => 2,
        ]);
        $licao->exercicios()->create([
            'enunciado' => 'Em Lua, o primeiro índice de uma lista é:',
            'alternativa_a' => '0', 'alternativa_b' => '1', 'alternativa_c' => '-1',   // só 3 alternativas
            'resposta_correta' => 'b',
            'explicacao' => 'Por convenção, listas em Lua começam em 1.',
        ]);
    }
}
