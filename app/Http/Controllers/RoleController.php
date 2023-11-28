<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $logged_user = auth()->user();
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'slug' => 'required|string',
        ]);

        if($logged_user->role->name === 'Admin') {
            return Role::create($request->all());
        } else {
            return response()->json(['message' => 'Only Admin can Add New Role'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $logged_user = auth()->user();
        
        if($logged_user->role->name === 'Admin') {
            $task = Role::findOrFail($id)->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Only Admin can Delete Role'], 404);
        }
    }
}
