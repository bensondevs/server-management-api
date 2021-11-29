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

use App\Models\{ SambaShare as Share, SambaUser, SambaGroup };

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
     * @param \App\Models\SambaShare  $share
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
}