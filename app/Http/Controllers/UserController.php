<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $response = ["success" => false, "message" => 'Your stores have not been found', "data" => []];

    public function index()
    {
        return [
            'success' => true,
            'message' => 'List of users',
            'data' => User::with('role', 'visits')->where('is_active', true)->get()
        ];
    }

    public function ordersStoresByUser($idStore)
    {
        $user = auth()->user();
        $orders = Order::query()
            ->where('store_id', '=', $idStore)
            ->where('delivered_by', '=', $user->id)
            ->where('is_active', '=', true)
            ->with('status')
            ->get();
        $this->response['success'] = true;
        $this->response['message'] = 'Stores Orders by User';
        $this->response['data'] = $orders;
        return $this->response;
    }

    public function storesByUser()
    {
        $user = auth()->user();
        $stores = User::with('dealers')
            ->where('id', '=', $user->id)
            ->first()
            ->dealers;
        if (sizeof($stores) > 0) {
            $this->response['success'] = true;
            $this->response['message'] = 'List of stores by user';
            $this->response['data'] = $stores;
        }
        return $this->response;
    }

    public function updateDealer(UserRequest $request)
    {
        try {
            $userFound = User::query()->where('id', $request->id)->where('is_active', true)->first();
            if ($userFound) {
                $userFound->update([
                    'name' => $request->name,
                    'first_surname' => $request->first_surname,
                    'second_surname' => $request->second_surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ]);
                $this->response['success'] = true;
                $this->response['message'] = 'Your user have been updated';
                $this->response['data'] = $userFound;
                return $this->response;
            }
            return $this->response;
        } catch (\Exception $e) {
            $this->response['message'] = 'Your dealer have not been updated';
            return $this->response;
        }
    }

    public function storeDealer(UserRequest $request)
    {
        try {
            $passwordText = $this->generatePass();
            $user = new User([
                'email' => $request->email,
                'password' => Hash::make($passwordText),
                'name' => $request->name,
                'first_surname' => $request->first_surname,
                'second_surname' => $request->second_surname,
                'phone' => $request->phone,
                'role_id' => 2,
            ]);
            $user->save();
            $user->rawPassword = $passwordText;
            $this->response['success'] = true;
            $this->response['message'] = 'Your delaer have been stored';
            $this->response['data'] = $user;
            return $this->response;
        } catch (\Exception $e) {
            $this->response['message'] = 'Your dealer have not been stored';
            return $this->response;
        }
    }


    private function generatePass()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function store(UserRequest $request)
    {
        $user = new User([
            'email' => $request->email,
            'password' => Hash::make($request->password),
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
