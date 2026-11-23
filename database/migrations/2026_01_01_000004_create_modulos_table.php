<?php

/*
 * MIGRATION: tabela "modulos" (RF23)
 * Um curso tem vários módulos (1 curso -> N módulos).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();  // apagou o curso -> apaga os módulos
            $table->string('titulo', 100);
            $table->string('descricao');
            $table->string('dificuldade', 20);                     // iniciante, intermediario ou avancado
            $table->integer('ordem');                              // posição dentro do curso (1, 2, 3...)
            $table->text('projeto')->nullable();                   // projeto sugerido (HTML)
            $table->text('aprofundamento')->nullable();            // leitura extra (HTML)
            $table->text('referencias')->nullable();               // fontes (HTML)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
