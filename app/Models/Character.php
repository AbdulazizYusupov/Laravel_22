<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = ['name'];

    public function attchars()
    {
        return $this->hasMany(Attchar::class, 'char_id');
    }
}
