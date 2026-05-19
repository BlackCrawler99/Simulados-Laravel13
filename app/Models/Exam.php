<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // Adicione 'total_questions' na lista de campos permitidos
    protected $fillable = [
        'user_id',
        'total_questions', // <-- A liberação que estava faltando!
        'score',
        'completed_at',
    ];

    /**
     * Relacionamento: Um simulado pertence a um aluno (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Um simulado possui várias respostas (Answers)
     */
    public function answers()
    {
        return $this->hasMany(Answer::class); // Ou o nome correto do seu model de respostas
    }
}