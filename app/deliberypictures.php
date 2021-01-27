<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class deliberypictures extends Model
{
    protected $table = 'deliberypictures';
    protected $fillable = ['name', 'requests_id'];
}
