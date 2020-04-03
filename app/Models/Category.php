<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    //
    public $timestamps=false;

    public $fillable=["name","description"];

    public function Topics()
    {
        return $this->hasMany(Topic::class);
    }


}
