<?php

namespace App\Http\Requests\Containers\Samba\Share\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\{ Container, SambaShare };

class AddSambaShareUserRequest extends FormRequest
{
    /**
     * Target container model container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Target samba share model container
     * 
     * @var \App\Models\SambaShare|null
     */
    private $sambaShare;

    /**
     * Added samba user model container
     * 
     * @var \App\Models\SambaUser|null
     */
    private $sambaUser;

    /**
     * Get target server container from the route parameter
     * of `container` or supplied input of `container_id` or
     * target samba share's container or added samba user's container
     * 
     * @return \App\Models\Container|abort 404
     */
    public function getServerContainer()
    {
        // Target container has been set in class attribute
        // Just return it directly without doing any process
        // This will make the get action of container in current
        // request faster, because it needs no process.
        if ($this->serverContainer) return $this->serverContainer;

        // Get container from supplied route parameter of `container`
        // If the supplied paratemeter is filled, then just set the
        // found model to class attribute and return it as method return
        if ($container = $this->route('container')) {
            return $this->serverContainer = $container;
        }

        if ($id = $this->input('container_id')) {
            $container = Container::findOrFail($id);
            return $this->serverContainer = $container;
        }

        if ($sambaShare = $this->sambaShare) {
            $this->serverContainer = $sambaShare->container;
            return $this->serverContainer;
        }

        if ($sambaUser = $this->sambaUser) {
            $this->serverContainer = $sambaUser->container;
            return $this->serverContainer;
        }

        $sambaUser = $this->getSambaUser();
        $container = $sambaUser->container;
        return $this->serverContainer = $container;
    }

    /**
     * Get samba share from route parameter of `share` or
     * supplied input of `samba_share_id`.
     * 
     * @return \App\Models\SambaShare|abort 404
     */
    public function getSambaShare()
    {
        // Samba share is already set, just return it
        if ($this->sambaShare) return $this->sambaShare;

        // Samba share has been supplied at the route parameter
        // Just set it to class attribute and return it
        if ($share = $this->route('share')) {
            return $this->sambaShare = $share;
        }

        // Get the samba share input of `samba_share_id`
        // Find it in database, if exists, return it
        // If not, just give the error of 404
        $id = $this->input('samba_share_id');
        return $this->sambaShare = SambaShare::findOrFail($id);
    }

    /**
     * Get samba user from supplied parameter `user` of route or
     * supplied input of `samba_user_id`
     * 
     * @return \App\Models\SambaUser|abort 404
     */
    public function getSambaUser()
    {
        if ($this->sambaUser) return $this->sambaUser;

        if ($user = $this->route('user')) {
            return $this->sambaUser = $user;
        }

        $id = $this->input('samba_user_id');
        return $this->sambaUser = SambaUser::findOrFail($id);
    }

    /**
     * Prepare request before being validated
     * 
     * Currently, this method is used to load important
     * data for the request processing in backend logic
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        // Get samba share to be processed
        // as the target share to be added of the user
        $this->getSambaShare();

        // Get the target samba user to be added 
        // into the samba share in backend process 
        $this->getSambaUser();

        // Get the contaiener as target where the 
        // data is actively running at.
        $this->getServerContainer();
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
