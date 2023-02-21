<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\TaskComment;
use App\Http\Resources\TaskCommentResource;
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
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'task_file'       => $this->file_path ? asset($this->file_path)             : null,
            'duration'        => $this->duration,
            'delivery_date'   => $this->pivot->delivery_date ?? null,
            'delivered_date'  => $this->pivot->delivered_date ?? null,
            'student_submit'  => $this->pivot->file_path ?asset($this->pivot->file_path): null ,
            'student_comment' => $this->pivot->student_comment ?? null,
            'status'          => $this->pivot->status ?? null,
            'delay'           => $this->pivot->delay ?? null,
            // 'comments'        => $this->pivot->comments  ?? null,
            'start_date'      => date('Y-m-d', strtotime($this->pivot->created_at)) ,
            'comments'        =>  TaskCommentResource::collection(TaskComment::where('user_task_id',$this->pivot->id)->get()),
        ];
    }
}
