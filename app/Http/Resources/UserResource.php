<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'title'=>$this->title,
            'about'=>$this->about,
            'picture'=>$this->picture ? asset($this->picture): null,
            'birthdate'=>($this->birthdate ? date('d/m/Y',strtotime(str_replace('/','-',$this->birthdate))) : null),
            'role'=>$this->roles[0]->name,
            'articles'=>$this->article,
            //'articles'=>$this->whenLoaded('article', fn () => ArticleOfAuthorResource::collection($this->article)),
            //'articles'=>$this->whenLoaded('article', ArticleOfAuthorResource::collection($this->article)),
            'status'=>$this->paypalOrder->where('type','register')->first()->order_status ?? null
      ];

    }
}
