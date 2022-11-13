<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index()
    {
        return [
            "success" => true,
            "message" => 'List of roles',
            "data" => Role::all()->where('is_active', true)
        ];
    }


    public function show($id)
    {
        $response = ["success" => false, "message" => 'Your role have not been found', "data" => []];
        $status = Role::query()->where('id', $id)->where('is_active', true)->first();
        if ($status) {
            return [
                "success" => true,
                "message" => 'Your role have been found',
                "data" => $status
            ];
        }
        return $response;
    }

    // TODO WHY WITH DEALER ROLE GIVES ME DIFFETENT ARRAY?
    public function indexByRoleDealer()
    {
        $response = ["success" => false, "message" => 'Your role have not been found', "data" => []];
        $users = User::query()->where('is_active', true)->where('role_id', '=', 2)->get();
        if ($users)
            return [
                "success" => true,
                "message" => 'List of users by delaer role',
                "data" => $users
            ];
        return $response;
    }
}
