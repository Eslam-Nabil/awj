<?php

namespace App\Http\Resources;

use App\Http\Resources\CommentsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
        'category_id'=>$this->category_id,
        'article_file_path'=>asset($this->article_file_path),
        'audio_file_path'=>asset($this->audio_file_path),
        'cover_file_path'=>asset($this->cover_file_path),
        'price'=>$this->price,
        'title'=>$this->title,
        'description'=>$this->description,
        'translations'=>$this->translations,
        'user'=>$this->user,
        'comments'=>CommentsResource::collection($this->comments)
       ];
    }
}
