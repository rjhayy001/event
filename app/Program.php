<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    // RELATION

    public function event(){
        return $this->belongsTo(Event::class);
    }
    
}
