<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\AttachTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;

use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new TaskCollection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        return Task::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return TaskResource::make(Task::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::findOrFail($id);

        $task->update($request->all());
        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id)->delete();
        return response()->json(['message' => 'Resource deleted successfully'], 200);
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
    public function attachTaskToUser(AttachTaskRequest $request)
    {
        $users = User::whereIn('id', $request->user_id)->get();
        $tasks = Task::whereIn('id', $request->task_id)->get();

        foreach ($tasks as $task) {
            foreach ($users as $user) {
                $task->users()->sync($user->id, false);
            }
            $task->update(['status' => 'Assigned']);
        }
        return response()->json(['message' => 'Task Assigned successfully'], 200);
    }

    /**
     * Detach task from User.
     */
    public function detachTask($task_id)
    {
        $task = Task::findOrFail($task_id);
        
        $task->users()->detach();
        $task->update(['status' => 'Pending']);
        return response()->json(['message' => 'Task Detached successfully'], 200);
    }
}
