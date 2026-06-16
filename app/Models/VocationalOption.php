<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalOption extends Model
{
    protected $fillable = ['vocational_question_id', 'text', 'area', 'points'];

    public function question()
    {
        return $this->belongsTo(VocationalQuestion::class, 'vocational_question_id');
    }
}
