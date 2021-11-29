<?php

namespace App\Http\Requests\CommandHistories;

use Illuminate\Foundation\Http\FormRequest;

class PopulateCommandHistoriesRequest extends FormRequest
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
        $rules = [];

        if (request()->input('start'))
            $rules['start'] = [
                'required', 
                'date_format:Y-m-d H:i:s'
            ];

        if (request()->input('end'))
            $rules['end'] = [
                'required', 
                'date_format:Y-m-d H:i:s'
            ];

        return $rules;
    }
}
