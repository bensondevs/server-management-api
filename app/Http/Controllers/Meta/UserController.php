<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\User\UserAccountType;

class UserController extends Controller
{
    /**
     * Get all user account types
     * 
     * @return  \Illuminate\Support\Facades\Response
     */
    public function accountTypes()
    {
        $types = UserAccountType::asSelectArray();

        return response()->json($types);
    }
}