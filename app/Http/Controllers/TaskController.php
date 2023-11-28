<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $logged_user = auth()->user();

        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        if($logged_user->role->name === 'Admin') {
            return Task::create($request->all());
        } else {
            return response()->json(['message' => 'Only Admin can detach tasks'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $logged_user = auth()->user();

        if($logged_user->role->name === 'Admin') {
            return Task::find($id);
        } else {
            return response()->json(['message' => 'Only Admin can fetch tasks'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $logged_user = auth()->user();
        $task = Task::findOrFail($id);
        
        if($logged_user->role->name === 'Admin') {
            $task->update($request->all());
            return $task;
        } else {
            return response()->json(['message' => 'Only Admin can Update tasks'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $logged_user = auth()->user();
        
        if($logged_user->role->name === 'Admin') {
            $task = Task::findOrFail($id)->delete();
            return response()->json(['message' => 'Resource deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Only Admin can Delete tasks'], 404);
        }
    }

    /**
     * Search the specified resource from storage.
     */
    public function search(string $name)
    {
        return Task::where('name', 'like', '%'.$name.'%')->get();
    }

    /**
     * Attach task to User.
     */
    public function attachTaskToUser(Request $request)
    {
        $logged_user = auth()->user();

        $fields = $request->validate([
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $user = User::findOrFail($fields['user_id']);
        $task = Task::findOrFail($fields['task_id']);
        
        if ($logged_user->role->name === 'Admin') {
            $task->users()->sync($user->id, false);
            return response()->json(['message' => 'Task Assigned successfully'], 200);
        } else {
            return response()->json(['message' => 'Only Admin can attach tasks'], 403);
        }
    }

    /**
     * Detach task from User.
     */
    public function detachTask($task_id)
    {
        $logged_user = auth()->user();

        $task = Task::findOrFail($task_id);
        
        if($logged_user->role->name === 'Admin') {
            $task->users()->detach();
            return response()->json(['message' => 'Task Detached successfully'], 200);
        } else {
            return response()->json(['message' => 'Only Admin can detach tasks'], 404);
        }
    }
}
