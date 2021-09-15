<?php

namespace App\Http\Requests\API\V1\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'type' => 'sometimes|in:all,success,waiting,cancel',
            'started' => 'sometimes|date_format:Y-m-d',
            'finished' => 'sometimes|date_format:Y-m-d|after:started',
            'page' => 'sometimes|integer|min:1'
        ];
    }
}
