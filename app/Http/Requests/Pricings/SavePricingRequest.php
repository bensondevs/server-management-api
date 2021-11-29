<?php

namespace App\Http\Requests\Pricings;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRules;

use App\Models\Pricing;

class SavePricingRequest extends FormRequest
{
    use InputRules;

    private $pricing;

    public function getPricing()
    {
        if ($this->pricing) return $this->pricing;

        $id = $this->input('id') ?: $this->input('pricing_id');
        $pricing = Pricing::findOrFail($id);
        return $this->model = $this->pricing = $pricing;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $pricing = $this->getPricing();
            return true;
        }

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
            'priceable_id' => ['required', 'string'],
            'priceable_type' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'currency' => ['required', 'integer'],
        ]);

        return $this->returnRules();
    }
}
