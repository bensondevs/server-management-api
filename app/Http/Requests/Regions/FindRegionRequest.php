<?php

namespace App\Http\Requests\Regions;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Region;

class FindRegionRequest extends FormRequest
{
    private $region;

    public function getRegion()
    {
        if ($this->region) return $this->region;

        $id = $this->input('id');
        return $this->region = Region::findOrFail($id);
    }

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
            'id' => ['required', 'string'],
        ];
    }
}
