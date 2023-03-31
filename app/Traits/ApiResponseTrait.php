<?php

namespace App\Traits;

trait ApiResponseTrait
{
    //return a success message and data for api request
    public function apiResponse($data, $status = 200, $msg) {
        return response()->json([
            'status' => $status,
            'message' => $msg,
            'data' => $data,
        ]);
    }

    //return an error message for api request
    public function responseErrorMsg($status, $msg) {
        return response()->json([
            'status' => $status,
            'message' => $msg
        ]);
    }

    //return a success message and data for api request
    public function responseSuccessMsg($status = '000', $msg) {
        return response()->json([
            'status' => $status,
            'message' => $msg,
        ]);
    }

}
