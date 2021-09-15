<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Http\Traits\JSONResponseTrait;

class Handler extends ExceptionHandler
{
    use JSONResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->failed($exception->errors() ,$exception->status);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Accept JSON
        if ($request->expectsJson()) {
            return $this->failed(['error' => 'Unauthenticated.'], 401);
        }
        // Guest
        if (env('APP_DEBUG') == false) {
            return abort(404);
        }
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->failed($validator->errors()->all(), 422));
    }
}
