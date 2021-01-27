<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisabilityCategories extends Model
{
    protected $table = 'disability_categories';
    public $timestamps = false;
    protected $fillable = ['name', 'active'];
}
