<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //ترتيب تلقائي حسب تاريخ الانشاء
    public function sorting(){
        $tasks = Task::orderBy("created_at","asc")->get();
        return response()->json($tasks,200);
    }

    //البحث حسب اسم المهمة
    public function search(Request $request)
    {
        $query = Task::query();
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        return TaskResource::collection($query->get());
    }

    //فلترة المهام حسب الحالة
    public function getTasksByStatus(Request $request)
    {
        $request->validate([
            'status'=> 'required|in:completed,in_progress,pending',
        ]);
        $query = Task::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        return TaskResource::collection($query->get());
    }
    //من هون لتحت توابع الحذف والتعديل والعرض والاضافة
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

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
