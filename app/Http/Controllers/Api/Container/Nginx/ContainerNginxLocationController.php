<?php

namespace App\Http\Controllers\Api\Container\Nginx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Nginx\{
    CreateNginxLocationRequest as CreateRequest
};

use App\Models\{ Container, NginxLocation };
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
        $this->nginx->setModel($container);
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
        $this->nginx->setModel($container);
        $nginxLocationName = $request->input('nginx_location');
        $this->nginx->createLocation($nginxLocationName);
        return apiResponse($this->nginx);
    }

    /**
     * Remove NGINX location
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\NginxLocation  $location
     * @return \Illuminate\Support\Facades\Response
     */
    public function remove(Container $container, NginxLocation $location) 
    {
        $this->nginx->setModel($container);
        $this->nginx->removeLocation($location);
        return apiResponse($this->nginx);
    }
}
