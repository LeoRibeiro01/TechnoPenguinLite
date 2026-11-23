{{--
  CARTÃO DE UM CURSO com a barra de progresso (RF16). Usado no painel.
  Uso:  @include('partials.curso-card', ['curso' => $curso])
  O controller (PainelController) já calculou $curso->feitas, $curso->licoes_count e $curso->porcentagem.
--}}
<a class="cartao curso" href="{{ route('cursos.show', $curso) }}">
  <span class="icone" style="background: {{ $curso->cor }}">{{ $curso->sigla }}</span>
  <h3>{{ $curso->titulo }}</h3>
  <p class="suave">{{ $curso->descricao }}</p>
  @if ($curso->professor)
    <small class="suave">por {{ '@' . $curso->professor->nome_usuario }}</small>
  @endif
  <div class="barra"><div style="width: {{ $curso->porcentagem }}%"></div></div>
  <small>{{ $curso->feitas }} de {{ $curso->licoes_count }} lições ({{ $curso->porcentagem }}%)</small>
</a>
