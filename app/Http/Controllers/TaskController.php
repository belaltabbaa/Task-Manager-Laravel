<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->get();
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $user_id = Auth::user()->id;
        $validateData = $request->validated();
        $validateData['user_id'] = $user_id;
        $task = Task::create($validateData);
        return response()->json($task, 200);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($id);
        if ($task->user_id != $user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $task->update($request->validate([
            'title' => 'string|required'
        ]));
        return response()->json($task, 200);
    }

    public function destroy($id) {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
