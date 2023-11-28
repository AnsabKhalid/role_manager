<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'role_id' => $request->role_id
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check Email
        $user = User::where('email', $fields['email'])->first();

        // Check Password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => "Invalid Credentials"
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => "Logged out Successfully"
        ];
    }

    public function user_info()
    {
        $loggedUser = auth()->user();
        $loggedUser->load('role');
        return response([
            'user' => $loggedUser,
            'message' => "Logged user data"
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $password = $request->validate([
            'password' => 'required|string|confirmed',
        ]);
        $user = auth()->user();
        $user->password = Hash::make($password['password']);
        $user->save();
        return response([
            'message' => "Password changed successfully"
        ], 200);
    }
}
