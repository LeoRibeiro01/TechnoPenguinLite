<?php

/*
 * MIGRATION: tabela "compras"
 * Peças exclusivas do vestuário que cada usuário comprou com pontos.
 * (As peças grátis não precisam de linha aqui: todo mundo já tem.)
 *
 * O "preco" fica guardado para calcular o saldo:
 *   saldo = pontos acumulados (users.pontos) - soma dos preços das compras
 * Assim a pontuação acumulada (RF18) e as medalhas não diminuem quando o usuário compra algo.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('item', 40);    // código da peça (ex.: 'terno')
            $table->integer('preco');      // quanto custou, em pontos
            $table->timestamps();

            $table->unique(['user_id', 'item']);   // não compra a mesma peça duas vezes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
