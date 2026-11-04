<?php
declare(strict_types=1);

namespace TechnoPenguin\Support;

/**
 * Formatação mínima do conteúdo das lições: parágrafos separados por linha em branco,
 * blocos ```código```, `código` em linha, **negrito** e listas com "- ".
 */
final class Texto
{
    public static function html(string $texto): string
    {
        $partes = preg_split('/^```[a-z+#]*\R(.*?)^```$/ms', str_replace("\r\n", "\n", $texto), -1, PREG_SPLIT_DELIM_CAPTURE);
        $html = '';
        foreach ($partes as $i => $parte) {
            if ($i % 2 === 1) {
                $html .= '<pre class="codigo"><code>' . self::escapar(rtrim($parte, "\n")) . '</code></pre>';
                continue;
            }
            foreach (preg_split('/\n\s*\n/', trim($parte)) as $bloco) {
                if ($bloco === '') {
                    continue;
                }
                // Um bloco pode misturar frases e itens de lista ("Os tipos são:" seguido de "- ...").
                $texto = [];
                $itens = [];
                foreach (explode("\n", $bloco) as $linha) {
                    if (str_starts_with(ltrim($linha), '- ')) {
                        if ($texto !== []) {
                            $html .= '<p>' . implode('<br>', $texto) . '</p>';
                            $texto = [];
                        }
                        $itens[] = '<li>' . self::emLinha(substr(ltrim($linha), 2)) . '</li>';
                        continue;
                    }
                    if ($itens !== []) {
                        $html .= '<ul>' . implode('', $itens) . '</ul>';
                        $itens = [];
                    }
                    $texto[] = self::emLinha($linha);
                }
                $html .= $texto !== [] ? '<p>' . implode('<br>', $texto) . '</p>' : '';
                $html .= $itens !== [] ? '<ul>' . implode('', $itens) . '</ul>' : '';
            }
        }
        return $html;
    }

    private static function emLinha(string $texto): string
    {
        $texto = self::escapar($texto);
        $texto = preg_replace('/`([^`]+)`/', '<code>$1</code>', $texto);
        // Links no formato [texto](https://...); só http(s), já escapados acima.
        $texto = preg_replace('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>', $texto);
        $texto = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $texto);
        // *itálico* (títulos de livros); exige texto colado aos asteriscos para não pegar "a * b".
        return preg_replace('/(?<![\w*])\*(?! )([^*\n]+?)(?<! )\*(?![\w*])/u', '<em>$1</em>', $texto);
    }

    private static function escapar(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    }
}
