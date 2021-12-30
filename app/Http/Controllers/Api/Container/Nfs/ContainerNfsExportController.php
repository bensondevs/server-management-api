<?php

namespace App\Http\Controllers\Api\Container\Nfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Nfs\{
    CreateContainerNfsExportRequest as CreateRequest,
    UpdateContainerNfsExportRequest as UpdateRequest,
    DeleteContainerNfsExportRequest as DeleteRequest,
};

use App\Http\Resources\NfsExportResource;
use App\Repositories\ContainerNfsRepository;
use App\Models\{ Container, NfsExport };

class ContainerNfsExportController extends Controller
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
     * Populate existing NFS Exports
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function nfsExports(Container $container)
    {
        $nfsExports = $container->nfsExports;
        $nfsExports = NfsExportResource::collection($nfsExports);
        return response()->json(['nfs_exports' => $nfsExports]);
    }

    /**
     * Create Export of NFS
     * 
     * @param CreateRequest  $request
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function create(CreateRequest $request, Container $container)
    {
        $input = $request->validated();

        $this->nfs->setModel($container);
        $this->nfs->createExport($input);

        return apiResponse($this->nfs);
    }

    /**
     * Show export information
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\NfsExport  $nfsExport
     * @return \Illuminate\Support\Facades\Response
     */
    public function show(Container $container, NfsExport $nfsExport)
    {
        $nfsExport = new NfsExportResource($nfsExport);
        return response()->json(['nfs_export' => $nfsExport]);
    }

    /**
     * Update NFS Export
     * 
     * @param UpdateRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\NfsExport  $nfsExport
     * @return \Illuminate\Support\Facades\Response
     */
    public function update(UpdateRequest $request, Container $container, NfsExport $nfsExport) 
    {
        $input = $request->validated();

        $this->nfs->setModel($container);
        $this->nfs->updateExport($nfsExport, $input);

        return apiResponse($this->nfs);
    }

    /**
     * Delete NFS Export
     * 
     * @param DeleteReqest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\NfsExport  $nfsExport
     * @return \Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request, Container $container, NfsExport $nfsExport) 
    {
        $this->nfs->setModel($container);
        $this->nfs->deleteExport($nfsExport);

        return apiResponse($this->nfs);
    }
}
