<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    //Scope
    public function scopeFindCompany($query, $name ='')
    {
       return $query->where('name', 'like', "%" . $name . "%");
    }

    public function format(){
        return [
            'id' => $this->id ,
            'name'=>$this->name ,
            'contact person'=> $this->contacts['name'],
            'contact number'=> $this->contacts['phone']  ,
            'company email'=> $this->contacts['email'] ,
            'logo'=> $this->logo ,
            'created_at' => $this->created_at,
            'subtitle' => 'company',
            'icon' => 'mdi-office',
            'route' => 'view_company'
        ];
    }

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
}
