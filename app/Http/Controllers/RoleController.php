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
    public function indexByRole($query)
    {
        $response = ["success" => false, "message" => 'Your role have not been found', "data" => []];
        $role = Role::query()->where('is_active', true)->where('name', 'like', '%' . $query . '%')->first();
        if ($role)
            return [
                "success" => true,
                "message" => 'List of users by role',
                "data" => User::all()->where('role_id', $role->id)->where('is_active', true)
            ];
        return $response;
    }
}
