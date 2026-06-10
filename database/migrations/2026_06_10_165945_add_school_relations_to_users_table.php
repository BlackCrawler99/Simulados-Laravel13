<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a relação com a escola (colégio)
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            
            // Adiciona a relação com a turma (school_class)
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove as chaves estrangeiras e as colunas caso precise reverter
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
            
            $table->dropForeign(['school_class_id']);
            $table->dropColumn('school_class_id');
        });
    }
};