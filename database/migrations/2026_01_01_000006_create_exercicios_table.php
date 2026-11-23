<?php

/*
 * MIGRATION: tabela "exercicios" (RF25 a RF28)
 * Exercícios de múltipla escolha de cada lição (1 lição -> N exercícios).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licao_id')->constrained('licoes')->cascadeOnDelete();   // apagou a lição -> apaga os exercícios
            $table->text('enunciado');              // pergunta (HTML nos cursos oficiais, texto simples nos da comunidade)
            $table->text('alternativa_a');
            $table->text('alternativa_b');
            $table->text('alternativa_c')->nullable();   // C e D são opcionais (o professor pode usar só 2 ou 3)
            $table->text('alternativa_d')->nullable();
            $table->char('resposta_correta', 1);    // 'a', 'b', 'c' ou 'd' (RF26)
            $table->text('explicacao');             // mostrada quando o usuário erra (RF28)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercicios');
    }
};
