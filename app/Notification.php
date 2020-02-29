<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    public function event(){
        return $this->hasOne(Event::class);
    }
}
