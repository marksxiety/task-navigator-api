<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = Task::all();
        return $status->isEmpty()
            ? response()->json(['message' => 'No data available'], 200)
            : TaskResource::collection($status);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'system_id' => 'required|integer|exists:systems,id',       
            'mode_id' => 'required|integer|exists:modes,id', 
            'definition' => 'required|string|max:999',
            'status_id' => 'required|integer|exists:statuses,id',     
            'percentage' => 'required|numeric|min:1|max:100',
            'added_by' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 422);
        }

        $task = Task::create([
            'subject' => $request->subject,
            'system_id' => $request->system_id,
            'mode_id' => $request->mode_id,
            'definition' => $request->definition,
            'status_id' => $request->status_id,
            'percentage' => $request->percentage,
            'added_by' => $request->added_by,
        ]);

        return response()->json([
            'message' => 'Created Successfully',
            'data' => new TaskResource($task)
        ], 201);

    }

    public function show(Task $task)
    {
        return response()->json([
            'id' => $task->id,
            'subject' => $task->subject,
            'system' => $task->system->name,
            'mode' => $task->mode->name,
            'definition' => $task->definition,
            'status' => $task->status->name,
            'percentage' => $task->percentage,
            'added_by' => $task->added_by,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'system_id' => 'required|integer|exists:systems,id',       
            'mode_id' => 'required|integer|exists:modes,id', 
            'definition' => 'required|string|max:999',
            'status_id' => 'required|integer|exists:statuses,id',     
            'percentage' => 'required|numeric|min:1|max:100',
            'added_by' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 422);
        }

        $task->update([
            'subject' => $request->subject,
            'system_id' => $request->system_id,
            'mode_id' => $request->mode_id,
            'definition' => $request->definition,
            'status_id' => $request->status_id,
            'percentage' => $request->percentage,
            'added_by' => $request->added_by,
        ]);

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => new TaskResource($task)
        ], 201);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'Deleted Successfully',
            'data' => new TaskResource(resource: $task)
        ],200);
    }
}
