<?php

/*
 * MENSAGENS DE VALIDAÇÃO EM PORTUGUÊS
 * Quando $request->validate([...]) encontra um erro, o Laravel busca a mensagem aqui
 * (porque APP_LOCALE=pt_BR no .env). ":attribute" vira o nome do campo (lista "attributes" no fim).
 * Se usar uma regra nova que não está aqui, a mensagem aparece como "validation.nome_da_regra":
 * é só adicionar a linha.
 */

return [
    'required'  => 'Preencha o campo :attribute.',
    'email'     => 'Digite um e-mail válido.',
    'unique'    => 'Este :attribute já está em uso.',
    'exists'    => 'O :attribute escolhido não existe.',
    'confirmed' => 'A confirmação de :attribute não é igual.',
    'in'        => 'Escolha uma opção válida em :attribute.',
    'integer'   => 'O campo :attribute deve ser um número inteiro.',
    'image'     => 'O arquivo deve ser uma imagem.',
    'mimes'     => 'A imagem deve ser do tipo: :values.',
    'regex'     => 'O formato de :attribute é inválido.',
    'current_password' => 'A senha atual está errada.',
    'between' => [
        'numeric' => 'O campo :attribute deve ficar entre :min e :max.',
    ],
    'min' => [
        'string'  => 'O campo :attribute precisa ter pelo menos :min caracteres.',
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
    ],
    'max' => [
        'string'  => 'O campo :attribute pode ter no máximo :max caracteres.',
        'numeric' => 'O campo :attribute deve ser no máximo :max.',
        'file'    => 'O arquivo pode ter no máximo :max KB.',
    ],

    // Nome "bonito" de cada campo, usado no lugar de :attribute.
    'attributes' => [
        'nome'         => 'nome',
        'nome_usuario' => 'nome de usuário',
        'email'        => 'e-mail',
        'password'     => 'senha',
        'senha_atual'  => 'senha atual',
        'linguagem_id' => 'linguagem',
        'nivel'        => 'nível',
        'meta_diaria'  => 'meta diária',
        'foto'         => 'foto',
        'tipo'         => 'tipo de conta',
        'corpo'        => 'cor da pele',
        'barriga'      => 'cor da barriga',
        'detalhe'      => 'cor do bico e patas',
        'fundo'        => 'cor do fundo',
        'login'        => 'e-mail ou nome de usuário',
        'tamanho_fonte' => 'tamanho do texto',
        // Área do professor
        'titulo'       => 'título',
        'descricao'    => 'descrição',
        'sigla'        => 'sigla',
        'cor'          => 'cor',
        'dificuldade'  => 'dificuldade',
        'teoria'       => 'teoria',
        'pratica'      => 'prática',
        'enunciado'    => 'pergunta',
        'alternativa_a' => 'alternativa A',
        'alternativa_b' => 'alternativa B',
        'alternativa_c' => 'alternativa C',
        'alternativa_d' => 'alternativa D',
        'resposta_correta' => 'resposta correta',
        'explicacao'   => 'explicação',
    ],
];
