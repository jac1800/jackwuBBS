<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public $timestamps=false;

    public $fillable=["name","description"];

    public function Topic()
    {
        return $this->hasMany(Topic::class);
    }
}
