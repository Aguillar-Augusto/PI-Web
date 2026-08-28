<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('livros', function (Blueprint $table) {
            // Adicionando as colunas de caminho (path) após a coluna 'sinopse'
            $table->string('capa_path')->nullable()->after('sinopse');
            $table->string('pdf_path')->after('capa_path');
        });
    }

    public function down(): void
    {
        Schema::table('livros', function (Blueprint $table) {
            // Se precisarmos reverter, ele apaga apenas essas duas colunas
            $table->dropColumn(['capa_path', 'pdf_path']);
        });
    }
};