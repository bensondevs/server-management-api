<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Permissions\CheckPermissionRequest;
use App\Http\Requests\Permissions\CheckPermissionsRequest;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Repositories\UserRepository;
use App\Repositories\PermissionRepository;

class PermissionController extends Controller
{
	protected $user;
    protected $permission;

    public function __construct(
    	UserRepository $userRepository,
    	PermissionRepository $permissionRepository
    )
    {
    	$this->user = $userRepository;
    	$this->permission = $permissionRepository;
    }

    public function checkPermission(CheckPermissionRequest $request)
    {
    	$user = $request->user();

        if ($user->hasRole('administrator')) {
            return response()->json(['allowed' => true]);
        }

    	$permissions = $request->input('permission_name');

    	return response()->json([
    		'allowed' => $user->can($permissionName)
    	]);
    }

    public function checkPermissions(CheckPermissionsRequest $request)
    {
        $user = $request->user();
        $permissions = json_decode($request->input('permissions'));
        $allowedPermissions = [];

        foreach ($permissions as $permission)
            if ($user->hasPermissionTo($permission))
                $allowedPermissions[$permission] = true;

        return response()->json(['permissions' => $allowedPermissions]);
    }
}
