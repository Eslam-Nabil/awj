<?php

namespace App\Http\Resources\Front;

use App\Models\SectionType;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'title'=>$this->title,
            'description'=>$this->description,
            'image'=>($this->image_path ? asset($this->image_path) : null ),

            'section_type'=>new TypeResource(SectionType::find($this->section_type_id))
        ];
    }
}
