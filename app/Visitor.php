<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Visitor extends Authenticatable
{
    use Notifiable , HasApiTokens ;

    //RELATIONS
    public function events(){
        return $this->belongstoMany(Event::class);
    }
    public function account(){
        return $this->hasOne(Account::class);
    }
    public function events_fav(){
        return $this->belongsToMany(Event::class , 'favorites' , 'visitor_id' , 'event_id' );
    }


    public function getCreatedatAttribute($value) {
        return date('d F Y H:i', strtotime($value)) ; 
    }

    public function getImageAttribute($value) {
        return $value ? asset('images/'.$value) : asset('images/test_profile.jpg') ; 
    }
}
