<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SystemResource;
use Illuminate\Http\Request;
use App\Models\System;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class SystemController extends Controller
{
    public function index()
    {
        try {
            $status = System::all();

            if ($status->isEmpty()) {
                return ResponseHelper::success('No data available', [], 200);
            }

            return ResponseHelper::success('Request Successful', SystemResource::collection($status), 200);
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
            $system = System::create([
                'name' => $request->name,
            ]);

            return ResponseHelper::success('Created Successfully', new SystemResource($system), 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function show(System $system)
    {
        return ResponseHelper::success('Request Successful', new SystemResource($system), 200);
    }

    public function update(Request $request, System $system)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error('Invalid Request', $validator->messages(), 422);
        }

        try {
            $system->update([
                'name' => $request->name,
            ]);

            return ResponseHelper::success('Updated Successfully', new SystemResource($system), 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function destroy(System $system)
    {
        try {
            $system->delete();
            return ResponseHelper::success('Deleted Successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }
}
