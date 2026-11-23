<?php

/*
 * MIGRATION: tabela "cursos" (RF22)
 *
 * Dois tipos de curso:
 *   - OFICIAIS: vêm do seeder (professor_id = NULL). Os textos longos já estão em HTML.
 *   - DA COMUNIDADE: criados por um professor (professor_id = id do professor).
 *     Só aparecem para os alunos quando publicado = true.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            // nullable: cursos de assunto (ex.: Redes) e da comunidade não têm linguagem
            $table->foreignId('linguagem_id')->nullable()->constrained('linguagens');
            // Dono do curso da comunidade. cascadeOnDelete: se o professor for excluído, os cursos dele também são.
            $table->foreignId('professor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->boolean('publicado')->default(true);   // cursos da comunidade começam como rascunho (false)
            $table->string('titulo', 100);
            $table->string('tipo', 20);              // base, linguagem, assunto ou comunidade
            $table->string('sigla', 5);              // texto do ícone
            $table->string('cor', 7);                // cor do ícone
            $table->string('descricao');
            $table->text('historia')->nullable();    // como surgiu
            $table->text('hoje')->nullable();        // como é usado hoje
            $table->text('referencias')->nullable(); // livros e links
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
