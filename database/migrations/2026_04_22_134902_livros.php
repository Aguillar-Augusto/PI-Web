<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('autor');
            //$table->string('password');
            $table->string('genero1');
            $table->string('genero2');
            $table->text('sinopse');
            $table->timestamp('dataupload');
            $table->foreignId('users_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->timestamp('dataatualizacao')->nullable();
        });
    }
    /**
     * Eu imagino que eu vou ter que criar um campo pra quardar o arquivo pdf do livro,
     * mas eu não sei qual campo colocar pra isso, então não vai ter nessa primeira versão.
     */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
