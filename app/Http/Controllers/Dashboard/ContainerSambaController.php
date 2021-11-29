<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\FindContainerRequest as FindRequest;
use App\Http\Requests\Containers\Samba\CreateDirectoryRequest;

use App\Models\Container;

use App\Repositories\ContainerSambaRepository;

class ContainerSambaController extends Controller
{
    private $containerSamba;

    public function __construct(ContainerSambaRepository $containerSamba)
    {
        $this->containerSamba = $containerSamba;
    }

    public function completeCheck()
    {
        $informations = $this->containerSamba->setModel($container);
        return response()->json(['samba_informations' => $sambaInformations]);
    }

    public function loadSettings()
    {
        $settings = $this->containerSamba->setModel($container);
    }

    public function checkStatus(Container $container)
    {
        $this->containerSamba->setModel($container);
        $status = $this->containerSamba->checkStatus();

        return response()->json(['status' => $status]);
    }

    public function checkPidNumbers(Container $container)
    {
        $this->containerSamba->setModel($container);
        $pidNumbers = $this->containerSamba->checkPidNumbers();

        return response()->json(['pid_numbers' => $pidNumbers]);
    }

    public function start(Container $container)
    {
        $this->containerSamba->setModel($container);
        $this->containerSamba->start();

        return apiResponse($this->containerSamba);
    }

    public function stop(Container $container)
    {
        $this->containerSamba->setModel($container);
        $this->containerSamba->stop();

        return apiResponse($this->containerSamba);
    }

    public function restart(Container $container)
    {
        $this->containerSamba->setModel($container);
        $this->containerSamba->restart();

        return apiResponse($this->containerSamba);
    }

    public function toggleStartOnBoot(Container $container, Request $request)
    {
        $this->containerSamba->setModel($container);

        $status = $request->input('status');
        $this->containerSamba->toggleStartOnBoot($status);

        return apiResponse($this->containerSamba);
    }

    public function createDirectory(CreateDirectoryRequest $request, Container $container)
    {
        $this->containerSamba->setModel($container);

        $directoryName = $request->input('directory_name');
        $this->containerSamba->createDirectory($directoryName);

        return apiResponse($this->containerSamba);
    }

    public function createShare(CreateShareRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerSamba->setModel($container);

        $shareName = $request->input('share_name');
        $this->containerSamba->createShare($shareName);

        return apiResponse($this->containerSamba);
    }

    public function deleteShare(DeleteShareRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerSamba->setModel($container);

        $shareName = $request->input('share_name');
        $this->containerSamba->deleteShare($shareName);

        return apiResponse($this->containerSamba);
    }

    public function createUser(CreateUserRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerSamba->setModel($container);

        $username = $request->input('username');
        $password = $request->input('password');
        $this->containerSamba->createUser($username, $password);

        return apiResponse($this->containerSamba);
    }

    public function deleteUser(DeleteUserRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerSamba->setModel($container);

        $username = $request->input('username');
        $this->containerSamba->deleteUser($username);

        return apiResponse($this->containerSamba);
    }

    public function addShareUser(AddShareUserRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerSamba->setModel($container);

        $username = $request->input('username');
        $shareName = $request->input('share_name');
        $this->containerSamba->addShareUser($username, $shareName);

        return apiResponse($this->containerSamba);
    }

    public function addShareGroup(AddShareGroupRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerSamba->setModel($container);

        $groupName = $request->input('group_name');
        $shareName = $request->input('share_name');
        $this->containerSamba->addShareGroup($groupName, $shareName);
    }
}

