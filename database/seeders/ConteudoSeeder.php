<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Linguagem;
use Illuminate\Database\Seeder;

/*
 * SEEDER DO CONTEÚDO
 * Lê o arquivo database/seeders/dados/conteudo.json e cadastra:
 *   linguagens -> cursos -> módulos -> lições -> exercícios
 * (30 cursos, 90 módulos, 270 lições e 810 exercícios escritos pela equipe)
 *
 * O JSON é uma lista de cursos. Cada curso tem "modulos", cada módulo tem "licoes"
 * e cada lição tem "exercicios". Por isso os foreach ficam um dentro do outro.
 */
class ConteudoSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(__DIR__ . '/dados/conteudo.json');
        $cursos = json_decode($json, true);   // true = vira array do PHP

        foreach ($cursos as $dadosCurso) {
            // Só os cursos do tipo "linguagem" criam uma linguagem (RF10).
            $linguagemId = null;
            if ($dadosCurso['tipo'] === 'linguagem') {
                $linguagem = Linguagem::create([
                    'nome'  => $dadosCurso['titulo'],
                    'sigla' => $dadosCurso['sigla'],
                    'cor'   => $dadosCurso['cor'],
                ]);
                $linguagemId = $linguagem->id;
            }

            $curso = Curso::create([
                'linguagem_id' => $linguagemId,
                'titulo'       => $dadosCurso['titulo'],
                'tipo'         => $dadosCurso['tipo'],
                'sigla'        => $dadosCurso['sigla'],
                'cor'          => $dadosCurso['cor'],
                'descricao'    => $dadosCurso['descricao'],
                'historia'     => $dadosCurso['historia'],
                'hoje'         => $dadosCurso['hoje'],
                'referencias'  => $dadosCurso['referencias'],
            ]);

            foreach ($dadosCurso['modulos'] as $i => $dadosModulo) {
                // $curso->modulos()->create(...) já preenche o curso_id sozinho.
                $modulo = $curso->modulos()->create([
                    'titulo'         => $dadosModulo['titulo'],
                    'descricao'      => $dadosModulo['descricao'],
                    'dificuldade'    => $dadosModulo['dificuldade'],
                    'ordem'          => $i + 1,
                    'projeto'        => $dadosModulo['projeto'],
                    'aprofundamento' => $dadosModulo['aprofundamento'],
                    'referencias'    => $dadosModulo['referencias'],
                ]);

                foreach ($dadosModulo['licoes'] as $j => $dadosLicao) {
                    $licao = $modulo->licoes()->create([
                        'titulo'  => $dadosLicao['titulo'],
                        'teoria'  => $dadosLicao['teoria'],
                        'pratica' => $dadosLicao['pratica'],
                        'ordem'   => $j + 1,
                    ]);

                    foreach ($dadosLicao['exercicios'] as $dadosExercicio) {
                        // No JSON os nomes já são iguais aos das colunas, então dá para passar direto.
                        $licao->exercicios()->create($dadosExercicio);
                    }
                }
            }
        }
    }
}
