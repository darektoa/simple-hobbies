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
        $item = (object) $this->all();

        return [
            'id'            => $item->id,
            'name'          => $item->name,
            'email'         => $item->email,
            'phone'         => $item->phone,
            'created_at'    => $item->created_at,
            'updated_at'    => $item->updated_at,
            'token'         => TokenResource::make($this->when($item->token, collect(['token' => $item->token])))
        ];
    }
}
