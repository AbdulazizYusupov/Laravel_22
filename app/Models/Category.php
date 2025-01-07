<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','sort'];

    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class,'category_id');
    }
}
