<?php

namespace App\Http\Requests\Containers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Traits\PopulateRequestOptions;

class PopulateContainersRequest extends FormRequest
{
    use RequestHasRelations;
    use PopulateRequestOptions;

    protected $relationNames = [
        'with_customer' => true,
        'with_server' => true,
        'with_subnet_ip' => true,
        'with_service_plan' => true,
        'with_order' => false,
        'with_vpn_users' => false,
        'with_nfs_exports' => false,
    ];

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
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
            //
        ];
    }

    public function options()
    {
        $relations = $this->relations();
        $this->setWiths($relations);

        return $this->collectOptions();
    }
}
