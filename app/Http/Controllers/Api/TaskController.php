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
            // Eager load the related data (system, mode, and status)
            $tasks = Task::with(['system', 'mode', 'status'])->get();

            // Check if tasks are empty
            if ($tasks->isEmpty()) {
                return ResponseHelper::success('No data available', [], 200);
            }

            // Map through tasks to format the data as needed (subquery style)
            $formattedTasks = $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'subject' => $task->subject,
                    'system' => $task->system ? $task->system->name : null,
                    'mode' => $task->mode ? $task->mode->name : null,
                    'definition' => $task->definition,
                    'status' => $task->status ? $task->status->name : null,
                    'percentage' => $task->percentage,
                    'added_by' => $task->added_by,
                    'created_at' => $task->created_at,
                    'updated_at' => $task->updated_at,
                ];
            });

            return ResponseHelper::success('Request Successful', $formattedTasks, 200);
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

    public function show(Request $request)
    {
        try {

            $validParameters = ['system_id', 'mode_id', 'status_id', 'percentage', 'created_at', 'updated_at', 'added_by'];
            $requestParameters = $request->only($validParameters);
            $query = Task::query();

            foreach ($requestParameters as $column => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($column, $value);
                }
            }


            $tasks = $query->get();
            $subqueriedTask = $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'subject' => $task->subject,
                    'system_id' => $task->system ? $task->system->name : null,
                    'mode_id' => $task->mode ? $task->mode->name : null,
                    'definition' => $task->definition,
                    'status_id' => $task->status_id,
                    'status_name' => $task->status ? $task->status->name : null,
                    'percentage' => $task->percentage,
                    'added_by' => $task->added_by,
                    'created_at' => $task->created_at,
                    'updated_at' => $task->updated_at
                ];
            });

            return ResponseHelper::success('Tasks fetched successfully', $subqueriedTask);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
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
