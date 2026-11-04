# Fundamentos de testes
> Por que testar, tipos de teste e os primeiros testes automatizados.

## Projeto
**Testando a calculadora de notas:** escreva uma função que calcula a média e a situação de um aluno (aprovado, recuperação, reprovado) e cubra-a com pelo menos 10 testes em pytest ou Jest, incluindo casos extremos (nota 0, 10, exatamente 7, lista vazia, nota inválida). Meça a cobertura e explique quais casos você escolheu e por quê.

## Por que testar software
### Teoria
Testar é executar o software com a intenção de **encontrar defeitos** e ganhar confiança de que ele faz o que deveria. Alguns termos:
- **Erro (engano):** a ação humana errada.
- **Defeito (bug):** o problema que ficou no código.
- **Falha:** o comportamento errado observado quando o defeito é executado.

Princípios importantes do ISTQB:
- Testes mostram a **presença** de defeitos, não provam sua ausência.
- **Testar tudo é impossível**: é preciso escolher bem os casos.
- **Testar cedo** economiza: corrigir um defeito em produção custa muito mais do que na escrita do código.
- Defeitos se **agrupam** em poucas partes do sistema.

Testes **manuais** são feitos por pessoas; testes **automatizados** são código que testa código e podem rodar a cada mudança.

### Prática
Um teste automatizado tem três partes, o padrão **AAA** (*Arrange, Act, Assert*):

```python
# arquivo: notas.py
def media(notas):
    return sum(notas) / len(notas)

# arquivo: test_notas.py  (rode com: pytest)
from notas import media

def test_media_de_tres_notas():
    notas = [6, 7, 8]          # Arrange: prepara os dados
    resultado = media(notas)   # Act: executa
    assert resultado == 7      # Assert: verifica
```

O que acontece com `media([])`? Um bom teste revela que a função quebra com uma lista vazia (divisão por zero).

### Questão
O que um teste que passa garante?
- [ ] Que o software não tem nenhum defeito
- [x] Que, para aquele caso, o comportamento foi o esperado
- [ ] Que o código está bonito
- [ ] Que não é preciso testar de novo
> Testes mostram a presença de defeitos, não provam sua ausência.

### Questão
Qual a diferença entre defeito e falha?
- [ ] São sinônimos
- [x] O defeito está no código; a falha é o comportamento errado observado quando ele é executado
- [ ] A falha está no código; o defeito é visto pelo usuário
- [ ] Defeitos só existem em hardware
> Um defeito pode existir por muito tempo sem causar falha, até ser executado.

### Questão
O que significa o padrão AAA num teste?
- [ ] Alta, Alta, Alta prioridade
- [x] Arrange, Act, Assert: preparar, executar e verificar
- [ ] Automatizar, Analisar, Aprovar
- [ ] Um tipo de banco de dados
> Seguir AAA deixa os testes fáceis de ler.

## A pirâmide de testes
### Teoria
Testes têm níveis diferentes:
- **Unidade:** testam uma função ou classe isolada. Rápidos e numerosos.
- **Integração:** testam partes conversando (código e banco de dados, duas APIs).
- **Ponta a ponta (E2E):** simulam o usuário usando o sistema inteiro, por exemplo clicando no navegador. Mais lentos e frágeis.
- **Aceitação:** confirmam que o sistema atende aos requisitos do cliente.

A **pirâmide de testes**, de Mike Cohn, recomenda muitos testes de unidade na base, menos de integração no meio e poucos E2E no topo. Inverter a pirâmide (muitos testes E2E e poucos de unidade) deixa a suíte lenta e difícil de manter.

Testes também podem ser **funcionais** (o quê) ou **não funcionais** (desempenho, segurança, usabilidade, acessibilidade).

### Prática
O mesmo recurso testado em três níveis:

```
Unidade:     calcular_desconto(100, 0.1) == 90
Integração:  salvar um pedido com desconto no banco e ler de volta
Ponta a ponta: abrir o site, adicionar um produto ao carrinho,
               aplicar o cupom e ver o total de R$ 90,00 na tela
```

Ferramentas: **pytest**, **JUnit**, **Jest/Vitest** (unidade e integração), **Playwright** e **Cypress** (ponta a ponta).

### Questão
Segundo a pirâmide de testes, qual tipo deve existir em maior quantidade?
- [x] Testes de unidade
- [ ] Testes de ponta a ponta
- [ ] Testes manuais
- [ ] Testes de aceitação
> São rápidos, baratos e apontam exatamente onde está o problema.

