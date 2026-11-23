<?php

/*
 * MIGRATION: tabela "users" (os usuários) e "password_reset_tokens"
 *
 * O nome "users" e as colunas "email" e "password" são o padrão do Laravel:
 * assim o login (Auth) e a recuperação de senha (Password) funcionam sem configuração.
 *
 * A coluna "tipo" define o que o usuário pode fazer:
 *   aluno     -> estuda (todas as telas de estudo)
 *   professor -> estuda + cria cursos na área do professor (aba Comunidade)
 *   admin     -> tudo acima + painel de administração (remover usuários, trocar tipos, moderar cursos)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20)->default('aluno');        // aluno, professor ou admin
            $table->string('nome', 80);                          // RF01
            $table->string('nome_usuario', 30)->unique();        // RF06: unique() impede repetidos
            $table->string('email', 120)->unique();              // RF01
            $table->string('password');                          // RNF02: guarda o HASH da senha
            $table->string('foto')->nullable();                  // RF12: nome do arquivo em public/uploads
            // RF08/RF09: o pinguim (avatar). Cores em hexadecimal; padrões = cores da foto original.
            $table->string('cor_corpo', 7)->default('#384048');
            $table->string('cor_barriga', 7)->default('#ecf0f0');
            $table->string('cor_detalhe', 7)->default('#ec9038');
            $table->string('cor_fundo', 7)->default('#00bcd4');
            // Peça vestida em cada camada (código do catálogo app/Support/Pinguim.php, ou NULL = nada)
            $table->string('roupa', 40)->nullable();
            $table->string('pescoco', 40)->nullable();
            $table->string('oculos', 40)->nullable();
            $table->string('chapeu', 40)->nullable();
            // RF10: linguagem escolhida. foreignId + constrained = chave estrangeira para linguagens.id
            $table->foreignId('linguagem_id')->nullable()->constrained('linguagens');
            $table->string('nivel', 20)->nullable();             // RF14: iniciante, intermediario ou avancado
            $table->integer('meta_diaria')->default(1);          // RF20: lições por dia
            $table->integer('pontos')->default(0);               // RF18
            $table->integer('sequencia')->default(0);            // RF19: dias seguidos estudando
            $table->date('ultimo_estudo')->nullable();           // RF19: último dia com lição aprovada
            // Acessibilidade (tela Configurações)
            $table->string('tamanho_fonte', 20)->default('normal');   // normal, grande ou muito_grande
            $table->boolean('alto_contraste')->default(false);
            $table->boolean('destacar_links')->default(false);
            $table->timestamps();
        });

        // Usada pela recuperação de senha do Laravel (RF03): guarda o código enviado por e-mail.
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
