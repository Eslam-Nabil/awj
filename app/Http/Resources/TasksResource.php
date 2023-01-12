<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'title'=>$this->title,
            'description'=>$this->description,
            'duration'=>$this->duration,
            'status'=>$this->pivot->status,
            'delay'=>$this->pivot->delay,
            'delivery_date'=>$this->pivot->delivery_date,
            'file'=>asset($this->file_path) ?? null,
        ];
    }
}
