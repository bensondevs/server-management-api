<?php

namespace App\Http\Controllers\Api\Container\Samba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Samba\User\{
    CreateSambaUserRequest as CreateRequest,
    ChangeSambaUserPasswordRequest as ChangePasswordRequest,
    DeleteSambaUserRequest as DeleteRequest,
};
use App\Http\Resources\SambaUserResource;

use App\Models\{ Container, SambaGroup, SambaUser };
use App\Repositories\ContainerSambaRepository;

class ContainerSambaUserController extends Controller
{
    /**
     * Samba Repository Class Container
     * 
     * @var \App\Repositories\ContainerSambaRepository
     */
    private $samba;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\ContainerSambaRepository  $samba
     */
    public function __construct(ContainerSambaRepository $samba)
    {
        $this->samba = $samba;
    }

    /**
     * Populate with existing samba users
     * 
     * @param \App\Models\Container
     * @return Illuminate\Support\Facades\Response
     */
    public function sambaUsers(Container $container)
    {
        $users = $container->sambaUsers;
        $users = SambaUserResource::collection($users);
        return response()->json(['samba_users' => $users]);
    }

    /**
     * Populate with non-group users
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $group
     * @return Illuminate\Support\Facades\Response
     */
    public function notInGroup(Container $container, SambaGroup $group)
    {
        $users = SambaUser::whereInContainer($container)
            ->whereNotInGroup($group)
            ->get();
        $users = SambaUserResource::collection($users);
        return response()->json(['samba_users' => $users]);
    }

    /**
     * Create Container Samba User
     * 
     * @param CreateRequest  $request
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function create(CreateRequest $request, Container $container)
    {
        $input = $request->validated();

        $this->samba->setModel($container);
        $this->samba->createUser($input);
        
        return apiResponse($this->samba);
    }

    /**
     * Change Container Samba User Passwod
     * 
     * @param ChangePasswordRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaUser  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function changePassword(
        ChangePasswordRequest $request,
        Container $container,
        SambaUser $user
    ) {
        $password = $request->input('password');

        $this->samba->setModel($container);
        $this->samba->changeUserPassword($user, $password);

        return apiResponse($this->samba);
    }

    /**
     * Delete Container Samba User
     * 
     * @param DeleteRequest  $request
     * @param \App\Models\SambaUser  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(Container $container, SambaUser $user) 
    {
        $this->samba->setModel($container);
        $this->samba->deleteUser($user);

        return apiResponse($this->samba);
    }
}
