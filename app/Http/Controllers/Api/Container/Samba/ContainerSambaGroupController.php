<?php

namespace App\Http\Controllers\Api\Container\Samba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Samba\Group\{
    DeleteSambaGroupRequest as DeleteGroupRequest,
    User\AddSambaGroupUserRequest as AddGroupUserRequest,
    User\RemoveSambaGroupUserRequest as RemoveGroupUserRequest,
};
use App\Http\Resources\{
    SambaGroupResource,
    SambaUserResource
};

use App\Models\{ Container, SambaGroup, SambaUser, SambaGroupUser };
use App\Repositories\ContainerSambaRepository;

class ContainerSambaGroupController extends Controller
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
     * Populate with existing samba groups
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function sambaGroups(Container $container)
    {
        $groups = $container->sambaGroups;
        $groups = SambaGroupResource::collection($groups);
        return response()->json(['samba_groups' => $groups]);
    }

    /**
     * Show samba group details
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $group
     * @return \Illuminate\Support\Facades\Response
     */
    public function show(Container $container, SambaGroup $group)
    {
        $group->load(['shares', 'users']);
        return response()->json(['samba_group' => $group]);
    }

    /**
     * Show samba group users
     * 
     * @param  \App\Models\Container   $container
     * @param  \App\Models\SambaGroup  $group
     * @return \Illuminate\Support\Facades\Response
     */
    public function groupUsers(Container $container, SambaGroup $group)
    {
        $users = $group->users;
        $users = SambaUserResource::collection($users);

        return response()->json(['samba_users' => $users]);
    }

    /**
     * Add samba user to samba group
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $group
     * @param \App\Models\SambaUser  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function addUser(
        Container $container, 
        SambaGroup $group, 
        SambaUser $user
    ) {
        $this->samba->setModel($container);
        $this->samba->addGroupUser($group, $user);
        return apiResponse($this->samba);
    }

    /**
     * Remove samba user from samba group
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $group
     * @param \App\Models\SambaUser  $user
     */
    public function removeUser(
        Container $container, 
        SambaGroup $group, 
        SambaUser $user
    ) {
        $this->samba->setModel($container);
        $groupUser = SambaGroupUser::where('samba_group_id', $group->id)
            ->where('samba_user_id', $user->id)
            ->firstOrFail();
        $this->samba->removeGroupUser($groupUser);
        return apiResponse($this->samba);
    }

    /**
     * Delete samba group
     * 
     * @param DeleteRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $group
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(
        DeleteRequest $request, 
        Container $container, 
        SambaGroup $group
    ) {
        $this->samba->setModel($container);
        $this->samba->deleteGroup($group);
        return apiResponse($this->samba);
    }
}
