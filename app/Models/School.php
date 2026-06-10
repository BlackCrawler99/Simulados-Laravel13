<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'module_colegios',
    ];

    // Uma escola tem muitas turmas
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    // Uma escola tem muitos alunos
    public function users()
    {
        return $this->hasMany(User::class);
    }
}