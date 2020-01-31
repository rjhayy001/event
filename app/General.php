<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    //MUTATOR

    public function getCategoryAttribute($value)
    {
        return ucfirst($value);
    }
    public function getFieldAttribute($value)
    {
        return ucfirst($value);
    }
}
