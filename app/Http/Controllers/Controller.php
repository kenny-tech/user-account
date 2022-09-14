<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccess($data, $message, $code = 200)
    {
        $response = [
            'success' => 'true',
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public function sendError($data, $message, $code = 404)
    {
        $response = [
            'success' => 'false',
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
}
