<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                foreach ($user->tokens as $token) $token->delete();
                $token = $user->createToken('authToken')->plainTextToken;
                $this->response['success'] = true;
                $this->response['message'] = 'User logged in';
                $this->response['data'] = $user;
                $this->response['token'] = $token;
            }
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
