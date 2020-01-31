<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Company_eventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'id'=> $this->id,
            'name'=> $this->name,
            'activity'=> $this->activity,
            'description'=> $this->description,
            'logo'=> asset('company_logo/'.$this->logo),
            'contact_person'=> $this->contact->name ,
            'contact_number'=> $this->contact->phone ,
            'contact_email'=> $this->contact->email ,
            'is_restaurant on this event'=> $this->pivot->is_restaurant,
            'details on this event'=> $this->pivot->description,
            'is_hightlight on this event'=> $this->pivot->highlight,
        ];
    }
}
