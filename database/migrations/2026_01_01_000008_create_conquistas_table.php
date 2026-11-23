<?php

/*
 * MIGRATION: tabelas "conquistas" e "conquista_user" (RF17)
 *
 * conquistas: a lista de conquistas e medalhas que existem.
 *   "tipo" diz qual número do usuário é comparado com a "meta":
 *   'licoes' = lições aprovadas | 'sequencia' = dias seguidos | 'pontos' = pontos
 *
 * conquista_user: quais conquistas cada usuário já ganhou.
 *   É uma tabela "pivô" (relação N:N): um usuário tem várias conquistas
 *   e uma conquista pode ser de vários usuários.
 *   O nome segue o padrão do Laravel: os dois nomes no singular, em ordem alfabética.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conquistas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60);
            $table->string('descricao', 150);
            $table->string('icone', 10);    // um emoji
            $table->string('tipo', 20);     // licoes, sequencia ou pontos
            $table->integer('meta');        // número que precisa alcançar
            $table->timestamps();
        });

        Schema::create('conquista_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conquista_id')->constrained('conquistas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();           // created_at = quando ganhou
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conquista_user');
        Schema::dropIfExists('conquistas');
    }
};
