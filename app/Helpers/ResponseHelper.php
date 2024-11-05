<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = [], $message = 'Request was successful', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($message = 'An error occurred', $status = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
        ], $status);
    }
}
