<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    // Libera as colunas da alternativa
    protected $fillable = [
        'question_id', 
        'text', 
        'is_correct'
    ];

    // Relacionamento: Uma alternativa pertence a uma questão
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}