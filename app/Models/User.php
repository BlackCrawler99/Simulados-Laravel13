<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
        'name',
        'email',
        'password',
        'phone',
        'city',
        'uf',
        'desired_course',
        'school_year',
        'accepts_info',
        'interested_course',
        'school_id',
        'school_class_id',
    ])]
#[Hidden([
        'password',
        'remember_token'
    ])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function exams() {
        return $this->hasMany(Exam::class);
    }

    // O aluno pertence a uma escola (Premium)
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // O aluno pertence a uma turma (Premium)
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
