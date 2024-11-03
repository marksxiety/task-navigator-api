<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SystemResource;
use Illuminate\Http\Request;
use App\Models\System;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = System::all();
        return $status->isEmpty()
            ? response()->json(['message' => 'No data available'], 200)
            : SystemResource::collection($status);
    }

    public function store(Request $request, System $system)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 422);
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $system = System::create([
            'name' => $request->name,
        ]);
    
        return response()->json([
            'message' => 'Created Successfully',
            'data' => new SystemResource($system)
        ], 201);
    }


    public function show(System $system)
    {
        return new SystemResource($system);
    }

    public function update(Request $request, System $system)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $system->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => new SystemResource($system)
        ], 200);
    }

    public function destroy(System $system)
    {
        $system->delete();
        return response()->json([
            'message' => 'Deleted Successfully',
            'data' => new SystemResource(resource: $system)
        ],200);
    }
}
