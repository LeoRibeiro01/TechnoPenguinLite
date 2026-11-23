<?php

/*
 * ROTAS - liga cada endereço (URL) a um método de um controller.
 *
 * Formato:  Route::MÉTODO_HTTP('/endereco', [Controller::class, 'metodo'])->name('nome.da.rota');
 *   GET    -> abrir uma página
 *   POST   -> enviar um formulário (criar algo)
 *   PUT    -> enviar um formulário que ATUALIZA algo (no HTML: @method('PUT'))
 *   DELETE -> apagar algo (no HTML: @method('DELETE'))
 *
 * O ->name() permite gerar o link na view com route('nome.da.rota'), sem escrever a URL.
 * Ver todas as rotas no terminal:  php artisan route:list --except-vendor
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\ConquistaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\LicaoController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Professor;   // pasta app/Http/Controllers/Professor (usada como Professor\CursoController)
use App\Http\Controllers\SenhaController;
use App\Http\Controllers\VestuarioController;
use Illuminate\Support\Facades\Route;

// Landing page (resources/views/welcome.blade.php). Route::view mostra uma view direto, sem controller.
Route::view('/', 'welcome')->name('inicio');

// ------------------------------------------------------------------
// Só para VISITANTES (quem NÃO está logado). Middleware 'guest'.
// ------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/cadastro', [AuthController::class, 'formCadastro'])->name('cadastro');   // RF01
    Route::post('/cadastro', [AuthController::class, 'cadastrar']);

    Route::get('/login', [AuthController::class, 'formLogin'])->name('login');            // RF02
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/esqueci-senha', [SenhaController::class, 'formEsqueci'])->name('senha.esqueci');   // RF03
    Route::post('/esqueci-senha', [SenhaController::class, 'enviarLink']);
    // O nome "password.reset" é exigido pelo Laravel para montar o link do e-mail.
    Route::get('/redefinir-senha/{token}', [SenhaController::class, 'formRedefinir'])->name('password.reset');
    Route::post('/redefinir-senha', [SenhaController::class, 'redefinir'])->name('senha.redefinir');
});

// ------------------------------------------------------------------
// Só para quem ESTÁ LOGADO. Middleware 'auth' (se não estiver, vai para a rota 'login').
// ------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/painel', [PainelController::class, 'index'])->name('painel');            // RF16, RF18-20, RF22
    Route::get('/comunidade', [PainelController::class, 'comunidade'])->name('comunidade'); // cursos dos professores

    Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show'); // RF22-24

    Route::get('/licoes/{licao}', [LicaoController::class, 'show'])->name('licoes.show'); // RF24
    Route::post('/licoes/{licao}', [LicaoController::class, 'responder'])->name('licoes.responder'); // RF25-29

    Route::get('/conquistas', [ConquistaController::class, 'index'])->name('conquistas'); // RF17

    Route::get('/perfil', [PerfilController::class, 'editar'])->name('perfil.editar');    // RF06/07, 10/11, 14/15, 20/21
    Route::put('/perfil', [PerfilController::class, 'atualizar'])->name('perfil.atualizar');

    Route::get('/perfil/avatar', [PerfilController::class, 'formAvatar'])->name('perfil.avatar');   // RF08/09
    Route::put('/perfil/avatar', [PerfilController::class, 'salvarAvatar']);

    // Vestuário do pinguim (RF08/09): vestir peças e comprar as exclusivas com pontos
    Route::get('/vestuario', [VestuarioController::class, 'index'])->name('vestuario');
    Route::post('/vestuario/vestir', [VestuarioController::class, 'vestir'])->name('vestuario.vestir');
    Route::post('/vestuario/comprar', [VestuarioController::class, 'comprar'])->name('vestuario.comprar');

    Route::get('/perfil/foto', [PerfilController::class, 'formFoto'])->name('perfil.foto');         // RF12/13
    Route::post('/perfil/foto', [PerfilController::class, 'salvarFoto']);

    Route::get('/senha', [SenhaController::class, 'formAlterar'])->name('senha.alterar');            // RF04/05
    Route::put('/senha', [SenhaController::class, 'alterar']);

    // Configurações: conta, acessibilidade e excluir conta
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes');
    Route::put('/configuracoes/conta', [ConfiguracaoController::class, 'atualizarConta'])->name('configuracoes.conta');
    Route::put('/configuracoes/acessibilidade', [ConfiguracaoController::class, 'atualizarAcessibilidade'])->name('configuracoes.acessibilidade');
    Route::delete('/configuracoes/conta', [ConfiguracaoController::class, 'excluirConta'])->name('configuracoes.excluir');

    // --------------------------------------------------------------
    // ÁREA DO PROFESSOR: só professor e admin (middleware 'tipo').
    // prefix('professor') coloca /professor na frente de todas as URLs do grupo;
    // name('professor.') coloca "professor." na frente de todos os nomes.
    // --------------------------------------------------------------
    Route::middleware('tipo:professor,admin')->prefix('professor')->name('professor.')->group(function () {
        // Cursos  (ex.: GET /professor/cursos/criar  ->  route('professor.cursos.create'))
        Route::get('/', [Professor\CursoController::class, 'index'])->name('cursos.index');
        Route::get('/cursos/criar', [Professor\CursoController::class, 'create'])->name('cursos.create');
        Route::post('/cursos', [Professor\CursoController::class, 'store'])->name('cursos.store');
        Route::get('/cursos/{curso}/editar', [Professor\CursoController::class, 'edit'])->name('cursos.edit');
        Route::put('/cursos/{curso}', [Professor\CursoController::class, 'update'])->name('cursos.update');
        Route::delete('/cursos/{curso}', [Professor\CursoController::class, 'destroy'])->name('cursos.destroy');

        // Módulos (criados dentro de um curso)
        Route::post('/cursos/{curso}/modulos', [Professor\ModuloController::class, 'store'])->name('modulos.store');
        Route::get('/modulos/{modulo}/editar', [Professor\ModuloController::class, 'edit'])->name('modulos.edit');
        Route::put('/modulos/{modulo}', [Professor\ModuloController::class, 'update'])->name('modulos.update');
        Route::delete('/modulos/{modulo}', [Professor\ModuloController::class, 'destroy'])->name('modulos.destroy');

        // Lições (criadas dentro de um módulo)
        Route::get('/modulos/{modulo}/licoes/criar', [Professor\LicaoController::class, 'create'])->name('licoes.create');
        Route::post('/modulos/{modulo}/licoes', [Professor\LicaoController::class, 'store'])->name('licoes.store');
        Route::get('/licoes/{licao}/editar', [Professor\LicaoController::class, 'edit'])->name('licoes.edit');
        Route::put('/licoes/{licao}', [Professor\LicaoController::class, 'update'])->name('licoes.update');
        Route::delete('/licoes/{licao}', [Professor\LicaoController::class, 'destroy'])->name('licoes.destroy');

        // Exercícios (criados dentro de uma lição)
        Route::post('/licoes/{licao}/exercicios', [Professor\ExercicioController::class, 'store'])->name('exercicios.store');
        Route::delete('/exercicios/{exercicio}', [Professor\ExercicioController::class, 'destroy'])->name('exercicios.destroy');
    });

    // --------------------------------------------------------------
    // ADMINISTRAÇÃO: só admin.
    // --------------------------------------------------------------
    Route::middleware('tipo:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::put('/usuarios/{usuario}/tipo', [AdminController::class, 'alterarTipo'])->name('usuarios.tipo');
        Route::delete('/usuarios/{usuario}', [AdminController::class, 'excluir'])->name('usuarios.excluir');
    });
});
