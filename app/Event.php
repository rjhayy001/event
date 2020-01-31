<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // RELATION

    public function programs(){
        return $this->hasMany(Program::class);
    }
    public function companies(){
        return $this->belongsToMany(Company::class)->withPivot(['paidprice','emplacement','is_restaurant','lat','lng','highlight','description']);
    }
    public function categories(){
        return $this->belongsToMany(Category::class)->withPivot(['price']);
    }
    public function visitors(){
        return $this->belongsToMany(Visitor::class);
    }
    public function presentation(){
        return $this->hasOne(Presentation::class);
    }
    public function visitor_fav(){
        return $this->belongsToMany(Visitor::class , 'favorites' , 'event_id' , 'visitor_id' );
    }

    // MUTATOR
    
    public function getDurationAttribute($value)
    {   
        if(!empty($this->fromdate) &&!empty($this->todate))
        {
            return date('d F Y', strtotime($this->fromdate)) .' - '. date('d F Y', strtotime($this->todate)) ;
        }
            return '' ;
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
     public function getFormatfromAttribute($value)
    {
        if($this->fromdate == '')
        {
            return 'no given date yet ';
        }
        else
        {
            return date('d F Y', strtotime($this->fromdate)) ;
        }
    }

    public function getCompanycountAttribute($value)
    {
        return $this->companies->count();
    }

    public function getVisitorcountAttribute($value)
    {
        return $this->visitors->count();
    }

    public function getPresentationImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Presentation.png') ;
    }
    public function getProgrammeImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Programme.png') ;
    }
    public function getTarifsImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Tarifs.png') ;
    }
    public function getPlanImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Plan.png') ;
    }
    public function getRestaurantImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Restauration.png') ;
    }
    public function getHighlightsImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Highlights.png') ;
    }
    public function getLogoAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Event_logo.png') ;
    }
}
