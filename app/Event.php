<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{


    // Scope
    public function scopeFindEvent($query, $name ='')
    {
       return $query->where('name', 'like', "%" . $name . "%");
    }

    public function format(){
        return [
            'id' => $this->id ,
            'name'=>$this->name   ,
            'from'=> $this->fromdate ?  date('d F Y', strtotime($this->fromdate)) : 'no given date' ,
            'to'=> $this->todate ? date('d F Y', strtotime($this->todate)) : 'no given date',
            'start' => $this->fromdate,
            'end' => $this->todate,
            'company_count'=> $this->companies->count() ,
            'visitor_count' => $this->visitors->count(),
            'logo' => $this->logo,
            'subtitle' => 'event',
            'icon' => 'mdi-history',
            'route' => 'view_event'
        ];
    }
    
    // RELATION

    public function programs(){
        return $this->hasMany(Program::class);
    }
    public function companies(){
        return $this->belongsToMany(Company::class)->withPivot(['paidprice','emplacement','is_restaurant','x','y','highlight','description']);
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

    // public function getCompanycountAttribute($value)
    // {
    //     return $this->companies->count();
    // }

    public function getStartDateAttribute($value)
    {
        return  date('d F Y', strtotime($this->fromdate));
    }
    public function getEndDateAttribute($value)
    {
        return  date('d F Y', strtotime($this->todate));
    }

    public function getPresentationImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Presentation.jpeg') ;
    }
    public function getProgrammeImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Programme.jpeg') ;
    }
    public function getTarifsImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Tarifs.jpeg') ;
    }
    public function getPlanImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Plan.jpeg') ;
    }
    public function getRestaurantImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Restauration.jpeg') ;
    }
    public function getHighlightsImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Highlights.jpeg') ;
    }
    public function getLogoAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Event_Logo.jpeg') ;
    }
    public function getImageAttribute($value){
        return $value ? asset('event_images/'. $value) : asset('Settings/Event_Logo.jpeg') ;
    }
    public function getMapAttribute($value){
        return $value ? asset('event_images/'. $value) :'' ;
    }
    public function getFullmapAttribute($value){
        return $value ? asset('event_images/'. $value) :'' ;
    }
}
