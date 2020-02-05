<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    // RELATION

    public function event(){
        return $this->hasOne(Event::class);
    }

    public function getImageAttribute($value){
        return $value ? asset('event_images/'. $value) :'' ;
    }
}
