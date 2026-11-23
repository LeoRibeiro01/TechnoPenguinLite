<?php

/*
 * MIGRATION: tabela "licoes" (RF24)
 * Um módulo tem várias lições (1 módulo -> N lições).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();   // apagou o módulo -> apaga as lições
            $table->string('titulo', 100);
            $table->text('teoria');      // explicação (HTML nos cursos oficiais, texto simples nos da comunidade)
            $table->text('pratica')->nullable();   // exemplo de código (opcional para o professor)
            $table->integer('ordem');    // posição dentro do módulo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licoes');
    }
};
