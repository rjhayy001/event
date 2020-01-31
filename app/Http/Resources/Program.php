<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Program extends JsonResource
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
            'time'=> date('h:i' , strtotime($this->time)),
            'name'=> $this->name,
            'details'=> $this->details,
            'highlight'=> $this->highlight,
        ];
    }
}
