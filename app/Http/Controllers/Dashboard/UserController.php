<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Users\SaveUserRequest;
use App\Http\Requests\Users\FindUserRequest;
use App\Http\Requests\Users\UpdateUserProfileRequest;
use App\Http\Requests\Users\UpdateUserAccountRequest;
use App\Http\Requests\Users\UpdateUserPasswordRequest;
use App\Http\Requests\Permissions\UpdatePermissionsRequest;

use App\Models\User;

use App\Http\Resources\UserResource;

use App\Repositories\UserRepository;
use App\Repositories\PermissionRepository;

class UserController extends Controller
{
    protected $user;
    protected $permission;

    public function __construct(UserRepository $user, PermissionRepository $permission)
    {
    	$this->user = $user;
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->populate();
        }

    	return view('dashboard.users.index');
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function view(FindUserRequest $request)
    {
        $user = $this->user->find($request->get('id'));
        $user->permissions = $user->getPermissionNames()->toArray();

        return view('dashboard.users.view', compact('user'));
    }

    public function confirmDelete(FindUserRequest $request)
    {
        $user = $request->getUser();
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log(auth()->user()->anchorName() . ' had attempted deleting ' . $user->anchorName());

        return view('dashboard.users.confirm-delete', compact('user'));
    }

    public function populate()
    {
        $users = $this->user->allWithoutAdmins();
        $users = $this->user->paginate();
        $users->data = UserResource::collection($users);
        
    	return response()->json(['users' => $users]);
    }

    public function populateAll()
    {
        $users = $this->user->all();
        return response()->json([
            'users' => UserResource::collection($users),
        ]);
    }

    public function store(SaveUserRequest $request)
    {
        $input = $request->validated();
    	$user = $this->user->save($input);

        flash_repository($this->user);

    	return redirect()->route('dashboard.users.index');
    }

    public function updateProfile(UpdateUserProfileRequest $request)
    {
    	$user = $this->user->find($request->input('id'));
    	$user = $this->user->updateProfile($request->validated());

        flash_repository($this->user);

    	return redirect()->back();
    }

    public function updateAccount(UpdateUserAccountRequest $request)
    {
        $user = $request->getUser();
        $user = $this->user->setModel($user);
        $input = $request->validated();
        $user = $this->user->updateAccount($input);

        flash_repository($this->user);

        return redirect()->back();
    }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $user = $request->getUser();
        $user = $this->user->setModel($user);

        $input = $request->validated();
        $user = $this->user->updatePassword($input);

        flash_repository($this->user);

        return redirect()->back();
    }

    public function updatePermissions(UpdatePermissionsRequest $request)
    {
        // Selected Permissions
        $permissions = $request->input('permissions');

        // User Permissions
        $user = User::findOrFail($request->input('id'));

        $this->permission->updateUserPermissions($user, $permissions);

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log($user->anchorName() . '\'s permissions have been updated by ' . auth()->user()->anchorName());
        flashMessage($this->permission);

        return redirect()->back();
    }

    public function updateNewsletter(FindUserRequest $request)
    {
        $user = $request->getUser();
        $this->user->setModel($user);
        $this->user->toggleNewsletter();

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log($user->anchorName() . '\'s newsletter subscription status has been updated by ' . auth()->user()->anchorName());
        flashMessage($this->user);

        return redirect()->back();
    }

    public function confirmPromoteUser(FindUserRequest $request)
    {
        $user = $request->getUser();

        return view('dashboard.users.confirm-promote', compact('user'));
    }

    public function promoteUser(FindUserRequest $request)
    {
        $user = $request->getUser();
        $user = $this->user->setModel($user);
        $this->permission->assignRole($user, 'administrator');

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log($user->anchorName() . ' has been promoted as administrator by ' . auth()->user()->anchorName());
        flashMessage($this->permission);

        return redirect()->route('dashboard.administrators.index');
    }

    public function delete(FindUserRequest $request)
    {
        $user = $request->getUser();
    	$this->user->setModel($user);
    	$this->user->delete();

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log($user->anchorName() . ' has been deleted by ' . auth()->user()->anchorName());
        flash_repository($this->user);

    	return redirect()->route('dashboard.users.index');
    }
}