### Questão
Qual ferramenta é usada para testes de ponta a ponta no navegador?
- [ ] pytest
- [x] Playwright
- [ ] Git
- [ ] MySQL
> Playwright e Cypress automatizam cliques e digitação num navegador real.

### Questão
Um teste que verifica se o código salva corretamente no banco de dados é de qual nível?
- [ ] Unidade
- [x] Integração
- [ ] Usabilidade
- [ ] Desempenho
> Ele testa a conversa entre o código e outro componente.

## Escolhendo bons casos de teste
### Teoria
Como testar tudo é impossível, usamos técnicas para escolher poucos casos que encontrem muitos defeitos:

- **Partição de equivalência:** divide as entradas em grupos que o sistema trata igual e testa um de cada grupo. Para "idade entre 18 e 60": um valor abaixo, um dentro e um acima.
- **Análise de valor limite:** defeitos adoram as fronteiras. Teste 17, 18, 60 e 61.
- **Tabela de decisão:** combinações de condições e as ações esperadas.
- **Teste baseado em erros comuns:** listas vazias, nulos, zero, números negativos, textos com acentos e emojis, valores enormes.

Isso é teste **caixa-preta** (sem olhar o código). No teste **caixa-branca**, olhamos o código para cobrir caminhos e condições.

### Prática
```python
import pytest

def situacao(nota):
    if not 0 <= nota <= 10:
        raise ValueError("nota inválida")
    if nota >= 7:
        return "aprovado"
    if nota >= 5:
        return "recuperação"
    return "reprovado"

@pytest.mark.parametrize("nota, esperado", [
    (0, "reprovado"), (4.9, "reprovado"),       # partição "reprovado" e seu limite
    (5, "recuperação"), (6.9, "recuperação"),   # limites da recuperação
    (7, "aprovado"), (10, "aprovado"),          # limites do aprovado
])
def test_situacao(nota, esperado):
    assert situacao(nota) == esperado

@pytest.mark.parametrize("nota", [-1, 10.1])
def test_nota_invalida(nota):
    with pytest.raises(ValueError):
        situacao(nota)
```

### Questão
Para a regra "aprovado com nota maior ou igual a 7", quais valores a análise de limite sugere?
- [ ] 0 e 10
- [x] 6,9 e 7
- [ ] 3 e 8
- [ ] Apenas 7,5
> Os defeitos aparecem com frequência exatamente na fronteira.

### Questão
O que é partição de equivalência?
- [ ] Dividir o código em arquivos
- [x] Agrupar entradas tratadas da mesma forma e testar um representante de cada grupo
- [ ] Testar todas as entradas possíveis
- [ ] Dividir a equipe de testes
> Isso reduz muito o número de testes sem perder cobertura dos comportamentos.

### Questão
O que caracteriza o teste caixa-preta?
- [ ] Testar só à noite
- [x] Testar pelo comportamento esperado, sem olhar a implementação
- [ ] Testar apenas o banco de dados
- [ ] Ler todo o código-fonte
> Caixa-branca, ao contrário, usa o código para escolher os casos.

# Testes no dia a dia do desenvolvimento [intermediario]
> TDD, dublês de teste, cobertura e testes de integração.

## Projeto
**Carrinho com TDD:** desenvolva um carrinho de compras usando TDD do começo ao fim: adicionar item, remover, calcular total, aplicar cupom e frete grátis acima de um valor. Use um dublê para simular o serviço de frete e registre no README o ciclo vermelho-verde-refatorar de cada funcionalidade.

## TDD: desenvolvimento guiado por testes
### Teoria
No **TDD** (*Test-Driven Development*), popularizado por **Kent Beck**, o teste vem antes do código, num ciclo curto:

1. **Vermelho:** escreva um teste para um comportamento que ainda não existe. Ele falha.
2. **Verde:** escreva o mínimo de código para o teste passar.
3. **Refatorar:** melhore o código (nomes, duplicações) com os testes garantindo que nada quebrou.

Benefícios: o código nasce testável, o projeto fica mais simples (você só escreve o que é necessário) e os testes viram uma especificação viva. O TDD exige prática: no começo parece mais lento, mas reduz o tempo gasto depurando.

### Prática
```javascript
// carrinho.test.js  (Jest ou Vitest)
import { Carrinho } from "./carrinho.js";

test("carrinho novo tem total zero", () => {
  expect(new Carrinho().total()).toBe(0);
});

test("soma o preço vezes a quantidade", () => {
  const c = new Carrinho();
  c.adicionar({ nome: "Sardinha", preco: 5 }, 3);
  expect(c.total()).toBe(15);
});

// carrinho.js — código mínimo para os testes passarem
export class Carrinho {
  #itens = [];
  adicionar(produto, quantidade) { this.#itens.push({ produto, quantidade }); }
  total() { return this.#itens.reduce((s, i) => s + i.produto.preco * i.quantidade, 0); }
}
```

