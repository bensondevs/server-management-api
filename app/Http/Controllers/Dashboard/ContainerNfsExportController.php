<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ContainerNfsRepository;

class ContainerNfsExportController extends Controller
{
    private $containerNfs;

    public function __construct(ContainerNfsRepository $containerNfs)
    {
        $this->containerNfs = $containerNfs;
    }

    public function create()
    {
        return view('dashboard.containers.manage.nfses.exports.create');
    }

    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $this->containerNfs->createExport($input);

        flash_repository($this->containerNfs);

        return redirect()->route('dashboard.containers.nfs.manage', ['id' => $input['container_id']]);
    }

    public function edit(FindRequest $request)
    {
        $export = $request->getNfsExport();

        return view('dashboard.containers.manage.nfses.exports.edit', compact('export'));
    }

    public function update(UpdateRequest $request)
    {
        $export = $request->getNfsExport();
        $input = $request->validated();
        $this->containerNfs->updateExport($export, $input);

        flash_repository($this->containerNfs);

        return redirect()->route('dashboard.containers.nfs.manage', ['id' => $export->container_id]);
    }

    public function delete(FindRequest $request)
    {
        $export = $request->getNfsExport();
        return view('dashboard.containers.manage.nfses.exports.create', compact('export'));
    }

    public function destroy(DeleteRequest $request)
    {
        $export = $request->getNfsExport();
        $containerId = $export->container_id;
        $this->containerNfs->deleteExport($export);

        flash_repository($this->containerNfs);

        return redirect()->route('dashboard.containers.nfs.manage', ['id' => $containerId]);
    }
}
