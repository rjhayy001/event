<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Program;
use App\Http\Resources\VisitorResource as VisitorResource;
use App\Http\Resources\CompanyResource as CompanyResource;
use App\Http\Resources\priceResource as priceResource;
use App\Http\Resources\PresentationResource ;


class Event extends JsonResource
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
            'image'=>$this->image ,
            'name'=> $this->name,
            'from'=> $this->fromdate,
            'to'=> $this->todate,
            'place'=> $this->place,
            'description'=> strip_tags($this->description),
            'fullmap'=> $this->fullmap ,
            'map' => $this->map ,
            'programs'=> Program::collection($this->programs),
            'companies' =>Company_eventResource::collection($this->companies),
            'visitors' =>VisitorResource::collection($this->visitors),
            'prices' =>priceResource::collection($this->categories),

            'event_images' => [
                'presentation_image' => $this->presentation_image,
                'Tarifs_image' => $this->tarifs_image,
                'Programme_image' => $this->programme_image,
                'Restaurant_image' => $this->restaurant_image,
                'Plan_image' => $this->plan_image,
                'highlight_image' => $this->highlights_image,
                'Logo' => $this->logo,
            ],

            'presentation_details' => [
                'details' => $this->presentation['details']  ? strip_tags($this->presentation['details']): null ,
                'image' => $this->presentation['image']  ?  asset('event_images/'.$this->presentation['image']) : null ,
            ],

            'highlights' =>  [
                'program' => $this->programs->where('highlight','1'),
                'company' => $this->companies()->wherePivot('highlight','1')->get(),
            ],

            'restauration' => [
                'company' => $this->companies()->wherePivot('is_restaurant','1')->get(),
            ]

        ];
    }
  
}
