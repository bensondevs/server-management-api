<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

class PopulateOrdersApiRequest extends FormRequest
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
        if ($start = $this->get('start')) {
            $start = carbon($start)->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $start,
            ]);
        }

        if ($end = $this->get('end')) {
            $end = carbon($end)->toDateTimeString();
            $this->addWhere([
                'column' => 'created_at',
                'operator' => '<=',
                'value' => $end,
            ]);
        }

        $this->addWhere([
            'column' => 'customer_id',
            'value' => $this->user()->id,
        ]);

        $this->setWiths(['container', 'payment']);

        $this->addOrderBy('order_number', 'DESC');

        return $this->collectOptions();
    }
}
