<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //RELATION

    public function company(){
        return $this->hasOne(Company::class);
    }
}
