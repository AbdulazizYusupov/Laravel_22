<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['element_id', 'attchar_id'];

    public function element()
    {
        return $this->belongsTo(Element::class, 'element_id');
    }

    public function attchar()
    {
        return $this->belongsTo(Attchar::class, 'attchar_id');
    }
}
