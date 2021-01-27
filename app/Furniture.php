<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    protected $table = 'furnitures';
    
    public $timestamps = false;
    
    protected $fillable = ['name'];
    
    public function requestfurnitures(){
        return $this->hasMany('RequestFurniture', 'furnitures_id');
    }
}
