<?php

namespace App\Http\Requests\Containers;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\RequestHasRelations;

use App\Models\Container;

class FindContainerRequest extends FormRequest
{
    use RequestHasRelations;

    protected $relationNames = [
        'with_customer' => true,
        'with_server' => true,
        'with_subnet_ip' => true,
        'with_service_plan' => true,
        'with_order' => true,
        'with_vpn_users' => true,
        'with_nfs_exports' => true,
        'with_nginx_locations' => true,
        'with_samba_shares' => true,
    ];

    protected $serverContainer;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $id = $this->input('id') ?: $this->input('container_id');
        $relations = $this->relations();
        return $this->serverContainer = Container::with($relations)->findOrFail($id);
    }

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
}
