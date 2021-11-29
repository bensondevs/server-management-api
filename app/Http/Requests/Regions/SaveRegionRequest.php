<?php

namespace App\Http\Requests\Regions;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRules;

use App\Models\Region;

class SaveRegionRequest extends FormRequest
{
    use InputRules;

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
        $this->setRules([
            'region_name' => ['required', 'string', 'unique:regions,region_name'],
        ]);

        return $this->returnRules();
    }
}
