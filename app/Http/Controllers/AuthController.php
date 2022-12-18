<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $response = ["success" => false, "message" => 'User not found', "data" => []];

    public function login(AuthRequest $request)
    {
        $user = User::query()
            ->where('email', $request->email)
            ->where('is_active', true)
            ->with('role')
            ->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('authToken')->plainTextToken;
                $this->response['success'] = true;
                $this->response['message'] = 'User logged in';
                $this->response['data'] = $user;
                $this->response['token'] = $token;
            }
        }
        return $this->response;
    }

    public function profile(UserRequest $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->email = $request->email;
            $user->name = $request->name;
            $user->first_surname = $request->first_surname;
            $user->second_surname = $request->second_surname;
            $user->phone = $request->phone;
            $user->save();
            $this->response['success'] = true;
            $this->response['message'] = 'Profile updated';
            $this->response['data'] = $user;
        }
        return $this->response;
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required|confirmed'
        ]);
        if (!$validator->fails()) {
            $user = auth()->user();
            if ($user && Hash::check($request->password, $user->password)) {
                $user->tokens()->delete();
                $user->password = Hash::make($request->new_password);
                $user->save();
                $this->response['success'] = true;
                $this->response['message'] = 'Password updated';
                $this->response['data'] = $user;
            }
        } else {
            return $this->response;
        }
        return $this->response;
    }

    public function logout()
    {
        $user = auth()->user();
        foreach ($user->tokens as $token) $token->delete();
        return ["success" => true, "message" => 'User logged out', "data" => []];
    }

}
