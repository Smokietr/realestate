<?php

namespace App\Http\Resources\API\V1\Auth;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    protected $user, $login;

    /**
     * @param $id
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable
    */

    protected function login($id) {
        return auth()->loginUsingId($id);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->login = $this->login($this->id);

        return [
            'email' => $this->login->email,
            'token' => [
                'code' => 'Bearer ' . $this->login->createToken('authToken')->plainTextToken,
                'expire' => Carbon::now()->addWeeks(1)
            ],
            'rememberToken' => $this->login->remember_token
        ];
    }
}
