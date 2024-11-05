<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Status;
use App\Http\Resources\StatusResource;
use App\Helpers\ResponseHelper;

class StatusController extends Controller
{
    public function index()
    {
        try {
            $status = Status::all();

            if ($status->isEmpty()) {
                return ResponseHelper::success('No data available', [], 200);
            }

            return ResponseHelper::success('Request Successful', StatusResource::collection($status), 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error('Invalid Request', $validator->messages(), 422);
        }

        try {
            $status = Status::create([
                'name' => $request->name,
            ]);

            return ResponseHelper::success('Created Successfully', new StatusResource($status), 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function show(Status $status)
    {
        return ResponseHelper::success('Request Successful', new StatusResource($status), 200);
    }

    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error('Invalid Request', $validator->messages(), 422);
        }

        try {
            $status->update([
                'name' => $request->name,
            ]);

            return ResponseHelper::success('Updated Successfully', new StatusResource($status), 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function destroy(Status $status)
    {
        try {
            $status->delete();
            return ResponseHelper::success('Deleted Successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }
}
