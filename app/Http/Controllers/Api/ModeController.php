<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mode;
use App\Http\Resources\ModeResource;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class ModeController extends Controller
{
    public function index()
    {
        try {
            $status = Mode::all();

            if ($status->isEmpty()) {
                return ResponseHelper::success('No data available', [], 200);
            }

            return ResponseHelper::success("Request Successful", ModeResource::collection($status), 200);
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
            $mode = Mode::create([
                'name' => $request->name,
            ]);

            return ResponseHelper::success('Created Successfully', new ModeResource($mode), 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function show(Mode $mode)
    {
        return ResponseHelper::success("Request Successful", new ModeResource($mode), 200);
    }

    public function update(Request $request, Mode $mode)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error('Invalid Request', $validator->messages(), 422);
        }

        try {
            $mode->update([
                'name' => $request->name,
            ]);

            return ResponseHelper::success('Updated Successfully', new ModeResource($mode), 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }

    public function destroy(Mode $mode)
    {
        try {
            $mode->delete();
            return ResponseHelper::success('Deleted Successfully', [], 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('An error occurred', $e->getMessage(), 500);
        }
    }
}
