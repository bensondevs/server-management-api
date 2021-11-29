<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\FindContainerRequest as FindRequest;
use App\Http\Requests\Containers\Nfs\CreateContainerNfsExportRequest as CreateExport;
use App\Http\Requests\Containers\Nfs\DeleteContainerNfsExportRequest as DeleteExport;

use App\Models\Container;

use App\Repositories\ContainerNfsRepository;

class ContainerNfsController extends Controller
{
    private $containerNfs;

    public function __construct(ContainerNfsRepository $containerNfs)
    {
        $this->containerNfs = $containerNfs;
    }

    public function manage(Container $container)
    {
        $this->containerNfs->setModel($container);
        $exports = $this->containerNfs->exports();

        return view('dashboard.containers.manage.nfs', compact('exports'));
    }

    public function checkStatus(Container $container)
    {
        $this->containerNfs->setModel($container);
        $status = $this->containerNfs->checkStatus();

        return apiResponse($this->containerNfs, ['nfs_status' => $status]);
    }

    public function checkPidNumbers(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNfs->setModel($container);
        $pidNumbers = $this->containerNfs->checkPidNumbers();

        return apiResponse($this->containerNfs, ['pid_numbers' => $pidNumbers]);
    }

    public function createExport(CreateExportRequest $request)
    {
        $input = $request->validated();
        $this->containerNfs->createExport($input);

        flash_repository($this->containerNfs);

        $container = $request->getServerContainer();
        return redirect()->route('dashboard.containers.nfs.manage', ['id' => $container->id]);
    }

    public function updateExport(UpdateExportRequest $request)
    {
        $export = $request->getNfsExport();
        $input = $request->validated();

        $this->containerNfs->updateExport($export, $input);

        flash_repository($this->containerNfs);

        $container = $request->getServerContainer();
        return redirect()->route('dashboard.containers.nfs.manage', ['id' => $container->id]);
    }

    public function deleteExport(DeleteExportRequest $request)
    {
        $export = $request->getNfsExport();
        $this->containerNfs->deleteExport($export);

        flash_repository($this->containerNfs);

        return redirect()->route('dashboard.containers.nfs.manage', ['id' => $export->container_id]);
    }

    public function start(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNfs->setModel($container);
        $this->containerNfs->start();

        return apiResponse($this->containerNfs);
    }

    public function restart(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNfs->setModel($container);
        $this->containerNfs->restart();

        return apiResponse($this->containerNfs);
    }

    public function reload(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNfs->setModel($container);
        $this->containerNfs->reload();

        return apiResponse($this->containerNfs);
    }

    public function stop(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNfs->setModel($container);
        $this->containerNfs->stop();

        return apiResponse($this->containerNfs);
    }

    public function toggleStartOnBoot(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNfs->setModel($container);

        $status = $request->input('status');
        $this->containerNfs->toggleStartOnBoot($status);

        return apiResponse($this->containerNfs);
    }
}
