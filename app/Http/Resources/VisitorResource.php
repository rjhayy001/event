<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Http\Resources\Event as eventResource;

class VisitorResource extends JsonResource
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
            'phone'=> $this->phone,
            'email'=> $this->email,
            'username'=> $this->username,
            'password'=> $this->password,
            'iostoken'=> $this->iostoken,
            'fcmtoken'=> $this->fcmtoken,
            'profile_pic'=>$this->image ,
            // 'events' => Company_eventResource::collection($this->events)
        ];
    }
}
