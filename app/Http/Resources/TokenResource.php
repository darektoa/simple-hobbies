<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TokenResource extends JsonResource
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
            'access'     => $item->token,
            'type'       => $item->type ?? 'bearer',
            'expires_in' => (Auth::factory()->getTTL() * 60) ?? null,
        ];
    }
}
