<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 
        'question_id', 
        'option_id', 
        'is_correct'
    ];

    // 1. Relacionamento: A Resposta pertence a uma Questão
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // 2. Relacionamento: A Resposta pertence a uma Alternativa (Opção)
    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    // 3. (Opcional) Relacionamento: A Resposta pertence a um Exame
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}