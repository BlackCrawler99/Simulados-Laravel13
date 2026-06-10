<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'period',
    ];

    // Uma turma pertence a uma escola
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Uma turma tem muitos alunos
    public function users()
    {
        return $this->hasMany(User::class);
    }
}