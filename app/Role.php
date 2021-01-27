<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    public $timestamps = false;
    protected $fillable = ['name','code', 'active'];
    public function user(){
        return $this->hasMany('User', 'roles_id');
    }

}
