<?php

namespace App\Http\Controllers\Api\Container\Nfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Container;

use App\Repositories\ContainerNfsRepository;

class ContainerNfsController extends Controller
{
    /**
     * Container NFS Repository class container
     * 
     * @var \App\Repositories\ContainerNfsRepository
     */
    private $nfs;

    /**
     * Controller constructor function
     * 
     * @param App\Repositories\ContainerNfsRepository  $nfs
     * @return void
     */
    public function __construct(ContainerNfsRepository $nfs)
    {
        $this->nfs = $nfs;
    }

    /**
     * Do complete check for Container NFS and get NFS informations
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function containerNfs(Container $container)
    {
        $this->nfs->setModel($container);
        $informations = $this->nfs->completeCheck();

        return response()->json(['nfs_informations' => $informations]);
    }

    /**
     * Start NFS Service and return current status
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function start(Container $container)
    {
        $this->nfs->setModel($container);
        $status = $this->nfs->start();
        return apiResponse($this->nfs, ['nfs_status' => $status]);
    }

    /**
     * Restart NFS Service and return current status
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function restart(Container $container)
    {
        $this->nfs->setModel($container);
        $status = $this->nfs->restart();

        return apiResponse($this->nfs, ['nfs_status' => $status]);
    }

    /**
     * Reload NFS Service and return current status
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function reload(Container $container)
    {
        $this->nfs->setModel($container);
        $status = $this->nfs->reload();

        return apiResponse($this->nfs, ['nfs_status' => $status]);
    }

    /**
     * Stop NFS Service and return current status
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function stop(Container $container)
    {
        $this->nfs->setModel($container);
        $status = $this->nfs->stop();
        return apiResponse($this->nfs, ['nfs_status' => $status]);
    }

    /**
     * Enable NFS Service and return current start on boot NFS status
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function enable(Container $container)
    {
        $this->nfs->setModel($container);
        $enability = $this->nfs->enable();
        return apiResponse($this->nfs, [
            'nfs_enability' => $enability
        ]);
    }

    /**
     * Disable NFS Service and return current start on boot NFS status
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function disable(Container $container)
    {
        $this->nfs->setModel($container);
        $enability = $this->nfs->disable();
        return apiResponse($this->nfs, [
            'nfs_enability' => $enability
        ]);
    }
}
