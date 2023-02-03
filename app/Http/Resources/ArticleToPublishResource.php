<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleToPublishResource extends JsonResource
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
            'serial_number'=>$this->serial_number,
            'category_id'=>$this->category_id,
            'price'=>$this->price,
            'status'=>$this->status,
            'pages_count'=>$this->pages_count,
            'title'=>$this->title,
            'user'=>$this->user->name,
           ];
    }
}
