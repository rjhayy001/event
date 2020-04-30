<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'activity'=> $this->activity,
            'description'=> strip_tags($this->description),
            'address1'=> $this->address1,
            'address2'=> $this->address2,
            'city'=> $this->city,
            'zip_code'=> $this->zip_code,
            'country'=> $this->country,
            'logo'=> $this->logo,
            'contact_person'=> $this->contact->name ,
            'contact_number'=> $this->contact->phone ,
            'contact_email'=> $this->contact->email ,
        ];
    }
}
