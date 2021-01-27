<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RPDDisabilities extends Model
{
    protected $table = 'rpd_disabilities';
    public $timestamps = false;

    protected $fillable = ['disability_id', 'disabilitycategories_id', 'requestsPersonalData_id'];

}
