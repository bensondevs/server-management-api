<?php

namespace App\Http\Controllers\Api\Container\Samba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Samba\Share\{
    CreateSambaShareRequest as CreateRequest,
    DeleteSambaShareRequest as DeleteShareRequest,
    User\AddSambaShareUserRequest as AddUserRequest,
    User\RemoveSambaShareUserRequest as RemoveUserRequest,
    Group\AddSambaShareGroupRequest as AddGroupRequest,
    Group\RemoveSambaShareGroupRequest as RemoveGroupRequest,
};
use App\Http\Resources\SambaShareResource;
use App\Models\{ Container, SambaShare };

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
        $input = $request->validated();
        $this->samba->setModel($container);
        $this->samba->createUser($input);
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
     * Add Samba Share User
     * 
     * @param AddUserRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @return Illuminate\Support\Facades\Response
     */
    public function addUser(AddUserRequest $request, Container $container, SambaShare $share)
    {
        $this->samba->setModel($container);
        $user = $request->getSambaUser();
        $this->samba->addShareUser($share, $user);
        return apiResponse($this->samba);
    }

    /**
     * Remove Samba Share User
     * 
     * @param RemoveUserRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @param \App\Models\SambaUser  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function removeUser(
        RemoveUserRequest $request, 
        SambaShare $share, 
        SambaUser $user
    ) {
        //
    }

    /**
     * Add Samba Share Group
     * 
     * @param AddGroupRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @return Illuminate\Support\Facades\Response
     */
    public function addGroup(
        AddGroupRequest $request, 
        Container $container, 
        SambaShare $share
    ) {
        //
    }

    /**
     * Remove Samba Share Group
     * 
     * @param RemoveGroupRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @param \App\Models\SambaUser  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function removeGroup(
        RemoveGroupRequest $request,
        Container $container,
        SambaShare $share,
        SambaUser $user
    ) {
        //
    }

    /**
     * Delete samba share
     * 
     * @param DeleteRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\SambaShare  $share
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(
        DeleteRequest $request, 
        Container $container, 
        SambaShare $share
    ) {
        //
    }
}
