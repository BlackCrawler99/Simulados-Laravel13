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
        Schema::create('vocational_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocational_question_id')->constrained()->cascadeOnDelete();
            $table->string('text'); // O texto da resposta
            $table->string('area'); // A qual área essa resposta soma pontos (Ex: 'Exatas', 'Humanas', 'Saúde')
            $table->integer('points')->default(1); // Peso da resposta (geralmente 1 ponto)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocational_options');
    }
};
