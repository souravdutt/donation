<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Albums extends Model
{
    use HasFactory;
    protected $table = 'albums';

    function media(){
        return $this->hasMany(\App\Models\Media::class, "album_id", "id");
    }
}
