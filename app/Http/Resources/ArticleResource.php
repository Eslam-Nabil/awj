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
        'pages_count'=>$this->pages_count,
        'title'=>$this->title,
        'description'=>$this->description,
        'summary'=>$this->summary,
        'language'=>$this->language,
        'translations'=>$this->translations,
        'user'=>$this->user,
        'comments'=>CommentsResource::collection($this->comments->where('show',1))
       ];
    }
}