### Questão
Qual é a ordem do ciclo do TDD?
- [ ] Código, teste, deploy
- [x] Vermelho (teste falhando), verde (código mínimo), refatorar
- [ ] Refatorar, testar, codificar
- [ ] Testar só no final
> O teste vem antes do código.

### Questão
Por que escrever só o mínimo de código no passo "verde"?
- [ ] Para economizar teclado
- [x] Para não criar código que nenhum teste exige, mantendo o projeto simples
- [ ] Porque o compilador exige
- [ ] Para o teste falhar de novo
> Funcionalidades novas entram por novos testes.

### Questão
O que o passo de refatorar garante no TDD?
- [ ] Novas funcionalidades
- [x] Melhorias na estrutura do código, com os testes confirmando que o comportamento não mudou
- [ ] Que os testes sejam apagados
- [ ] Mais bugs
> Refatorar sem testes é arriscado; com eles, é seguro.

## Dublês de teste: mocks e stubs
### Teoria
Testes de unidade devem ser **rápidos e isolados**. Mas e quando o código depende de banco de dados, APIs externas, data atual ou envio de email? Usamos **dublês de teste** (o nome vem dos dublês de cinema):

- **Stub:** devolve respostas prontas ("o frete custa R$ 10").
- **Mock:** além de responder, verifica se foi chamado do jeito certo ("o email foi enviado uma vez?").
- **Fake:** implementação simplificada que funciona (um banco em memória).
- **Spy:** registra as chamadas a um objeto real.

Para usar dublês, o código precisa permitir a troca da dependência, por exemplo recebendo-a como parâmetro (**injeção de dependência**). Cuidado com o excesso: testes com mocks demais testam a implementação, não o comportamento, e quebram a cada refatoração.

### Prática
```python
from unittest.mock import Mock

class Pedido:
    def __init__(self, servico_frete, enviador_email):
        self.frete = servico_frete
        self.email = enviador_email

    def fechar(self, total, cep):
        valor_frete = 0 if total >= 100 else self.frete.calcular(cep)
        self.email.enviar("Pedido confirmado!")
        return total + valor_frete

def test_frete_gratis_acima_de_100():
    frete = Mock()                       # stub/mock do serviço externo
    email = Mock()
    pedido = Pedido(frete, email)
    assert pedido.fechar(150, "01000-000") == 150
    frete.calcular.assert_not_called()   # nem consultou o frete
    email.enviar.assert_called_once()    # mas enviou o email

def test_cobra_frete_abaixo_de_100():
    frete = Mock()
    frete.calcular.return_value = 12     # stub: resposta pronta
    assert Pedido(frete, Mock()).fechar(50, "01000-000") == 62
```

### Questão
Para que servem os dublês de teste?
- [ ] Para deixar os testes mais lentos
- [x] Para substituir dependências externas e manter o teste rápido e isolado
- [ ] Para substituir o programador
- [ ] Para apagar o banco de dados
> Assim o teste não depende de rede, banco ou serviços de terceiros.

### Questão
Qual a diferença principal entre stub e mock?
- [ ] Nenhuma
- [x] O stub só devolve respostas prontas; o mock também verifica como foi chamado
- [ ] O mock é sempre um banco de dados
- [ ] O stub só existe em Java
> Mocks verificam interações; stubs fornecem dados.

### Questão
O que facilita o uso de dublês no código?
- [ ] Variáveis globais
- [x] Receber as dependências por parâmetro (injeção de dependência)
- [ ] Criar as dependências com new dentro de cada função
- [ ] Não usar funções
> Dependências injetadas podem ser trocadas por dublês nos testes.

## Cobertura e testes de integração
### Teoria
**Cobertura de código** mede quanto do código foi executado pelos testes:
- **De linhas (statements):** quais linhas rodaram.
- **De ramos (branches):** se cada lado de cada `if` foi testado. É mais rigorosa.

Ferramentas: `pytest --cov`, `jest --coverage`, JaCoCo (Java). Cuidado: cobertura alta **não garante** bons testes. Um teste sem `assert` executa linhas sem verificar nada. Use a cobertura para encontrar o que **não** foi testado, não como meta absoluta.

