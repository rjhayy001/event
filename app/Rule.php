<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    //
    public function getRequiredAttribute($value) {
        return $value == 1 ? true : false ; 
    }
    
}
