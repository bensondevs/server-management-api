<?php

namespace App\Http\Controllers\Api\Container;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ContainerSambaRepository;

class ContainerSambaController extends Controller
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
     * Get container samba service informations
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function containerSamba(Container $container)
    {
        $this->samba->setModel($container);
        return response()->json([
            'samba_informations' => $this->samba->completeCheck()
        ]);
    }

    /**
     * Get Samba Settings
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function settings(Container $container)
    {
        $this->samba->setModel($container);
        $settings = $this->samba->settings();
        return response()->json(['samba_settings' => $settings]);
    }

    /**
     * Send command to start samba service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function start(Container $container)
    {
        $this->samba->setModel($container);
        $status = $this->samba->start();
        return apiResponse($this->samba, ['samba_status' => $status]);
    }

    /**
     * Send command to stop samba service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function stop(Container $container)
    {
        $this->samba->setModel($container);
        $this->samba->stop();
        return apiResponse($this->samba, ['samba_status' => $status]);
    }

    /**
     * Send command to reload samba service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function reload(Container $container)
    {
        $this->samba->setModel($container);
        $this->samba->reload();
        return apiResponse($this->samba, ['samba_status' => $status]);
    }

    /**
     * Send command to restart samba service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function restart(Container $container)
    {
        $this->samba->setModel($container);
        $this->samba->restart();
        return apiResponse($this->samba, ['samba_status' => $status]);
    }

    /**
     * Send command to enable samba service on boot
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function enable(Container $container)
    {
        $this->samba->setModel($container);
        $enability = $this->samba->enable();
        return apiResponse($this->samba, ['samba_enability' => $enability]);
    }

    /**
     * Send command to disable samba service on boot
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function disable(Container $container)
    {
        $this->samba->setModel($container);
        $enability = $this->samba->disable();
        return apiResponse($this->samba, ['samba_enability' => $enability]);
    }

    /**
     * Send command to toggle samba service enability on boot
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function toggleEnability(Container $container)
    {
        $this->samba->setModel($container);
        $status = $request->status ?: 'disable';
        $this->samba->toggleEnability($status);
        return apiResponse($this->samba);
    }

    /**
     * Send command to bind samba to Public IP
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function bindPublicIp(Container $container)
    {
        $this->samba->setModel($container);
        $this->samba->bindPublicIp();
        return apiResponse($this->samba);
    }

    /**
     * Send command to unbind samba to Public IP
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function unbindPublicIp(Container $container)
    {
        $this->samba->setModel($container);
        $this->samba->unbindPublicIp();
        return apiResponse($this->samba);
    }

    /**
     * Send command to modify binded subnet
     * 
     * @param Illuminate\Http\Request  $request
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function modifyBindedSubnet(Request $request, Container $container)
    {
        //
    }
}
