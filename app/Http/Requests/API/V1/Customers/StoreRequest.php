<?php

namespace App\Http\Requests\API\V1\Customers;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:10',
            'lastname' => 'required|string|min:3|max:10',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|digits:10'
        ];
    }
}
