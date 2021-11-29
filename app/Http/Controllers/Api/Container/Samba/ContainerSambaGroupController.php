<?php

namespace App\Http\Controllers\Api\Container\Samba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Samba\Group\{
    DeleteSambaGroupRequest as DeleteGroupRequest,
    User\AddSambaGroupUserRequest as AddGroupUserRequest,
    User\RemoveSambaGroupUserRequest as RemoveGroupUserRequest,
};

use App\Models\{ Container, SambaGroup, SambaUser };

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
        $this->samba->setModel($container);
        $groups = $this->samba->groups();
        return response()->json(['samba_groups' => $groups]);
    }

    /**
     * Show samba group details
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $group
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Container $container, SambaGroup $group)
    {
        $group->load(['shares', 'users']);
        return response()->json(['samba_group' => $group]);
    }

    /**
     * Add samba user to samba group
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $sambaGroup
     * @param \App\Models\SambaUser  $sambaUser
     * @return Illuminate\Support\Facades\Response
     */
    public function addUser(
        Container $container, 
        SambaGroup $sambaGroup, 
        SambaUser $sambaUser
    ) {
        $this->samba->setModel($container);
        $this->samba->addGroupUser($sambaGroup, $sambaUser);
        return apiResponse($this->samba);
    }

    /**
     * Remove samba user from samba group
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaGroup  $sambaGroup
     * @param \App\Models\SambaUser  $sambaUser
     */
    public function removeUser(
        Container $container, 
        SambaGroup $sambaGroup, 
        SambaUser $sambaUser
    ) {
        $this->samba->setModel($container);
        $this->samba->removeGroupUser($sambaGroup, $sambaUser);
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
        SambaGroup $sambaGroup
    ) {
        $this->samba->setModel($container);
        $this->samba->deleteGroup($sambaGroup);
        return apiResponse($this->samba);
    }
}
