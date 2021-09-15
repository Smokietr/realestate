<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use App\Http\Resources\API\V1\Auth\AuthResource;
use App\Models\User;

class RegisterController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request)
    {
        $parameters = $request->only(['name', 'email', 'password']);

        $create = User::create($parameters);

        if($create) {
            return $this->success(new AuthResource((object)['id' => $create->id]));
        }

        return $this->unknownError();
    }
}
