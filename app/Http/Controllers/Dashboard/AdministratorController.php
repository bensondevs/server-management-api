<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Users\FindUserRequest;

use App\Http\Resources\UserResource;

use App\Models\User;

use App\Repositories\UserRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\ActivityLogRepository;

class AdministratorController extends Controller
{
    protected $user;
    protected $permission;
    protected $activityLog;

    public function __construct(
    	UserRepository $userRepository,
		PermissionRepository $permissionRepository,
        ActivityLogRepository $activityLogRepository
    )
    {
    	$this->user = $userRepository;
    	$this->permission = $permissionRepository;
        $this->activityLog = $activityLogRepository;
    }

    public function index()
    {
    	return view('dashboard.administrators.index');
    }

    public function view(Request $request)
    {
        $administrator = $this->user->find($request->get('id'));

        if ($request->get('start'))
            $this->activityLog->setStart($request->get('start'));
        if ($request->get('end'))
            $this->activityLog->setEnd($request->get('end'));

        $activities = $this->activityLog->activitiesOf($administrator);

        return view(
            'dashboard.administrators.view', 
            compact(['administrator', 'activities'])
        );
    }

    public function addUsers()
    {
        $users = $this->user->allWithoutAdmins();

        return view(
            'dashboard.administrators.promote_users', 
            compact('users')
        );
    }

    public function promoteUser(FindUserRequest $request)
    {
    	$user = $this->user->find($request->input('id'));
    	$this->permission->assignRole($user, 'administrator');

    	activity()
    		->performedOn($user)
    		->causedBy(auth()->user())
    		->log($user->anchorName() . ' has been promoted as administrator by ' . auth()->user()->anchorName());

    	flashMessage($this->permission);

    	return redirect()->route('dashboard.administrators.index');
    }

    public function demoteAdministrator(FindUserRequest $request)
    {
    	$administrator = $this->user->find($request->input('id'));
    	$this->permission->revokeRole($administrator, 'administrator');

    	activity()
    		->performedOn($user)
    		->causedBy(auth()->user())
    		->log($user->anchorName() . ' has been demoted to user by ' . auth()->user()->anchorName());

    	flashMessage($this->permission);

    	return redirect()->route('dashboard.administrators.index');
    }

    public function populate()
    {
    	$administrators = $this->user->administrators();
        $administrators = $this->user->paginate();
        $administrators->data = UserResource::collection($administrators);

    	return response()->json(['administrators' => $administrators]);
    }
}