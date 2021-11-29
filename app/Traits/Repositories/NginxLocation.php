<?php

namespace App\Traits\Repositories;

use App\Jobs\Container\Nginx\{
    CreateNginxLocation as CreateLocation,
    DeleteNginxLocation as DeleteLocation
};

use App\Http\Resources\NginxLocationResource;

trait NginxLocation 
{
    /**
     * Populate Existing NGINX Locations
     * 
     * @return array
     */
    public function locations()
    {
        $container = $this->getModel();
        $locations = $container->nginxLocations;
        return NginxLocationResource::collection($locations);
    }

    /**
     * Create NGINX Location
     * 
     * @param string  $locationName
     * @return bool
     */
    public function createLocation(string $locationName)
    {
        try {
            $container = $this->getModel();
            $job = new CreateLocation($container, $locationName);
            $container->trackDispatch($job);
            
            $this->setSuccess('Creating NGINX location...');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed for creating NGINX location.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Delete NGINX Location
     * 
     * @param \App\Models\NginxLocation  $nginxLocation
     * @return bool
     */
    public function deleteLocation(NginxLocation $nginxLocation)
    {
        try {
            $container = $this->getModel();
            $job = new DeleteLocation($container, $nginxLocation);
            $container->trackDispatch($job);
            
            $this->setSuccess('Removing NGINX location...');        
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed for removing NGINX location.', $error);
        }

        return $this->returnResponse();
    }
}