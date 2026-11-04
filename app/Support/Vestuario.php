<?php
declare(strict_types=1);

namespace TechnoPenguin\Support;

/**
 * Catálogo do vestuário. Todas as imagens têm 520x1024 e já vêm posicionadas sobre o pinguim,
 * então cada item é só uma camada empilhada. Os itens com preço são exclusivos e custam pontos.
 */
final class Vestuario
{
    /** Ordem de empilhamento, de baixo para cima. */
    public const CAMADAS = [
        'roupa' => 'Corpo',
        'pescoco' => 'Pescoço',
        'oculos' => 'Óculos',
        'chapeu' => 'Cabeça',
    ];

    /** slug => [nome, camada, imagem, filtro CSS, preço em pontos]. Preço 0 é grátis. */
    public const ITENS = [
        'bone' => ['Boné', 'chapeu', 'bone', '', 0],
        'cartola' => ['Cartola clássica', 'chapeu', 'cartola', '', 0],
        'chapeu-festa' => ['Chapéu de festa', 'chapeu', 'chapeu_festa', '', 40],
        'chapeu-bruxo' => ['Chapéu de feiticeiro', 'chapeu', 'chapeu_bruxo', '', 80],

        'oculos-leitura' => ['Óculos de leitura', 'oculos', 'oculos_leitura', '', 0],
        'oculos-sol' => ['Óculos de sol', 'oculos', 'oculos_sol', '', 30],

        'gravata-borboleta' => ['Gravata borboleta', 'pescoco', 'gravata_borboleta', '', 0],
        'gravata-longa' => ['Gravata longa', 'pescoco', 'gravata_longa', '', 0],
        'corrente' => ['Corrente', 'pescoco', 'corrente', '', 100],

        'camisa-polo' => ['Camisa polo', 'roupa', 'camisa_polo', '', 0],
        'moletom' => ['Moletom', 'roupa', 'moletom', '', 0],
        'terno' => ['Terno', 'roupa', 'terno', '', 120],
    ];

    /** O Teco, mascote que guia o questionário inicial, usa terno e cartola. */
    public const TECO = ['roupa' => 'terno', 'chapeu' => 'cartola'];

    public static function item(string $slug): ?array
    {
        $dados = self::ITENS[$slug] ?? null;
        if ($dados === null) {
            return null;
        }
        [$nome, $camada, $imagem, $filtro, $preco] = $dados;
        $imagem = 'assets/imgs/vestuario/' . $imagem . '.png';
        return compact('slug', 'nome', 'camada', 'imagem', 'filtro', 'preco');
    }

    public static function gratis(string $slug): bool
    {
        return (self::ITENS[$slug][4] ?? null) === 0;
    }
}
