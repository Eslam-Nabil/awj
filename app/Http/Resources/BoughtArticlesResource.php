<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BoughtArticlesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();
        // return $user->tasks->where('article_id',$this->id);
        return[
            'id'=>$this->id,
            'serial_number'=>$this->serial_number,
            'category_id'=>$this->category_id,
            'article_file_path'=>asset($this->article_file_path),
            'audio_file_path'=>asset($this->audio_file_path),
            'cover_file_path'=>asset($this->cover_file_path),
            'price'=>$this->price,
            'status'=>$this->status,
            'pages_count'=>$this->pages_count,
            'title'=>$this->title,
            'description'=>$this->description,
            'summary'=>$this->summary,
            'language'=>$this->language,
            'tasks'=>User_Tasks::collection($user->tasks->where('article_id',$this->id) )
           ];
    }
}
