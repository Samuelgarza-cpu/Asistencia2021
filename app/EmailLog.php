<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'emaillog';
    
    protected $fillable = ['sender', 'recipient' ,'status', 'descriptionStatus'];
    
}
