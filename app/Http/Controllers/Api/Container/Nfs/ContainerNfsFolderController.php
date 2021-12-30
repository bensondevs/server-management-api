<?php

namespace App\Http\Controllers\Api\Container\Nfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Nfs\{
    CreateContainerNfsFolderRequest as CreateRequest,
    DeleteContainerNfsFolderRequest as DeleteRequest
};

use App\Http\Resources\NfsFolderResource;
use App\Repositories\ContainerNfsRepository as NfsRepository;
use App\Models\{ Container, NfsFolder };

class ContainerNfsFolderController extends Controller
{
    /**
     * Container NFS repository class container
     * 
     * @var \App\Repositories\ContainerNfsRepository
     */
    private $nfs;

    /**
     * Controller constructor function
     * 
     * @return void
     */
    public function __construct(NfsRepository $nfs)
    {
        $this->nfs = $nfs;
    }

    /**
     * Populate Existing NFS Folders
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function nfsFolders(Container $container)
    {
        $nfsFolders = $container->nfsFolders;
        $nfsFolders = NfsFolderResource::collection($nfsFolders);
        return response()->json(['nfs_folders' => $nfsFolders]);
    }

    /**
     * Create NFS Folder
     * 
     * @param CreateRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function create(CreateRequest $request, Container $container)
    {
        $this->nfs->setModel($container);

        $folderName = $request->input('folder_name');
        $this->nfs->createFolder($folderName);

        return apiResponse($this->nfs);
    }

    /**
     * Delete NFS Folder
     * 
     * @param DeleteRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\NfsFolder  $folder
     * @return \Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request, Container $container, NfsFolder $folder)
    {   
        $this->nfs->setModel($container);
        
        $this->nfs->deleteFolder($folder);
        return apiResponse($this->nfs);
    }
}
