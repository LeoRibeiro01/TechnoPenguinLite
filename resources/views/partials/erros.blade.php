{{--
  ERROS DE VALIDAÇÃO - pedaço de tela reutilizável.
  Quando $request->validate() falha no controller, o Laravel volta para o formulário
  e preenche $errors. Aqui mostramos todos eles numa lista.
  Uso:  @include('partials.erros')
--}}
@if ($errors->any())
  <div class="erro">
    <ul>
      @foreach ($errors->all() as $erro)
        <li>{{ $erro }}</li>
      @endforeach
    </ul>
  </div>
@endif
