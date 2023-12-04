<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the User.
     */
    public function usersList()
    {
        // $logged_user = User::with('role')->get();
        // $logged_user->load('role');
        // return response([
        //     'user' => $logged_user,
        //     'message' => "All Users data"
        // ], 200);
        return new UserCollection(User::all());
    }

    /**
     * Display the specified User.
     */
    public function show(string $id)
    {
        return UserResource::make(User::find($id));
    }

    /**
     * Update the specified User in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country
        ]);
        return UserResource::make($user);
    }

    /**
     * Remove the specified User from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    /**
     * Search the specified user from storage.
     */
    public function search(string $name)
    {
        return User::where('first_name', 'like', '%'.$name.'%')->get();
    }
}
