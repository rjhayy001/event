<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    // RELATION

    public function event(){
        return $this->hasOne(Event::class);
    }
}
