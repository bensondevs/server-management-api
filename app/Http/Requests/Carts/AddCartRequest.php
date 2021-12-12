<?php

namespace App\Http\Requests\Carts;

use Illuminate\Foundation\Http\FormRequest;

class AddCartRequest extends FormRequest
{
    /**
     * Cartable model container
     * 
     * @var mixed
     */
    private $cartable;

    /**
     * Get cartable from the inputted request
     * 
     * @return mixed|abort 404
     */
    public function getCartable()
    {
        if ($this->cartable) return $this->cartable;

        $type = $this->input('cartable_type');
        $id = $this->input('cartable_id');
        return $this->cartable = $type::findOrFail($id);
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
            'cartable_type' => ['required', 'string'],
            'cartable_id' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
        ];
    }
}
