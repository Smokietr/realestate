<?php

namespace App\Http\Resources\API\V1\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'email' => $this->email,
            'token' => [
                'code' => 'Bearer ' . $this->createToken('authToken', ['read:limited'])->plainTextToken,
                'expire' => now()->subMinutes(30)
            ],
            'rememberToken' => $this->remember_token
        ];
    }
}
