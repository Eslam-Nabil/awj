<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       return[
        'id'=>$this->id,
        'picture_path'=>($this->picture_path ? asset($this->picture_path) : null),
        'name'=>$this->name,
        'translations'=>$this->translations,
        'title'=>$this->job_title,
       ];
    }
}
