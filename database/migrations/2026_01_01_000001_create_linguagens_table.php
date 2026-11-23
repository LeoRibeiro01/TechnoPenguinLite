<?php

/*
 * MIGRATION: tabela "linguagens"
 * As linguagens de programação que o usuário pode escolher para estudar (RF10).
 *
 * Migration = arquivo que CRIA (up) ou DESFAZ (down) uma tabela.
 * Rodar todas:  php artisan migrate
 * Apagar tudo e criar de novo + conteúdo:  php artisan migrate:fresh --seed
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linguagens', function (Blueprint $table) {
            $table->id();                       // id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('nome', 40);         // ex.: Python
            $table->string('sigla', 5);         // texto do ícone, ex.: Py
            $table->string('cor', 7);           // cor do ícone, ex.: #3776ab
            $table->timestamps();               // colunas created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linguagens');
    }
};
