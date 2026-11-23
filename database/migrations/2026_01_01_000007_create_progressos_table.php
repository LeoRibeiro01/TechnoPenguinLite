<?php

/*
 * MIGRATION: tabela "progressos" (RF16 e RF29)
 * Resultado de cada usuário em cada lição. Uma linha por usuário + lição.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progressos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('licao_id')->constrained('licoes')->cascadeOnDelete();
            $table->integer('acertos');                       // melhor número de acertos
            $table->integer('total');                         // quantos exercícios a lição tem
            $table->boolean('aprovado')->default(false);      // true = libera a próxima lição
            $table->dateTime('concluido_em')->nullable();     // quando foi aprovado pela 1ª vez
            $table->timestamps();

            $table->unique(['user_id', 'licao_id']);          // não deixa repetir usuário + lição
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progressos');
    }
};
