<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Resources\API\V1\Auth\AuthResource;
use App\Http\Resources\API\V1\Auth\LoginResource;
use App\Models\user;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $remember = false;

    public function __construct()
    {
        if (\request()->has('remember_me')) {
            $this->remember = \request()->get('remember_me');
        }

        $this->middleware('auth:sanctum')->only('logout');

    }

    /**
     * @param Request $request
     * @return JsonResponse|object
     */

    public function store(LoginRequest $request)
    {

        $parameter = $request->only('email', 'password');

        if (!auth()->attempt($parameter, $this->remember)) {
            return $this->failed(['Wrong Username and/or Password.'], 403);
        }

        return $this->success(new LoginResource(auth()->user()));
    }

    /**
     * @return LoginController|JsonResponse|object
     */

    public function logout()
    {
        if (auth()->user()->tokens()->delete()) {
            return $this->success(['message' => 'Done !']);
        }

        return $this->unknownError();
    }
}
