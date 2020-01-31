<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    // FUNCTIONS
    public function events(){
        return $this->belongsToMany(Event::class)->withPivot(['price']);
    }

    // MUTATOR
    public function getPersonAttribute($value)
    {
        return ucfirst($value);
    }
    public function getCreatedatAttribute($value) {
        return date('d F Y H:i', strtotime($value)) ; 
    }
    
}
