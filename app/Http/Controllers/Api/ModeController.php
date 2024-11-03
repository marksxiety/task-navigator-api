<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mode;
use App\Http\Resources\ModeResource;
use Illuminate\Support\Facades\Validator;

class ModeController extends Controller
{
    public function index()
    {
        $status = Mode::all();
        return $status->isEmpty()
            ? response()->json(['message' => 'No data available'], 200)
            : ModeResource::collection($status);
    }


    public function store(Request $request)
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
    
        $mode = Mode::create([
            'name' => $request->name,
        ]);
    
        return response()->json([
            'message' => 'Created Successfully',
            'data' => new ModeResource($mode)
        ], 201);
    }
    
    

    public function show(Mode $mode)
    {
        return new ModeResource($mode);
    }


    public function update(Request $request, Mode $mode)
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

        $mode->update([
            'name' => $request->name,
        ]);
    
        return response()->json([
            'message' => 'Created Successfully',
            'data' => new ModeResource($mode)
        ], 201);
    }

    public function destroy(Mode $mode)
    {
        $mode->delete();
        return response()->json([
            'message' => 'Deleted Successfully',
            'data' => new ModeResource( $mode)
        ],200);
    }
}
