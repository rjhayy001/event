<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    //
    public function getFieldsAttribute($value) {
        return ucfirst($value); 
    }
}
