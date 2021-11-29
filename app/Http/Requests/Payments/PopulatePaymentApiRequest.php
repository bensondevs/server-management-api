<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

class PopulatePaymentApiRequest extends FormRequest
{
    use PopulateRequestOptions;

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
            //
        ];
    }

    public function options()
    {
        if ($orderId = $this->get('order_id')) {
            $this->addWhere([
                'column' => 'order_id',
                'value' => $orderId,
            ]);
        }

        $this->addWhere([
            'column' => 'user_id',
            'value' => $this->user()->id,
        ]);

        return $this->collectOptions();
    }
}
