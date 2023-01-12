<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User_Tasks extends JsonResource
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
            'task_file'=>asset($this->file_path) ?? null,
            'duration'=>$this->duration,
            'delivery_date'=>$this->pivot->delivery_date,
            'student_submit'=>$this->pivot->file_path,
            'student_comment'=>$this->pivot->student_comment,
            'status'=>$this->pivot->status,
            'delay'=>$this->pivot->delay,
            'start_date'=>$this->pivot->created_at,
        ];
    }
}
