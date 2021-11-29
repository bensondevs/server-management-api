<?php

namespace App\Traits\Repositories;

use Illuminate\Database\QueryException;

use App\Jobs\Containers\Nfs\Folder\{
    CreateNfsFolder as CreateFolder,
    DeleteNfsFolder as DeleteFolder,
};

use App\Http\Resources\NfsFolderResource;

use App\Models\NfsFolder;

trait NfsFolder 
{
    /**
     * Populate Existing NFS Folders
     * 
     * @return array
     */
    public function folders()
    {
        $container = $this->getModel();
        $folders = $container->nfsFolders;
        return NfsFolderResource::collection($folders);
    }

    /**
     * Create Folder Job Execution
     * 
     * @param string  $folderName
     * @return bool
     */
    public function createFolder(string $folderName)
    {
        try {
            $container = $this->getModel();
            $create = new CreateFolder($container, $folderName);
            $container->trackDispatch($create);

            $this->setSuccess('Creating folder in NFS server.');
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed to create folder in NFS server.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Delete Folder Job Execution
     * 
     * @param \App\Models\NfsFolder  $nfsFolder
     * @return bool
     */
    public function deleteFolder(NfsFolder $nfsFolder)
    {
        try {
            $container = $this->getModel();
            $delete = new DeleteFolder($folder);
            $container->trackDispatch($delete);

            $this->setSuccess('Deleting folder in NFS server.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to folder in NFS server.');
        }

        return $this->returnResponse();
    }
}