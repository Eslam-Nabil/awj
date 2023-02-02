<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTaskResource extends JsonResource
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
            'delivery_date'=>$this->pivot->delivery_date ?? null,
            'delivered_date'=>$this->pivot->delivered_date ?? null,
            'student_submit'=>$this->pivot->file_path ?? null ,
            'student_comment'=>$this->pivot->student_comment ?? null,
            'status'=>$this->pivot->status ?? null,
            'delay'=>$this->pivot->delay ?? null,
          //  'start_date'=>date('Y-m-d', strtotime($this->pivot->created_at)) ,
        ];
    }
}
