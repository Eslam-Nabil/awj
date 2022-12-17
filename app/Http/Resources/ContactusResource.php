<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactusResource extends JsonResource
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
        'phone_number_1' => $this->phone_number_1,
        'phone_number_2' => $this->phone_number_2,
        'email'          => $this->email,
        'location'       => $this->location,
        'facebook'       => $this->facebook,
        'instagram'      => $this->instagram,
        'youtube'        => $this->youtube,
        'linkedin'       => $this->linkedin,
        'twitter'        => $this->twitter,
       ];

    }
}
