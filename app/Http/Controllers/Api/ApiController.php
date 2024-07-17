<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;


class ApiController extends BaseController
{

    public function respondWith($data, $message = "Action successful.", $code = 200, $status = true ) {
        $response = [
            'success' => $status,
            'data'    => $data,
            'message' => $message ?? 'Action successful.'
        ];

        return response()->json($response, $code ?? 200);
    }
}
