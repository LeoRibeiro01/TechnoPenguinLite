<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Support\Texto;

$tituloPagina = 'Política de Privacidade';
$conteudo = Texto::html(<<<'MD'
Esta política explica, em linguagem simples, quais dados pessoais o TechnoPenguin trata, por quê e como você controla esses dados, de acordo com a **Lei Geral de Proteção de Dados (Lei nº 13.709/2018, LGPD)**.

**1. Quais dados coletamos**

- **Cadastro:** nome, @, email, senha (guardada só como hash bcrypt, nunca em texto), data de nascimento e, para menores de 12 anos, o email do responsável.
- **Uso da plataforma:** trilhas escolhidas, respostas do questionário inicial, progresso e acertos nas lições, XP, pontos, gelo (ofensiva), certificados, conquistas, peças de vestuário e cores do pinguim.
- **Social:** amizades, iglus, mensagens do chat dos iglus e perguntas feitas aos professores.
- **Técnicos:** data do último acesso (para o indicador "online") e tentativas de login com falha (guardadas de forma cifrada por 1 dia, para bloquear ataques).

**2. Para que usamos**

- Criar e manter sua conta e o seu progresso (execução do serviço que você pediu).
- Mostrar seu perfil e sua atividade a amigos e membros dos seus iglus, conforme suas configurações de privacidade.
- Emitir certificados com o seu nome.
- Garantir a segurança (bloqueio de tentativas de login, idade mínima para o chat).

Não vendemos seus dados, não exibimos publicidade e não compartilhamos seus dados com terceiros para marketing.

**3. Crianças e adolescentes**

Os dados de crianças e adolescentes são tratados no seu melhor interesse (art. 14 da LGPD). Menores de 12 anos só usam a plataforma com o consentimento de um responsável. O chat de texto dos iglus é restrito a maiores de 18 anos.

**4. Seus direitos e como exercê-los**

Em **Minha conta** você pode, a qualquer momento:

- **Acessar e levar seus dados:** baixe tudo em um arquivo JSON.
- **Corrigir:** edite nome, @, email e data de nascimento.
- **Controlar a exposição:** escolha quem vê seu perfil, se aparece na busca e se mostra quando está online.
- **Excluir:** apague sua conta e seus dados pessoais. Iglus que você criou passam para outro membro; cursos que você criou como professor ficam sem autor e são despublicados.

**5. Por quanto tempo guardamos**

Enquanto sua conta existir. Ao excluir a conta, os dados pessoais são apagados imediatamente do banco de dados. Certificados excluídos deixam de ser verificáveis.

**6. Segurança**

Senhas com hash bcrypt, proteção contra CSRF em todos os formulários, cookies de sessão HttpOnly e SameSite, consultas parametrizadas contra SQL injection, escape de todo conteúdo exibido contra XSS e bloqueio temporário após várias tentativas de login com falha.

**7. Contato**

Dúvidas sobre seus dados? Fale com a equipe do TechnoPenguin pelo email de contato do projeto. Você também pode procurar a Autoridade Nacional de Proteção de Dados (ANPD).
MD);
require __DIR__ . '/app/Views/documento.php';
