<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    public $timestamps = false;
    protected $hidden = [
        'code'
    ];
    protected $fillable = ['name','code','active'];
    public function  request(){
        return $this->hasMany('Requisition', 'status_id');
    }
}