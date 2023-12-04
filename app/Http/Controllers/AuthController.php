<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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

    public function login(UserLoginRequest $request)
    {
        // Check Email
        $user = User::where('email', $request->email)->first();

        // Check Password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => "Invalid Credentials"
            ], 401);
        }
        // $guard = Auth::guard(Arr::first(config('sanctum.guard')));
        // if(!$guard->attempt([
        //     'email' => $request->email,
        //     'password' => $request->password,
        // ])) {
        //     throw new \Error('Invalid Credentials');
            
        // }
        
        // $user = $guard->user();
        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => "Logged out Successfully"
        ];
    }

    public function userInfo()
    {
        // $loggedUser = auth()->user();
        // $loggedUser->load('role');
        // return response([
            //     'user' => $loggedUser,
            //     'message' => "Logged user data"
            // ], 200);
        
        $user = User::whereId(auth()->user()->id)->with('role')->first();
        return UserResource::make($user);
    }

    public function changePassword(UserPasswordRequest $request)
    {
        $user = auth()->user();

         // Check if the provided current password matches the user's actual current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response([
                'message' => "Current password is incorrect"
            ], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return response([
            'message' => "Password changed successfully"
        ], 200);
    }
}
