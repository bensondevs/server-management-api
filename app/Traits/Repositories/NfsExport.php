<?php

namespace App\Traits\Repositories;

use App\Jobs\Container\Nfs\Export\{
    CreateNfsExport as CreateExport,
    UpdateNfsExport as UpdateExport,
    DeleteNfsExport as DeleteExport
};

use App\Models\NfsExport as Export;
use App\Http\Resources\NfsExportResource;

trait NfsExport 
{
    /**
     * Populate Existing NFS Exports
     * 
     * @return array
     */
    public function exports()
    {
        $container = $this->getModel();
        $exports = $container->nfsExports;
        return NfsExportResource::collection($exports);
    }

    /**
     * Create NFS Export
     * 
     * @param array  $exportData
     * @return bool
     */
    public function createExport(array $exportData = [])
    {
        try {
            $container = $this->getModel();
            $job = new CreateExport($container, [
                'nfs_folder_id' => $exportData['nfs_folder_id'],
                'ip_address' => $exportData['ip_address'],
                'permissions' => $exportData['permissions'],
            ]);
            $container->trackDispatch($job);
            
            $this->setSuccess('Creating Export...');        
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed creating export', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Update NFS Export
     * 
     * @param Export  $nfsExport
     * @param array  $exportData
     * @return bool
     */
    public function updateExport(Export $export, array $exportData = [])
    {
        try {
            $container = $export->container;
            $job = new UpdateExport($export, $exportData);
            $container->trackDispatch($job);

            $this->setSuccess('Updating export...');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            return $this->setError('Failed to update NFS Export.');
        }

        return $this->returnResponse();
    }

    /**
     * Delete NFS Export
     * 
     * @param Export  $nfsExport
     * @return bool
     */
    public function deleteExport(Export $export)
    {
        try {
            $container = $this->getModel();
            $job = new DeleteExport($export);
            $container->trackDispatch($job);
            
            $this->setSuccess('Deleting export...');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed deleting export', $error);
        }

        return $this->returnResponse();
    }
}