<?php

namespace App\Http\Resources;

use App\Models\SectionType;
use App\Http\Resources\TypeResource;
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
            'translations'=>$this->translations,
            'section_type'=>new TypeResource(SectionType::find($this->section_type_id))
        ];
    }
}
