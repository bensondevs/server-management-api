<?php

namespace App\Http\Controllers\Api\Container\Samba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Samba\Share\{
    CreateSambaShareRequest as CreateRequest,
    DeleteSambaShareRequest as DeleteShareRequest,
    User\AddSambaShareUserRequest as AddUserRequest,
    Group\AddSambaShareGroupRequest as AddGroupRequest,
};
use App\Http\Resources\{ 
    SambaShareResource, 
    SambaGroupResource, 
    SambaUserResource 
};
use App\Models\{ 
    Container, 
    SambaShare, 
    SambaGroup, 
    SambaUser,
    SambaShareUser,
    SambaShareGroup
};

use App\Repositories\ContainerSambaRepository;

class ContainerSambaShareController extends Controller
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
     * Populate with existing samba shares
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function sambaShares(Container $container)
    {
        $this->samba->setModel($container);
        $shares = $this->samba->shares();
        return response()->json(['samba_shares' => $shares]);
    }

    /**
     * Create samba share
     * 
     * @param CreateRequest  $request
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function create(CreateRequest $request, Container $container)
    {
        $this->samba->setModel($container);
        $directoryName = $request->input('directory_name');
        $this->samba->createShare($directoryName);
        return apiResponse($this->samba);
    }

    /**
     * Show samba share details
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Container $container, SambaShare $share)
    {
        $share->load(['groups', 'users']);
        $share = new SambaShareResource($share);
        return response()->json(['samba_share' => $share]);
    }

    /**
     * Populate samba share users
     * 
     * @param  \App\Models\Container   $container
     * @param  \App\Models\SambaShare  $share
     * @return \Illuminate\Support\Facades\Response
     */
    public function shareUsers(Container $container, SambaShare $share)
    {
        $users = $share->users;
        $users = SambaUserResource::collection($users);

        return response()->json(['samba_users' => $users]);
    }

    /**
     * Add Samba Share User
     * 
     * @param  \App\Models\Container  $container
     * @param  \App\Models\SambaShare  $share
     * @return \Illuminate\Support\Facades\Response
     */
    public function addUser(Container $container, SambaShare $share, SambaUser $user)
    {
        $this->samba->setModel($container);
        $this->samba->addShareUser($share, $user);
        return apiResponse($this->samba);
    }

    /**
     * Remove Samba Share User
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @param \App\Models\SambaUser  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function removeUser(Container $container, SambaShare $share, SambaUser $user) 
    {
        $this->samba->setModel($container);
        $shareUser = SambaShareUser::where('samba_share_id', $share->id)
            ->where('samba_user_id', $user->id)
            ->first();
        $this->samba->removeShareUser($shareUser);

        return apiResponse($this->samba);
    }

    /**
     * Populate samba share groups
     * 
     * @param  \App\Models\Container  $container
     * @param  \App\Models\SambaShare $share
     * @return \Illuminate\Support\Facades\Response
     */
    public function shareGroups(Container $container, SambaShare $share)
    {
        $groups = $share->groups;
        $groups = SambaGroupResource::collection($groups);

        return response()->json(['samba_groups' => $groups]);
    }

    /**
     * Add Samba Share Group
     * 
     * @param AddSambaShareGroupRequest
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @return \Illuminate\Support\Facades\Response
     */
    public function addGroup(Container $container, SambaShare $share, SambaGroup $group) 
    {
        $this->samba->setModel($container);
        $this->samba->addShareGroup($share, $group);

        return apiResponse($this->samba);
    }

    /**
     * Remove Samba Share Group
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @param \App\Models\SambaGroup  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function removeGroup(Container $container, SambaShare $share, SambaGroup $group) 
    {
        $this->samba->setModel($container);
        $shareGroup = SambaShareGroup::where('samba_share_id', $share->id)
            ->where('samba_group_id', $group->id)
            ->firstOrFail();
        $this->samba->removeShareGroup($shareGroup);

        return apiResponse($this->samba);
    }

    /**
     * Delete samba share
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(Container $container, SambaShare $share) 
    {
        $this->samba->setModel($container);
        $this->samba->deleteShare($share);

        return apiResponse($this->samba);
    }
}
