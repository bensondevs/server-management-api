<?php

namespace App\Http\Controllers\Api\Container\Nginx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Nginx\{
    CreateLocationRequest as CreateRequest,
    DeleteLocationRequest as DeleteRequest
};

use App\Models\Container;

use App\Repositories\ContainerNginxRepository as NginxRepository;

class ContainerNginxLocationController extends Controller
{
    /**
     * NGINX repository class container
     * 
     * @var NginxRepository
     */
    private $nginx;

    /**
     * Controller constructor class
     * 
     * @param NginxRepository  $nginx
     * @return void
     */
    public function __construct(NginxRepository $nginx)
    {
        $this->nginx = $nginx;
    }

    /**
     * Populate NGINX Locations
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function nginxLocations(Container $container)
    {
        $locations = $this->nginx->locations();
        return response()->json(['nginx_locations' => $locations]);
    }

    /**
     * Create NGINX location
     * 
     * @param CreateRequest  $request
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function create(CreateRequest $request, Container $container)
    {
        $locationName = $request->input('location_name');
        $this->nginx->createLocation($locationName);
        return apiResponse($this->nginx);
    }

    /**
     * Delete NGINX location
     * 
     * @param DeleteRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\NginxLocation  $location
     * @return \Illuminate\Support\Facades\Response
     */
    public function delete(
        DeleteRequest $request, 
        Container $container, 
        NginxLocation $location
    ) {
        $this->nginx->deleteLocation($location);
        return apiResponse($this->nginx);
    }
}
