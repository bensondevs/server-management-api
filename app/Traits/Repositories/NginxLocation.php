<?php

namespace App\Traits\Repositories;

use App\Jobs\Container\Nginx\Location\{
    CreateNginxLocation as CreateLocation,
    RemoveNginxLocation as RemoveLocation
};
use App\Models\NginxLocation as Location;
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
     * Remove NGINX Location
     * 
     * @param Location  $nginxLocation
     * @return bool
     */
    public function removeLocation(Location $nginxLocation)
    {
        try {
            $container = $this->getModel();
            $job = new RemoveLocation($nginxLocation);
            $container->trackDispatch($job);
            
            $this->setSuccess('Removing NGINX location...');        
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed for removing NGINX location.', $error);
        }

        return $this->returnResponse();
    }
}