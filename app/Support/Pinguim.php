<?php

namespace App\Support;

/*
 * CATÁLOGO DO PINGUIM (avatar e vestuário) - RF08 e RF09
 *
 * Não é uma tabela do banco: são listas fixas (constantes) usadas pelos controllers e pelas views.
 *   - CORES_PADRAO: as cores originais da foto do pinguim
 *   - PALETAS: as cores sugeridas na tela "Meu pinguim" (também dá para escolher qualquer cor)
 *   - CAMADAS: as partes do corpo onde se veste uma peça (uma peça por camada)
 *   - ITENS: as roupas. Preço 0 = grátis; preço > 0 = exclusiva, comprada com pontos.
 *
 * Todas as imagens de roupa têm 520x1024, o mesmo tamanho da foto, e já estão na posição certa.
 * Por isso o avatar é só uma pilha de imagens uma em cima da outra (veja partials/avatar.blade.php).
 */
class Pinguim
{
    const CORES_PADRAO = [
        'corpo'   => '#384048',
        'barriga' => '#ecf0f0',
        'detalhe' => '#ec9038',
        'fundo'   => '#00bcd4',
    ];

    // parte => [cor => nome da cor]
    const PALETAS = [
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

    // Nome e explicação de cada parte, para a tela "Meu pinguim".
    const PARTES = [
        'corpo'   => ['Pele', 'As penas da cabeça, das costas e das asas.'],
        'barriga' => ['Barriga e rosto', 'A parte clara da frente do pinguim.'],
        'detalhe' => ['Bico e patas', 'O toque de cor nas pontas.'],
        'fundo'   => ['Fundo', 'A cor atrás do seu pinguim.'],
    ];

    // Ordem de empilhamento, de baixo para cima. A chave é também o nome da coluna em "users".
    const CAMADAS = [
        'roupa'   => 'Corpo',
        'pescoco' => 'Pescoço',
        'oculos'  => 'Óculos',
        'chapeu'  => 'Cabeça',
    ];

    // código => [nome, camada, arquivo em public/imgs/vestuario, preço em pontos]
    const ITENS = [
        'bone'              => ['Boné', 'chapeu', 'bone', 0],
        'cartola'           => ['Cartola clássica', 'chapeu', 'cartola', 0],
        'chapeu-festa'      => ['Chapéu de festa', 'chapeu', 'chapeu_festa', 40],
        'chapeu-bruxo'      => ['Chapéu de feiticeiro', 'chapeu', 'chapeu_bruxo', 80],

        'oculos-leitura'    => ['Óculos de leitura', 'oculos', 'oculos_leitura', 0],
        'oculos-sol'        => ['Óculos de sol', 'oculos', 'oculos_sol', 30],

        'gravata-borboleta' => ['Gravata borboleta', 'pescoco', 'gravata_borboleta', 0],
        'gravata-longa'     => ['Gravata longa', 'pescoco', 'gravata_longa', 0],
        'corrente'          => ['Corrente', 'pescoco', 'corrente', 100],

        'camisa-polo'       => ['Camisa polo', 'roupa', 'camisa_polo', 0],
        'moletom'           => ['Moletom', 'roupa', 'moletom', 0],
        'terno'             => ['Terno', 'roupa', 'terno', 120],
    ];

    /*
     * Dados de uma peça pelo código, num formato fácil de usar:
     *   Pinguim::item('terno') -> ['codigo' => 'terno', 'nome' => 'Terno', 'camada' => 'roupa',
     *                              'imagem' => 'imgs/vestuario/terno.png', 'preco' => 120]
     * Devolve null se o código não existir (ou for null).
     */
    public static function item($codigo)
    {
        if ($codigo === null || !isset(self::ITENS[$codigo])) {
            return null;
        }
        $dados = self::ITENS[$codigo];
        return [
            'codigo' => $codigo,
            'nome'   => $dados[0],
            'camada' => $dados[1],
            'imagem' => 'imgs/vestuario/' . $dados[2] . '.png',
            'preco'  => $dados[3],
        ];
    }

    // Todas as peças de uma camada (para a tela do vestuário).
    public static function itensDaCamada($camada)
    {
        $lista = [];
        foreach (array_keys(self::ITENS) as $codigo) {
            $item = self::item($codigo);
            if ($item['camada'] === $camada) {
                $lista[] = $item;
            }
        }
        return $lista;
    }
}
