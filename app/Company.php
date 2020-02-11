<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // RELATION
    public function events(){
        return $this->belongsToMany(Event::class)->withPivot(['paidprice','emplacement','is_restaurant','x','y','highlight','description']);
    }
    public function contacts(){
        return $this->hasOne(Contact::class);
    }

    // MUTATOR
    public function getCreatedatAttribute($value) {
        return date('d F Y H:i', strtotime($value)) ; 
    }
    public function getEventcountAttribute($value)
    {
       return $this->$value->events->count();
    }
    public function getLogoAttribute($value)
    {
       return asset('company_logo/'.$value);
    }
    public function getContactattribute($value)
    {
       return $value ? $value :'not set';
    }
    public function getNameattribute($value)
    {
       return $value ? $value :'not set';
    }
    public function getEmailattribute($value)
    {
       return $value ? $value :'not set';
    }
}
