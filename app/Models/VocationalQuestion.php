<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalQuestion extends Model
{
    protected $fillable = ['text', 'is_active'];

    public function options()
    {
        return $this->hasMany(VocationalOption::class);
    }
}