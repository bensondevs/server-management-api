<?php

namespace App\Traits\Repositories;

use App\Http\Resources\SambaShareResource;

use App\Jobs\Container\Samba\Share\{
    CreateSambaShare as Create,
    DeleteSambaShare as Delete,
    Group\AddSambaShareGroup as AddGroup,
    Group\RemoveSambaShareGroup as RemoveGroup,
    User\AddSambaShareUser as AddUser,
    User\RemoveSambaShareUser as RemoveUser,
};

use App\Models\{ 
    SambaShare as Share, 
    SambaUser, 
    SambaGroup, 
    SambaShareGroup, 
    SambaShareUser 
};

trait SambaShare 
{
    /**
     * Get existing samba shares in container
     * 
     * @return array
     */
    public function shares()
    {
        $container = $this->getModel();
        $shares = $container->sambaShares;
        return SambaShareResource::collection($shares);
    }

    /**
     * Send command to create samba share
     * 
     * @param string  $shareName
     * @return bool
     */
    public function createShare(string $shareName)
    {
        try {
            $container = $this->getModel();
            $job = new Create($container, $shareName);
            $container->trackDispatch($job);
            
            $this->setSuccess('Creating share for directory...');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed creating share for directory.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Send command to delete samba share
     * 
     * @param Share  $share
     * @return bool
     */
    public function deleteShare(Share $share)
    {
        try {
            $container = $this->getModel();
            $job = new Delete($share);
            $container->trackDispatch($job);

            $this->setSuccess('Deleting share...');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed to delete share', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Send command to add user to share
     * 
     * @param  Share  $share
     * @param  \App\Models\SambaUser  $user
     * @return bool
     */
    public function addShareUser(Share $share, SambaUser $user)
    {
        try {
            $container = $share->container;
            $job = new AddUser($share, $user);
            $container->trackDispatch($job);
            
            $this->setSuccess('Adding user to share...');       
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed adding share user.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Send command to unlink user to share
     * 
     * @param  \App\Models\SambaShareUser  $shareUser
     * @return bool
     */
    public function removeShareUser(SambaShareUser $shareUser)
    {
        try {
            $container = $shareUser->container;
            $job = new RemoveUser($shareUser);
            $container->trackDispatch($job);
            
            $this->setSuccess('Removing share user...');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed removing share user.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Send command to link group to share
     * 
     * @param  Share  $share
     * @param  \App\Models\SambaGroup  $group
     * @return bool
     */
    public function addShareGroup(Share $share, SambaGroup $group)
    {
        try {
            $container = $share->container;
            $job = new AddGroup($share, $group);
            $container->trackDispatch($job);
            
            $this->setSuccess('Adding group to share...');      
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed adding group to share.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Send command to unlink group from share
     * 
     * @param  \App\Models\SambaShareGroup  $shareGroup
     * @return bool
     */
    public function removeShareGroup(SambaShareGroup $shareGroup)
    {
        try {
            $container = $shareGroup->container;
            $job = new RemoveGroup($shareGroup);
            $container->trackDispatch($job);
            
            $this->setSuccess('Removing group from share...');      
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed removing group from share.', $error);
        }

        return $this->returnResponse();
    }
}