<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalResult extends Model
{
    protected $fillable = ['user_id', 'recommended_area', 'scores'];

    // Diz pro Laravel tratar a coluna scores como um array automaticamente
    protected $casts = [
        'scores' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}