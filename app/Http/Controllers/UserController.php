<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    private $response = ["success" => false, "message" => 'Your stores have not been found', "data" => []];

    public function index()
    {
        return [
            'success' => true,
            'message' => 'List of users',
            'data' => User::with('role')->where('is_active', true)->get()
        ];
    }

    public function storesByUser($id)
    {
        $stores = User::with('dealers')->where('id', '=', $id)->first()->dealers;
        if(sizeof($stores) > 0) {
            $this->response['success'] = true;
            $this->response['message'] = 'List of stores by user';
            $this->response['data'] = $stores;
        }
        return $this->response;
    }

    public function store(UserRequest $request)
    {
        $user = new User([
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
            'first_surname' => $request->first_surname,
            'second_surname' => $request->second_surname,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ]);
        $user->save();
        return [
            'success' => true,
            'message' => 'Your user have been stored',
            'data' => $user
        ];
    }

    public function show($id)
    {
        $user = User::with('role')->where('id', $id)->where('is_active', true)->first();
        if ($user) return ["success" => true, "message" => 'Your role have been found', "data" => $user];
        return $this->response;
    }

    public function update(UserRequest $request)
    {
        $user = User::query()->where('id', $request->id)->where('is_active', true)->first();
        if ($user) {
            $user->email = $request->email;
            $user->password = $request->password;
            $user->name = $request->name;
            $user->first_surname = $request->first_surname;
            $user->second_surname = $request->second_surname;
            $user->phone = $request->phone;
            $user->role_id = $request->role_id;
            $user->save();
            return [
                'success' => true,
                'message' => 'Your user have been updated',
                'data' => $user
            ];
        }
        return $this->response;
    }


    public function destroy($id)
    {
        $user = User::query()->where('id', $id)->where('is_active', true)->first();
        if ($user) {
            $user->is_active = false;
            $user->save();
            return [
                'success' => true,
                'message' => 'Your user have been deleted',
                'data' => $user
            ];
        }
        return $this->response;
    }
}
