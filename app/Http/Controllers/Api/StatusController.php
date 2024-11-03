<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Status;
use App\Http\Resources\StatusResource;

class StatusController extends Controller
{

    public function index()
    {
        $status = Status::all();
        return $status->isEmpty()
            ? response()->json(['message' => 'No data available'], 200)
            : StatusResource::collection($status);
    }

     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255'
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'message' => 'Field are required',
                 'error' => $validator->messages()
             ], 422);
         }
     
         $request->validate([
             'name' => 'required|string|max:255',
         ]);
     
         $status = Status::create([
             'name' => $request->name,
         ]);
     
         return response()->json([
             'message' => 'Created Successfully',
             'data' => new StatusResource($status)
         ], 201);
     }

    public function show(Status $status)
    {
        return new StatusResource($status);
    }


    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Field is required',
                'error' => $validator->messages()
            ], 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => new StatusResource($status)
        ], 200);
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return response()->json([
            'message' => 'Deleted Successfully',
            'data' => new StatusResource(resource: $status)
        ],200);
    }
}
