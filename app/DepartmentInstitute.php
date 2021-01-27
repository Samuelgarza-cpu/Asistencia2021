<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentInstitute extends Model
{
    protected $table = 'departments_institutes';
    public $timestamps = false;
    protected $fillable = ['departments_id','institutes_id','stamp','image'];

    public function department(){
        return $this->belongsTo('Department');
    }

    public function institute(){
        return $this->belongsTo('Institute');
    }

    public function insdepsuppro(){
            return $this->hasMany('InsDepSupPro', 'departments_institutes_id');
    }

    public function user(){
        return $this->hasMany('User', 'departments_institutes_id');
    }
}
