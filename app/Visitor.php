<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Visitor extends Authenticatable
{
    use Notifiable , HasApiTokens ;

    // Scope
    public function scopeFindVisitor($query, $name ='')
    {
       return $query->where('name', 'like', "%" . $name . "%");
    }
    public function format(){
        return [
            'id' => $this->id ,
            'name'=>$this->name ,
            'contact'=>$this->phone ,
            'email'=>$this->email,
            'created_at'=> $this->created_at,
            'logo' => $this->image,
            'subtitle' => 'visitor',
            'active' => $this->active,
            'icon' => 'mdi-account-group',
            'route' => 'view_visitor'
        ];
    }

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
    public function getPhoneattribute($value)
    {
       return $value ? $value :'not set';
    }
    public function getNameattribute($value)
    {
       return $value ? ucfirst($value) :'not set';
    }
    public function getEmailattribute($value)
    {
       return $value ? $value :'not set';
    }
}
