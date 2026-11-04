<?php
declare(strict_types=1);

require_once __DIR__ . '/app/web.php';

use TechnoPenguin\Support\Texto;

$tituloPagina = 'Termos de Uso';
$conteudo = Texto::html(<<<'MD'
Bem-vindo ao TechnoPenguin! Ao criar uma conta, você concorda com estes termos.

**1. O serviço**

O TechnoPenguin é uma plataforma educacional gratuita para aprender programação, com trilhas, lições, questões, certificados, iglus (grupos de estudo) e cursos criados pela comunidade.

**2. Sua conta**

- Use dados verdadeiros. Menores de 12 anos precisam da autorização de um responsável.
- Você é responsável pela sua senha. Não compartilhe sua conta.
- Seu @ precisa ser único e não pode imitar outra pessoa.

**3. Convivência**

Nos iglus, no chat e nas perguntas da comunidade, não é permitido:

- ofender, ameaçar, discriminar ou assediar qualquer pessoa;
- publicar dados pessoais de outras pessoas;
- enviar spam, golpes, links maliciosos ou conteúdo impróprio;
- se passar por professor ou por outro usuário.

Donos e admins de iglus podem apagar mensagens e remover membros. Contas que desrespeitarem estas regras podem ser suspensas.

**4. Chat dos iglus**

O chat de texto é exclusivo para maiores de 18 anos e pode ser desligado pelos admins de cada iglu.

**5. Professores e conteúdo da comunidade**

Professores são responsáveis pelo conteúdo dos cursos que publicam e só podem publicar material próprio ou que tenham direito de usar. O TechnoPenguin pode despublicar conteúdo que viole estes termos.

**6. Certificados**

Certificados atestam a conclusão das atividades na plataforma, com a carga horária estimada, e podem ser verificados pelo código. Eles não substituem diplomas de cursos regulamentados.

**7. Pontos e vestuário**

Pontos e peças de vestuário são virtuais, não têm valor em dinheiro e não podem ser trocados fora da plataforma.

**8. Privacidade**

O tratamento dos seus dados segue a nossa Política de Privacidade e a LGPD. Você pode baixar ou excluir seus dados a qualquer momento em Minha conta.
MD);
require __DIR__ . '/app/Views/documento.php';