Testes de **integração** verificam partes reais juntas: o código com um banco de dados de verdade (em contêiner, com **Testcontainers**), ou a API completa recebendo requisições HTTP. Eles pegam erros que os testes de unidade não veem: SQL errado, configurações e serialização.

### Prática
```python
# test_api.py — teste de integração de uma API Flask com banco SQLite em memória
import pytest
from app import criar_app

@pytest.fixture
def cliente():
    app = criar_app(banco="sqlite:///:memory:")
    with app.test_client() as cliente:
        yield cliente

def test_criar_e_listar_tarefa(cliente):
    resposta = cliente.post("/tarefas", json={"titulo": "Estudar testes"})
    assert resposta.status_code == 201
    lista = cliente.get("/tarefas").get_json()
    assert lista[0]["titulo"] == "Estudar testes"
```

Rode com cobertura: `pytest --cov=app --cov-branch`.

### Questão
Cobertura de 100% garante que o software não tem defeitos?
- [ ] Sim
- [x] Não; o código pode ser executado sem que o resultado seja verificado corretamente
- [ ] Sim, se for cobertura de linhas
- [ ] Só em Python
> Cobertura mede o que rodou, não se as verificações são boas.

### Questão
Qual cobertura é mais rigorosa?
- [ ] De linhas
- [x] De ramos (branches)
- [ ] De arquivos
- [ ] De comentários
> Ela exige testar os dois lados de cada decisão.

### Questão
Que tipo de erro um teste de integração com banco real pega e um de unidade com mock não pega?
- [ ] Erro de lógica numa soma
- [x] Uma consulta SQL escrita errada
- [ ] Um nome de variável feio
- [ ] Um comentário desatualizado
> O mock nunca executa o SQL de verdade.

# Qualidade em escala [avancado]
> Testes ponta a ponta, testes não funcionais e qualidade contínua.

## Projeto
**Suíte completa no CI:** para uma aplicação web sua (pode ser a lista de tarefas), crie testes de unidade, integração e um fluxo ponta a ponta com Playwright, um teste de carga simples com k6 e uma checagem de acessibilidade com axe. Faça tudo rodar no GitHub Actions a cada pull request e bloqueie o merge se algo falhar.

## Testes ponta a ponta
### Teoria
Testes **ponta a ponta (E2E)** controlam um navegador real e simulam o usuário: abrir páginas, clicar, digitar e verificar o que aparece. São os que mais se parecem com o uso real, mas são lentos e podem ficar **instáveis** (*flaky*): falhar às vezes sem nenhuma mudança no código.

Boas práticas:
- Teste **jornadas críticas** (cadastro, login, compra), não cada detalhe.
- Selecione elementos como o usuário os vê: pelo papel e texto (`getByRole("button", { name: "Entrar" })`), não por classes CSS que mudam.
- Evite esperas fixas (`sleep`); use esperas automáticas pelo elemento.
- Cada teste deve preparar seus próprios dados e não depender da ordem dos outros.

### Prática
```javascript
// tests/login.spec.js  (Playwright)
import { test, expect } from "@playwright/test";

test("usuário entra e vê o painel", async ({ page }) => {
  await page.goto("http://localhost/technopenguin-Website/login.php");
  await page.getByLabel("Email ou @").fill("usuario_teste");
  await page.getByLabel("Senha").fill("PenguinDev2026!");
  await page.getByRole("button", { name: "Entrar" }).click();
  await expect(page.getByRole("heading", { name: /Suas linguagens/ })).toBeVisible();
});
```

Rode com `npx playwright test`; o modo `--ui` mostra cada passo.

### Questão
Por que selecionar elementos pelo papel e pelo texto visível?
- [ ] Porque é mais curto
- [x] Porque reflete como o usuário encontra o elemento e não quebra quando o CSS muda
- [ ] Porque classes CSS não existem
- [ ] Porque é obrigatório no Playwright
> Seletores frágeis são a principal causa de testes E2E instáveis.

### Questão
O que é um teste "flaky"?
- [ ] Um teste muito rápido
- [x] Um teste que às vezes passa e às vezes falha sem mudança no código
- [ ] Um teste sem assert
- [ ] Um teste de unidade
> Testes instáveis destroem a confiança na suíte e devem ser corrigidos.

### Questão
O que os testes E2E devem priorizar?
- [ ] Cada cor e margem da página
- [x] As jornadas críticas do usuário
- [ ] Testar o banco de dados diretamente
- [ ] Substituir todos os testes de unidade
> São caros, então focamos no que mais importa para o negócio.

## Testes não funcionais
### Teoria
Funcionar não basta: o sistema precisa ser rápido, seguro e acessível.

