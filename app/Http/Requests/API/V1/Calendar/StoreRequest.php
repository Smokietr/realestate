<?php

namespace App\Http\Requests\API\V1\Calendar;

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
            'customer' => 'required|integer|exists:customers,id',
            'address' => 'required|string|min:3|max:191',
            'code' => 'required|string',
            'mode' => 'sometimes|string|in:driving,bicycling,transit,walking'
        ];
    }
}
