<?php

namespace App\Http\Resources;

use App\Http\Resources\SectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       return [
        'id'=>$this->id,
        // 'page_name'=>$this->page_name,
        // 'slug'=>$this->slug,
        // 'main_title'=>$this->main_title,
        // 'slogan'=>$this->slogan,
        'main_image_path'=>($this->main_image_path ? asset($this->main_image_path) : Null),
        'second_image_path'=>($this->second_image_path ?  asset($this->second_image_path) :Null),
        // 'main_description'=>$this->main_description,
        // 'second_description'=>$this->second_description,
        //'type'=>($this->additional_section->type ? $this->additional_section : null )
        'translation'=>$this->translations,
        'sections'=>($this->additional_section ? SectionResource::collection($this->additional_section) : null ),

    ];
    }
}