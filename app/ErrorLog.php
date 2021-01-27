<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $table = 'emaillog';
    
    protected $fillable = ['description', 'users_id'];


    public function user(){
        return $this->belongsTo('User');
    }
}
