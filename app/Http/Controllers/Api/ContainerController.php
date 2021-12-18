<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Containers\{
    PopulateContainersRequest as PopulateRequest,
    FindContainerRequest as FindRequest
};
use App\Http\Resources\ContainerResource;

use App\Models\{ User, Container };
use App\Repositories\ContainerRepository;

class ContainerController extends Controller
{   
    /**
     * Container Class Repository container
     * 
     * @var ContainerRepository
     */
    private $container;

    /**
     * Controller constructor function
     * 
     * @param \App\Repositories\ContainerRepository  $container
     * @return void
     */
    public function __construct(ContainerRepository $container)
    {
    	$this->container = $container;
    }

    /**
     * Check a user has container
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function exists()
    {
        $user = auth()->user();
        $exists = $user->containers()->active()->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Populate containers that belongs to users
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function userContainers()
    {
        return response()->json(['containers' => auth()->user()->containers()]);
    }

    /**
     * Get current container that has been selected by user
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function current(Request $request)
    {
        $user = auth()->user();
        $container = $this->container->current($user);
        return response()->json(['container' => $container]);
    }

    /**
     * Show container informations of user
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Container $container)
    {
        $container = new ContainerResource($container);
        return response()->json(['container' => $container]);
    }

    /**
     * Set a container to be current container
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function select(Container $container)
    {
        $this->container->setModel($container);
        $this->container->setCurrent();

        return apiResponse($this->container);
    }
}