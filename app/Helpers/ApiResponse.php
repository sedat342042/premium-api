<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'timestamp' => now(),
        ], $status);
    }

    public static function failed($message = null, $errors = null, $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'status' => $status,
            'timestamp' => now(),
        ], $status);
    }
}