- **Desempenho:** **testes de carga** (uso esperado), de **estresse** (além do limite, para ver como quebra) e de **pico** (picos repentinos, como na Black Friday). Medimos o tempo de resposta em percentis: o **p95** é o tempo em que 95% das requisições terminam. Ferramentas: **k6**, JMeter, Locust.
- **Segurança:** análise estática (SAST), de dependências e dinâmica (DAST, como o OWASP ZAP).
- **Acessibilidade:** verificações automáticas com **axe** e o Lighthouse, mais testes manuais com teclado e leitor de tela.
- **Usabilidade:** observar pessoas reais usando o sistema.

### Prática
```javascript
// carga.js — rode com: k6 run carga.js
import http from "k6/http";
import { check, sleep } from "k6";

export const options = {
  vus: 50,                 // 50 usuários virtuais
  duration: "1m",
  thresholds: {
    http_req_duration: ["p(95)<500"],   // 95% abaixo de 500 ms
    http_req_failed: ["rate<0.01"],     // menos de 1% de erros
  },
};

export default function () {
  const resposta = http.get("http://localhost/technopenguin-Website/index.html");
  check(resposta, { "status 200": (r) => r.status === 200 });
  sleep(1);
}
```

### Questão
O que significa "p95 = 500 ms"?
- [ ] A média foi 500 ms
- [x] 95% das requisições terminaram em até 500 ms
- [ ] 95 usuários esperaram 500 ms
- [ ] O servidor ficou fora do ar por 500 ms
> Percentis mostram a experiência da maioria, inclusive os casos lentos que a média esconde.

### Questão
Qual a diferença entre teste de carga e de estresse?
- [ ] Nenhuma
- [x] Carga simula o uso esperado; estresse vai além do limite para ver como o sistema falha
- [ ] Estresse testa a equipe
- [ ] Carga só funciona em Java
> O teste de estresse revela o ponto de ruptura e se o sistema se recupera.

### Questão
Qual ferramenta ajuda a encontrar problemas de acessibilidade automaticamente?
- [ ] k6
- [x] axe
- [ ] Git
- [ ] Docker
> axe e Lighthouse apontam problemas como falta de alt e contraste baixo.

## Qualidade contínua
### Teoria
Qualidade é responsabilidade de toda a equipe, o tempo todo:

- **Testes no CI:** cada pull request roda a suíte; nada entra na main com teste falhando.
- **Revisão de código:** encontra defeitos que testes não pegam e espalha conhecimento.
- **Análise estática e linters:** pegam erros e más práticas antes de executar.
- **Testes de regressão:** garantem que bugs corrigidos não voltam (todo bug corrigido ganha um teste).
- **Teste exploratório:** pessoas investigando o sistema com criatividade, onde scripts não chegam.
- **Monitoramento em produção:** alguns problemas só aparecem com usuários reais; observabilidade e lançamentos graduais (canary) são a última rede de proteção.

O **teste de mutação** (ferramentas como Stryker e mutmut) altera o código de propósito; se os testes continuam passando, eles são fracos.

### Prática
Fluxo recomendado ao corrigir um bug:

```
1. Reproduza o bug e escreva um teste que falha por causa dele (vermelho)
2. Corrija o código até o teste passar (verde)
3. Rode a suíte inteira para garantir que nada mais quebrou
4. Abra o PR: o CI roda testes, linters e cobertura
5. Um colega revisa; o teste novo documenta o bug para sempre
```

Teste de mutação em Python: `mutmut run` e depois `mutmut results`.

### Questão
O que fazer ao corrigir um bug, segundo boas práticas?
- [ ] Corrigir e não mexer nos testes
- [x] Primeiro escrever um teste que reproduz o bug, depois corrigir
- [ ] Apagar os testes que falham
- [ ] Esperar o usuário reclamar de novo
> O teste de regressão impede que o bug volte.

### Questão
O que o teste de mutação avalia?
- [ ] A velocidade do servidor
- [x] Se os testes detectam pequenas alterações propositais no código
- [ ] A ortografia dos comentários
- [ ] O tamanho do banco
> Se uma mutação sobrevive, há um comportamento que nenhum teste verifica.

### Questão
Por que testes exploratórios continuam importantes mesmo com automação?
- [ ] Porque automação não existe
- [x] Porque pessoas encontram problemas inesperados que scripts não foram programados para procurar
- [ ] Porque são mais rápidos que testes de unidade
- [ ] Porque substituem a revisão de código
> Automação verifica o esperado; exploração descobre o inesperado.
