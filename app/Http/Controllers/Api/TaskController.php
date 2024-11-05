<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::all();

            if ($tasks->isEmpty()) {
                return ResponseHelper::success('No data available', [], 200);
            }

            return ResponseHelper::success('Request Successful', TaskResource::collection($tasks), 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
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
            return ResponseHelper::error('Invalid Request', $validator->messages(), 422);
        }

        try {
            $task = Task::create($request->all());

            return ResponseHelper::success('Created Successfully', new TaskResource($task), 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function show(Task $task)
    {
        return ResponseHelper::success('Request Successful', new TaskResource($task), 200);
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
            return ResponseHelper::error('Invalid Request', $validator->messages(), 422);
        }

        try {
            $task->update($request->all());

            return ResponseHelper::success('Updated Successfully', new TaskResource($task), 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return ResponseHelper::success('Deleted Successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }
}
