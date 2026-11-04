<?php
/**
 * Campos do formulário de iglu (criar e editar), com pré-visualização ao vivo do cartão.
 * Usa $igluForm (nome, descricao, cor, icone, aberto, chat, trilhas => [slug => obrigatória]).
 */

use TechnoPenguin\Controllers\IgluController;
use TechnoPenguin\Support\Catalogo;
use TechnoPenguin\Support\Trilhas;

$igluForm = ($igluForm ?? []) + [
    'nome' => '', 'descricao' => '', 'cor' => '#5c7aa5', 'icone' => IgluController::ICONES[0],
    'aberto' => true, 'chat' => true, 'trilhas' => [],
];
$coresIglu = ['#5c7aa5', '#2897e6', '#00897b', '#43a047', '#f5a623', '#e5533d', '#e91e63', '#8e24aa', '#546e7a', '#1a2e46'];
$grupos = [
    'Fundamentos' => Catalogo::trilhasPorTipo('base'),
    'Linguagens' => Catalogo::trilhasPorTipo('linguagem'),
    'Assuntos' => Catalogo::trilhasPorTipo('assunto'),
    'Comunidade' => array_column(Trilhas::comunidade(conexao()), null, 'slug'),
];
?>
<div class="form-iglu__grade">
  <div class="form-iglu__campos">
    <label class="campo">
      <span>Nome do iglu</span>
      <input type="text" name="nome" value="<?= e($igluForm['nome']) ?>" required minlength="3" maxlength="60" placeholder="Ex.: Pinguins do Python" data-previa="nome">
    </label>
    <label class="campo">
      <span>Descrição</span>
      <textarea name="descricao" required minlength="5" maxlength="400" rows="3" placeholder="O que vocês vão estudar juntos? Qual o ritmo do grupo?" data-previa="descricao"><?= e($igluForm['descricao']) ?></textarea>
    </label>

    <fieldset class="seletor-icone">
      <legend>Ícone</legend>
      <?php foreach (IgluController::ICONES as $icone): ?>
        <label><input type="radio" name="icone" value="<?= e($icone) ?>" <?= $icone === $igluForm['icone'] ? 'checked' : '' ?>><span><?= e($icone) ?></span></label>
      <?php endforeach; ?>
    </fieldset>

    <fieldset class="cores">
      <legend>Cor do iglu</legend>
      <div class="cores__lista">
        <?php foreach ($coresIglu as $cor): ?>
          <label class="cor"><input type="radio" name="cor" value="<?= $cor ?>" <?= $cor === $igluForm['cor'] ? 'checked' : '' ?>><span class="cor__amostra" style="--cor: <?= $cor ?>"></span></label>
        <?php endforeach; ?>
      </div>
    </fieldset>

    <div class="interruptores">
      <label class="interruptor">
        <input type="checkbox" name="aberto" value="1" <?= $igluForm['aberto'] ? 'checked' : '' ?>>
        <span><strong>Aberto para pedidos</strong><small>Qualquer pinguim pode pedir para entrar. Desligado: só por convite.</small></span>
      </label>
      <label class="interruptor">
        <input type="checkbox" name="chat" value="1" <?= $igluForm['chat'] ? 'checked' : '' ?>>
        <span><strong>Chat de texto</strong><small>Disponível só para membros maiores de 18 anos.</small></span>
      </label>
    </div>
  </div>

  <aside class="form-iglu__previa" aria-label="Pré-visualização">
    <p class="campo__ajuda">Pré-visualização</p>
    <div class="cartao-iglu cartao-iglu--previa" id="previa-iglu" style="--cor-iglu: <?= e($igluForm['cor']) ?>">
      <span class="iglu-emblema" aria-hidden="true"><?= e($igluForm['icone']) ?></span>
      <div>
        <strong data-alvo="nome"><?= e($igluForm['nome'] ?: 'Nome do iglu') ?></strong>
        <small>1 membro · 0 dias de gelo</small>
        <p data-alvo="descricao"><?= e($igluForm['descricao'] ?: 'A descrição aparece aqui.') ?></p>
      </div>
    </div>
  </aside>
</div>

<fieldset class="trilhas-iglu">
  <legend>Linguagens e assuntos do iglu</legend>
  <p class="campo__ajuda">Toque para incluir. Use o cadeado para tornar <strong>obrigatória</strong>: ela será adicionada ao perfil de quem entrar no iglu.</p>
  <?php foreach ($grupos as $grupo => $trilhas):
      if ($trilhas === []) {
          continue;
      }
  ?>
    <h3 class="trilhas-iglu__grupo"><?= e($grupo) ?></h3>
    <div class="trilhas-iglu__lista">
      <?php foreach ($trilhas as $slug => $trilha):
          $incluida = array_key_exists($slug, $igluForm['trilhas']);
      ?>
        <div class="trilha-iglu <?= $incluida ? 'trilha-iglu--ativa' : '' ?>">
          <label class="trilha-iglu__incluir">
            <input type="checkbox" name="trilhas[]" value="<?= e($slug) ?>" <?= $incluida ? 'checked' : '' ?>>
            <?= iconeTrilha($slug) ?> <span><?= e($trilha['titulo']) ?></span>
          </label>
          <label class="trilha-iglu__obrigatoria" title="Tornar obrigatória">
            <input type="checkbox" name="obrigatorias[]" value="<?= e($slug) ?>" <?= $incluida && $igluForm['trilhas'][$slug] ? 'checked' : '' ?>>
            <span aria-hidden="true">🔒</span><span class="sr-only">Obrigatória</span>
          </label>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</fieldset>
<script>
  (() => {
    document.querySelectorAll('.trilha-iglu').forEach((linha) => {
      const [incluir, obrigatoria] = linha.querySelectorAll('input');
      const pintar = () => linha.classList.toggle('trilha-iglu--ativa', incluir.checked);
      obrigatoria.addEventListener('change', () => { if (obrigatoria.checked) incluir.checked = true; pintar(); });
      incluir.addEventListener('change', () => { if (!incluir.checked) obrigatoria.checked = false; pintar(); });
    });
    const previa = document.querySelector('#previa-iglu');
    const form = previa.closest('form');
    form.addEventListener('input', (evento) => {
      const campo = evento.target;
      if (campo.dataset.previa) {
        previa.querySelector(`[data-alvo="${campo.dataset.previa}"]`).textContent = campo.value || campo.placeholder;
      }
      if (campo.name === 'cor') previa.style.setProperty('--cor-iglu', campo.value);
      if (campo.name === 'icone') previa.querySelector('.iglu-emblema').textContent = campo.value;
    });
  })();
</script>
